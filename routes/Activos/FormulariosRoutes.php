<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Activos\Formulario\FormularioController;

// Grupo de middleware para roles ADMINISTRADOR o SERVICIOS
Route::middleware(['role:ADMINISTRADOR|SERVICIOS'])->group(function () {
    // Ruta formularios
    Route::prefix('formularios')->group(function () {
        Route::get('/', [FormularioController::class, 'index'])
            ->name('formularios.index')
            ->middleware('can:ver_activos');

        Route::get('/create', [FormularioController::class, 'create'])
            ->name('formularios.create')
            ->middleware('can:crear_activo');

        Route::post('/', [FormularioController::class, 'store'])
            ->name('formularios.store')
            ->middleware('can:crear_activo');

        Route::get('/{formulario}', [FormularioController::class, 'show'])
            ->name('formularios.show')
            ->middleware('can:ver_activos');

        Route::get('/{formulario}/edit', [FormularioController::class, 'edit'])
            ->name('formularios.edit')
            ->middleware('can:editar_activo');

        Route::put('/{formulario}', [FormularioController::class, 'update'])
            ->name('formularios.update')
            ->middleware('can:actualizar_activo');

        Route::delete('/{formulario}', [FormularioController::class, 'destroy'])
            ->name('formularios.destroy')
            ->middleware('can:eliminar_activo');
    });
});
