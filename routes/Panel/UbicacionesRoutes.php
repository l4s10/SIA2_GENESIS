<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Panel\UbicacionController;

//Prefix para todas las rutas "/panel"

Route::prefix('panel')->group(function () {
    //Definir cada ruta de ubicaciones con nombre "ubicaciones.{nombre_ruta}"
    //** Listar ubicaciones */
    Route::get('/ubicaciones', [UbicacionController::class, 'index'])
        ->name('panel.ubicaciones.index')
        ->middleware('can:ver_panel_control');

    //** Crear ubicacion */
    Route::get('/ubicaciones/create', [UbicacionController::class, 'create'])
        ->name('panel.ubicaciones.create')
        ->middleware('can:crear_panel_control');

    //** Guardar ubicacion */
    Route::post('/ubicaciones', [UbicacionController::class, 'store'])
        ->name('panel.ubicaciones.store')
        ->middleware('can:crear_panel_control');

    //** Mostrar ubicacion */
    Route::get('/ubicaciones/{ubicacion}', [UbicacionController::class, 'show'])
        ->name('panel.ubicaciones.show')
        ->middleware('can:ver_panel_control');

    //** Editar ubicacion */
    Route::get('/ubicaciones/{ubicacion}/edit', [UbicacionController::class, 'edit'])
        ->name('panel.ubicaciones.edit')
        ->middleware('can:editar_panel_control');

    //** Actualizar ubicacion */
    Route::put('/ubicaciones/{ubicacion}', [UbicacionController::class, 'update'])
        ->name('panel.ubicaciones.update')
        ->middleware('can:actualizar_panel_control');

    //** Eliminar ubicacion */
    Route::delete('/ubicaciones/{ubicacion}', [UbicacionController::class, 'destroy'])
        ->name('panel.ubicaciones.destroy')
        ->middleware('can:eliminar_panel_control');
});
