<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Directivos\CargoController;

// Prefix para las rutas de cargos/
Route::prefix('cargos')->group(function () {
    //** Listar cargos */
    Route::get('/', [CargoController::class, 'index'])
        ->name('cargos.index');
        //->middleware('can:ver_cargos');

    //  Crear cargo
    Route::get('/cargos/create', [CargoController::class, 'create'])
        ->name('cargos.create');
        //->middleware('can:crear_cargo');

    //  Guardar cargo 
    Route::post('/', [CargoController::class, 'store'])
        ->name('cargos.store');
        //->middleware('can:crear_cargo');

    
    //  Editar cargo
    Route::get('/{cargo}/edit', [CargoController::class, 'edit'])
        ->name('cargos.edit');
        //->middleware('can:editar_cargo');

    //  Actualizar cargo
    Route::put('/{cargo}', [CargoController::class, 'update'])
        ->name('cargos.update');
        //->middleware('can:actualizar_cargo');

    //  Eliminar cargo
    Route::delete('/{cargo}', [CargoController::class, 'destroy'])
        ->name('cargos.destroy');
        //->middleware('can:eliminar_cargo');
});
