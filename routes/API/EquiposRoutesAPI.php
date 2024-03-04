<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Api\ReportesEquiposController;

// rutas para api de equipos
Route::middleware('auth:sanctum')->get('/reportes/equipos/get-graficos', [ReportesEquiposController::class, 'getGraficos']);

Route::middleware('auth:sanctum')->post('/reportes/equipos/filtrar-general', [ReportesEquiposController::class, 'filtrarGeneral']);

Route::middleware('auth:sanctum')->post('/reportes/equipos/grafico-1', [ReportesEquiposController::class, 'Grafico1']);

Route::middleware('auth:sanctum')->post('/reportes/equipos/grafico-2', [ReportesEquiposController::class, 'Grafico2']);

Route::middleware('auth:sanctum')->post('/reportes/equipos/grafico-3', [ReportesEquiposController::class, 'Grafico3']);

Route::middleware('auth:sanctum')->post('/reportes/equipos/grafico-4', [ReportesEquiposController::class, 'Grafico4']);

Route::middleware('auth:sanctum')->post('/reportes/equipos/grafico-5', [ReportesEquiposController::class, 'Grafico5']);
