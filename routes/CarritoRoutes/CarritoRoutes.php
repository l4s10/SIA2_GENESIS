<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Activos\Material\MaterialController;
use App\Http\Controllers\Activos\Formulario\FormularioController;
use App\Http\Controllers\Activos\Equipo\TipoEquipoController;


// Rutas para Materiales
Route::prefix('material')->name('materiales.')->group(function () {
    Route::post('/addToCart/{material}', [MaterialController::class, 'addToCart'])->name('addToCart');
    Route::delete('/removeItem/{id}', [MaterialController::class, 'deleteFromCart'])->name('removeItem');
});

// Rutas para Formularios
Route::prefix('formulario')->name('formularios.')->group(function () {
    Route::post('/addToCart/{formulario}', [FormularioController::class, 'addToCart'])->name('addToCart');
    // Route::get('/showCart', [FormularioController::class, 'showCart'])->name('showCart');
    Route::delete('/removeItem/{id}', [FormularioController::class, 'deleteFromCart'])->name('removeItem');
});

// Rutas para Tipos de Equipos
Route::prefix('tipoequipo')->name('tiposequipos.')->group(function () {
    Route::post('/addToCart/{tipoequipo}', [TipoEquipoController::class, 'addToCart'])->name('addToCart');
    Route::delete('/removeItem/{id}', [TipoEquipoController::class, 'deleteFromCart'])->name('removeItem');
});
