<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Activos\Bodega\BodegaController;

// Grupo de middleware para roles ADMINISTRADOR o INFORMATICA
Route::middleware(['role:ADMINISTRADOR|INFORMATICA'])->group(function () {
    // Prefix para las rutas de bodegas
    Route::prefix('bodegas')->group(function () {
        Route::get('/', [BodegaController::class, 'index'])
            ->name('bodegas.index')
            ->middleware('can:ver_activos');

        Route::get('/getFilteredData', [BodegaController::class, 'getFilteredData'])
            ->name('bodegas.search')
            ->middleware('can:ver_activos');

        Route::get('/create', [BodegaController::class, 'create'])
            ->name('bodegas.create')
            ->middleware('can:crear_activo');

        Route::post('/', [BodegaController::class, 'store'])
            ->name('bodegas.store')
            ->middleware('can:crear_activo');

        Route::get('/{bodega}', [BodegaController::class, 'show'])
            ->name('bodegas.show')
            ->middleware('can:ver_activos');

        Route::get('/{bodega}/edit', [BodegaController::class, 'edit'])
            ->name('bodegas.edit')
            ->middleware('can:editar_activo');

        Route::put('/{bodega}', [BodegaController::class, 'update'])
            ->name('bodegas.update')
            ->middleware('can:actualizar_activo');

        Route::delete('/{bodega}', [BodegaController::class, 'destroy'])
            ->name('bodegas.destroy')
            ->middleware('can:eliminar_activo');
    });
});
