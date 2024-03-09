<?php

use Illuminate\Support\Facades\Route;


// Ruta menú de inventarios
// Grupo de middleware para roles ADMINISTRADOR o INFORMATICA
Route::middleware(['role:ADMINISTRADOR|INFORMATICA|SERVICIOS'])->group(function () {
    Route::get('inventarios', function(){
        return view('sia2.activos.index');
    })->name('inventarios.index')->middleware(['auth', 'can:ver_activos']);
});
