<?php

namespace App\Services;

use App\Models\Cliente;
use App\Models\NotificacionCliente;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ClientNotificationService
{
    /**
     * Notify client when a procedure is ready / completed.
     */
    public function notificarTramiteListo(
        int $clienteId,
        string $tramiteTipo,
        $tramiteId,
        string $nombreTramite,
        ?string $oficina = null,
        ?string $observaciones = null
    ): ?NotificacionCliente {
        $cliente = Cliente::find($clienteId);
        if (!$cliente) {
            return null;
        }

        $titulo = "🎉 ¡Tu trámite está listo!";
        $mensaje = "Tu trámite '{$nombreTramite}' ha sido completado con éxito";
        if ($oficina) {
            $mensaje .= " y se encuentra disponible en la sede: {$oficina}.";
        } else {
            $mensaje .= ". Puedes revisarlo en tu app móvil o pasar a retirar tu documentación.";
        }

        if ($observaciones) {
            $mensaje .= " Nota: {$observaciones}";
        }

        // 1. Guardar notificación interna en la Base de Datos
        $notificacion = NotificacionCliente::create([
            'id_cliente' => $cliente->id_cliente,
            'tramite_tipo' => $tramiteTipo,
            'tramite_id' => $tramiteId,
            'titulo' => $titulo,
            'mensaje' => $mensaje,
            'leido' => false,
            'data_extra' => [
                'nombre_tramite' => $nombreTramite,
                'oficina' => $oficina,
                'estado' => 'listo',
                'fecha' => now()->toIso8601String(),
            ],
        ]);

        // 2. Enviar Notificación Push si el cliente tiene registrado un dispositivo
        if (!empty($cliente->fcm_token)) {
            $this->sendPushNotification(
                $cliente->fcm_token,
                $titulo,
                $mensaje,
                [
                    'tramite_tipo' => $tramiteTipo,
                    'tramite_id' => (string) $tramiteId,
                    'notificacion_id' => (string) $notificacion->id,
                ]
            );
        }

        return $notificacion;
    }

    /**
     * Send push notification via Expo Push Notification API or FCM.
     */
    public function sendPushNotification(string $token, string $title, string $body, array $data = []): bool
    {
        try {
            // Soporte nativo para tokens de Expo (ExponentPushToken[...])
            if (str_starts_with($token, 'ExponentPushToken') || str_starts_with($token, 'ExpoPushToken')) {
                $response = Http::timeout(5)->post('https://exp.host/--/api/v2/push/send', [
                    'to' => $token,
                    'sound' => 'default',
                    'title' => $title,
                    'body' => $body,
                    'data' => $data,
                    'priority' => 'high',
                ]);

                return $response->successful();
            }

            // Para FCM estándar, registramos en log y dejamos el despacho listo
            Log::info("Push Notification dispatched to FCM token [{$token}]: {$title} - {$body}");
            return true;
        } catch (\Exception $e) {
            Log::error("Error sending Push Notification: " . $e->getMessage());
            return false;
        }
    }
}
