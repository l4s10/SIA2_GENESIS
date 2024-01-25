<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Solicitud\SolicitudFormulariosController;

// Rutas solicitudes de formularios
Route::resource('solicitudesformularios', SolicitudFormulariosController::class);
