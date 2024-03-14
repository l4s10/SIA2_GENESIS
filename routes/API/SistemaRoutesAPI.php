<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Api\ReportesSistemaController;
use App\Http\Controllers\Panel\DepartamentoController;
use App\Http\Controllers\Panel\OficinasController;
use App\Http\Controllers\Panel\UbicacionController;

// Rutas para graficos del sistema
Route::middleware('auth:sanctum')->get('/reportes/sistema/get-graficos', [ReportesSistemaController::class, 'getGraficos']);

Route::middleware('auth:sanctum')->post('/reportes/sistema/filtrar-general', [ReportesSistemaController::class, 'filtrarGeneral']);

Route::middleware('auth:sanctum')->post('/reportes/sistema/grafico-1', [ReportesSistemaController::class, 'rankingSolicitudes']);

Route::middleware('auth:sanctum')->post('/reportes/sistema/grafico-2', [ReportesSistemaController::class, 'getDistribucionPorGenero']);

//Para filtros de registro usuarios
Route::get('/get-ubicaciones/{id}', [UbicacionController::class, 'getUbicaciones']);
Route::get('/get-departamentos/{id}', [DepartamentoController::class, 'getDepartamentos']);

//RUTA GET PARA TABLA CONTINGENCIA
Route::get('/get-totals/ubicaciones/{id}', [ReportesSistemaController::class, 'getTotalsPorUbicacion']);
Route::get('/get-totals/departamentos/{id}', [ReportesSistemaController::class, 'getTotalsPorDepartamento']);

