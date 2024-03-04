<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Api\ReportesReparacionesController;

// Rutas para la API de reparaciones
Route::middleware('auth:sanctum')->get('/reportes/reparaciones/get-graficos', [ReportesReparacionesController::class, 'getGraficos']);

Route::middleware('auth:sanctum')->post('/reportes/reparaciones/filtrar-general', [ReportesReparacionesController::class, 'filtrarGeneral']);

Route::middleware('auth:sanctum')->post('/reportes/reparaciones/grafico-1', [ReportesReparacionesController::class, 'Grafico1']);

Route::middleware('auth:sanctum')->post('/reportes/reparaciones/grafico-2', [ReportesReparacionesController::class, 'Grafico2']);

Route::middleware('auth:sanctum')->post('/reportes/reparaciones/grafico-3', [ReportesReparacionesController::class, 'Grafico3']);

Route::middleware('auth:sanctum')->post('/reportes/reparaciones/grafico-4', [ReportesReparacionesController::class, 'Grafico4']);

Route::middleware('auth:sanctum')->post('/reportes/reparaciones/grafico-5', [ReportesReparacionesController::class, 'Grafico5']);

Route::middleware('auth:sanctum')->post('/reportes/reparaciones/grafico-6', [ReportesReparacionesController::class, 'Grafico6']);

Route::middleware('auth:sanctum')->post('/reportes/reparaciones/grafico-7', [ReportesReparacionesController::class, 'Grafico7']);

Route::middleware('auth:sanctum')->post('/reportes/reparaciones/grafico-8', [ReportesReparacionesController::class, 'Grafico8']);

Route::middleware('auth:sanctum')->post('/reportes/reparaciones/grafico-9', [ReportesReparacionesController::class, 'Grafico9']);
