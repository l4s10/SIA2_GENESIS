<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Panel\DepartamentosController;


//Prefix para todas las rutas "/panel"

Route::prefix('panel')->group(function () {
    //Definir cada ruta de departamentos con nombre "departamentos.{nombre_ruta}"
    //** Listar departamentos */
    Route::get('/departamentos', [DepartamentosController::class, 'index'])->name('panel.departamentos.index');

    //** Crear departamento */
    Route::get('/departamentos/create', [DepartamentosController::class, 'create'])->name('panel.departamentos.create');

    //** Guardar departamento */
    Route::post('/departamentos', [DepartamentosController::class, 'store'])->name('panel.departamentos.store');

    //** Mostrar departamento */
    Route::get('/departamentos/{departamento}', [DepartamentosController::class, 'show'])->name('panel.departamentos.show');

    //** Editar departamento */
    Route::get('/departamentos/{departamento}/edit', [DepartamentosController::class, 'edit'])->name('panel.departamentos.edit');

    //** Actualizar departamento */
    Route::put('/departamentos/{departamento}', [DepartamentosController::class, 'update'])->name('panel.departamentos.update');
    
    //** Eliminar departamento */
    Route::delete('/departamentos/{departamento}', [DepartamentosController::class, 'destroy'])->name('panel.departamentos.destroy');
});
