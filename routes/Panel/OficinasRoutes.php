<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Panel\OficinasController;

//Prefix para todas las rutas "/panel"

Route::prefix('panel')->group(function () {
    //Definir cada ruta de oficinas con nombre "oficinas.{nombre_ruta}"
    //** Listar oficinas */
    Route::get('/oficinas', [OficinasController::class, 'index'])->name('panel.oficinas.index');

    //** Crear oficina */
    Route::get('/oficinas/create', [OficinasController::class, 'create'])->name('panel.oficinas.create');

    //** Guardar oficina */
    Route::post('/oficinas', [OficinasController::class, 'store'])->name('panel.oficinas.store');

    //** Mostrar oficina */
    Route::get('/oficinas/{oficina}', [OficinasController::class, 'show'])->name('panel.oficinas.show');

    //** Editar oficina */
    Route::get('/oficinas/{oficina}/edit', [OficinasController::class, 'edit'])->name('panel.oficinas.edit');

    //** Actualizar oficina */
    Route::put('/oficinas/{oficina}', [OficinasController::class, 'update'])->name('panel.oficinas.update');

    //** Eliminar oficina */
    Route::delete('/oficinas/{oficina}', [OficinasController::class, 'destroy'])->name('panel.oficinas.destroy');
});
