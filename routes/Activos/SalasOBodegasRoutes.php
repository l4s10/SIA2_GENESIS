<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Activos\SalaOBodega\SalaOBodegaController;

// Ruta salas o bodegas
Route::resource('salasobodegas',SalaOBodegaController::class);


