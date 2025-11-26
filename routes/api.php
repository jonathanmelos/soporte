<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TechnicianProfileController;
use App\Http\Controllers\Api\DeviceController;

use App\Http\Controllers\Api\WorkSessionSyncController;
use App\Http\Controllers\Api\WorkSessionLocationController;
use App\Http\Controllers\Api\WorkSessionPauseSyncController;
use App\Http\Controllers\Api\WorkSessionScanSyncController;
use App\Http\Controllers\Api\WorkSessionPhotoSyncController;   // ⭐ NUEVO

use App\Http\Controllers\Api\JornadaFotosController;            // ⭐ Subida directa desde Flutter

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Todas estas rutas usan el prefijo /api y el middleware "api".
|
*/

Route::get('/ping', function () {
    return response()->json(['message' => 'API OK']);
});

// =======================
// 🔐 AUTENTICACIÓN OTP
// =======================
Route::post('/auth/otp-request', [AuthController::class, 'requestOtp']);
Route::post('/auth/verify', [AuthController::class, 'verifyOtp']);


// ============================================================
// 🔒 RUTAS PROTEGIDAS POR SANCTUM
// ============================================================
Route::middleware('auth:sanctum')->group(function () {

    // Usuario autenticado
    Route::get('/me', [AuthController::class, 'me']);

    // Perfil del Técnico
    Route::get('/tecnico/profile', [TechnicianProfileController::class, 'show']);
    Route::put('/tecnico/profile', [TechnicianProfileController::class, 'update']);
    Route::patch('/tecnico/profile', [TechnicianProfileController::class, 'update']);

    // Registro de dispositivo
    Route::post('/devices/register', [DeviceController::class, 'register']);


    // =======================
    // 📌 SINCRONIZACIONES
    // =======================

    // Sesiones de trabajo
    Route::post('/work-sessions/sync', [WorkSessionSyncController::class, 'sync']);
    Route::get('/work-sessions',       [WorkSessionSyncController::class, 'index']);

    // Ubicaciones
    Route::post(
        '/work-session-locations/sync',
        [WorkSessionLocationController::class, 'sync']
    );

    // Pausas
    Route::post(
        '/work-session-pauses/sync',
        [WorkSessionPauseSyncController::class, 'sync']
    );

    // Escaneos QR
    Route::post(
        '/work-session-scans/sync',
        [WorkSessionScanSyncController::class, 'sync']
    );

    // ⭐ NUEVO ⭐ Sincronización OFFLINE-FIRST de fotos almacenadas localmente
    Route::post(
        '/work-session-photos/sync',
        [WorkSessionPhotoSyncController::class, 'sync']
    );


    // ===========================
    // 📷 SUBIDA DIRECTA DE FOTOS
    // ===========================
    // Flutter envía selfie + contexto al iniciar jornada
    // Debe incluir work_session_id
    Route::post(
        '/jornada/iniciar',
        [JornadaFotosController::class, 'store']
    );
});
