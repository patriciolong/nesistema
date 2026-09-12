<?php

use App\Http\Controllers\Api\ClientAuthController;
use App\Http\Controllers\Api\ClientTramiteController;
use App\Http\Controllers\Api\ClientNotificationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes - NESISTEMA 2.0 Client Portal & Mobile App
|--------------------------------------------------------------------------
*/

Route::prefix('v1/client')->group(function () {
    
    // Autenticación pública del cliente
    Route::post('/login', [ClientAuthController::class, 'login'])->name('api.client.login');

    // Rutas protegidas por Token de Cliente
    Route::middleware('auth.client')->group(function () {
        
        // Perfil y Dispositivo
        Route::get('/profile', [ClientAuthController::class, 'profile'])->name('api.client.profile');
        Route::post('/register-device', [ClientAuthController::class, 'registerDevice'])->name('api.client.register_device');
        Route::post('/update-password', [ClientAuthController::class, 'updatePassword'])->name('api.client.update_password');
        Route::post('/logout', [ClientAuthController::class, 'logout'])->name('api.client.logout');

        // Trámites
        Route::get('/tramites', [ClientTramiteController::class, 'index'])->name('api.client.tramites.index');
        Route::get('/tramites/{categoria}/{id}', [ClientTramiteController::class, 'show'])->name('api.client.tramites.show');

        // Notificaciones
        Route::get('/notificaciones', [ClientNotificationController::class, 'index'])->name('api.client.notificaciones.index');
        Route::post('/notificaciones/{id}/read', [ClientNotificationController::class, 'markAsRead'])->name('api.client.notificaciones.read');
    });
});
