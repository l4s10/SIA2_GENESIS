<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Solicitud\SolicitudVehiculosController;

// Ruta solicitudes de Vehículos
Route::resource('solicitudesvehiculos', SolicitudVehiculosController::class)->except(['show']);// Excluir las rutas que no estás utilizando actualmente

// Ruta personalizada para exportar las solicitudes vehiculares a Excel
Route::get('solicitudesvehiculos/exportar', [SolicitudVehiculosController::class, 'export'])->name('solicitudesvehiculos.exportar');

// Ruta para descargar la plantilla de Excel
Route::get('descargar-plantilla/{id}', [SolicitudVehiculosController::class, 'descargarPlantilla'])->name('descargar.plantilla');
