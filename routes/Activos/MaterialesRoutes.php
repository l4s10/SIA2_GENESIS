<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Activos\Material\TipoMaterialController;
use App\Http\Controllers\Activos\Material\MaterialController;

// Ruta materiales
Route::resource('materiales', MaterialController::class);
// Ruta tipos de material
Route::resource('tiposmateriales', TipoMaterialController::class);

// Ruta para exportar a excel
Route::get('materiales/exportables/excel', [MaterialController::class, 'exportExcel'])->name('exportar-materiales');

