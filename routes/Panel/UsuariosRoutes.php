<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Panel\UsuarioController;

//Prefix para todas las rutas "/panel"

Route::prefix('panel')->group(function () {
    //Definir cada ruta de usuarios con nombre "usuarios.{nombre_ruta}"
    //** Listar usuarios */
    Route::get('/usuarios', [UsuarioController::class, 'index'])
        ->name('panel.usuarios.index')
        ->middleware('can:ver_panel_control');

    //** Crear usuario */
    Route::get('/usuarios/create', [UsuarioController::class, 'create'])
        ->name('panel.usuarios.create')
        ->middleware('can:crear_panel_control');

    //** Guardar usuario */
    Route::post('/usuarios', [UsuarioController::class, 'store'])
        ->name('panel.usuarios.store')
        ->middleware('can:crear_panel_control');

    //** Mostrar usuario */
    Route::get('/usuarios/{usuario}', [UsuarioController::class, 'show'])
        ->name('panel.usuarios.show')
        ->middleware('can:ver_panel_control');

    //** Editar usuario */
    Route::get('/usuarios/{usuario}/edit', [UsuarioController::class, 'edit'])
        ->name('panel.usuarios.edit')
        ->middleware('can:editar_panel_control');

    //** Actualizar usuario */
    Route::put('/usuarios/{usuario}', [UsuarioController::class, 'update'])
        ->name('panel.usuarios.update')
        ->middleware('can:actualizar_panel_control');

    //** Eliminar usuario */
    Route::delete('/usuarios/{usuario}', [UsuarioController::class, 'destroy'])
        ->name('panel.usuarios.destroy')
        ->middleware('can:eliminar_panel_control');
});
