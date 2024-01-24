<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Activos\Equipo\TipoEquipoController;
use App\Http\Controllers\Activos\Equipo\EquipoController;

// Ruta tipos de equipos
Route::resource('tiposequipos', TipoEquipoController::class);

// Ruta equipos
Route::resource('equipos', EquipoController::class);
