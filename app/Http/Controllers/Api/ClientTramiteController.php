<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\DocumentoGenerado;
use App\Models\TipoTramite;
use App\Models\TramitePersonalizado;
use App\Models\TramiteVario;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClientTramiteController extends Controller
{
    /**
     * Get all procedures for the authenticated client, grouped & categorized.
     */
    public function index(Request $request): JsonResponse
    {
        /** @var Cliente $cliente */
        $cliente = $request->attributes->get('cliente');
        $clienteId = $cliente->id_cliente;

        $categoriaFilter = $request->input('categoria', 'todas'); // todas, poderes, divorcios, impuestos, varios, formularios, documentos
        $estadoFilter = $request->input('estado', 'todos'); // todos, en_proceso, en_revision, listo, entregado
        $buscar = strtolower(trim($request->input('buscar', '')));

        $tramites = collect();

        // 1. Trámites Personalizados / Formularios Dinámicos
        if ($categoriaFilter === 'todas' || $categoriaFilter === 'formularios') {
            $personalizados = TramitePersonalizado::with('tipoTramite')
                ->where('id_cliente', $clienteId)
                ->orderBy('created_at', 'desc')
                ->get();

            foreach ($personalizados as $tp) {
                $nombre = $tp->tipoTramite ? $tp->tipoTramite->nombre : 'Trámite Personalizado';
                $color = $tp->tipoTramite ? $tp->tipoTramite->color_gradient : 'linear-gradient(135deg, #4f46e5, #7c3aed)';
                $fechaRaw = $tp->created_at ?: ($tp->fecha ?: now());
                $fechaStr = is_string($fechaRaw) ? $fechaRaw : $fechaRaw->format('Y-m-d H:i:s');
                
                $tramites->push([
                    'id' => (int) $tp->id,
                    'categoria' => 'formularios',
                    'categoria_label' => 'Formulario Notarial',
                    'categoria_icono' => 'document-text',
                    'nombre' => $nombre,
                    'descripcion' => $tp->observaciones ?: 'Formulario dinámico registrado',
                    'estado' => $tp->estado ?: 'en_proceso',
                    'estado_label' => $this->getEstadoLabel($tp->estado ?: 'en_proceso'),
                    'estado_color' => $this->getEstadoColor($tp->estado ?: 'en_proceso'),
                    'color_badge' => $color,
                    'fecha' => date('Y-m-d', strtotime($fechaStr)),
                    'fecha_formateada' => date('d M, Y', strtotime($fechaStr)),
                    'fecha_completado' => !empty($tp->fecha_completado) ? date('d M, Y h:i A', strtotime((string)$tp->fecha_completado)) : null,
                    'oficina' => $tp->oficina ?: $cliente->c_oficina_registro,
                    'asesor' => $tp->usuario ?: 'Asesor Notarial',
                    'costo_total' => (float) $tp->valor_tramite,
                    'abono' => (float) $tp->abono_tramite,
                    'saldo' => (float) $tp->saldo,
                    'detalles' => $tp->datos_formulario ?: [],
                    'porcentaje_avance' => $this->getPorcentajeAvance($tp->estado ?: 'en_proceso'),
                ]);
            }
        }

        // 2. Poderes
        if ($categoriaFilter === 'todas' || $categoriaFilter === 'poderes') {
            $poderes = DB::table('tramite_poderes')
                ->where('id_cliente', $clienteId)
                ->orderBy('id_tram_poderes', 'desc')
                ->get();

            foreach ($poderes as $p) {
                $fechaRaw = $p->tp_fecha ?: date('Y-m-d');
                $tramites->push([
                    'id' => (int) $p->id_tram_poderes,
                    'categoria' => 'poderes',
                    'categoria_label' => 'Poder Notarial',
                    'categoria_icono' => 'document-duplicate',
                    'nombre' => 'Poder Notarial: ' . ($p->tp_razon_otorga_poder ?: 'General'),
                    'descripcion' => "Otorga poder a: {$p->tp_nombres_otorga_poder} (C.I: {$p->tp_cedulla_otorga_poder})",
                    'estado' => $p->estado ?: 'en_proceso',
                    'estado_label' => $this->getEstadoLabel($p->estado ?: 'en_proceso'),
                    'estado_color' => $this->getEstadoColor($p->estado ?: 'en_proceso'),
                    'color_badge' => 'linear-gradient(135deg, #0284c7, #0369a1)',
                    'fecha' => date('Y-m-d', strtotime($fechaRaw)),
                    'fecha_formateada' => date('d M, Y', strtotime($fechaRaw)),
                    'fecha_completado' => !empty($p->fecha_completado) ? date('d M, Y h:i A', strtotime((string)$p->fecha_completado)) : null,
                    'oficina' => $p->tp_oficina ?: $cliente->c_oficina_registro,
                    'asesor' => 'Asesoría Legal',
                    'costo_total' => (float) $p->tp_costo_tramite,
                    'abono' => (float) $p->tp_abono_tramite,
                    'saldo' => (float) $p->tp_saldo,
                    'detalles' => [
                        'Apoderado' => $p->tp_nombres_otorga_poder,
                        'Identificación Apoderado' => $p->tp_cedulla_otorga_poder,
                        'Razón' => $p->tp_razon_otorga_poder,
                        'Envío a' => $p->tp_enviar_nombrede ?: 'N/A',
                        'Ciudad' => $p->tp_ciudad_enviar ?: 'N/A',
                    ],
                    'porcentaje_avance' => $this->getPorcentajeAvance($p->estado ?: 'en_proceso'),
                ]);
            }
        }

        // 3. Divorcios
        if ($categoriaFilter === 'todas' || $categoriaFilter === 'divorcios') {
            $divorcios = DB::table('tramite_divorcio')
                ->where('id_cliente', $clienteId)
                ->orderBy('id_tram_div', 'desc')
                ->get();

            foreach ($divorcios as $d) {
                $tipoDiv = $d->td_consensual ? 'Mutuo Consentimiento' : ($d->td_controvertido ? 'Controvertido' : 'Notarial');
                $fechaRaw = $d->td_fecha ?: date('Y-m-d');
                $tramites->push([
                    'id' => (int) $d->id_tram_div,
                    'categoria' => 'divorcios',
                    'categoria_label' => 'Trámite de Divorcio',
                    'categoria_icono' => 'heart-broken',
                    'nombre' => "Divorcio: {$tipoDiv}",
                    'descripcion' => "Cónyuge: {$d->td_nombre_c} ({$d->td_identificacion_c})",
                    'estado' => $d->estado ?: 'en_proceso',
                    'estado_label' => $this->getEstadoLabel($d->estado ?: 'en_proceso'),
                    'estado_color' => $this->getEstadoColor($d->estado ?: 'en_proceso'),
                    'color_badge' => 'linear-gradient(135deg, #e11d48, #be123c)',
                    'fecha' => date('Y-m-d', strtotime($fechaRaw)),
                    'fecha_formateada' => date('d M, Y', strtotime($fechaRaw)),
                    'fecha_completado' => !empty($d->fecha_completado) ? date('d M, Y h:i A', strtotime((string)$d->fecha_completado)) : null,
                    'oficina' => $d->td_oficina ?: $cliente->c_oficina_registro,
                    'asesor' => 'Departamento Legal',
                    'costo_total' => (float) $d->td_valor,
                    'abono' => (float) $d->td_abono,
                    'saldo' => (float) $d->td_saldo,
                    'detalles' => [
                        'Cónyuge' => $d->td_nombre_c,
                        'Identificación Cónyuge' => $d->td_identificacion_c,
                        'Lugar de Matrimonio' => $d->td_lugar_matrimonio ?: 'N/A',
                        'Fecha Matrimonio' => $d->td_fecha_matrimonio ?: 'N/A',
                        'Tipo de Proceso' => $tipoDiv,
                    ],
                    'porcentaje_avance' => $this->getPorcentajeAvance($d->estado ?: 'en_proceso'),
                ]);
            }
        }

        // 4. Impuestos (Taxes / ITIN)
        if ($categoriaFilter === 'todas' || $categoriaFilter === 'impuestos') {
            $impuestos = DB::table('tramite_impuestos')
                ->where('id_cliente', $clienteId)
                ->orderBy('id_tram_impuestos', 'desc')
                ->get();

            foreach ($impuestos as $imp) {
                $fechaRaw = $imp->ti_fecha ?: date('Y-m-d');
                $tramites->push([
                    'id' => (int) $imp->id_tram_impuestos,
                    'categoria' => 'impuestos',
                    'categoria_label' => 'Impuestos / Tax Return',
                    'categoria_icono' => 'calculator',
                    'nombre' => 'Declaración de Impuestos ' . ($imp->ti_anio_reporte ?: date('Y')),
                    'descripcion' => "Reporte de impuestos año {$imp->ti_anio_reporte} - ITIN/SSN",
                    'estado' => $imp->estado ?: 'en_proceso',
                    'estado_label' => $this->getEstadoLabel($imp->estado ?: 'en_proceso'),
                    'estado_color' => $this->getEstadoColor($imp->estado ?: 'en_proceso'),
                    'color_badge' => 'linear-gradient(135deg, #059669, #047857)',
                    'fecha' => date('Y-m-d', strtotime($fechaRaw)),
                    'fecha_formateada' => date('d M, Y', strtotime($fechaRaw)),
                    'fecha_completado' => !empty($imp->fecha_completado) ? date('d M, Y h:i A', strtotime((string)$imp->fecha_completado)) : null,
                    'oficina' => $imp->ti_oficina ?: $cliente->c_oficina_registro,
                    'asesor' => 'Contabilidad / Taxes',
                    'costo_total' => (float) $imp->ti_costo_tramite,
                    'abono' => (float) $imp->ti_abono_tramite,
                    'saldo' => (float) $imp->ti_saldo,
                    'detalles' => [
                        'Año Reporte' => $imp->ti_anio_reporte,
                        'Profesión' => $imp->ti_profesion ?: 'N/A',
                        'Estado Civil' => $imp->ti_ecivil ?: 'N/A',
                        'Dependientes' => $imp->ti_dependientes ?: '0',
                    ],
                    'porcentaje_avance' => $this->getPorcentajeAvance($imp->estado ?: 'en_proceso'),
                ]);
            }
        }

        // 5. Trámites Varios
        if ($categoriaFilter === 'todas' || $categoriaFilter === 'varios') {
            $varios = TramiteVario::where('id_cliente', $clienteId)
                ->orderBy('id_tramite_varios', 'desc')
                ->get();

            foreach ($varios as $tv) {
                $fechaRaw = $tv->tv_fecha ?: date('Y-m-d');
                $tramites->push([
                    'id' => (int) $tv->id_tramite_varios,
                    'categoria' => 'varios',
                    'categoria_label' => 'Trámite Notarial Vario',
                    'categoria_icono' => 'folder',
                    'nombre' => 'Trámite: ' . ($tv->tv_motivo ?: 'Gestión Notarial'),
                    'descripcion' => $tv->tv_observaciones ?: ($tv->tv_tip_documento ?: 'Servicio notarial múltiple'),
                    'estado' => $tv->estado ?: 'en_proceso',
                    'estado_label' => $this->getEstadoLabel($tv->estado ?: 'en_proceso'),
                    'estado_color' => $this->getEstadoColor($tv->estado ?: 'en_proceso'),
                    'color_badge' => 'linear-gradient(135deg, #d97706, #b45309)',
                    'fecha' => date('Y-m-d', strtotime($fechaRaw)),
                    'fecha_formateada' => date('d M, Y', strtotime($fechaRaw)),
                    'fecha_completado' => !empty($tv->fecha_completado) ? date('d M, Y h:i A', strtotime((string)$tv->fecha_completado)) : null,
                    'oficina' => $tv->tv_oficina ?: $cliente->c_oficina_registro,
                    'asesor' => 'Notaría',
                    'costo_total' => (float) $tv->tv_valor_tramite,
                    'abono' => (float) $tv->tv_abono_tramite,
                    'saldo' => (float) $tv->tv_saldo,
                    'detalles' => [
                        'Tipo Documento' => $tv->tv_tip_documento ?: 'N/A',
                        'Notarización' => $tv->tv_notarizacion ? 'Sí' : 'No',
                        'Apostilla' => $tv->tv_apostilla ? 'Sí' : 'No',
                        'Traducción' => $tv->tv_traducciones ? 'Sí' : 'No',
                        'Certificación' => $tv->tv_certificacion ? 'Sí' : 'No',
                    ],
                    'porcentaje_avance' => $this->getPorcentajeAvance($tv->estado ?: 'en_proceso'),
                ]);
            }
        }

        // 6. Documentos Notariales Emitidos
        if ($categoriaFilter === 'todas' || $categoriaFilter === 'documentos') {
            $docs = DocumentoGenerado::with('plantilla')
                ->where('id_cliente', $clienteId)
                ->orderBy('created_at', 'desc')
                ->get();

            foreach ($docs as $doc) {
                $nombreDoc = $doc->plantilla ? $doc->plantilla->nombre_plantilla : 'Acta / Documento Notarial';
                $fechaRaw = $doc->created_at ?: now();
                $fechaStr = is_string($fechaRaw) ? $fechaRaw : $fechaRaw->format('Y-m-d H:i:s');
                $tramites->push([
                    'id' => (int) $doc->id,
                    'categoria' => 'documentos',
                    'categoria_label' => 'Acta Notarial Oficial',
                    'categoria_icono' => 'document-check',
                    'nombre' => $nombreDoc,
                    'descripcion' => 'Documento notarial certificado emitido para el cliente',
                    'estado' => $doc->estado ?: 'listo',
                    'estado_label' => $this->getEstadoLabel($doc->estado ?: 'listo'),
                    'estado_color' => $this->getEstadoColor($doc->estado ?: 'listo'),
                    'color_badge' => 'linear-gradient(135deg, #10b981, #059669)',
                    'fecha' => date('Y-m-d', strtotime($fechaStr)),
                    'fecha_formateada' => date('d M, Y', strtotime($fechaStr)),
                    'fecha_completado' => !empty($doc->fecha_completado) ? date('d M, Y h:i A', strtotime((string)$doc->fecha_completado)) : date('d M, Y h:i A', strtotime($fechaStr)),
                    'oficina' => $cliente->c_oficina_registro,
                    'asesor' => $doc->usuario_creador ?: 'Notaría NESISTEMA',
                    'costo_total' => 0.0,
                    'abono' => 0.0,
                    'saldo' => 0.0,
                    'detalles' => [
                        'Tipo' => 'Documento Notarial Generado',
                        'Plantilla' => $nombreDoc,
                        'Fecha Emisión' => date('d/m/Y h:i A', strtotime($fechaStr)),
                    ],
                    'porcentaje_avance' => 100,
                ]);
            }
        }

        // Aplicar filtros de estado si corresponde
        if ($estadoFilter !== 'todos') {
            $tramites = $tramites->filter(function ($t) use ($estadoFilter) {
                return $t['estado'] === $estadoFilter;
            });
        }

        // Aplicar filtro de búsqueda si corresponde
        if ($buscar !== '') {
            $tramites = $tramites->filter(function ($t) use ($buscar) {
                return str_contains(strtolower($t['nombre']), $buscar) ||
                       str_contains(strtolower($t['descripcion']), $buscar) ||
                       str_contains(strtolower($t['categoria_label']), $buscar) ||
                       str_contains(strtolower($t['oficina']), $buscar);
            });
        }

        $allTramites = $tramites->sortByDesc('fecha')->values();

        // Calcular estadísticas
        $stats = [
            'total' => $allTramites->count(),
            'en_proceso' => $allTramites->where('estado', 'en_proceso')->count(),
            'en_revision' => $allTramites->where('estado', 'en_revision')->count(),
            'listos' => $allTramites->where('estado', 'listo')->count(),
            'entregados' => $allTramites->where('estado', 'entregado')->count(),
        ];

        return response()->json([
            'success' => true,
            'stats' => $stats,
            'tramites' => $allTramites,
        ]);
    }

    /**
     * Get single procedure detail with progress timeline.
     */
    public function show(Request $request, string $categoria, $id): JsonResponse
    {
        /** @var Cliente $cliente */
        $cliente = $request->attributes->get('cliente');

        // Reutilizamos index para obtener el item específico enriquecido
        $fakeRequest = new Request([
            'categoria' => $categoria,
            'estado' => 'todos',
        ]);
        $fakeRequest->attributes->set('cliente', $cliente);

        $response = $this->index($fakeRequest);
        $data = $response->getData(true);

        $item = collect($data['tramites'] ?? [])->firstWhere('id', (int) $id);

        if (!$item) {
            return response()->json([
                'success' => false,
                'message' => 'Trámite no encontrado.',
            ], 404);
        }

        // Construir la línea de tiempo de avance visual
        $timeline = [
            [
                'fase' => 1,
                'titulo' => 'Solicitud Recibida',
                'descripcion' => 'Recepción y apertura del expediente del trámite',
                'completado' => true,
                'activo' => false,
                'fecha' => $item['fecha_formateada'] ?: 'Iniciado',
            ],
            [
                'fase' => 2,
                'titulo' => 'En Elaboración Notarial',
                'descripcion' => 'Redacción legal y recopilación de documentación requerida',
                'completado' => in_array($item['estado'], ['en_revision', 'listo', 'entregado']),
                'activo' => $item['estado'] === 'en_proceso',
                'fecha' => $item['estado'] === 'en_proceso' ? 'En curso' : null,
            ],
            [
                'fase' => 3,
                'titulo' => 'En Revisión Legal / Firma',
                'descripcion' => 'Validación notarial, revisión de firmas y sellos oficiales',
                'completado' => in_array($item['estado'], ['listo', 'entregado']),
                'activo' => $item['estado'] === 'en_revision',
                'fecha' => $item['estado'] === 'en_revision' ? 'En revisión' : null,
            ],
            [
                'fase' => 4,
                'titulo' => '¡Listo para Entrega y Retiro!',
                'descripcion' => 'Trámite completado. Disponible para entrega en tu oficina notarial',
                'completado' => in_array($item['estado'], ['listo', 'entregado']),
                'activo' => $item['estado'] === 'listo',
                'fecha' => $item['fecha_completado'] ?: ($item['estado'] === 'listo' ? '¡Listo hoy!' : null),
            ],
        ];

        return response()->json([
            'success' => true,
            'tramite' => $item,
            'timeline' => $timeline,
        ]);
    }

    private function getEstadoLabel(string $estado): string
    {
        return match ($estado) {
            'listo' => 'Listo para Retiro',
            'en_revision' => 'En Revisión Legal',
            'entregado' => 'Entregado al Cliente',
            'cancelado' => 'Cancelado',
            default => 'En Proceso',
        };
    }

    private function getEstadoColor(string $estado): string
    {
        return match ($estado) {
            'listo' => '#10b981', // Emerald
            'en_revision' => '#3b82f6', // Blue
            'entregado' => '#64748b', // Slate
            'cancelado' => '#ef4444', // Red
            default => '#f59e0b', // Amber
        };
    }

    private function getPorcentajeAvance(string $estado): int
    {
        return match ($estado) {
            'listo' => 100,
            'entregado' => 100,
            'en_revision' => 70,
            'cancelado' => 0,
            default => 35,
        };
    }
}
