<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Directivos\CargoController;

// Prefix para las rutas de cargos/
Route::prefix('cargos')->group(function () {
    //** Listar cargos */
    Route::get('/', [CargoController::class, 'index'])
        ->name('cargos.index')
        ->middleware('can:ver_repositorio');

    //  Crear cargo
    Route::get('/cargos/create', [CargoController::class, 'create'])
        ->name('cargos.create')
        ->middleware('can:crear_repositorio');


    //  Guardar cargo
    Route::post('/', [CargoController::class, 'store'])
        ->name('cargos.store')
        ->middleware('can:crear_repositorio');

    //  Editar cargo
    Route::get('/{cargo}/edit', [CargoController::class, 'edit'])
        ->name('cargos.edit')
        ->middleware('can:editar_repositorio');

    //  Actualizar cargo
    Route::put('/{cargo}', [CargoController::class, 'update'])
        ->name('cargos.update')
        ->middleware('can:actualizar_repositorio');

    //  Eliminar cargo
    Route::delete('/{cargo}', [CargoController::class, 'destroy'])
        ->name('cargos.destroy')
        ->middleware('can:eliminar_repositorio');
});
