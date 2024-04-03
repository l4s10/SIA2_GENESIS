<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Solicitud\SolicitudFormulariosController;

// Rutas solicitudes de formularios
// Route::resource('solicitudesformularios', SolicitudFormulariosController::class);

// Prefix para las rutas de formularios solicitudes/
Route::prefix('solicitudes')->group(function () {
    //Definir cada ruta de solicitud de formularios con nombre "solicitudesformularios.{nombre_ruta}"
    //** Listar solicitudes */
    Route::get('/formularios', [SolicitudFormulariosController::class, 'index'])
        ->name('solicitudes.formularios.index')
        ->middleware('can:ver_solicitudes');

    //** Crear solicitud */
    Route::get('/formularios/create', [SolicitudFormulariosController::class, 'create'])
        ->name('solicitudes.formularios.create')
        ->middleware('can:crear_solicitud');

    //** Guardar solicitud */
    Route::post('/formularios', [SolicitudFormulariosController::class, 'store'])
        ->name('solicitudes.formularios.store')
        ->middleware('can:crear_solicitud');

    //** Mostrar solicitud */
    Route::get('/formularios/{solicitudesformulario}', [SolicitudFormulariosController::class, 'show'])
        ->name('solicitudes.formularios.show')
        ->middleware('can:ver_solicitudes');

    //** Editar solicitud */
    Route::get('/formularios/{solicitudesformulario}/edit', [SolicitudFormulariosController::class, 'edit'])
        ->name('solicitudes.formularios.edit')
        ->middleware(['role:ADMINISTRADOR|SERVICIOS']);

    //** Actualizar solicitud */
    Route::put('/formularios/{solicitudesformulario}', [SolicitudFormulariosController::class, 'update'])
        ->name('solicitudes.formularios.update')
        ->middleware(['role:ADMINISTRADOR|SERVICIOS']);

    //** Eliminar solicitud */
    Route::delete('/formularios/{solicitudesformulario}', [SolicitudFormulariosController::class, 'destroy'])
        ->name('solicitudes.formularios.destroy')
        ->middleware('can:eliminar_solicitud');
});
