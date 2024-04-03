<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Solicitud\SolicitudReparacionesController;

// Rutas para solicitudes de reparaciones
// Prefix para las rutas de reparaciones solicitudes/
Route::prefix('solicitudes')->group(function () {
    //Definir cada ruta de solicitud de reparaciones con nombre "solicitudes.reparaciones.{nombre_ruta}"
    //** Listar solicitudes */
    Route::get('/reparaciones', [SolicitudReparacionesController::class, 'index'])
        ->name('solicitudes.reparaciones.index')
        ->middleware('can:ver_solicitudes');

    //** Crear solicitud */
    Route::get('/reparaciones/create', [SolicitudReparacionesController::class, 'create'])
        ->name('solicitudes.reparaciones.create')
        ->middleware('can:crear_solicitud');

    //** Guardar solicitud */
    Route::post('/reparaciones', [SolicitudReparacionesController::class, 'store'])
        ->name('solicitudes.reparaciones.store')
        ->middleware('can:crear_solicitud');

    //** Mostrar solicitud */
    Route::get('/reparaciones/{solicitud}', [SolicitudReparacionesController::class, 'show'])
        ->name('solicitudes.reparaciones.show')
        ->middleware('can:ver_solicitudes');

    //** Editar solicitud */
    Route::get('/reparaciones/{solicitud}/edit', [SolicitudReparacionesController::class, 'edit'])
        ->name('solicitudes.reparaciones.edit')
        ->middleware(['role:ADMINISTRADOR|SERVICIOS']);

    //** Actualizar solicitud */
    Route::put('/reparaciones/{solicitud}', [SolicitudReparacionesController::class, 'update'])
        ->name('solicitudes.reparaciones.update')
        ->middleware(['role:ADMINISTRADOR|SERVICIOS']);

    //** Eliminar solicitud */
    Route::delete('/reparaciones/{solicitud}', [SolicitudReparacionesController::class, 'destroy'])
        ->name('solicitudes.reparaciones.destroy')
        ->middleware('can:eliminar_solicitud');
});
