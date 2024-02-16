<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Solicitud\SolicitudBodegasController;

// Rutas para solicitudes de bodegas
// Prefix para las rutas de bodegas solicitudes/
Route::prefix('solicitudes')->group(function () {
    //Definir cada ruta de solicitud de bodegas con nombre "solicitudesbodegas.{nombre_ruta}"
    //** Listar solicitudes */
    Route::get('/bodegas', [SolicitudBodegasController::class, 'index'])
        ->name('solicitudes.bodegas.index')
        ->middleware('can:ver_solicitudes');

    //** Crear solicitud */
    Route::get('/bodegas/create', [SolicitudBodegasController::class, 'create'])
        ->name('solicitudes.bodegas.create')
        ->middleware('can:crear_solicitud');

    //** Guardar solicitud */
    Route::post('/bodegas', [SolicitudBodegasController::class, 'store'])
        ->name('solicitudes.bodegas.store')
        ->middleware('can:crear_solicitud');

    //** Mostrar solicitud */
    Route::get('/bodegas/{solicitud}', [SolicitudBodegasController::class, 'show'])
        ->name('solicitudes.bodegas.show')
        ->middleware('can:ver_solicitudes');

    //** Editar solicitud */
    Route::get('/bodegas/{solicitud}/edit', [SolicitudBodegasController::class, 'edit'])
        ->name('solicitudes.bodegas.edit')
        ->middleware('can:editar_solicitud');

    //** Actualizar solicitud */
    Route::put('/bodegas/{solicitud}', [SolicitudBodegasController::class, 'update'])
        ->name('solicitudes.bodegas.update')
        ->middleware('can:actualizar_solicitud');

    //** Eliminar solicitud */
    Route::delete('/bodegas/{solicitud}', [SolicitudBodegasController::class, 'destroy'])
        ->name('solicitudes.bodegas.destroy')
        ->middleware('can:eliminar_solicitud');
});
