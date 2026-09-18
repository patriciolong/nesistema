<?php

namespace App\Http\Controllers;

use App\Models\CajaSesion;
use App\Models\CajaMovimiento;
use App\Models\User;
use App\Models\Oficina;
use App\Services\CajaService;
use App\Exports\CajasExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class CajaReporteController extends Controller
{
    protected CajaService $cajaService;

    public function __construct(CajaService $cajaService)
    {
        $this->cajaService = $cajaService;
    }

    private function checkAdmin()
    {
        if (!auth()->check() || !auth()->user()->hasPermission('reportes.cajas')) {
            abort(403, 'Acceso denegado: No cuentas con el permiso necesario para auditar reportes de caja.');
        }
    }

    private function buildQuery(Request $request)
    {
        $query = CajaSesion::with('user');

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('oficina')) {
            $query->where('oficina', $request->oficina);
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('cuadre')) {
            if ($request->cuadre === 'cuadrado') {
                $query->where('cuadrado', true);
            } elseif ($request->cuadre === 'descuadrado') {
                $query->where('estado', 'cerrada')->where('cuadrado', false);
            }
        }

        if ($request->filled('fecha_desde')) {
            $query->whereDate('fecha_apertura', '>=', $request->fecha_desde);
        }

        if ($request->filled('fecha_hasta')) {
            $query->whereDate('fecha_apertura', '<=', $request->fecha_hasta);
        }

        return $query;
    }

    /**
     * Main Admin Cash Registers Report.
     */
    public function index(Request $request)
    {
        $this->checkAdmin();

        $query = $this->buildQuery($request);

        // Compute aggregate metrics
        $statsQuery = clone $query;
        $totalRecaudado = (float) $statsQuery->sum('total_sistema_total');
        $totalEfectivo = (float) (clone $query)->sum('total_sistema_efectivo');
        $totalTarjeta = (float) (clone $query)->sum('total_sistema_tarjeta');
        $totalTransferencia = (float) (clone $query)->sum('total_sistema_transferencia');
        $totalCheque = (float) (clone $query)->sum('total_sistema_cheque');
        $totalZelle = (float) (clone $query)->sum('total_sistema_zelle');
        $cajasAbiertas = (clone $query)->where('estado', 'abierta')->count();
        $cajasCerradas = (clone $query)->where('estado', 'cerrada')->count();

        $cajas = $query->orderBy('fecha_apertura', 'desc')->paginate(15);

        $usuarios = User::orderBy('name')->get();
        $oficinas = Oficina::where('status', 'Activa')->orderBy('nombre')->get();

        $stats = [
            'total_recaudado' => $totalRecaudado,
            'total_efectivo' => $totalEfectivo,
            'total_tarjeta' => $totalTarjeta,
            'total_transferencia' => $totalTransferencia,
            'total_cheque' => $totalCheque,
            'total_zelle' => $totalZelle,
            'cajas_abiertas' => $cajasAbiertas,
            'cajas_cerradas' => $cajasCerradas,
        ];

        return view('cajas.reportes.index', compact('cajas', 'usuarios', 'oficinas', 'stats'));
    }

    /**
     * Export cash registers report to Excel.
     */
    public function export(Request $request)
    {
        $this->checkAdmin();
        $query = $this->buildQuery($request);

        return Excel::download(new CajasExport($query), 'reporte_cajas_' . now()->format('Ymd_His') . '.xlsx');
    }

    /**
     * Show detailed audit of a specific cash register session.
     */
    public function show($id)
    {
        $this->checkAdmin();

        $caja = CajaSesion::with(['user', 'movimientos.cliente', 'movimientos.banco', 'movimientos.tarjeta'])->findOrFail($id);
        $totales = $this->cajaService->calcularTotalesSistema($caja);

        $movimientosPorMetodo = [
            'Efectivo' => $caja->movimientos->where('metodo_pago', 'Efectivo'),
            'Tarjeta' => $caja->movimientos->where('metodo_pago', 'Tarjeta'),
            'Transferencia' => $caja->movimientos->where('metodo_pago', 'Transferencia'),
            'Cheque' => $caja->movimientos->where('metodo_pago', 'Cheque'),
            'Zelle' => $caja->movimientos->where('metodo_pago', 'Zelle'),
            'Crédito' => $caja->movimientos->where('metodo_pago', 'Crédito'),
        ];

        return view('cajas.reportes.show', compact('caja', 'totales', 'movimientosPorMetodo'));
    }

    /**
     * Cashier Performance & Audit Dashboard.
     */
    public function desempeno(Request $request)
    {
        $this->checkAdmin();

        $fechaDesde = $request->input('fecha_desde', now()->startOfMonth()->toDateString());
        $fechaHasta = $request->input('fecha_hasta', now()->toDateString());

        // Performance query grouped by user
        $cajeros = User::withCount([
            'cajaSesiones' => function ($q) use ($fechaDesde, $fechaHasta) {
                $q->whereBetween(DB::raw('DATE(fecha_apertura)'), [$fechaDesde, $fechaHasta]);
            },
            'cajaSesiones as cajas_cuadradas_count' => function ($q) use ($fechaDesde, $fechaHasta) {
                $q->where('cuadrado', true)
                  ->whereBetween(DB::raw('DATE(fecha_apertura)'), [$fechaDesde, $fechaHasta]);
            }
        ])
        ->withSum([
            'cajaSesiones as total_recaudado' => function ($q) use ($fechaDesde, $fechaHasta) {
                $q->whereBetween(DB::raw('DATE(fecha_apertura)'), [$fechaDesde, $fechaHasta]);
            }
        ], 'total_sistema_total')
        ->withSum([
            'cajaSesiones as total_efectivo' => function ($q) use ($fechaDesde, $fechaHasta) {
                $q->whereBetween(DB::raw('DATE(fecha_apertura)'), [$fechaDesde, $fechaHasta]);
            }
        ], 'total_sistema_efectivo')
        ->withSum([
            'cajaSesiones as total_tarjeta' => function ($q) use ($fechaDesde, $fechaHasta) {
                $q->whereBetween(DB::raw('DATE(fecha_apertura)'), [$fechaDesde, $fechaHasta]);
            }
        ], 'total_sistema_tarjeta')
        ->withSum([
            'cajaSesiones as total_transferencia' => function ($q) use ($fechaDesde, $fechaHasta) {
                $q->whereBetween(DB::raw('DATE(fecha_apertura)'), [$fechaDesde, $fechaHasta]);
            }
        ], 'total_sistema_transferencia')
        ->withSum([
            'cajaSesiones as total_cheque' => function ($q) use ($fechaDesde, $fechaHasta) {
                $q->whereBetween(DB::raw('DATE(fecha_apertura)'), [$fechaDesde, $fechaHasta]);
            }
        ], 'total_sistema_cheque')
        ->withSum([
            'cajaSesiones as total_zelle' => function ($q) use ($fechaDesde, $fechaHasta) {
                $q->whereBetween(DB::raw('DATE(fecha_apertura)'), [$fechaDesde, $fechaHasta]);
            }
        ], 'total_sistema_zelle')
        ->get();

        // Get total transactions per user
        $movimientosTotales = CajaMovimiento::select('user_id', DB::raw('count(*) as transacciones_count'), DB::raw('sum(monto) as volumen_total'))
            ->whereBetween(DB::raw('DATE(created_at)'), [$fechaDesde, $fechaHasta])
            ->groupBy('user_id')
            ->pluck('transacciones_count', 'user_id');

        return view('cajas.reportes.desempeno', compact('cajeros', 'movimientosTotales', 'fechaDesde', 'fechaHasta'));
    }
}
