<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ClientAuthController extends Controller
{
    /**
     * Authenticate client and return access token + profile.
     */
    public function login(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'identificacion' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Por favor completa todos los campos requeridos.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $identificacion = trim($request->input('identificacion'));
        $password = trim($request->input('password'));

        // Buscar cliente por cédula / identificación o correo electrónico
        $cliente = Cliente::where('c_identificacion', $identificacion)
            ->orWhere('c_email', $identificacion)
            ->first();

        if (!$cliente || !$cliente->verifyPassword($password)) {
            return response()->json([
                'success' => false,
                'message' => 'Identificación o contraseña incorrecta. Verifica tus datos e inténtalo nuevamente.',
            ], 401);
        }

        // Generar token de sesión
        $token = $cliente->generateApiToken();

        // Opcional: registrar FCM token si se envió en el login
        if ($request->filled('fcm_token')) {
            $cliente->fcm_token = $request->input('fcm_token');
            $cliente->fcm_platform = $request->input('fcm_platform', 'android');
            $cliente->save();
        }

        return response()->json([
            'success' => true,
            'message' => "¡Bienvenido/a, {$cliente->c_nombre}!",
            'token' => $token,
            'cliente' => [
                'id' => $cliente->id_cliente,
                'identificacion' => $cliente->c_identificacion,
                'nombre' => $cliente->c_nombre,
                'apellido' => $cliente->c_apellido,
                'nombre_completo' => trim("{$cliente->c_nombre} {$cliente->c_apellido}"),
                'email' => $cliente->c_email,
                'telefono' => $cliente->c_telefono,
                'oficina' => $cliente->c_oficina_registro,
                'ciudad' => $cliente->c_ciudad,
                'pais' => $cliente->c_pais,
                'saldo_pendiente' => (float) $cliente->c_saldo,
                'total_abonado' => (float) $cliente->c_abonado,
                'total_deuda' => (float) $cliente->c_deuda,
            ],
        ]);
    }

    /**
     * Return authenticated client profile & stats.
     */
    public function profile(Request $request): JsonResponse
    {
        /** @var Cliente $cliente */
        $cliente = $request->attributes->get('cliente');

        $unreadCount = $cliente->notificaciones()->where('leido', false)->count();

        return response()->json([
            'success' => true,
            'cliente' => [
                'id' => $cliente->id_cliente,
                'identificacion' => $cliente->c_identificacion,
                'nombre' => $cliente->c_nombre,
                'apellido' => $cliente->c_apellido,
                'nombre_completo' => trim("{$cliente->c_nombre} {$cliente->c_apellido}"),
                'email' => $cliente->c_email,
                'telefono' => $cliente->c_telefono,
                'direccion' => $cliente->c_direccion,
                'ciudad' => $cliente->c_ciudad,
                'pais' => $cliente->c_pais,
                'oficina' => $cliente->c_oficina_registro,
                'saldo_pendiente' => (float) $cliente->c_saldo,
                'total_abonado' => (float) $cliente->c_abonado,
                'total_deuda' => (float) $cliente->c_deuda,
                'notificaciones_no_leidas' => $unreadCount,
            ],
        ]);
    }

    /**
     * Register or update push notification device token.
     */
    public function registerDevice(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'fcm_token' => 'required|string',
            'platform' => 'nullable|string|in:android,ios,web',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        /** @var Cliente $cliente */
        $cliente = $request->attributes->get('cliente');
        $cliente->fcm_token = $request->input('fcm_token');
        $cliente->fcm_platform = $request->input('platform', 'android');
        $cliente->save();

        return response()->json([
            'success' => true,
            'message' => 'Dispositivo registrado exitosamente para notificaciones push.',
        ]);
    }

    /**
     * Update client password.
     */
    public function updatePassword(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'password_actual' => 'required|string',
            'password_nuevo' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'La nueva contraseña debe tener al menos 6 caracteres.',
                'errors' => $validator->errors(),
            ], 422);
        }

        /** @var Cliente $cliente */
        $cliente = $request->attributes->get('cliente');

        if (!$cliente->verifyPassword($request->password_actual)) {
            return response()->json([
                'success' => false,
                'message' => 'La contraseña actual no es correcta.',
            ], 422);
        }

        $cliente->c_password = Hash::make($request->password_nuevo);
        $cliente->save();

        return response()->json([
            'success' => true,
            'message' => '¡Contraseña actualizada exitosamente!',
        ]);
    }

    /**
     * Log out client by invalidating token.
     */
    public function logout(Request $request): JsonResponse
    {
        /** @var Cliente $cliente */
        $cliente = $request->attributes->get('cliente');
        $cliente->api_token = null;
        $cliente->save();

        return response()->json([
            'success' => true,
            'message' => 'Sesión cerrada con éxito.',
        ]);
    }
}
