<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Pago;
use App\Models\Banco;
use App\Models\Tarjeta;
use App\Services\CajaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ClientesExport;
use Barryvdh\DomPDF\Facade\Pdf;

class ClienteController extends Controller
{
    protected CajaService $cajaService;

    public function __construct(CajaService $cajaService)
    {
        $this->cajaService = $cajaService;
    }

    private function buildQuery(Request $request)
    {
        $user = Auth::user();
        $query = Cliente::query();

        if ($user->role !== 'Administrador' && $user->role !== 'Supervisor') {
            $query->where('c_oficina_registro', $user->office);
        } elseif ($request->filled('oficina')) {
            $query->where('c_oficina_registro', $request->oficina);
        }

        if ($request->filled('buscar')) {
            $busqueda = $request->input('buscar');
            $query->where(function ($q) use ($busqueda) {
                $q->where('c_nombre', 'like', "%{$busqueda}%")
                  ->orWhere('c_apellido', 'like', "%{$busqueda}%")
                  ->orWhere('c_identificacion', 'like', "%{$busqueda}%")
                  ->orWhere('c_telefono', 'like', "%{$busqueda}%");
            });
        }

        if ($request->filled('deuda')) {
            if ($request->deuda === 'con_deuda') {
                $query->where('c_saldo', '>', 0);
            } elseif ($request->deuda === 'sin_deuda') {
                $query->where('c_saldo', '<=', 0);
            }
        }

        return $query;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = $this->buildQuery($request);

        $statsQuery = clone $query;
        $stats = [
            'total_clientes' => $statsQuery->count(),
            'total_deuda' => (float) $statsQuery->sum('c_deuda'),
            'total_abonado' => (float) $statsQuery->sum('c_abonado'),
            'total_saldo' => (float) $statsQuery->sum('c_saldo'),
            'con_deuda_count' => (clone $query)->where('c_saldo', '>', 0)->count(),
        ];

        $clientes = $query->paginate(15);
        $oficinas = Cliente::select('c_oficina_registro')->distinct()->pluck('c_oficina_registro');
        $bancos = Banco::activos()->orderBy('nombre')->get();
        $tarjetas = Tarjeta::with('banco')->activas()->orderBy('nombre')->get();
        $cajaAbierta = $this->cajaService->getCajaAbierta($user);

        return view('clientes.index', compact('clientes', 'user', 'oficinas', 'stats', 'bancos', 'tarjetas', 'cajaAbierta'));
    }

