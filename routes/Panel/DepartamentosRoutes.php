<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Panel\DepartamentoController;


//Prefix para todas las rutas "/panel"

Route::prefix('panel')->group(function () {
    //Definir cada ruta de departamentos con nombre "departamentos.{nombre_ruta}"
    //** Listar departamentos */
    Route::get('/departamentos', [DepartamentoController::class, 'index'])->name('panel.departamentos.index');

    //** Crear departamento */
    Route::get('/departamentos/create', [DepartamentoController::class, 'create'])->name('panel.departamentos.create');

    //** Guardar departamento */
    Route::post('/departamentos', [DepartamentoController::class, 'store'])->name('panel.departamentos.store');

    //** Mostrar departamento */
    Route::get('/departamentos/{departamento}', [DepartamentoController::class, 'show'])->name('panel.departamentos.show');

    //** Editar departamento */
    Route::get('/departamentos/{departamento}/edit', [DepartamentoController::class, 'edit'])->name('panel.departamentos.edit');

    //** Actualizar departamento */
    Route::put('/departamentos/{departamento}', [DepartamentoController::class, 'update'])->name('panel.departamentos.update');
    
    //** Eliminar departamento */
    Route::delete('/departamentos/{departamento}', [DepartamentoController::class, 'destroy'])->name('panel.departamentos.destroy');
});
