<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Solicitud\SolicitudEquiposController;

// Ruta solicitudes de equipos
Route::resource('solicitudesequipos', SolicitudEquiposController::class);
