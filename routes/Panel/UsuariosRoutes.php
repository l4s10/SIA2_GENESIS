<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Panel\UsuarioController;

//Prefix para todas las rutas "/panel"

Route::prefix('panel')->group(function () {
    //Definir cada ruta de usuarios con nombre "usuarios.{nombre_ruta}"
    //** Listar usuarios */
    Route::get('/usuarios', [UsuarioController::class, 'index'])->name('panel.usuarios.index');

    //** Crear usuario */
    Route::get('/usuarios/create', [UsuarioController::class, 'create'])->name('panel.usuarios.create');

    //** Guardar usuario */
    Route::post('/usuarios', [UsuarioController::class, 'store'])->name('panel.usuarios.store');

    //** Mostrar usuario */
    Route::get('/usuarios/{usuario}', [UsuarioController::class, 'show'])->name('panel.usuarios.show');

    //** Editar usuario */
    Route::get('/usuarios/{usuario}/edit', [UsuarioController::class, 'edit'])->name('panel.usuarios.edit');

    //** Actualizar usuario */
    Route::put('/usuarios/{usuario}', [UsuarioController::class, 'update'])->name('panel.usuarios.update');

    //** Eliminar usuario */
    Route::delete('/usuarios/{usuario}', [UsuarioController::class, 'destroy'])->name('panel.usuarios.destroy');
});
