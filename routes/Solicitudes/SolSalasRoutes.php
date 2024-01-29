<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Solicitud\SolicitudSalasController;

// Rutas para solicitudes de salas
Route::resource('solicitudes/salas', SolicitudSalasController::class);

