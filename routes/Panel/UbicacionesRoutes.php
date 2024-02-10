<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Panel\UbicacionesController;

//Prefix para todas las rutas "/panel"

Route::prefix('panel')->group(function () {
    //Definir cada ruta de ubicaciones con nombre "ubicaciones.{nombre_ruta}"
    //** Listar ubicaciones */
    Route::get('/ubicaciones', [UbicacionesController::class, 'index'])->name('panel.ubicaciones.index');

    //** Crear ubicacion */
    Route::get('/ubicaciones/create', [UbicacionesController::class, 'create'])->name('panel.ubicaciones.create');

    //** Guardar ubicacion */
    Route::post('/ubicaciones', [UbicacionesController::class, 'store'])->name('panel.ubicaciones.store');

    //** Mostrar ubicacion */
    Route::get('/ubicaciones/{ubicacion}', [UbicacionesController::class, 'show'])->name('panel.ubicaciones.show');

    //** Editar ubicacion */
    Route::get('/ubicaciones/{ubicacion}/edit', [UbicacionesController::class, 'edit'])->name('panel.ubicaciones.edit');

    //** Actualizar ubicacion */
    Route::put('/ubicaciones/{ubicacion}', [UbicacionesController::class, 'update'])->name('panel.ubicaciones.update');

    //** Eliminar ubicacion */
    Route::delete('/ubicaciones/{ubicacion}', [UbicacionesController::class, 'destroy'])->name('panel.ubicaciones.destroy');
});
