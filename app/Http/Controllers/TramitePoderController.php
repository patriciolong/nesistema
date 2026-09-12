<?php

namespace App\Http\Controllers;

use App\Models\TramitePoder;
use App\Models\Cliente;
use App\Models\Pago;
use App\Models\Banco;
use App\Models\Tarjeta;
use App\Services\CajaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class TramitePoderController extends Controller
{
    protected CajaService $cajaService;

    public function __construct(CajaService $cajaService)
    {
        $this->cajaService = $cajaService;
    }

    public function index()
    {
        //
    }

    public function create(Cliente $cliente)
    {
        if (!$this->cajaService->hasCajaAbierta(Auth::user())) {
            return redirect()->route('cajas.index')
                ->with('error', '⚠️ Debes abrir tu caja de atención antes de iniciar o registrar un trámite.');
        }

        $bancos = Banco::activos()->orderBy('nombre')->get();
        $tarjetas = Tarjeta::with('banco')->activas()->orderBy('nombre')->get();
        $cajaAbierta = $this->cajaService->getCajaAbierta(Auth::user());

        return view('poderes.create', compact('cliente', 'bancos', 'tarjetas', 'cajaAbierta'));
    }

    public function store(Request $request, Cliente $cliente)
    {
        $request->validate([
            'ofifirmar' => 'required|string',
            'fecha' => 'required|date',
            'estcivil' => 'required|string',
            'honorarios' => 'required|numeric|min:0',
            'abono' => 'required|numeric|min:0',
            'nombres_otorga' => 'required|string',
            'cedula_otorga' => 'required|string',
            'razon_poder' => 'required|string',
            'opcion_envio_poder' => 'required|string',
            'metodo_pago' => 'nullable|string|in:Efectivo,Cheque,Transferencia,Tarjeta,Crédito',
            'banco_id' => 'nullable|exists:bancos,id',
            'tarjeta_id' => 'nullable|exists:tarjetas,id',
            'numero_referencia' => 'nullable|string|max:100',
        ]);

        $honorarios = (float) $request->honorarios;
        $abono = (float) $request->abono;
        $saldo = max(0, $honorarios - $abono);

        $user = Auth::user();
        $caja = $this->cajaService->getCajaAbierta($user);

        if ($abono > 0 && !$caja) {
            return redirect()->back()->withInput()->with('error', 'Debes abrir caja antes de registrar un trámite con cobro o abono inicial.');
        }

        DB::beginTransaction();

        try {
            $tramite = TramitePoder::create([
                'id_cliente' => $cliente->id_cliente,
                'id_usuario' => Auth::id(),
                'tp_oficina' => Auth::user()->office ?? '',
                'tp_fecha' => $request->fecha,
                'tp_firmar_en' => $request->ofifirmar,
                'tp_estado_civil' => $request->estcivil,
                'tp_nombres_otorga_poder' => $request->nombres_otorga,
                'tp_cedulla_otorga_poder' => $request->cedula_otorga,
                'tp_nombres_otorga_poder2' => $request->nombres_otorga2,
                'tp_cedulla_otorga_poder2' => $request->cedula_otorga2,
                'tp_razon_otorga_poder' => $request->razon_poder,
                'tp_opcion_envio_poder' => $request->opcion_envio_poder,
                'tp_enviar_nombrede' => $request->remitente,
                'tp_ciudad_enviar' => $request->ciudad_r,
                'tp_provincia' => $request->provincia_r,
                'tp_telefonos_enviar' => $request->telefono_r,
                'tp_observaciones' => $request->observaciones_p ?? '',
                'tp_costo_tramite' => $honorarios,
                'tp_abono_tramite' => $abono,
                'tp_saldo' => $saldo,
                'tp_nombre2' => $request->nombre2,
                'tp_apellido2' => $request->apellido2,
                'tp_identificacion2' => $request->identificacion2,
                'tp_telefono2' => $request->telefono2,
            ]);

            if ($abono > 0 && $caja) {
                $pago = Pago::create([
                    'cliente_id' => $cliente->id_cliente,
                    'monto' => $abono,
                    'concepto' => 'Abono Inicial - Poder #' . $tramite->id_tram_poderes,
                    'usuario' => Auth::user()->name,
                    'oficina' => Auth::user()->office ?? '',
                    'caja_sesion_id' => $caja->id,
                    'metodo_pago' => $request->metodo_pago ?? 'Efectivo',
                    'banco_id' => $request->banco_id,
                    'tarjeta_id' => $request->tarjeta_id,
                    'numero_referencia' => $request->numero_referencia,
                ]);

                $this->cajaService->registrarMovimiento([
                    'caja_sesion_id' => $caja->id,
                    'user_id' => Auth::id(),
                    'cliente_id' => $cliente->id_cliente,
                    'tipo' => 'ingreso_tramite',
                    'monto' => $abono,
                    'metodo_pago' => $request->metodo_pago ?? 'Efectivo',
                    'banco_id' => $request->banco_id,
                    'tarjeta_id' => $request->tarjeta_id,
                    'pago_id' => $pago->id,
                    'concepto' => 'Abono Inicial - Poder #' . $tramite->id_tram_poderes,
                    'numero_referencia' => $request->numero_referencia,
                    'tramite_tipo' => 'Poder',
                    'tramite_id' => $tramite->id_tram_poderes,
                ]);
            }

            $cliente->c_saldo += $saldo;
            $cliente->c_abonado += $abono;
            $cliente->save();

            DB::commit();

            return redirect()->route('clientes.tramites', $cliente->id_cliente)
                             ->with('success', 'Trámite de poder registrado correctamente.')
                             ->with('imprimir_tramite', route('poderes.show', $tramite->id_tram_poderes))
                             ->with('imprimir_recibo', $abono > 0 ? route('clientes.recibo_abono', ['cliente' => $cliente->id_cliente, 'monto' => $abono]) : null);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                             ->withInput()
                             ->with('error', 'Error al guardar el trámite: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $tramite = TramitePoder::with(['cliente', 'usuario'])->findOrFail($id);
        $cliente = $tramite->cliente;
        
        $data = [
            'tramite' => $tramite,
            'cliente' => $cliente
        ];

        $pdf = Pdf::loadView('poderes.recibo_pdf', $data);
        return $pdf->stream('Tramite_Poder_' . $tramite->id_tram_poderes . '.pdf');
    }
}
