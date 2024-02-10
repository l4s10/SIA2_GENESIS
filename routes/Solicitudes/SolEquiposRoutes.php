<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Solicitud\SolicitudEquiposController;

// Ruta solicitudes de equipos
// Route::resource('solicitudesequipos', SolicitudEquiposController::class);

// Prefix para las rutas de equipos solicitudes/
Route::prefix('solicitudes')->group(function () {
    //Definir cada ruta de solicitud de equipos con nombre "solicitudesequipos.{nombre_ruta}"
    //** Listar solicitudes */
    Route::get('/equipos', [SolicitudEquiposController::class, 'index'])->name('solicitudes.equipos.index');

    //** Crear solicitud */
    Route::get('/equipos/create', [SolicitudEquiposController::class, 'create'])->name('solicitudes.equipos.create');

    //** Guardar solicitud */
    Route::post('/equipos', [SolicitudEquiposController::class, 'store'])->name('solicitudes.equipos.store');

    //** Mostrar solicitud */
    Route::get('/equipos/{solicitudesequipo}', [SolicitudEquiposController::class, 'show'])->name('solicitudes.equipos.show');

    //** Editar solicitud */
    Route::get('/equipos/{solicitudesequipo}/edit', [SolicitudEquiposController::class, 'edit'])->name('solicitudes.equipos.edit');

    //** Actualizar solicitud */
    Route::put('/equipos/{solicitudesequipo}', [SolicitudEquiposController::class, 'update'])->name('solicitudes.equipos.update');

    //** Eliminar solicitud */
    Route::delete('/equipos/{solicitudesequipo}', [SolicitudEquiposController::class, 'destroy'])->name('solicitudes.equipos.destroy');
});
