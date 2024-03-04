<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Api\ReportesMaterialesController;


Route::middleware('auth:sanctum')->get('/reportes/materiales/get-graficos', [ReportesMaterialesController::class, 'getGraficos']);

Route::middleware('auth:sanctum')->post('/reportes/materiales/filtrar-general', [ReportesMaterialesController::class, 'filtrarGeneral']);

Route::middleware('auth:sanctum')->post('/reportes/materiales/grafico-1', [ReportesMaterialesController::class, 'Grafico1']);

Route::middleware('auth:sanctum')->post('/reportes/materiales/grafico-2', [ReportesMaterialesController::class, 'Grafico2']);

Route::middleware('auth:sanctum')->post('/reportes/materiales/grafico-3', [ReportesMaterialesController::class, 'Grafico3']);

Route::middleware('auth:sanctum')->post('/reportes/materiales/grafico-4', [ReportesMaterialesController::class, 'Grafico4']);

Route::middleware('auth:sanctum')->post('/reportes/materiales/grafico-5', [ReportesMaterialesController::class, 'Grafico5']);
