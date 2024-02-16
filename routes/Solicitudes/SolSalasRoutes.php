<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Solicitud\SolicitudSalasController;

// Rutas para solicitudes de salas

// Prefix para las rutas de salas solicitudes/
Route::prefix('solicitudes')->group(function () {
    //Definir cada ruta de solicitud de salas con nombre "solicitudesmateriales.{nombre_ruta}"
    //** Listar solicitudes */
    Route::get('/salas', [SolicitudSalasController::class, 'index'])
        ->name('solicitudes.salas.index')
        ->middleware('can:ver_solicitudes');

    //** Crear solicitud */
    Route::get('/salas/create', [SolicitudSalasController::class, 'create'])
        ->name('solicitudes.salas.create')
        ->middleware('can:crear_solicitud');

    //** Guardar solicitud */
    Route::post('/salas', [SolicitudSalasController::class, 'store'])
        ->name('solicitudes.salas.store')
        ->middleware('can:crear_solicitud');

    //** Mostrar solicitud */
    Route::get('/salas/{solicitud}', [SolicitudSalasController::class, 'show'])
        ->name('solicitudes.salas.show')
        ->middleware('can:ver_solicitudes');

    //** Editar solicitud */
    Route::get('/salas/{solicitud}/edit', [SolicitudSalasController::class, 'edit'])
        ->name('solicitudes.salas.edit')
        ->middleware('can:editar_solicitud');

    //** Actualizar solicitud */
    Route::put('/salas/{solicitud}', [SolicitudSalasController::class, 'update'])
        ->name('solicitudes.salas.update')
        ->middleware('can:actualizar_solicitud');

    //** Eliminar solicitud */
    Route::delete('/salas/{solicitud}', [SolicitudSalasController::class, 'destroy'])
        ->name('solicitudes.salas.destroy')
        ->middleware('can:eliminar_solicitud');
});

