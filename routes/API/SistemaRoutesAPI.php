<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Api\ReportesSistemaController;

// Rutas para graficos del sistema
Route::middleware('auth:sanctum')->get('/reportes/sistema/get-graficos', [ReportesSistemaController::class, 'getGraficos']);

Route::middleware('auth:sanctum')->post('/reportes/sistema/filtrar-general', [ReportesSistemaController::class, 'filtrarGeneral']);

Route::middleware('auth:sanctum')->post('/reportes/sistema/grafico-1', [ReportesSistemaController::class, 'rankingSolicitudes']);
