<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Panel\ComunasController;


//Prefix para todas las rutas "/panel"

Route::prefix('panel')->group(function () {
    //Definir cada ruta de comunas con nombre "comunas.{nombre_ruta}"
    //** Listar comunas */
    Route::get('/comunas', [ComunasController::class, 'index'])->name('panel.comunas.index');
    //** Crear comuna */
    Route::get('/comunas/create', [ComunasController::class, 'create'])->name('panel.comunas.create');
    //** Guardar comuna */
    Route::post('/comunas', [ComunasController::class, 'store'])->name('panel.comunas.store');
    //** Mostrar comuna */
    Route::get('/comunas/{comuna}', [ComunasController::class, 'show'])->name('panel.comunas.show');
    //** Editar comuna */
    Route::get('/comunas/{comuna}/edit', [ComunasController::class, 'edit'])->name('panel.comunas.edit');
    //** Actualizar comuna */
    Route::put('/comunas/{comuna}', [ComunasController::class, 'update'])->name('panel.comunas.update');
    //** Eliminar comuna */
    Route::delete('/comunas/{comuna}', [ComunasController::class, 'destroy'])->name('panel.comunas.destroy');
});
