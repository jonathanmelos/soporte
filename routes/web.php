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
/*
|--------------------------------------------------------------------------|
| Rutas Públicas                                                          |
|--------------------------------------------------------------------------|
*/

// Ruta raíz: muestra la vista de login
Route::get('/', function () {
    return view('auth.login');
});

/*
|--------------------------------------------------------------------------|
| Rutas protegidas por autenticación                                       |
|--------------------------------------------------------------------------|
*/

// CRUD para empleados, protegido por login
Route::resource('empleado', EmpleadoController::class)->middleware('auth');
Route::resource('requerimientos', RequerimientosController::class)->middleware('auth');
Route::resource('proyecto', ProyectoController::class)->middleware('auth');
Route::resource('reporte', ReporteController::class)->middleware('auth');
Route::resource('chatreporte', ChatreporteController::class)->middleware('auth');
Route::resource('rubros', RubroController::class)->middleware('auth');  // <--- Agregado

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
|--------------------------------------------------------------------------|
| Autenticación                                                            |
|--------------------------------------------------------------------------|
*/

// Rutas de login/logout, sin permitir registro ni reset de contraseña
Auth::routes([
    'register' => true,
    'reset' => false,
]);
