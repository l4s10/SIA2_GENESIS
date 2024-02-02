<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Panel\UsuariosController;

//Prefix para todas las rutas "/panel"

Route::prefix('panel')->group(function () {
    //Definir cada ruta de usuarios con nombre "usuarios.{nombre_ruta}"
    //** Listar usuarios */
    Route::get('/usuarios', [UsuariosController::class, 'index'])->name('panel.usuarios.index');

    //** Crear usuario */
    Route::get('/usuarios/create', [UsuariosController::class, 'create'])->name('panel.usuarios.create');

    //** Guardar usuario */
    Route::post('/usuarios', [UsuariosController::class, 'store'])->name('panel.usuarios.store');

    //** Mostrar usuario */
    Route::get('/usuarios/{usuario}', [UsuariosController::class, 'show'])->name('panel.usuarios.show');

    //** Editar usuario */
    Route::get('/usuarios/{usuario}/edit', [UsuariosController::class, 'edit'])->name('panel.usuarios.edit');

    //** Actualizar usuario */
    Route::put('/usuarios/{usuario}', [UsuariosController::class, 'update'])->name('panel.usuarios.update');

    //** Eliminar usuario */
    Route::delete('/usuarios/{usuario}', [UsuariosController::class, 'destroy'])->name('panel.usuarios.destroy');
});
