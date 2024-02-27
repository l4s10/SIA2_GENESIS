<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Directivos\FacultadController;

// Prefix para las rutas de facultades facultades/
Route::prefix('facultades')->group(function () {
    //** Listar facultades */
    Route::get('/', [FacultadController::class, 'index'])
        ->name('facultades.index');
        //->middleware('can:ver_facultades');

    //  Crear Facultad
    Route::get('/facultades/create', [FacultadController::class, 'create'])
        ->name('facultades.create');
        //->middleware('can:crear_facultad');

    //  Guardar facultad 
    Route::post('/', [FacultadController::class, 'store'])
        ->name('facultades.store');
        //->middleware('can:crear_facultad');

    
    //  Editar facultad
    Route::get('/{facultad}/edit', [FacultadController::class, 'edit'])
        ->name('facultades.edit');
        //->middleware('can:editar_facultad');

    //  Actualizar facultad
    Route::put('/{facultad}', [FacultadController::class, 'update'])
        ->name('facultades.update');
        //->middleware('can:actualizar_facultad');

    //  Eliminar facultad
    Route::delete('/{facultad}', [FacultadController::class, 'destroy'])
        ->name('facultades.destroy');
        //->middleware('can:eliminar_facultad');
});
