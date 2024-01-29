<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Solicitud\SolicitudBodegasController;

// Rutas para solicitudes de bodegas
Route::resource('solicitudes/bodegas', SolicitudBodegasController::class);
