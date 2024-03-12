<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Panel\ComunaController;


//Prefix para todas las rutas "/panel"

Route::prefix('panel')->group(function () {
    //Definir cada ruta de comunas con nombre "comunas.{nombre_ruta}"
    //** Listar comunas */
    Route::get('/comunas', [ComunaController::class, 'index'])->name('panel.comunas.index');
    //** Crear comuna */
    Route::get('/comunas/create', [ComunaController::class, 'create'])->name('panel.comunas.create');
    //** Guardar comuna */
    Route::post('/comunas', [ComunaController::class, 'store'])->name('panel.comunas.store');
    //** Mostrar comuna */
    Route::get('/comunas/{comuna}', [ComunaController::class, 'show'])->name('panel.comunas.show');
    //** Editar comuna */
    Route::get('/comunas/{comuna}/edit', [ComunaController::class, 'edit'])->name('panel.comunas.edit');
    //** Actualizar comuna */
    Route::put('/comunas/{comuna}', [ComunaController::class, 'update'])->name('panel.comunas.update');
    //** Eliminar comuna */
    Route::delete('/comunas/{comuna}', [ComunaController::class, 'destroy'])->name('panel.comunas.destroy');
});
