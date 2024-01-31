<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Movimientos\MovimientosController;

//Prefix para movimientos
Route::prefix('movimientos')->group(function () {
    //Vistas materiales
    Route::get('/materiales', [MovimientosController::class, 'materiales'])->name('movimientos.materiales');
    //Vistas equipos
    Route::get('/equipos', [MovimientosController::class, 'equipos'])->name('movimientos.equipos');
});
