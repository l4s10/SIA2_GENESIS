<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Activos\Sala\SalaController;

// Ruta salas
Route::resource('salas',SalaController::class);