    /**
     * Export a listing of the resource to excel.
     */
    public function export(Request $request)
    {
        return Excel::download(new ClientesExport($this->buildQuery($request)), 'clientes.xlsx');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('clientes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validation and creation
        $validated = $request->validate([
            'c_identificacion' => 'required|string|max:50',
            'c_nombre' => 'required|string|max:50',
            'c_apellido' => 'required|string|max:50',
            'c_telefono' => 'required|string|max:50',
            'c_edad' => 'required|integer',
            'c_direccion' => 'required|string|max:50',
            'c_pais' => 'required|string|max:50',
            'c_estado' => 'required|string|max:50',
            'c_ciudad' => 'required|string|max:50',
            'c_codpostal' => 'required|string|max:50',
            'c_email' => 'required|email|max:50',
            'c_napartamento' => 'required|string|max:50',
        ]);

        $validated['c_register'] = Auth::user()->name;
        $validated['c_oficina_registro'] = Auth::user()->office;
        $validated['c_abonado'] = 0;
        $validated['c_deuda'] = 0;
        $validated['c_saldo'] = 0;

        Cliente::create($validated);

        return redirect()->route('clientes.index')->with('success', 'Cliente creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cliente $cliente)
    {
        return view('clientes.edit', compact('cliente'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cliente $cliente)
    {
        // Validation and update
        $validated = $request->validate([
            'c_identificacion' => 'required|string|max:50',
            'c_nombre' => 'required|string|max:50',
            'c_apellido' => 'required|string|max:50',
            'c_telefono' => 'required|string|max:50',
            'c_edad' => 'required|integer',
            'c_direccion' => 'required|string|max:50',
            'c_pais' => 'required|string|max:50',
            'c_estado' => 'required|string|max:50',
            'c_ciudad' => 'required|string|max:50',
            'c_codpostal' => 'required|string|max:50',
            'c_email' => 'required|email|max:50',
            'c_napartamento' => 'required|string|max:50',
        ]);

        $cliente->update($validated);

        return redirect()->route('clientes.index')->with('success', 'Cliente actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Store a payment (abono) for a specific client.
     */
    public function abonar(Request $request, Cliente $cliente)
    {
        $request->validate([
            'nuevo_abono' => 'required|numeric|min:0.01',
            'metodo_pago' => 'required|in:Efectivo,Cheque,Transferencia,Tarjeta,Zelle',
            'banco_id' => 'nullable|exists:bancos,id',
            'tarjeta_id' => 'nullable|exists:tarjetas,id',
            'numero_referencia' => 'nullable|string|max:100',
        ]);

        $user = Auth::user();
        $caja = $this->cajaService->getCajaAbierta($user);

        // All users who transact require an open cash register
        if (!$caja) {
            return redirect()->back()->with('error', 'No tienes una caja abierta actualmente. Debes abrir caja para poder registrar abonos.');
        }

        $nuevo_abono = floatval($request->input('nuevo_abono'));
        $abono_actual = floatval($cliente->c_abonado);
        $deuda_actual = floatval($cliente->c_saldo);

        $nuevo_total = $abono_actual + $nuevo_abono;
        $nuevo_saldo = $deuda_actual - $nuevo_abono;

        if ($nuevo_saldo < -0.01) {
            return redirect()->back()->with('error', 'El abono ($' . number_format($nuevo_abono, 2) . ') no puede ser mayor al saldo pendiente ($' . number_format($deuda_actual, 2) . ').');
        }

        DB::beginTransaction();
        try {
            $cliente->c_abonado = $nuevo_total;
            $cliente->c_saldo = max(0, $nuevo_saldo);
            $cliente->save();

            // 1. Registrar en historial de pagos
            $pago = Pago::create([
                'cliente_id' => $cliente->id_cliente,
                'monto' => $nuevo_abono,
                'concepto' => 'Abono a deuda cliente',
                'usuario' => $user->name,
                'oficina' => $user->office ?? 'General',
                'caja_sesion_id' => $caja->id,
                'metodo_pago' => $request->metodo_pago,
                'banco_id' => $request->banco_id,
                'tarjeta_id' => $request->tarjeta_id,
                'numero_referencia' => $request->numero_referencia,
            ]);

            // 2. Registrar movimiento en la sesión de caja activa
            $this->cajaService->registrarMovimiento([
                'caja_sesion_id' => $caja->id,
                'user_id' => $user->id,
                'cliente_id' => $cliente->id_cliente,
                'tipo' => 'ingreso_abono',
                'monto' => $nuevo_abono,
                'metodo_pago' => $request->metodo_pago,
                'banco_id' => $request->banco_id,
                'tarjeta_id' => $request->tarjeta_id,
                'pago_id' => $pago->id,
                'concepto' => 'Abono de cliente: ' . $cliente->c_nombre . ' ' . $cliente->c_apellido,
                'numero_referencia' => $request->numero_referencia,
            ]);

            DB::commit();

            return redirect()->back()
                ->with('success', '¡Abono de $' . number_format($nuevo_abono, 2) . ' registrado exitosamente en la Caja #' . $caja->id . '!')
                ->with('imprimir_recibo', route('clientes.recibo_abono', ['cliente' => $cliente->id_cliente, 'monto' => $nuevo_abono]));

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error al procesar el abono: ' . $e->getMessage());
        }
    }

    /**
     * Generate and stream a PDF receipt for a payment.
     */
    public function reciboAbono(Request $request, Cliente $cliente)
    {
        $monto = floatval($request->query('monto', 0));
        
        if ($monto <= 0) {
            return redirect()->route('clientes.index')->with('error', 'Monto inválido para el recibo.');
        }

        $data = [
            'cliente' => $cliente,
            'monto' => $monto,
            'fecha' => now()->format('d/m/Y H:i:s'),
            'usuario' => Auth::user()->name,
            'oficina' => Auth::user()->office,
        ];

        $pdf = Pdf::loadView('clientes.recibo_pdf', $data)->setPaper('letter', 'portrait');
        
        return $pdf->stream('Recibo_Abono_' . $cliente->c_identificacion . '.pdf');
    }

    /**
     * View payment history for a client.
     */
    public function pagos(Cliente $cliente)
    {
        $pagos = $cliente->pagos()->with(['banco', 'tarjeta', 'cajaSesion'])->orderBy('created_at', 'desc')->get();
        return view('clientes.pagos', compact('cliente', 'pagos'));
    }
}
