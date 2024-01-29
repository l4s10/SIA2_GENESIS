<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Solicitud\SolicitudEquiposController;

// Ruta solicitudes de equipos
// Route::resource('solicitudesequipos', SolicitudEquiposController::class);

// Prefix para las rutas de equipos solicitudes/
Route::prefix('solicitudes')->group(function () {
    //Definir cada ruta de solicitud de equipos con nombre "solicitudesequipos.{nombre_ruta}"
    //** Listar solicitudes */
    Route::get('/equipos', [SolicitudEquiposController::class, 'index'])->name('solicitudesequipos.index');

    //** Crear solicitud */
    Route::get('/equipos/create', [SolicitudEquiposController::class, 'create'])->name('solicitudesequipos.create');

    //** Guardar solicitud */
    Route::post('/equipos', [SolicitudEquiposController::class, 'store'])->name('solicitudesequipos.store');

    //** Mostrar solicitud */
    Route::get('/equipos/{solicitudesequipo}', [SolicitudEquiposController::class, 'show'])->name('solicitudesequipos.show');

    //** Editar solicitud */
    Route::get('/equipos/{solicitudesequipo}/edit', [SolicitudEquiposController::class, 'edit'])->name('solicitudesequipos.edit');

    //** Actualizar solicitud */
    Route::put('/equipos/{solicitudesequipo}', [SolicitudEquiposController::class, 'update'])->name('solicitudesequipos.update');
    
    //** Eliminar solicitud */
    Route::delete('/equipos/{solicitudesequipo}', [SolicitudEquiposController::class, 'destroy'])->name('solicitudesequipos.destroy');
});
