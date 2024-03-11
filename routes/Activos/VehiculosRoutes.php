<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Activos\Vehiculo\VehiculoController;

// Grupo de middleware para roles ADMINISTRADOR o SERVICIOS
Route::middleware(['role:ADMINISTRADOR|SERVICIOS'])->group(function () {
    // Ruta Vehiculos
    Route::prefix('vehiculos')->group(function () {
        Route::get('/', [VehiculoController::class, 'index'])
            ->name('vehiculos.index')
            ->middleware('can:ver_activos');

        Route::get('/getFilteredData', [VehiculoController::class, 'getFilteredData'])
            ->name('vehiculos.search')
            ->middleware('can:ver_activos');

        Route::get('/create', [VehiculoController::class, 'create'])
            ->name('vehiculos.create')
            ->middleware('can:crear_activo');

        Route::post('/', [VehiculoController::class, 'store'])
            ->name('vehiculos.store')
            ->middleware('can:crear_activo');

        Route::get('/{vehiculo}', [VehiculoController::class, 'show'])
            ->name('vehiculos.show')
            ->middleware('can:ver_activo');

        Route::get('/{vehiculo}/edit', [VehiculoController::class, 'edit'])
            ->name('vehiculos.edit')
            ->middleware('can:editar_activo');

        Route::put('/{vehiculo}', [VehiculoController::class, 'update'])
            ->name('vehiculos.update')
            ->middleware('can:actualizar_activo');

        Route::delete('/{vehiculo}', [VehiculoController::class, 'destroy'])
            ->name('vehiculos.destroy')
            ->middleware('can:eliminar_activo');
    });
});
