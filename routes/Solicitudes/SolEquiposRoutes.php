<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Solicitud\SolicitudEquiposController;

// Ruta solicitudes de equipos
// Route::resource('solicitudesequipos', SolicitudEquiposController::class);

// Prefix para las rutas de equipos solicitudes/
Route::prefix('solicitudes')->group(function () {
    //Definir cada ruta de solicitud de equipos con nombre "solicitudesequipos.{nombre_ruta}"
    //** Listar solicitudes */
    Route::get('/equipos', [SolicitudEquiposController::class, 'index'])
        ->name('solicitudes.equipos.index')
        ->middleware('can:ver_solicitudes');

    //** Crear solicitud */
    Route::get('/equipos/create', [SolicitudEquiposController::class, 'create'])
        ->name('solicitudes.equipos.create')
        ->middleware('can:crear_solicitud');

    //** Guardar solicitud */
    Route::post('/equipos', [SolicitudEquiposController::class, 'store'])
        ->name('solicitudes.equipos.store')
        ->middleware('can:crear_solicitud');

    //** Mostrar solicitud */
    Route::get('/equipos/{solicitudesequipo}', [SolicitudEquiposController::class, 'show'])
        ->name('solicitudes.equipos.show')
        ->middleware('can:ver_solicitudes');

    //** Editar solicitud */
    Route::get('/equipos/{solicitudesequipo}/edit', [SolicitudEquiposController::class, 'edit'])
        ->name('solicitudes.equipos.edit')
        ->middleware('can:editar_solicitud');

    //** Actualizar solicitud */
    Route::put('/equipos/{solicitudesequipo}', [SolicitudEquiposController::class, 'update'])
        ->name('solicitudes.equipos.update')
        ->middleware('can:actualizar_solicitud');

    //** Eliminar solicitud */
    Route::delete('/equipos/{solicitudesequipo}', [SolicitudEquiposController::class, 'destroy'])
        ->name('solicitudes.equipos.destroy')
        ->middleware('can:eliminar_solicitud');
});
