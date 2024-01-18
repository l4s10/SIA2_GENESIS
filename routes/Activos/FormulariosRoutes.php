<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Activos\Formulario\FormularioController;

// Ruta formularios
Route::resource('formularios', FormularioController::class);
