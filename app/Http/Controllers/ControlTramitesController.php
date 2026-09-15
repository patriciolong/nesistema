<?php

namespace App\Http\Controllers;

use App\Models\TramitePoder;
use App\Models\TramiteDivorcio;
use App\Models\TramiteImpuesto;
use App\Models\TramiteVario;
use App\Models\TramitePersonalizado;
use App\Models\Pago;
use App\Models\User;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class ControlTramitesController extends Controller
{
    /**
     * Verificar permisos para acceder al módulo de control de precios y desviaciones.
     */
    private function checkPermission()
    {
        if (!Auth::check() || (
            !Auth::user()->hasPermission('reportes.control_precios') &&
            !Auth::user()->hasPermission('reportes.cajas') &&
            Auth::user()->role !== 'Administrador' &&
            Auth::user()->role !== 'Supervisor'
        )) {
            abort(403, 'Acceso restringido: No dispones de los permisos requeridos para auditar el módulo de control de precios.');
        }
    }

    /**
     * Vista principal del módulo de control y auditoría de desviaciones de precios.
     */
    public function index(Request $request)
    {
        $this->checkPermission();

        // Parámetros de auditoría
        $precioBase = floatval($request->input('precio_base', 200.00));
        if ($precioBase <= 0) {
            $precioBase = 200.00;
        }

        $umbralPorc = floatval($request->input('umbral_porc', 5.0));
        if ($umbralPorc <= 0) {
            $umbralPorc = 5.0;
        }

        $filtroDesviacion = $request->input('filtro_desviacion', 'con_desviacion'); // 'con_desviacion', 'exceso', 'descuento', 'todos'
        $filtroTipo = $request->input('tipo_tramite', 'todos');
        $filtroUsuario = $request->input('usuario_id');
        $filtroOficina = $request->input('oficina');
        $fechaDesde = $request->input('fecha_desde');
        $fechaHasta = $request->input('fecha_hasta');
        $search = trim($request->input('search', ''));
        $orden = $request->input('orden', 'mayor_desviacion');

        // Límites de tolerancia
        $limiteSuperior = $precioBase * (1 + ($umbralPorc / 100));
        $limiteInferior = $precioBase * (1 - ($umbralPorc / 100));

        // 1. Obtener y normalizar todos los trámites
        $items = collect();

        // Cargar pagos asociados para mapeo rápido
        $pagosConsolidados = Pago::with(['cajaSesion.user', 'banco', 'tarjeta'])
            ->orderBy('id', 'desc')
            ->get()
            ->groupBy(function($p) {
                return $p->tramite_tipo . '_' . $p->tramite_id;
            });

        // 1.1 PODERES
        if ($filtroTipo === 'todos' || $filtroTipo === 'poder') {
            $poderes = TramitePoder::with(['cliente', 'usuario'])->get();
            foreach ($poderes as $p) {
                $costo = floatval($p->tp_costo_tramite);
                $diferencia = $costo - $precioBase;
                $desviacion = $precioBase > 0 ? ($diferencia / $precioBase) * 100 : 0;
                
                $pagoInfo = $pagosConsolidados->get('poder_' . $p->id_tram_poderes)?->first();

                $items->push([
                    'id' => $p->id_tram_poderes,
                    'tipo_key' => 'poder',
                    'tipo_nombre' => 'Trámite de Poder',
                    'tipo_icono' => '📜',
                    'tipo_color' => 'purple',
                    'cliente_id' => $p->id_cliente,
                    'cliente_nombre' => $p->cliente ? ($p->cliente->c_nombre . ' ' . $p->cliente->c_apellido) : 'Cliente No Asignado',
                    'cliente_identificacion' => $p->cliente->c_identificacion ?? 'S/I',
                    'cliente_telefono' => $p->cliente->c_telefono ?? 'S/T',
                    'cliente_email' => $p->cliente->c_email ?? '',
                    'cliente_direccion' => $p->cliente ? ($p->cliente->c_direccion . ($p->cliente->c_departamento ? ' Apt ' . $p->cliente->c_departamento : '')) : '',
                    'costo' => $costo,
                    'abono' => floatval($p->tp_abono_tramite),
                    'saldo' => floatval($p->tp_saldo),
                    'precio_base' => $precioBase,
                    'diferencia' => $diferencia,
                    'desviacion_porc' => $desviacion,
                    'tipo_desviacion' => $costo > $limiteSuperior ? 'exceso' : ($costo < $limiteInferior ? 'descuento' : 'regular'),
                    'fecha' => $p->tp_fecha ?: ($p->created_at ? $p->created_at->format('Y-m-d') : now()->toDateString()),
                    'hora' => $p->created_at ? $p->created_at->format('H:i A') : '09:00 AM',
                    'fecha_hora_formatted' => $p->tp_fecha ? date('d/m/Y', strtotime($p->tp_fecha)) : ($p->created_at ? $p->created_at->format('d/m/Y H:i') : date('d/m/Y')),
                    'usuario' => $p->usuario->name ?? 'Usuario Sistema',
                    'usuario_id' => $p->id_usuario,
                    'oficina' => $p->tp_oficina ?: 'Oficina General',
                    'observaciones' => $p->tp_observaciones ?: ($p->tp_razon_otorga_poder ?: 'Sin observaciones.'),
                    'route_pdf' => route('poderes.show', $p->id_tram_poderes),
                    'caja_id' => $pagoInfo?->caja_sesion_id,
                    'caja_estado' => $pagoInfo?->cajaSesion?->estado,
                    'cajero' => $pagoInfo?->cajaSesion?->user?->name ?? ($pagoInfo?->usuario ?? 'N/A'),
                    'metodo_pago' => $pagoInfo?->metodo_pago ?? 'Efectivo / Cartera',
                    'fecha_pago' => $pagoInfo?->created_at ? $pagoInfo->created_at->format('d/m/Y H:i') : null,
                ]);
            }
        }

        // 1.2 DIVORCIOS
        if ($filtroTipo === 'todos' || $filtroTipo === 'divorcio') {
            $divorcios = TramiteDivorcio::with(['cliente', 'usuario'])->get();
            foreach ($divorcios as $d) {
                $costo = floatval($d->td_valor);
                $diferencia = $costo - $precioBase;
                $desviacion = $precioBase > 0 ? ($diferencia / $precioBase) * 100 : 0;
                
                $pagoInfo = $pagosConsolidados->get('divorcio_' . $d->id_tram_div)?->first();

                $items->push([
                    'id' => $d->id_tram_div,
                    'tipo_key' => 'divorcio',
                    'tipo_nombre' => 'Registro de Divorcio',
                    'tipo_icono' => '⚖️',
                    'tipo_color' => 'blue',
                    'cliente_id' => $d->id_cliente,
                    'cliente_nombre' => $d->cliente ? ($d->cliente->c_nombre . ' ' . $d->cliente->c_apellido) : 'Cliente No Asignado',
                    'cliente_identificacion' => $d->cliente->c_identificacion ?? 'S/I',
                    'cliente_telefono' => $d->cliente->c_telefono ?? 'S/T',
                    'cliente_email' => $d->cliente->c_email ?? '',
                    'cliente_direccion' => $d->cliente ? ($d->cliente->c_direccion . ($d->cliente->c_departamento ? ' Apt ' . $d->cliente->c_departamento : '')) : '',
                    'costo' => $costo,
                    'abono' => floatval($d->td_abono),
                    'saldo' => floatval($d->td_saldo),
                    'precio_base' => $precioBase,
                    'diferencia' => $diferencia,
                    'desviacion_porc' => $desviacion,
                    'tipo_desviacion' => $costo > $limiteSuperior ? 'exceso' : ($costo < $limiteInferior ? 'descuento' : 'regular'),
                    'fecha' => $d->td_fecha ?: ($d->created_at ? $d->created_at->format('Y-m-d') : now()->toDateString()),
                    'hora' => $d->created_at ? $d->created_at->format('H:i A') : '09:00 AM',
                    'fecha_hora_formatted' => $d->td_fecha ? date('d/m/Y', strtotime($d->td_fecha)) : ($d->created_at ? $d->created_at->format('d/m/Y H:i') : date('d/m/Y')),
                    'usuario' => $d->usuario->name ?? 'Usuario Sistema',
                    'usuario_id' => $d->id_usuario,
                    'oficina' => $d->td_oficina ?: 'Oficina General',
                    'observaciones' => $d->td_observaciones ?: ($d->td_motivo_divorcio ?: 'Sin observaciones.'),
                    'route_pdf' => route('divorcios.show', $d->id_tram_div),
                    'caja_id' => $pagoInfo?->caja_sesion_id,
                    'caja_estado' => $pagoInfo?->cajaSesion?->estado,
                    'cajero' => $pagoInfo?->cajaSesion?->user?->name ?? ($pagoInfo?->usuario ?? 'N/A'),
                    'metodo_pago' => $pagoInfo?->metodo_pago ?? 'Efectivo / Cartera',
                    'fecha_pago' => $pagoInfo?->created_at ? $pagoInfo->created_at->format('d/m/Y H:i') : null,
                ]);
            }
        }

        // 1.3 IMPUESTOS
        if ($filtroTipo === 'todos' || $filtroTipo === 'impuesto') {
            $impuestos = TramiteImpuesto::with(['cliente', 'usuario'])->get();
            foreach ($impuestos as $ti) {
                $costo = floatval($ti->ti_costo_tramite);
                $diferencia = $costo - $precioBase;
                $desviacion = $precioBase > 0 ? ($diferencia / $precioBase) * 100 : 0;
                
                $pagoInfo = $pagosConsolidados->get('impuesto_' . $ti->id_tram_impuestos)?->first();

                $items->push([
                    'id' => $ti->id_tram_impuestos,
                    'tipo_key' => 'impuesto',
                    'tipo_nombre' => 'Declaración de Impuestos',
                    'tipo_icono' => '📑',
                    'tipo_color' => 'sky',
                    'cliente_id' => $ti->id_cliente,
                    'cliente_nombre' => $ti->cliente ? ($ti->cliente->c_nombre . ' ' . $ti->cliente->c_apellido) : 'Cliente No Asignado',
                    'cliente_identificacion' => $ti->cliente->c_identificacion ?? 'S/I',
                    'cliente_telefono' => $ti->cliente->c_telefono ?? 'S/T',
                    'cliente_email' => $ti->cliente->c_email ?? '',
                    'cliente_direccion' => $ti->cliente ? ($ti->cliente->c_direccion . ($ti->cliente->c_departamento ? ' Apt ' . $ti->cliente->c_departamento : '')) : '',
                    'costo' => $costo,
                    'abono' => floatval($ti->ti_abono_tramite),
                    'saldo' => floatval($ti->ti_saldo),
                    'precio_base' => $precioBase,
                    'diferencia' => $diferencia,
                    'desviacion_porc' => $desviacion,
                    'tipo_desviacion' => $costo > $limiteSuperior ? 'exceso' : ($costo < $limiteInferior ? 'descuento' : 'regular'),
                    'fecha' => $ti->ti_fecha ?: ($ti->created_at ? $ti->created_at->format('Y-m-d') : now()->toDateString()),
                    'hora' => $ti->created_at ? $ti->created_at->format('H:i A') : '09:00 AM',
                    'fecha_hora_formatted' => $ti->ti_fecha ? date('d/m/Y', strtotime($ti->ti_fecha)) : ($ti->created_at ? $ti->created_at->format('d/m/Y H:i') : date('d/m/Y')),
                    'usuario' => $ti->usuario->name ?? 'Usuario Sistema',
                    'usuario_id' => $ti->id_usuario,
                    'oficina' => $ti->ti_oficina ?: 'Oficina General',
                    'observaciones' => $ti->ti_observacion ?: 'Declaración de Taxes.',
                    'route_pdf' => route('impuestos.show', $ti->id_tram_impuestos),
                    'caja_id' => $pagoInfo?->caja_sesion_id,
                    'caja_estado' => $pagoInfo?->cajaSesion?->estado,
                    'cajero' => $pagoInfo?->cajaSesion?->user?->name ?? ($pagoInfo?->usuario ?? 'N/A'),
                    'metodo_pago' => $pagoInfo?->metodo_pago ?? 'Efectivo / Cartera',
                    'fecha_pago' => $pagoInfo?->created_at ? $pagoInfo->created_at->format('d/m/Y H:i') : null,
                ]);
            }
        }

        // 1.4 TRÁMITES VARIOS
        if ($filtroTipo === 'todos' || $filtroTipo === 'vario') {
            $varios = TramiteVario::with(['cliente', 'usuario'])->get();
            foreach ($varios as $v) {
                $costo = floatval($v->tv_valor_tramite);
                $diferencia = $costo - $precioBase;
                $desviacion = $precioBase > 0 ? ($diferencia / $precioBase) * 100 : 0;
                
                $pagoInfo = $pagosConsolidados->get('vario_' . $v->id_tramite_varios)?->first();

                $items->push([
                    'id' => $v->id_tramite_varios,
                    'tipo_key' => 'vario',
                    'tipo_nombre' => 'Trámite Varios',
                    'tipo_icono' => '📂',
                    'tipo_color' => 'indigo',
                    'cliente_id' => $v->id_cliente,
                    'cliente_nombre' => $v->cliente ? ($v->cliente->c_nombre . ' ' . $v->cliente->c_apellido) : 'Cliente No Asignado',
                    'cliente_identificacion' => $v->cliente->c_identificacion ?? 'S/I',
                    'cliente_telefono' => $v->cliente->c_telefono ?? 'S/T',
                    'cliente_email' => $v->cliente->c_email ?? '',
                    'cliente_direccion' => $v->cliente ? ($v->cliente->c_direccion . ($v->cliente->c_napartamento ? ' Apt ' . $v->cliente->c_napartamento : '')) : '',
                    'costo' => $costo,
                    'abono' => floatval($v->tv_abono_tramite),
                    'saldo' => floatval($v->tv_saldo),
                    'precio_base' => $precioBase,
                    'diferencia' => $diferencia,
                    'desviacion_porc' => $desviacion,
                    'tipo_desviacion' => $costo > $limiteSuperior ? 'exceso' : ($costo < $limiteInferior ? 'descuento' : 'regular'),
                    'fecha' => $v->tv_fecha ?: ($v->created_at ? $v->created_at->format('Y-m-d') : now()->toDateString()),
                    'hora' => $v->created_at ? $v->created_at->format('H:i A') : '09:00 AM',
                    'fecha_hora_formatted' => $v->tv_fecha ? date('d/m/Y', strtotime($v->tv_fecha)) : ($v->created_at ? $v->created_at->format('d/m/Y H:i') : date('d/m/Y')),
                    'usuario' => $v->usuario->name ?? 'Usuario Sistema',
                    'usuario_id' => $v->id_usuario,
                    'oficina' => $v->tv_oficina ?: 'Oficina General',
                    'observaciones' => $v->tv_observaciones ?: ($v->tv_motivo ?: 'Sin observaciones.'),
                    'route_pdf' => route('tramites_varios.show', $v->id_tramite_varios),
                    'caja_id' => $pagoInfo?->caja_sesion_id,
                    'caja_estado' => $pagoInfo?->cajaSesion?->estado,
                    'cajero' => $pagoInfo?->cajaSesion?->user?->name ?? ($pagoInfo?->usuario ?? 'N/A'),
                    'metodo_pago' => $pagoInfo?->metodo_pago ?? 'Efectivo / Cartera',
                    'fecha_pago' => $pagoInfo?->created_at ? $pagoInfo->created_at->format('d/m/Y H:i') : null,
                ]);
            }
        }

        // 1.5 PERSONALIZADOS
        if ($filtroTipo === 'todos' || $filtroTipo === 'personalizado') {
            $personalizados = TramitePersonalizado::with(['cliente', 'tipoTramite'])->get();
            foreach ($personalizados as $tp) {
                $costo = floatval($tp->valor_tramite);
                $diferencia = $costo - $precioBase;
                $desviacion = $precioBase > 0 ? ($diferencia / $precioBase) * 100 : 0;
                
                $pagoInfo = $pagosConsolidados->get('personalizado_' . $tp->id)?->first();

                $items->push([
                    'id' => $tp->id,
                    'tipo_key' => 'personalizado',
                    'tipo_nombre' => $tp->tipoTramite->nombre ?? 'Trámite Especial',
                    'tipo_icono' => '✨',
                    'tipo_color' => 'amber',
                    'cliente_id' => $tp->id_cliente,
                    'cliente_nombre' => $tp->cliente ? ($tp->cliente->c_nombre . ' ' . $tp->cliente->c_apellido) : 'Cliente No Asignado',
                    'cliente_identificacion' => $tp->cliente->c_identificacion ?? 'S/I',
                    'cliente_telefono' => $tp->cliente->c_telefono ?? 'S/T',
                    'cliente_email' => $tp->cliente->c_email ?? '',
                    'cliente_direccion' => $tp->cliente ? ($tp->cliente->c_direccion . ($tp->cliente->c_departamento ? ' Apt ' . $tp->cliente->c_departamento : '')) : '',
                    'costo' => $costo,
                    'abono' => floatval($tp->abono_tramite),
                    'saldo' => floatval($tp->saldo),
                    'precio_base' => $precioBase,
                    'diferencia' => $diferencia,
                    'desviacion_porc' => $desviacion,
                    'tipo_desviacion' => $costo > $limiteSuperior ? 'exceso' : ($costo < $limiteInferior ? 'descuento' : 'regular'),
                    'fecha' => $tp->fecha ? $tp->fecha->format('Y-m-d') : ($tp->created_at ? $tp->created_at->format('Y-m-d') : now()->toDateString()),
                    'hora' => $tp->created_at ? $tp->created_at->format('H:i A') : '09:00 AM',
                    'fecha_hora_formatted' => $tp->fecha ? $tp->fecha->format('d/m/Y') : ($tp->created_at ? $tp->created_at->format('d/m/Y H:i') : date('d/m/Y')),
                    'usuario' => $tp->usuario ?: 'Personal Autorizado',
                    'usuario_id' => null,
                    'oficina' => $tp->oficina ?: 'Oficina General',
                    'observaciones' => $tp->observaciones ?: 'Trámite personalizado emitido.',
                    'route_pdf' => route('tramites_personalizados.show', $tp->id),
                    'caja_id' => $pagoInfo?->caja_sesion_id,
                    'caja_estado' => $pagoInfo?->cajaSesion?->estado,
                    'cajero' => $pagoInfo?->cajaSesion?->user?->name ?? ($pagoInfo?->usuario ?? 'N/A'),
                    'metodo_pago' => $pagoInfo?->metodo_pago ?? 'Efectivo / Cartera',
                    'fecha_pago' => $pagoInfo?->created_at ? $pagoInfo->created_at->format('d/m/Y H:i') : null,
                ]);
            }
        }

        // Métricas globales antes del filtrado por desviación
        $totalAnalizados = $items->count();
        $totalExcesosCount = $items->where('tipo_desviacion', 'exceso')->count();
        $totalExcesosMonto = $items->where('tipo_desviacion', 'exceso')->sum('diferencia');

        $totalDescuentosCount = $items->where('tipo_desviacion', 'descuento')->count();
        $totalDescuentosMonto = abs($items->where('tipo_desviacion', 'descuento')->sum('diferencia'));

        $totalRegularesCount = $items->where('tipo_desviacion', 'regular')->count();
        $totalConDesviacion = $totalExcesosCount + $totalDescuentosCount;
        $promedioFacturado = $totalAnalizados > 0 ? $items->avg('costo') : 0;

        // 2. APLICAR FILTROS DE AUDITORÍA
        $filtrados = $items;

        // Filtro por tipo de desviación
        if ($filtroDesviacion === 'con_desviacion') {
            $filtrados = $filtrados->filter(function($item) {
                return $item['tipo_desviacion'] !== 'regular';
            });
        } elseif ($filtroDesviacion === 'exceso') {
            $filtrados = $filtrados->where('tipo_desviacion', 'exceso');
        } elseif ($filtroDesviacion === 'descuento') {
            $filtrados = $filtrados->where('tipo_desviacion', 'descuento');
        }

        // Filtro por Usuario
        if (!empty($filtroUsuario)) {
            $filtrados = $filtrados->filter(function($item) use ($filtroUsuario) {
                return $item['usuario_id'] == $filtroUsuario || stripos($item['usuario'], $filtroUsuario) !== false;
            });
        }

        // Filtro por Oficina
        if (!empty($filtroOficina)) {
            $filtrados = $filtrados->filter(function($item) use ($filtroOficina) {
                return $item['oficina'] === $filtroOficina;
            });
        }

        // Filtro por Fecha Desde
        if (!empty($fechaDesde)) {
            $filtrados = $filtrados->filter(function($item) use ($fechaDesde) {
                return $item['fecha'] >= $fechaDesde;
            });
        }

        // Filtro por Fecha Hasta
        if (!empty($fechaHasta)) {
            $filtrados = $filtrados->filter(function($item) use ($fechaHasta) {
                return $item['fecha'] <= $fechaHasta;
            });
        }

        // Búsqueda por texto (Nombre, Cédula, Teléfono, ID)
        if (!empty($search)) {
            $filtrados = $filtrados->filter(function($item) use ($search) {
                return stripos($item['cliente_nombre'], $search) !== false ||
                       stripos($item['cliente_identificacion'], $search) !== false ||
                       stripos($item['cliente_telefono'], $search) !== false ||
                       stripos((string)$item['id'], $search) !== false ||
                       stripos($item['tipo_nombre'], $search) !== false;
            });
        }

        // 3. ORDENAMIENTO
        switch ($orden) {
            case 'mayor_desviacion':
                $filtrados = $filtrados->sortByDesc(function($item) {
                    return abs($item['desviacion_porc']);
                });
                break;
            case 'menor_desviacion':
                $filtrados = $filtrados->sortBy(function($item) {
                    return abs($item['desviacion_porc']);
                });
                break;
            case 'mayor_precio':
                $filtrados = $filtrados->sortByDesc('costo');
                break;
            case 'menor_precio':
                $filtrados = $filtrados->sortBy('costo');
                break;
            case 'recientes':
            default:
                $filtrados = $filtrados->sortByDesc('fecha');
                break;
        }

        // 4. PAGINACIÓN
        $perPage = 20;
        $page = LengthAwarePaginator::resolveCurrentPage() ?: 1;
        $paginatedItems = new LengthAwarePaginator(
            $filtrados->forPage($page, $perPage)->values(),
            $filtrados->count(),
            $perPage,
            $page,
            ['path' => LengthAwarePaginator::resolveCurrentPath(), 'query' => $request->query()]
        );

        // Listas auxiliares para selects
        $usuarios = User::select('id', 'name')->orderBy('name')->get();
        $oficinas = Cliente::whereNotNull('c_oficina_registro')
            ->where('c_oficina_registro', '!=', '')
            ->distinct()
            ->pluck('c_oficina_registro');

        return view('control_tramites.index', [
            'tramites' => $paginatedItems,
            'totalAnalizados' => $totalAnalizados,
            'totalConDesviacion' => $totalConDesviacion,
            'totalExcesosCount' => $totalExcesosCount,
            'totalExcesosMonto' => $totalExcesosMonto,
            'totalDescuentosCount' => $totalDescuentosCount,
            'totalDescuentosMonto' => $totalDescuentosMonto,
            'totalRegularesCount' => $totalRegularesCount,
            'promedioFacturado' => $promedioFacturado,
            'precioBase' => $precioBase,
            'umbralPorc' => $umbralPorc,
            'limiteSuperior' => $limiteSuperior,
            'limiteInferior' => $limiteInferior,
            'usuarios' => $usuarios,
            'oficinas' => $oficinas,
        ]);
    }
}
