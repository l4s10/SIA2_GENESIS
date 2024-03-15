<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Panel\DepartamentoController;


//Prefix para todas las rutas "/panel"

Route::prefix('panel')->group(function () {
    //Definir cada ruta de departamentos con nombre "departamentos.{nombre_ruta}"
    //** Listar departamentos */
    Route::get('/departamentos', [DepartamentoController::class, 'index'])
        ->name('panel.departamentos.index')
        ->middleware('can:ver_panel_control');

    //** Crear departamento */
    Route::get('/departamentos/create', [DepartamentoController::class, 'create'])
        ->name('panel.departamentos.create')
        ->middleware('can:crear_panel_control');

    //** Guardar departamento */
    Route::post('/departamentos', [DepartamentoController::class, 'store'])
        ->name('panel.departamentos.store')
        ->middleware('can:crear_panel_control');

    //** Mostrar departamento */
    Route::get('/departamentos/{departamento}', [DepartamentoController::class, 'show'])
        ->name('panel.departamentos.show')
        ->middleware('can:ver_panel_control');

    //** Editar departamento */
    Route::get('/departamentos/{departamento}/edit', [DepartamentoController::class, 'edit'])
        ->name('panel.departamentos.edit')
        ->middleware('can:editar_panel_control');

    //** Actualizar departamento */
    Route::put('/departamentos/{departamento}', [DepartamentoController::class, 'update'])
        ->name('panel.departamentos.update')
        ->middleware('can:actualizar_panel_control');

    //** Eliminar departamento */
    Route::delete('/departamentos/{departamento}', [DepartamentoController::class, 'destroy'])
        ->name('panel.departamentos.destroy')
        ->middleware('can:eliminar_panel_control');
});
