<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\TipoTramite;
use App\Models\TramitePersonalizado;
use App\Models\TramiteVario;
use App\Models\DocumentoGenerado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TramiteController extends Controller
{
    /**
     * View the central hub of all procedures for a specific client.
     */
    public function index(Cliente $cliente)
    {
        // 1. Procedimientos nativos
        $tramitesVarios = TramiteVario::where('id_cliente', $cliente->id_cliente)->get();
        $divorcios = DB::table('tramite_divorcio')->where('id_cliente', $cliente->id_cliente)->get();
        $impuestos = DB::table('tramite_impuestos')->where('id_cliente', $cliente->id_cliente)->get();
        $poderes = DB::table('tramite_poderes')->where('id_cliente', $cliente->id_cliente)->get();

        // 2. Tipos de trámites personalizados activos
        $tiposPersonalizados = TipoTramite::where('activo', true)->orderBy('orden', 'asc')->get();

        // 3. Registros de trámites personalizados para este cliente
        $tramitesPersonalizados = TramitePersonalizado::with('tipoTramite')
            ->where('id_cliente', $cliente->id_cliente)
            ->orderBy('created_at', 'desc')
            ->get();

        // 4. Historial de Documentos Notariales Emitidos desde Plantillas
        $documentosGenerados = DocumentoGenerado::with('plantilla')
            ->where('id_cliente', $cliente->id_cliente)
            ->orderBy('created_at', 'desc')
            ->get();

        $tieneCajaAbierta = app(\App\Services\CajaService::class)->hasCajaAbierta(auth()->user());

        return view('tramites.index', compact(
            'cliente', 
            'tramitesVarios', 
            'divorcios', 
            'impuestos', 
            'poderes',
            'tiposPersonalizados',
            'tramitesPersonalizados',
            'documentosGenerados',
            'tieneCajaAbierta'
        ));
    }

    /**
     * Update the status of a procedure and notify the client if completed/ready.
     */
    public function cambiarEstado(Request $request)
    {
        $request->validate([
            'cliente_id' => 'required|integer',
            'tramite_tipo' => 'required|string|in:personalizados,poderes,divorcios,impuestos,varios,documentos',
            'tramite_id' => 'required|integer',
            'estado' => 'required|string|in:en_proceso,en_revision,listo,entregado,cancelado',
        ]);

        $clienteId = $request->input('cliente_id');
        $tramiteTipo = $request->input('tramite_tipo');
        $tramiteId = $request->input('tramite_id');
        $nuevoEstado = $request->input('estado');

        $nombreTramite = 'Trámite Notarial';
        $oficina = null;

        $updateData = ['estado' => $nuevoEstado];
        if ($nuevoEstado === 'listo' || $nuevoEstado === 'entregado') {
            $updateData['fecha_completado'] = now();
        }

        switch ($tramiteTipo) {
            case 'personalizados':
                $tramite = TramitePersonalizado::with('tipoTramite')->find($tramiteId);
                if ($tramite) {
                    $tramite->update($updateData);
                    $nombreTramite = $tramite->tipoTramite ? $tramite->tipoTramite->nombre : 'Trámite Personalizado';
                    $oficina = $tramite->oficina;
                }
                break;

            case 'poderes':
                $tramite = DB::table('tramite_poderes')->where('id_tram_poderes', $tramiteId)->first();
                if ($tramite) {
                    DB::table('tramite_poderes')->where('id_tram_poderes', $tramiteId)->update($updateData);
                    $nombreTramite = 'Poder Notarial (' . ($tramite->tp_razon_otorga_poder ?: 'General') . ')';
                    $oficina = $tramite->tp_oficina;
                }
                break;

            case 'divorcios':
                $tramite = DB::table('tramite_divorcio')->where('id_tram_div', $tramiteId)->first();
                if ($tramite) {
                    DB::table('tramite_divorcio')->where('id_tram_div', $tramiteId)->update($updateData);
                    $nombreTramite = 'Trámite de Divorcio';
                    $oficina = $tramite->td_oficina;
                }
                break;

            case 'impuestos':
                $tramite = DB::table('tramite_impuestos')->where('id_tram_impuestos', $tramiteId)->first();
                if ($tramite) {
                    DB::table('tramite_impuestos')->where('id_tram_impuestos', $tramiteId)->update($updateData);
                    $nombreTramite = 'Declaración de Impuestos / ITIN';
                    $oficina = $tramite->ti_oficina;
                }
                break;

            case 'varios':
                $tramite = TramiteVario::find($tramiteId);
                if ($tramite) {
                    $tramite->update($updateData);
                    $nombreTramite = 'Trámite Vario: ' . ($tramite->tv_motivo ?: 'Gestión Notarial');
                    $oficina = $tramite->tv_oficina;
                }
                break;

            case 'documentos':
                $tramite = DocumentoGenerado::with('plantilla')->find($tramiteId);
                if ($tramite) {
                    $tramite->update($updateData);
                    $nombreTramite = $tramite->plantilla ? $tramite->plantilla->nombre_plantilla : 'Documento Notarial';
                }
                break;
        }

        // Si el estado cambió a 'listo', enviar notificación al cliente
        if ($nuevoEstado === 'listo') {
            app(\App\Services\ClientNotificationService::class)->notificarTramiteListo(
                $clienteId,
                $tramiteTipo,
                $tramiteId,
                $nombreTramite,
                $oficina
            );
        }

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => '¡Estado actualizado exitosamente!' . ($nuevoEstado === 'listo' ? ' Notificación enviada al cliente.' : ''),
                'nuevo_estado' => $nuevoEstado,
            ]);
        }

        return redirect()->back()->with('success', '¡Estado del trámite actualizado exitosamente!' . ($nuevoEstado === 'listo' ? ' Se envió una alerta y notificación al cliente.' : ''));
    }
}
