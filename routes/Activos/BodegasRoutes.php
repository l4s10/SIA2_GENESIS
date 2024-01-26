<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Activos\Bodega\BodegaController;

// Ruta bodegas
Route::resource('bodegas', BodegaController::class);


