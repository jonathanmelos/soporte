<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TechnicianProfileController;
use App\Http\Controllers\Api\WorkSessionSyncController;
use App\Http\Controllers\Api\DeviceController;
use App\Http\Controllers\Api\WorkSessionLocationController;
use App\Http\Controllers\Api\WorkSessionPauseSyncController; 
use App\Http\Controllers\Api\WorkSessionScanSyncController; // ⬅️ NUEVO: control de escaneos QR

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Aquí se registran las rutas de la API. Estas rutas se cargan con el
| prefijo "api" y el middleware "api" definido en bootstrap/app.php.
|
*/

// Ruta de prueba rápida
Route::get('/ping', function () {
    return response()->json(['message' => 'API OK']);
});

// Autenticación por código (OTP)
Route::post('/auth/otp-request', [AuthController::class, 'requestOtp']);
Route::post('/auth/verify', [AuthController::class, 'verifyOtp']);

// Rutas protegidas por Sanctum
Route::middleware('auth:sanctum')->group(function () {

    Route::get('/me', [AuthController::class, 'me']);

    // Perfil del técnico
    Route::get('/tecnico/profile', [TechnicianProfileController::class, 'show']);
    Route::put('/tecnico/profile', [TechnicianProfileController::class, 'update']);
    Route::patch('/tecnico/profile', [TechnicianProfileController::class, 'update']);

    // Registro / actualización de dispositivo
    Route::post('/devices/register', [DeviceController::class, 'register']);

    // Sincronización de sesiones de trabajo (jornadas)
    Route::post('/work-sessions/sync', [WorkSessionSyncController::class, 'sync']);
    Route::get('/work-sessions', [WorkSessionSyncController::class, 'index']);

    // Sincronización de ubicaciones de sesiones
    Route::post(
        '/work-session-locations/sync',
        [WorkSessionLocationController::class, 'sync']
    );

    // Sincronización de PAUSAS de jornada
    Route::post(
        '/work-session-pauses/sync',
        [WorkSessionPauseSyncController::class, 'sync']
    );

    // ⬇️ NUEVO: Sincronización de ESCANEOS QR (work_session_scans)
    Route::post(
        '/work-session-scans/sync',
        [WorkSessionScanSyncController::class, 'sync']
    );
});
