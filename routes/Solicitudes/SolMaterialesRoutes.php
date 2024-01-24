<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Solicitud\SolicitudMaterialesController;
use App\Http\Controllers\Material\MaterialController;

// Ruta solicitudes de materiales
Route::resource('solicitudesmateriales', SolicitudMaterialesController::class);

Route::post('/addToCart/material/{material}', [MaterialController::class, 'addToCart'])->name('materiales.addToCart');

// Elimitar elemento del carrito
Route::delete('/removeItem/material/{id}', [MaterialController::class, 'deleteFromCart'])->name('materiales.removeItem');
