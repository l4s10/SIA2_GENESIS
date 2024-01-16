<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Equipo\TipoEquipoController;
use App\Http\Controllers\Equipo\EquipoController;

// Ruta para mostrar la lista de tipos de equipos
Route::resource('tiposequipos', TipoEquipoController::class);

// Ruta para mostrar la lista de equipos
Route::resource('equipos', EquipoController::class);
// Aquí puedes agregar más rutas relacionadas con los equipos
