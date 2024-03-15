<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Panel\RegionController;

//Prefix para todas las rutas "/panel"

Route::prefix('panel')->group(function () {
    //Definir cada ruta de regiones con nombre "regiones.{nombre_ruta}"
    //** Listar regiones */
    Route::get('/regiones', [RegionController::class, 'index'])
        ->name('panel.regiones.index')
        ->middleware('can:ver_panel_control');

    //** Crear region */
    Route::get('/regiones/create', [RegionController::class, 'create'])
        ->name('panel.regiones.create')
        ->middleware('can:crear_panel_control');

    //** Guardar region */
    Route::post('/regiones', [RegionController::class, 'store'])
        ->name('panel.regiones.store')
        ->middleware('can:crear_panel_control');

    //** Mostrar region */
    Route::get('/regiones/{region}', [RegionController::class, 'show'])
        ->name('panel.regiones.show')
        ->middleware('can:ver_panel_control');

    //** Editar region */
    Route::get('/regiones/{region}/edit', [RegionController::class, 'edit'])
        ->name('panel.regiones.edit')
        ->middleware('can:editar_panel_control');

    //** Actualizar region */
    Route::put('/regiones/{region}', [RegionController::class, 'update'])
        ->name('panel.regiones.update')
        ->middleware('can:actualizar_panel_control');

    //** Eliminar region */
    Route::delete('/regiones/{region}', [RegionController::class, 'destroy'])
        ->name('panel.regiones.destroy')
        ->middleware('can:eliminar_panel_control');
});
