<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\RequerimientosController;
use App\Http\Controllers\ProyectoController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\ChatreporteController;
use App\Http\Controllers\RubroController;
use App\Http\Controllers\FacturaController;
use App\Http\Controllers\UserController;

use App\Http\Controllers\ReporteHorasController;   // Vista 1
use App\Http\Controllers\ReporteDiarioController;  // Vista 2
use App\Http\Controllers\UbicacionController;      // Para guardar ubicaciones desde diario

use App\Http\Controllers\Admin\OtpNotificationController;

/*
|--------------------------------------------------------------------------
| Rutas Públicas
|--------------------------------------------------------------------------
*/

// Ruta raíz: muestra la vista de login
Route::get('/', function () {
    return view('auth.login');
});

/*
|--------------------------------------------------------------------------
| Rutas protegidas por autenticación
|--------------------------------------------------------------------------
*/

// CRUD para empleados, protegido por login
Route::resource('empleado', EmpleadoController::class)->middleware('auth');
Route::resource('requerimientos', RequerimientosController::class)->middleware('auth');
Route::resource('proyecto', ProyectoController::class)->middleware('auth');
Route::resource('reporte', ReporteController::class)->middleware('auth');
Route::resource('chatreporte', ChatreporteController::class)->middleware('auth');
Route::resource('rubros', RubroController::class)->middleware('auth');  // <--- Agregado

// Vista 1: reporte de horas por técnico
Route::get('/reportes/horas', [ReporteHorasController::class, 'index'])
    ->name('reportes.horas')
    ->middleware('auth');

// Vista 2: reporte diario con ubicaciones
Route::get('/reportes/diario', [ReporteDiarioController::class, 'index'])
    ->name('reportes.diario')
    ->middleware('auth');

// Guardar / crear ubicación desde la vista diario (modal)
Route::post('/ubicaciones/storeFromDiario', [UbicacionController::class, 'storeFromDiario'])
    ->name('ubicaciones.storeFromDiario')
    ->middleware('auth');

// Rutas para Facturas
Route::get('/facturas/upload', [FacturaController::class, 'formUpload'])->name('facturas.upload.form');
Route::post('/facturas/upload', [FacturaController::class, 'upload'])->name('facturas.upload');
Route::get('/facturas/{factura}', [FacturaController::class, 'show'])->name('facturas.show');
Route::get('/buscar-detalle', [\App\Http\Controllers\DetalleFacturaController::class, 'buscar'])->name('detalle.buscar');
Route::view('/buscador', 'detalles.buscador');

// Ruta para la segunda edición (Descartar)
Route::get('/requerimientos/{requerimiento}/descartar', [RequerimientosController::class, 'descartar'])->name('requerimientos.descartar')->middleware('auth');
Route::get('/proyecto/{id_proyecto}', [ProyectoController::class, 'show'])->name('proyecto.show');
Route::get('/proyecto/avance', [ProyectoController::class, 'avance'])->name('proyecto.avance')->middleware('auth');
Route::get('/proyecto/{proyecto}/cerrar', [ProyectoController::class, 'cerrar'])->name('proyecto.cerrar');
Route::get('/rubros/{id}/items', [RubroController::class, 'items'])->name('rubros.items');

// Ruta /home solo accesible si estás autenticado
Route::get('/home', [RequerimientosController::class, 'index'])->name('home')->middleware('auth');
Route::patch('/requerimientos/actualizar/{requerimiento}', [RequerimientosController::class, 'actualizar'])->name('requerimientos.actualizar');
Route::put('/proyecto/{proyecto}/cerrar', [ProyectoController::class, 'actualizarCierre'])->name('proyecto.actualizarCierre');

use App\Models\cliente;

Route::get('/buscar-cliente/{id_cliente}', function ($id_cliente) {
    $cliente = cliente::find($id_cliente);
    return response()->json($cliente);
});
Route::get('/clientes/autocompletar', [\App\Http\Controllers\ClienteController::class, 'autocompletar'])->name('clientes.autocompletar');

Route::get('/admin/register', [UserController::class, 'create'])
   ->name('admin.register')
   ->middleware('auth'); // Puedes cambiar esto a 'isAdmin' si tienes roles

/*
|--------------------------------------------------------------------------
| Autenticación
|--------------------------------------------------------------------------
*/

// Rutas de login/logout, sin permitir registro ni reset de contraseña
Auth::routes([
    'register' => true,
    'reset' => false,
]);

/*
|--------------------------------------------------------------------------
| Admin - Panel OTP Notifications
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/otp-notifications', [OtpNotificationController::class, 'index'])->name('admin.otp.index');
    Route::post('/otp-notifications/{notification}/mark-shared', [OtpNotificationController::class, 'markAsShared'])->name('admin.otp.markShared');
    Route::get('/otp-notifications/{notification}/send-whatsapp', [OtpNotificationController::class, 'sendWhatsApp'])->name('admin.otp.sendWhatsApp');
    Route::get('/otp-notifications/pending-count', [OtpNotificationController::class, 'pendingCount'])->name('admin.otp.pendingCount');
    Route::get('/otp-notifications/latest', [OtpNotificationController::class, 'latest'])->name('admin.otp.latest');
});
