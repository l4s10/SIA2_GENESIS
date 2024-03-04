<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Api\ReportesSalasController;

// rutas para API de salas
Route::middleware('auth:sanctum')->get('/reportes/salas/get-graficos', [ReportesSalasController::class, 'getGraficos']);

Route::middleware('auth:sanctum')->post('/reportes/salas/filtrar-general', [ReportesSalasController::class, 'filtrarGeneral']);

Route::middleware('auth:sanctum')->post('/reportes/salas/grafico-1', [ReportesSalasController::class, 'Grafico1']);

Route::middleware('auth:sanctum')->post('/reportes/salas/grafico-2', [ReportesSalasController::class, 'Grafico2']);

Route::middleware('auth:sanctum')->post('/reportes/salas/grafico-3', [ReportesSalasController::class, 'Grafico3']);

Route::middleware('auth:sanctum')->post('/reportes/salas/grafico-4', [ReportesSalasController::class, 'Grafico4']);

Route::middleware('auth:sanctum')->post('/reportes/salas/grafico-5', [ReportesSalasController::class, 'Grafico5']);
