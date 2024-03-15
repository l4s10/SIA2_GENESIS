<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Activos\Material\MaterialController;
use App\Http\Controllers\Activos\Formulario\FormularioController;
use App\Http\Controllers\Activos\Equipo\TipoEquipoController;


// Rutas para Materiales
Route::prefix('material')->name('materiales.')->group(function () {
    Route::post('/addToCart/{material}', [MaterialController::class, 'addToCart'])
        ->name('addToCart')
        ->middleware('can:crear_solicitud');

    Route::delete('/removeItem/{id}', [MaterialController::class, 'deleteFromCart'])
        ->name('removeItem')
        ->middleware('can:crear_solicitud');
});

// Rutas para Formularios
Route::prefix('formulario')->name('formularios.')->group(function () {
    Route::post('/addToCart/{formulario}', [FormularioController::class, 'addToCart'])
        ->name('addToCart')
        ->middleware('can:crear_solicitud');

    Route::delete('/removeItem/{id}', [FormularioController::class, 'deleteFromCart'])
        ->name('removeItem')
        ->middleware('can:crear_solicitud');
});

// Rutas para Tipos de Equipos
Route::prefix('tipoequipo')->name('tiposequipos.')->group(function () {
    Route::post('/addToCart/{tipoequipo}', [TipoEquipoController::class, 'addToCart'])
        ->name('addToCart')
        ->middleware('can:crear_solicitud');

    Route::delete('/removeItem/{id}', [TipoEquipoController::class, 'removeFromCart'])
        ->name('removeItem')
        ->middleware('can:crear_solicitud');
});
