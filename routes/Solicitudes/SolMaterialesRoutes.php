<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Solicitud\SolicitudMaterialesController;

// Prefix para las rutas de materiales solicitudes/
Route::prefix('solicitudes')->group(function () {
    //** Listar solicitudes */
    Route::get('/materiales', [SolicitudMaterialesController::class, 'index'])
        ->name('solicitudes.materiales.index')
        ->middleware('can:ver_solicitudes');

    //** Crear solicitud */
    Route::get('/materiales/create', [SolicitudMaterialesController::class, 'create'])
        ->name('solicitudes.materiales.create')
        ->middleware('can:crear_solicitud');

    //** Guardar solicitud */
    Route::post('/materiales', [SolicitudMaterialesController::class, 'store'])
        ->name('solicitudes.materiales.store')
        ->middleware('can:crear_solicitud');

    //** Mostrar solicitud */
    Route::get('/materiales/{solicitudesmaterial}', [SolicitudMaterialesController::class, 'show'])
        ->name('solicitudes.materiales.show')
        ->middleware('can:ver_solicitudes');

    //** Editar solicitud */
    Route::get('/materiales/{solicitudesmaterial}/edit', [SolicitudMaterialesController::class, 'edit'])
        ->name('solicitudes.materiales.edit')
        ->middleware(['role:ADMINISTRADOR|SERVICIOS']);

    //** Actualizar solicitud */
    Route::put('/materiales/{solicitudesmaterial}', [SolicitudMaterialesController::class, 'update'])
        ->name('solicitudes.materiales.update')
        ->middleware(['role:ADMINISTRADOR|SERVICIOS']);

    //** Eliminar solicitud */
    Route::delete('/materiales/{solicitudesmaterial}', [SolicitudMaterialesController::class, 'destroy'])
        ->name('solicitudes.materiales.destroy')
        ->middleware('can:eliminar_solicitud');

    // Enrutamiento para el metodo confirmar)
    Route::put('/materiales/{solicitudesmaterial}/confirmar', [SolicitudMaterialesController::class, 'confirmar'])
        ->name('solicitudes.materiales.confirmar');
});
