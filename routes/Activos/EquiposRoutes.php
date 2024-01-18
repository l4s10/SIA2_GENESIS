<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Equipo\TipoEquipoController;
use App\Http\Controllers\Equipo\EquipoController;

// Ruta tipos de equipos
Route::resource('tiposequipos', TipoEquipoController::class);

// Ruta equipos
Route::resource('equipos', EquipoController::class);
