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

// Importar archivos en la carpeta API

require __DIR__.'/API/MaterialesRoutesAPI.php';
require __DIR__.'/API/EquiposRoutesAPI.php';
require __DIR__.'/API/SalasRoutesAPI.php';
require __DIR__.'/API/BodegasRoutesAPI.php';
require __DIR__.'/API/ReparacionesRoutesAPI.php';
require __DIR__.'/API/SistemaRoutesAPI.php';
require __DIR__.'/API/VehiculosRoutesAPI.php';

