<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Api\ReportesMaterialesController;
use App\Http\Controllers\Api\ReportesEquiposController;
use App\Http\Controllers\Api\ReportesSalasController;
use App\Http\Controllers\Api\ReportesBodegasController;
use App\Http\Controllers\Api\ReportesReparacionesController;
use App\Http\Controllers\Api\ReportesSistemaController;
use App\Http\Controllers\Api\ReportesVehiculosController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/getToken', [LoginController::class, 'getToken']);

Route::middleware('auth:sanctum')->get('/reportes/materiales/get-graficos', [ReportesMaterialesController::class, 'getGraficos']);

// Route::get('/reportes/materiales', [ReportesMaterialesController::class, 'Grafico1']);

Route::middleware('auth:sanctum')->post('/reportes/materiales/filtrar-general', [ReportesMaterialesController::class, 'filtrarGeneral']);


// rutas para api de equipos
Route::middleware('auth:sanctum')->get('/reportes/equipos/get-graficos', [ReportesEquiposController::class, 'getGraficos']);

Route::middleware('auth:sanctum')->post('/reportes/equipos/filtrar-general', [ReportesEquiposController::class, 'filtrarGeneral']);

// rutas para api de salas
Route::middleware('auth:sanctum')->get('/reportes/salas/get-graficos', [ReportesSalasController::class, 'getGraficos']);

// Rutas para la API de bodegas
Route::middleware('auth:sanctum')->get('/reportes/bodegas/get-graficos', [ReportesBodegasController::class, 'getGraficos']);

Route::middleware('auth:sanctum')->post('/reportes/bodegas/filtrar-general', [ReportesBodegasController::class, 'filtrarGeneral']);

// Rutas para la API de reparaciones
Route::middleware('auth:sanctum')->get('/reportes/reparaciones/get-graficos', [ReportesReparacionesController::class, 'getGraficos']);

Route::middleware('auth:sanctum')->post('/reportes/reparaciones/filtrar-general', [ReportesReparacionesController::class, 'filtrarGeneral']);

// Rutas para graficos del sistema
Route::middleware('auth:sanctum')->get('/reportes/sistema/get-graficos', [ReportesSistemaController::class, 'getGraficos']);

Route::middleware('auth:sanctum')->post('/reportes/sistema/filtrar-general', [ReportesSistemaController::class, 'filtrarGeneral']);

// Rutas para graficos de vehiculos
Route::middleware('auth:sanctum')->get('/reportes/vehiculos/get-graficos', [ReportesVehiculosController::class, 'getGraficos']);

Route::middleware('auth:sanctum')->post('/reportes/vehiculos/filtrar-general', [ReportesVehiculosController::class, 'filtrarGeneral']);
