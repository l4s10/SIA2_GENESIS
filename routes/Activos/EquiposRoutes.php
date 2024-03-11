<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Activos\Equipo\TipoEquipoController;
use App\Http\Controllers\Activos\Equipo\EquipoController;

// Grupo de middleware para roles ADMINISTRADOR o INFORMATICA
Route::middleware(['role:ADMINISTRADOR|INFORMATICA'])->group(function () {

    // Rutas para tipos de equipos
    Route::prefix('tiposequipos')->group(function () {
        Route::get('/', [TipoEquipoController::class, 'index'])
            ->name('tiposequipos.index')
            ->middleware('can:ver_activos');

        Route::get('/create', [TipoEquipoController::class, 'create'])
            ->name('tiposequipos.create')
            ->middleware('can:crear_activo');

        Route::post('/', [TipoEquipoController::class, 'store'])
            ->name('tiposequipos.store')
            ->middleware('can:crear_activo');

        Route::get('/{tipoEquipo}', [TipoEquipoController::class, 'show'])
            ->name('tiposequipos.show')
            ->middleware('can:ver_activos');

        Route::get('/{tipoEquipo}/edit', [TipoEquipoController::class, 'edit'])
            ->name('tiposequipos.edit')
            ->middleware('can:editar_activo');

        Route::put('/{tipoEquipo}', [TipoEquipoController::class, 'update'])
            ->name('tiposequipos.update')
            ->middleware('can:actualizar_activo');

        Route::delete('/{tipoEquipo}', [TipoEquipoController::class, 'destroy'])
            ->name('tiposequipos.destroy')
            ->middleware('can:eliminar_activo');
    });

    // Rutas para equipos
    Route::prefix('equipos')->group(function () {
        Route::get('/', [EquipoController::class, 'index'])
            ->name('equipos.index')
            ->middleware('can:ver_activos');

        Route::get('/getFilteredData', [EquipoController::class, 'getFilteredData'])
            ->name('equipos.search')
            ->middleware('can:ver_activos');


        Route::get('/create', [EquipoController::class, 'create'])
            ->name('equipos.create')
            ->middleware('can:crear_activo');

        Route::post('/', [EquipoController::class, 'store'])
            ->name('equipos.store')
            ->middleware('can:crear_activo');

        Route::get('/{equipo}', [EquipoController::class, 'show'])
            ->name('equipos.show')
            ->middleware('can:ver_activos');

        Route::get('/{equipo}/edit', [EquipoController::class, 'edit'])
            ->name('equipos.edit')
            ->middleware('can:editar_activo');

        Route::put('/{equipo}', [EquipoController::class, 'update'])
            ->name('equipos.update')
            ->middleware('can:actualizar_activo');

        Route::delete('/{equipo}', [EquipoController::class, 'destroy'])
            ->name('equipos.destroy')
            ->middleware('can:eliminar_activo');
    });
});
