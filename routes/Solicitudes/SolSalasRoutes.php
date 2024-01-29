<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Solicitud\SolicitudSalasController;

// Rutas para solicitudes de salas

// Prefix para las rutas de salas solicitudes/
Route::prefix('solicitudes')->group(function () {
    //Definir cada ruta de solicitud de salas con nombre "solicitudesmateriales.{nombre_ruta}"
    //** Listar solicitudes */
    Route::get('/salas', [SolicitudSalasController::class, 'index'])->name('solicitudes.salas.index');

    //** Crear solicitud */
    Route::get('/salas/create', [SolicitudSalasController::class, 'create'])->name('solicitudes.salas.create');

    //** Guardar solicitud */
    Route::post('/salas', [SolicitudSalasController::class, 'store'])->name('solicitudes.salas.store');

    //** Mostrar solicitud */
    Route::get('/salas/{solicitud}', [SolicitudSalasController::class, 'show'])->name('solicitudes.salas.show');

    //** Editar solicitud */
    Route::get('/salas/{solicitud}/edit', [SolicitudSalasController::class, 'edit'])->name('solicitudes.salas.edit');

    //** Actualizar solicitud */
    Route::put('/salas/{solicitud}', [SolicitudSalasController::class, 'update'])->name('solicitudes.salas.update');

    //** Eliminar solicitud */
    Route::delete('/salas/{solicitud}', [SolicitudSalasController::class, 'destroy'])->name('solicitudes.salas.destroy');
});

