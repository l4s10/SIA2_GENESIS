<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Panel\RegionesController;

//Prefix para todas las rutas "/panel"

Route::prefix('panel')->group(function () {
    //Definir cada ruta de regiones con nombre "regiones.{nombre_ruta}"
    //** Listar regiones */
    Route::get('/regiones', [RegionesController::class, 'index'])->name('panel.regiones.index');

    //** Crear region */
    Route::get('/regiones/create', [RegionesController::class, 'create'])->name('panel.regiones.create');

    //** Guardar region */
    Route::post('/regiones', [RegionesController::class, 'store'])->name('panel.regiones.store');

    //** Mostrar region */
    Route::get('/regiones/{region}', [RegionesController::class, 'show'])->name('panel.regiones.show');

    //** Editar region */
    Route::get('/regiones/{region}/edit', [RegionesController::class, 'edit'])->name('panel.regiones.edit');

    //** Actualizar region */
    Route::put('/regiones/{region}', [RegionesController::class, 'update'])->name('panel.regiones.update');

    //** Eliminar region */
    Route::delete('/regiones/{region}', [RegionesController::class, 'destroy'])->name('panel.regiones.destroy');
});
