<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Api\ReportesBodegasController;


// Rutas para la API de bodegas

Route::middleware('auth:sanctum')->get('/reportes/bodegas/get-graficos', [ReportesBodegasController::class, 'getGraficos']);

Route::middleware('auth:sanctum')->post('/reportes/bodegas/filtrar-general', [ReportesBodegasController::class, 'filtrarGeneral']);

Route::middleware('auth:sanctum')->post('/reportes/bodegas/grafico-1', [ReportesBodegasController::class, 'Grafico1']);

Route::middleware('auth:sanctum')->post('/reportes/bodegas/grafico-2', [ReportesBodegasController::class, 'Grafico2']);

Route::middleware('auth:sanctum')->post('/reportes/bodegas/grafico-3', [ReportesBodegasController::class, 'Grafico3']);

Route::middleware('auth:sanctum')->post('/reportes/bodegas/grafico-4', [ReportesBodegasController::class, 'Grafico4']);

Route::middleware('auth:sanctum')->post('/reportes/bodegas/grafico-5', [ReportesBodegasController::class, 'Grafico5']);
