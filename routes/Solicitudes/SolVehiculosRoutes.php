<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Solicitud\SolicitudVehiculosController;

// Prefix para las rutas de vehículos solicitudes/
Route::prefix('solicitudesvehiculos')->group(function () {
    // Ruta para listar solicitudes de vehículos
    Route::get('/', [SolicitudVehiculosController::class, 'index'])
        ->name('solicitudesvehiculos.index')
        ->middleware('can:ver_solicitudes');

    // Ruta para crear solicitud de vehículo
    Route::get('/create', [SolicitudVehiculosController::class, 'create'])
        ->name('solicitudesvehiculos.create')
        ->middleware('can:crear_solicitud');

    // Ruta para almacenar solicitud de vehículo
    Route::post('/', [SolicitudVehiculosController::class, 'store'])
        ->name('solicitudesvehiculos.store')
        ->middleware('can:crear_solicitud');

    // Ruta para editar solicitud de vehículo (1ERA FASE)
    Route::get('/{solicitudesvehiculo}/edit', [SolicitudVehiculosController::class, 'edit'])
        ->name('solicitudesvehiculos.edit')
        ->middleware('can:editar_solicitud');

    // Ruta para actualizar/ REVISAR (1ERA FASE) solicitud de vehículo
    Route::put('/{solicitudesvehiculo}', [SolicitudVehiculosController::class, 'update'])
        ->name('solicitudesvehiculos.update')
        ->middleware('can:actualizar_solicitud');

    // Ruta para eliminar solicitud de vehículo
    Route::delete('/{solicitudesvehiculo}', [SolicitudVehiculosController::class, 'destroy'])
        ->name('solicitudesvehiculos.destroy')
        ->middleware('can:eliminar_solicitud');
});


// Ruta para index de solicitudes Por Aprobar
Route::get('solicitudesvehiculos/indexPorAprobar', [SolicitudVehiculosController::class, 'indexPorAprobar'])
    ->name('solicitudesvehiculos.indexPorAprobar')
    ->middleware('can:ver_solicitudes');

// Ruta para index de solicitudes Por Rendir
Route::get('solicitudesvehiculos/indexPorRendir', [SolicitudVehiculosController::class, 'indexPorRendir'])
    ->name('solicitudesvehiculos.indexPorRendir')
    ->middleware('can:ver_solicitudes');

// Ruta para acceder al Timeline
Route::get('solicitudesvehiculos/{id}', [SolicitudVehiculosController::class, 'timeline'])
    ->name('solicitudesvehiculos.timeline')
    ->middleware('can:ver_solicitudes');

// Agregar la ruta para verificar la contraseña
Route::post('verificar-contrasena', [SolicitudVehiculosController::class, 'verificarContrasena'])
    ->name('verificar.contrasena');

// Ruta para descargar PDF
Route::get('descargar-plantilla/{id}', [SolicitudVehiculosController::class, 'descargarPlantilla'])
    ->name('descargar.plantilla');
