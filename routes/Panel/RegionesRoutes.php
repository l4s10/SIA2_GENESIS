<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Panel\RegionController;

//Prefix para todas las rutas "/panel"

Route::prefix('panel')->group(function () {
    //Definir cada ruta de regiones con nombre "regiones.{nombre_ruta}"
    //** Listar regiones */
    Route::get('/regiones', [RegionController::class, 'index'])->name('panel.regiones.index');

    //** Crear region */
    Route::get('/regiones/create', [RegionController::class, 'create'])->name('panel.regiones.create');

    //** Guardar region */
    Route::post('/regiones', [RegionController::class, 'store'])->name('panel.regiones.store');

    //** Mostrar region */
    Route::get('/regiones/{region}', [RegionController::class, 'show'])->name('panel.regiones.show');

    //** Editar region */
    Route::get('/regiones/{region}/edit', [RegionController::class, 'edit'])->name('panel.regiones.edit');

    //** Actualizar region */
    Route::put('/regiones/{region}', [RegionController::class, 'update'])->name('panel.regiones.update');

    //** Eliminar region */
    Route::delete('/regiones/{region}', [RegionController::class, 'destroy'])->name('panel.regiones.destroy');
});
