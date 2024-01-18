<?php

use Illuminate\Support\Facades\Route;


// Ruta menú de inventarios
Route::get('/inventarios', function(){
    return view ('inventarios.index');
})->name('inventarios.index')->middleware('auth');