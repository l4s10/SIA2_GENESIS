<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Solicitud\SolicitudVehiculosController;

// Ruta solicitudes de Vehículos
Route::resource('solicitudesvehiculos', SolicitudVehiculosController::class)->except(['show']);// Excluir las rutas que no estás utilizando actualmente


// Ruta para index de solicitudes Por Aprobar
Route::get('solicitudesvehiculos/indexPorAprobar', [SolicitudVehiculosController::class, 'indexPorAprobar'])->name('solicitudesvehiculos.indexPorAprobar');

// Ruta para index de solicitudes Por Rendir
Route::get('solicitudesvehiculos/indexPorRendir', [SolicitudVehiculosController::class, 'indexPorRendir'])->name('solicitudesvehiculos.indexPorRendir');

// Ruta para acceder al Timeline
Route::get('solicitudesvehiculos/{id}', [SolicitudVehiculosController::class, 'timeline'])->name('solicitudesvehiculos.timeline');

// Agregar la ruta para verificar la contraseña
Route::post('verificar-contrasena', [SolicitudVehiculosController::class, 'verificarContrasena'])->name('verificar.contrasena');

// Ruta para descargar PDF
Route::get('descargar-plantilla/{id}', [SolicitudVehiculosController::class, 'descargarPlantilla'])->name('descargar.plantilla');
