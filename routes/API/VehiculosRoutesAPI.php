
<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Api\ReportesVehiculosController;

// Rutas para graficos de vehiculos
Route::middleware('auth:sanctum')->get('/reportes/vehiculos/get-graficos', [ReportesVehiculosController::class, 'getGraficos']);

Route::middleware('auth:sanctum')->post('/reportes/vehiculos/filtrar-general', [ReportesVehiculosController::class, 'filtrarGeneral']);

Route::middleware('auth:sanctum')->post('/reportes/vehiculos/grafico-1', [ReportesVehiculosController::class, 'Grafico1']);

Route::middleware('auth:sanctum')->post('/reportes/vehiculos/grafico-2', [ReportesVehiculosController::class, 'Grafico2']);

Route::middleware('auth:sanctum')->post('/reportes/vehiculos/grafico-3', [ReportesVehiculosController::class, 'Grafico3']);

Route::middleware('auth:sanctum')->post('/reportes/vehiculos/grafico-4', [ReportesVehiculosController::class, 'Grafico4']);

Route::middleware('auth:sanctum')->post('/reportes/vehiculos/grafico-5', [ReportesVehiculosController::class, 'Grafico5']);


// Ruta para georeferenciacion
Route::middleware('auth:sanctum')->get('/reportes/vehiculos/georeferenciacion', [ReportesVehiculosController::class, 'georeferenciacion']);
