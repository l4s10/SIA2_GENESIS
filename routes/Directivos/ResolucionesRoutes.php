<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Directivos\ResolucionController;

// Prefix para las rutas de resoluciones /
Route::prefix('resoluciones')->group(function () {
    //** Listar resoluciones */
    Route::get('/', [ResolucionController::class, 'index'])
        ->name('resoluciones.index')
        ->middleware('can:ver_repositorio');

    //  Crear resolucion
    Route::get('/resoluciones/create', [ResolucionController::class, 'create'])
        ->name('resoluciones.create')
        ->middleware('can:crear_repositorio');

    //  Guardar resolucion
    Route::post('/', [ResolucionController::class, 'store'])
        ->name('resoluciones.store')
        ->middleware('can:crear_repositorio');


    //  Editar resolucion
    Route::get('/{resolucion}/edit', [ResolucionController::class, 'edit'])
        ->name('resoluciones.edit')
        ->middleware('can:editar_repositorio');

    //  Actualizar resolucion
    Route::put('/{resolucion}', [ResolucionController::class, 'update'])
        ->name('resoluciones.update')
        ->middleware('can:actualizar_repositorio');

    //  Eliminar resolucion
    Route::delete('/{resolucion}', [ResolucionController::class, 'destroy'])
        ->name('resoluciones.destroy')
        ->middleware('can:eliminar_repositorio');
});
