<?php

namespace App\Http\Middleware;

use App\Models\Cliente;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateClientApi
{
    /**
     * Handle an incoming client API request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken() ?: $request->input('api_token');

        if (!$token) {
            return response()->json([
                'success' => false,
                'message' => 'Token de autorización no proporcionado.',
            ], 401);
        }

        $hashedToken = hash('sha256', $token);
        $cliente = Cliente::where('api_token', $hashedToken)->first();

        if (!$cliente) {
            return response()->json([
                'success' => false,
                'message' => 'Sesión inválida o expirada. Por favor ingresa nuevamente.',
            ], 401);
        }

        // Asignar el cliente a la petición
        $request->attributes->set('cliente', $cliente);

        return $next($request);
    }
}
