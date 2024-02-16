<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Activos\Sala\SalaController;

// Ruta salas
Route::prefix('salas')->group(function () {
    Route::get('/', [SalaController::class, 'index'])
        ->name('salas.index')
        ->middleware('can:ver_activos');

    Route::get('/create', [SalaController::class, 'create'])
        ->name('salas.create')
        ->middleware('can:crear_activo');

    Route::post('/', [SalaController::class, 'store'])
        ->name('salas.store')
        ->middleware('can:crear_activo');

    Route::get('/{sala}', [SalaController::class, 'show'])
        ->name('salas.show')
        ->middleware('can:ver_activo');

    Route::get('/{sala}/edit', [SalaController::class, 'edit'])
        ->name('salas.edit')
        ->middleware('can:editar_activo');

    Route::put('/{sala}', [SalaController::class, 'update'])
        ->name('salas.update')
        ->middleware('can:actualizar_activo');

    Route::delete('/{sala}', [SalaController::class, 'destroy'])
        ->name('salas.destroy')
        ->middleware('can:eliminar_activo');
});
