<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SolicitudMaterialesController;
use App\Http\Controllers\Material\MaterialController;

// Ruta solicitudes de materiales
Route::resource('solicitudesmateriales', SolicitudMaterialesController::class);

Route::post('/addToCart/{material}', [MaterialController::class, 'addToCart'])->name('materiales.addToCart');
Route::get('/showCart', [MaterialController::class, 'showCart'])->name('showCart');
