<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Api\ReportesMaterialesController;

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

