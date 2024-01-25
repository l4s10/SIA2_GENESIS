<?php

use Illuminate\Support\Facades\Route;


// Ruta menú de inventarios
Route::get('inventarios', function(){
    return view ('sia2.activos.index');
})->name('inventarios.index')->middleware('auth');
