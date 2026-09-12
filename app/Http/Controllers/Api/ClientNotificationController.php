<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\NotificacionCliente;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ClientNotificationController extends Controller
{
    /**
     * Get client notification feed.
     */
    public function index(Request $request): JsonResponse
    {
        /** @var Cliente $cliente */
        $cliente = $request->attributes->get('cliente');

        $notificaciones = $cliente->notificaciones()
            ->paginate(20);

        $unreadCount = $cliente->notificaciones()
            ->where('leido', false)
            ->count();

        return response()->json([
            'success' => true,
            'unread_count' => $unreadCount,
            'notificaciones' => $notificaciones->items(),
            'pagination' => [
                'current_page' => $notificaciones->currentPage(),
                'last_page' => $notificaciones->lastPage(),
                'total' => $notificaciones->total(),
            ],
        ]);
    }

    /**
     * Mark notification as read.
     */
    public function markAsRead(Request $request, $id): JsonResponse
    {
        /** @var Cliente $cliente */
        $cliente = $request->attributes->get('cliente');

        if ($id === 'all') {
            $cliente->notificaciones()->where('leido', false)->update(['leido' => true]);
            return response()->json([
                'success' => true,
                'message' => 'Todas las notificaciones han sido marcadas como leídas.',
            ]);
        }

        $notif = $cliente->notificaciones()->where('id', $id)->first();
        if ($notif) {
            $notif->leido = true;
            $notif->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Notificación marcada como leída.',
        ]);
    }
}
