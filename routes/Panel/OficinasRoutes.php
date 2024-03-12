<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Panel\OficinaController;

//Prefix para todas las rutas "/panel"

Route::prefix('panel')->group(function () {
    //Definir cada ruta de oficinas con nombre "oficinas.{nombre_ruta}"
    //** Listar oficinas */
    Route::get('/oficinas', [OficinaController::class, 'index'])->name('panel.oficinas.index');

    //** Crear oficina */
    Route::get('/oficinas/create', [OficinaController::class, 'create'])->name('panel.oficinas.create');

    //** Guardar oficina */
    Route::post('/oficinas', [OficinaController::class, 'store'])->name('panel.oficinas.store');

    //** Mostrar oficina */
    Route::get('/oficinas/{oficina}', [OficinaController::class, 'show'])->name('panel.oficinas.show');

    //** Editar oficina */
    Route::get('/oficinas/{oficina}/edit', [OficinaController::class, 'edit'])->name('panel.oficinas.edit');

    //** Actualizar oficina */
    Route::put('/oficinas/{oficina}', [OficinaController::class, 'update'])->name('panel.oficinas.update');

    //** Eliminar oficina */
    Route::delete('/oficinas/{oficina}', [OficinaController::class, 'destroy'])->name('panel.oficinas.destroy');
});
