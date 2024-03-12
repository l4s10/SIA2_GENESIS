<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Api\ReportesSistemaController;
use App\Http\Controllers\Panel\DepartamentosController;
use App\Http\Controllers\Panel\OficinasController;
use App\Http\Controllers\Panel\UbicacionesController;

// Rutas para graficos del sistema
Route::middleware('auth:sanctum')->get('/reportes/sistema/get-graficos', [ReportesSistemaController::class, 'getGraficos']);

Route::middleware('auth:sanctum')->post('/reportes/sistema/filtrar-general', [ReportesSistemaController::class, 'filtrarGeneral']);

Route::middleware('auth:sanctum')->post('/reportes/sistema/grafico-1', [ReportesSistemaController::class, 'rankingSolicitudes']);

Route::middleware('auth:sanctum')->post('/reportes/sistema/grafico-2', [ReportesSistemaController::class, 'getDistribucionPorGenero']);

//Para filtros de registro usuarios
Route::get('/get-ubicaciones/{id}', [UbicacionesController::class, 'getUbicaciones']);
Route::get('/get-departamentos/{id}', [DepartamentosController::class, 'getDepartamentos']);

//RUTA GET PARA TABLA CONTINGENCIA
Route::get('/get-totals/ubicaciones/{id}', [ReportesSistemaController::class, 'getTotalsPorUbicacion']);
Route::get('/get-totals/departamentos/{id}', [ReportesSistemaController::class, 'getTotalsPorDepartamento']);

