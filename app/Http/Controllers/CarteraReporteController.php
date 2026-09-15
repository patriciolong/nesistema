<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Oficina;
use App\Models\TramitePoder;
use App\Models\TramiteDivorcio;
use App\Models\TramiteImpuesto;
use App\Models\TramiteVario;
use App\Models\TramitePersonalizado;
use App\Exports\CarteraExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class CarteraReporteController extends Controller
{
    private function checkPermission()
    {
        if (!Auth::check() || (!Auth::user()->hasPermission('reportes.cartera') && !Auth::user()->hasPermission('cartera.view') && Auth::user()->role !== 'Administrador' && Auth::user()->role !== 'Supervisor')) {
            abort(403, 'Acceso denegado: No cuentas con el permiso necesario para auditar la reportería de cartera.');
        }
    }

    private function buildQuery(Request $request)
    {
        $query = Cliente::query();

        // 1. Buscador
        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function($q) use ($search) {
                $q->where('c_nombre', 'like', "%{$search}%")
                  ->orWhere('c_apellido', 'like', "%{$search}%")
                  ->orWhere('c_identificacion', 'like', "%{$search}%")
                  ->orWhere('c_telefono', 'like', "%{$search}%")
                  ->orWhere('c_email', 'like', "%{$search}%");
            });
        }

        // 2. Filtro por Oficina
        if ($request->filled('oficina')) {
            $query->where('c_oficina_registro', $request->input('oficina'));
        }

        // 3. Filtro por Estado de Deuda
        $filtroEstado = $request->input('filtro_estado', 'deudores');
        if ($filtroEstado === 'deudores') {
            $query->where('c_saldo', '>', 0);
        } elseif ($filtroEstado === 'al_dia') {
            $query->where('c_saldo', '<=', 0);
        }

        // 4. Filtro por Rango de Saldo
        if ($request->filled('monto_min')) {
            $query->where('c_saldo', '>=', floatval($request->input('monto_min')));
        }
        if ($request->filled('monto_max')) {
            $query->where('c_saldo', '<=', floatval($request->input('monto_max')));
        }

        // 5. Ordenamiento
        $orden = $request->input('orden', 'mayor_saldo');
        if ($orden === 'mayor_saldo') {
            $query->orderBy('c_saldo', 'desc');
        } elseif ($orden === 'menor_saldo') {
            $query->orderBy('c_saldo', 'asc');
        } elseif ($orden === 'mayor_facturado') {
            $query->orderBy('c_deuda', 'desc');
        } elseif ($orden === 'nombre') {
            $query->orderBy('c_nombre', 'asc')->orderBy('c_apellido', 'asc');
        } else {
            $query->orderBy('id_cliente', 'desc');
        }

        return $query;
    }

    /**
     * Main Executive Portfolio & Aging Report View.
     */
    public function index(Request $request)
    {
        $this->checkPermission();

        $query = $this->buildQuery($request);

        // Métricas Globales de Cartera
        $totalCarteraPorCobrar = Cliente::where('c_saldo', '>', 0)->sum('c_saldo');
        $totalDeudaHistorica = Cliente::sum('c_deuda');
        $totalAbonado = Cliente::sum('c_abonado');
        $clientesDeudoresCount = Cliente::where('c_saldo', '>', 0)->count();
        $clientesAlDiaCount = Cliente::where('c_saldo', '<=', 0)->count();
        $totalClientesCount = Cliente::count();

        $tasaRecuperacion = $totalDeudaHistorica > 0 ? ($totalAbonado / $totalDeudaHistorica) * 100 : 100;

        // Desglose de Saldo por Tipo de Trámite
        $saldoPoderes = floatval(TramitePoder::where('tp_saldo', '>', 0)->sum('tp_saldo'));
        $saldoDivorcios = floatval(TramiteDivorcio::where('td_saldo', '>', 0)->sum('td_saldo'));
        $saldoImpuestos = floatval(TramiteImpuesto::where('ti_saldo', '>', 0)->sum('ti_saldo'));
        $saldoVarios = floatval(TramiteVario::where('tv_saldo', '>', 0)->sum('tv_saldo'));
        $saldoPersonalizados = floatval(TramitePersonalizado::where('saldo', '>', 0)->sum('saldo'));

        $desgloseTramites = [
            'Poderes' => ['saldo' => $saldoPoderes, 'icon' => '📜', 'color' => 'amber'],
            'Divorcios' => ['saldo' => $saldoDivorcios, 'icon' => '💔', 'color' => 'rose'],
            'Impuestos / ITIN' => ['saldo' => $saldoImpuestos, 'icon' => '📑', 'color' => 'emerald'],
            'Trámites Varios' => ['saldo' => $saldoVarios, 'icon' => '📁', 'color' => 'indigo'],
            'Personalizados' => ['saldo' => $saldoPersonalizados, 'icon' => '✨', 'color' => 'purple'],
        ];

        // Desglose por Oficina de Registro
        $desgloseOficinas = Cliente::where('c_saldo', '>', 0)
            ->select('c_oficina_registro', DB::raw('SUM(c_saldo) as total_saldo'), DB::raw('SUM(c_deuda) as total_deuda'), DB::raw('COUNT(*) as total_deudores'))
            ->groupBy('c_oficina_registro')
            ->orderBy('total_saldo', 'desc')
            ->get();

        // Antigüedad de la Deuda (Aging por Fechas de Trámites Pendientes)
        $hace30Dias = now()->subDays(30)->toDateString();
        $hace60Dias = now()->subDays(60)->toDateString();
        $hace90Dias = now()->subDays(90)->toDateString();

        // Cálculo consolidado de aging
        $aging0a30 = 0;
        $aging31a60 = 0;
        $aging61a90 = 0;
        $agingMas90 = 0;

        // Poderes
        $aging0a30 += floatval(TramitePoder::where('tp_saldo', '>', 0)->whereDate('tp_fecha', '>=', $hace30Dias)->sum('tp_saldo'));
        $aging31a60 += floatval(TramitePoder::where('tp_saldo', '>', 0)->whereDate('tp_fecha', '<', $hace30Dias)->whereDate('tp_fecha', '>=', $hace60Dias)->sum('tp_saldo'));
        $aging61a90 += floatval(TramitePoder::where('tp_saldo', '>', 0)->whereDate('tp_fecha', '<', $hace60Dias)->whereDate('tp_fecha', '>=', $hace90Dias)->sum('tp_saldo'));
        $agingMas90 += floatval(TramitePoder::where('tp_saldo', '>', 0)->where(function($q) use ($hace90Dias) {
            $q->whereDate('tp_fecha', '<', $hace90Dias)->orWhereNull('tp_fecha');
        })->sum('tp_saldo'));

        // Divorcios
        $aging0a30 += floatval(TramiteDivorcio::where('td_saldo', '>', 0)->whereDate('td_fecha', '>=', $hace30Dias)->sum('td_saldo'));
        $aging31a60 += floatval(TramiteDivorcio::where('td_saldo', '>', 0)->whereDate('td_fecha', '<', $hace30Dias)->whereDate('td_fecha', '>=', $hace60Dias)->sum('td_saldo'));
        $aging61a90 += floatval(TramiteDivorcio::where('td_saldo', '>', 0)->whereDate('td_fecha', '<', $hace60Dias)->whereDate('td_fecha', '>=', $hace90Dias)->sum('td_saldo'));
        $agingMas90 += floatval(TramiteDivorcio::where('td_saldo', '>', 0)->where(function($q) use ($hace90Dias) {
            $q->whereDate('td_fecha', '<', $hace90Dias)->orWhereNull('td_fecha');
        })->sum('td_saldo'));

        // Impuestos
        $aging0a30 += floatval(TramiteImpuesto::where('ti_saldo', '>', 0)->whereDate('ti_fecha', '>=', $hace30Dias)->sum('ti_saldo'));
        $aging31a60 += floatval(TramiteImpuesto::where('ti_saldo', '>', 0)->whereDate('ti_fecha', '<', $hace30Dias)->whereDate('ti_fecha', '>=', $hace60Dias)->sum('ti_saldo'));
        $aging61a90 += floatval(TramiteImpuesto::where('ti_saldo', '>', 0)->whereDate('ti_fecha', '<', $hace60Dias)->whereDate('ti_fecha', '>=', $hace90Dias)->sum('ti_saldo'));
        $agingMas90 += floatval(TramiteImpuesto::where('ti_saldo', '>', 0)->where(function($q) use ($hace90Dias) {
            $q->whereDate('ti_fecha', '<', $hace90Dias)->orWhereNull('ti_fecha');
        })->sum('ti_saldo'));

        // Varios
        $aging0a30 += floatval(TramiteVario::where('tv_saldo', '>', 0)->whereDate('tv_fecha', '>=', $hace30Dias)->sum('tv_saldo'));
        $aging31a60 += floatval(TramiteVario::where('tv_saldo', '>', 0)->whereDate('tv_fecha', '<', $hace30Dias)->whereDate('tv_fecha', '>=', $hace60Dias)->sum('tv_saldo'));
        $aging61a90 += floatval(TramiteVario::where('tv_saldo', '>', 0)->whereDate('tv_fecha', '<', $hace60Dias)->whereDate('tv_fecha', '>=', $hace90Dias)->sum('tv_saldo'));
        $agingMas90 += floatval(TramiteVario::where('tv_saldo', '>', 0)->where(function($q) use ($hace90Dias) {
            $q->whereDate('tv_fecha', '<', $hace90Dias)->orWhereNull('tv_fecha');
        })->sum('tv_saldo'));

        // Personalizados
        $aging0a30 += floatval(TramitePersonalizado::where('saldo', '>', 0)->whereDate('created_at', '>=', $hace30Dias)->sum('saldo'));
        $aging31a60 += floatval(TramitePersonalizado::where('saldo', '>', 0)->whereDate('created_at', '<', $hace30Dias)->whereDate('created_at', '>=', $hace60Dias)->sum('saldo'));
        $aging61a90 += floatval(TramitePersonalizado::where('saldo', '>', 0)->whereDate('created_at', '<', $hace60Dias)->whereDate('created_at', '>=', $hace90Dias)->sum('saldo'));
        $agingMas90 += floatval(TramitePersonalizado::where('saldo', '>', 0)->where(function($q) use ($hace90Dias) {
            $q->whereDate('created_at', '<', $hace90Dias)->orWhereNull('created_at');
        })->sum('saldo'));

        $totalAging = $aging0a30 + $aging31a60 + $aging61a90 + $agingMas90;

        $aging = [
            '0_30' => ['monto' => $aging0a30, 'porcentaje' => $totalAging > 0 ? ($aging0a30 / $totalAging) * 100 : 0],
            '31_60' => ['monto' => $aging31a60, 'porcentaje' => $totalAging > 0 ? ($aging31a60 / $totalAging) * 100 : 0],
            '61_90' => ['monto' => $aging61a90, 'porcentaje' => $totalAging > 0 ? ($aging61a90 / $totalAging) * 100 : 0],
            'mas_90' => ['monto' => $agingMas90, 'porcentaje' => $totalAging > 0 ? ($agingMas90 / $totalAging) * 100 : 0],
            'total' => $totalAging,
        ];

        $clientes = $query->paginate(20)->withQueryString();

        $oficinas = Cliente::whereNotNull('c_oficina_registro')
            ->where('c_oficina_registro', '!=', '')
            ->distinct()
            ->pluck('c_oficina_registro');

        return view('reportes.cartera.index', compact(
            'clientes',
            'totalCarteraPorCobrar',
            'totalDeudaHistorica',
            'totalAbonado',
            'tasaRecuperacion',
            'clientesDeudoresCount',
            'clientesAlDiaCount',
            'totalClientesCount',
            'desgloseTramites',
            'desgloseOficinas',
            'aging',
            'oficinas'
        ));
    }

    /**
     * Generate PDF Executive Report.
     */
    public function pdf(Request $request)
    {
        $this->checkPermission();

        $query = $this->buildQuery($request);
        $clientes = $query->get();

        $totalCarteraPorCobrar = $clientes->where('c_saldo', '>', 0)->sum('c_saldo');
        $totalDeudaHistorica = $clientes->sum('c_deuda');
        $totalAbonado = $clientes->sum('c_abonado');
        $clientesDeudoresCount = $clientes->where('c_saldo', '>', 0)->count();

        $desgloseOficinas = $clientes->where('c_saldo', '>', 0)
            ->groupBy(function($c) {
                return $c->c_oficina_registro ?: 'Oficina General';
            })
            ->map(function($group, $ofi) {
                return [
                    'oficina' => $ofi,
                    'total_saldo' => $group->sum('c_saldo'),
                    'total_deuda' => $group->sum('c_deuda'),
                    'deudores_count' => $group->count(),
                ];
            })
            ->sortByDesc('total_saldo');

        $data = [
            'clientes' => $clientes,
            'totalCartera' => $totalCarteraPorCobrar,
            'totalDeuda' => $totalDeudaHistorica,
            'totalAbonado' => $totalAbonado,
            'deudoresCount' => $clientesDeudoresCount,
            'desgloseOficinas' => $desgloseOficinas,
            'usuario' => Auth::user()->name,
            'oficina' => Auth::user()->office ?? 'General',
            'fecha' => now()->format('d/m/Y H:i'),
            'filtros' => [
                'oficina' => $request->input('oficina'),
                'filtro_estado' => $request->input('filtro_estado', 'deudores'),
            ],
        ];

        $pdf = Pdf::loadView('reportes.cartera.pdf', $data)
            ->setPaper('a4', 'portrait');

        return $pdf->stream('Reporte_Cartera_' . now()->format('Ymd_His') . '.pdf');
    }

    /**
     * Export to Excel (.xlsx).
     */
    public function excel(Request $request)
    {
        $this->checkPermission();

        $query = $this->buildQuery($request);

        return Excel::download(
            new CarteraExport($query),
            'Reporte_Cartera_Clientes_' . now()->format('Ymd_His') . '.xlsx'
        );
    }
}
