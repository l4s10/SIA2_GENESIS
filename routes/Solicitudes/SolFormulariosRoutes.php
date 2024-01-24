<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Solicitud\SolicitudFormulariosController;
use App\Http\Controllers\Activos\Formulario\FormularioController;

// Rutas solicitudes de formularios
Route::resource('solicitudesformularios', SolicitudFormulariosController::class);

//Rutas para el carrito de compras
Route::post('/addToCart/formulario/{formulario}', [FormularioController::class, 'addToCart'])->name('formularios.addToCart');
Route::get('/showCart', [FormularioController::class, 'showCart'])->name('formularios.showCart');

// Borrar elemento del carrito
Route::delete('/removeItem/formulario/{id}', [FormularioController::class, 'deleteFromCart'])->name('formularios.removeItem');
