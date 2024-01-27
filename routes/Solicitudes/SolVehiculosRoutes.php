<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Solicitud\SolicitudVehiculosController;

// Ruta solicitudes de Vehículos
Route::resource('solicitudesvehiculos', SolicitudVehiculosController::class);
