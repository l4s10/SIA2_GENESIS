<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Solicitud\SolicitudMaterialesController;

// Ruta solicitudes de materiales
// Route::resource('solicitudesmateriales', SolicitudMaterialesController::class);

// Prefix para las rutas de materiales solicitudes/
Route::prefix('solicitudes')->group(function () {
    //Definir cada ruta de solicitud de materiales con nombre "solicitudesmateriales.{nombre_ruta}"
    //** Listar solicitudes */
    Route::get('/materiales', [SolicitudMaterialesController::class, 'index'])->name('solicitudesmateriales.index');

    //** Crear solicitud */
    Route::get('/materiales/create', [SolicitudMaterialesController::class, 'create'])->name('solicitudesmateriales.create');

    //** Guardar solicitud */
    Route::post('/materiales', [SolicitudMaterialesController::class, 'store'])->name('solicitudesmateriales.store');

    //** Mostrar solicitud */
    Route::get('/materiales/{solicitudesmaterial}', [SolicitudMaterialesController::class, 'show'])->name('solicitudesmateriales.show');

    //** Editar solicitud */
    Route::get('/materiales/{solicitudesmaterial}/edit', [SolicitudMaterialesController::class, 'edit'])->name('solicitudesmateriales.edit');

    //** Actualizar solicitud */
    Route::put('/materiales/{solicitudesmaterial}', [SolicitudMaterialesController::class, 'update'])->name('solicitudesmateriales.update');

    //** Eliminar solicitud */
    Route::delete('/materiales/{solicitudesmaterial}', [SolicitudMaterialesController::class, 'destroy'])->name('solicitudesmateriales.destroy');
});
