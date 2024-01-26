<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Activos\Vehiculo\VehiculoController;

// Ruta Vehiculos
Route::resource('vehiculos', VehiculoController::class);



