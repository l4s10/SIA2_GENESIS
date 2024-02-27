<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Directivos\PolizaController;

// Prefix para las rutas de polizas /
Route::prefix('polizas')->group(function () {
    //** Listar polizas */
    Route::get('/', [PolizaController::class, 'index'])
        ->name('polizas.index');
        //->middleware('can:ver_polizas');

    //  Crear poliza
    Route::get('/polizas/create', [PolizaController::class, 'create'])
        ->name('polizas.create');
        //->middleware('can:crear_poliza');

    //  Guardar poliza 
    Route::post('/', [PolizaController::class, 'store'])
        ->name('polizas.store');
        //->middleware('can:crear_poliza');

    
    //  Editar poliza
    Route::get('/{poliza}/edit', [PolizaController::class, 'edit'])
        ->name('polizas.edit');
        //->middleware('can:editar_poliza');

    //  Actualizar poliza
    Route::put('/{poliza}', [PolizaController::class, 'update'])
        ->name('polizas.update');
        //->middleware('can:actualizar_poliza');

    //  Eliminar poliza
    Route::delete('/{poliza}', [PolizaController::class, 'destroy'])
        ->name('polizas.destroy');
        //->middleware('can:eliminar_poliza');
});
