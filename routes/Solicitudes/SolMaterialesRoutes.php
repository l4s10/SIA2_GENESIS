<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Solicitud\SolicitudMaterialesController;

// Ruta solicitudes de materiales
Route::resource('solicitudesmateriales', SolicitudMaterialesController::class);
