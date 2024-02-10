<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Solicitud\SolicitudVehiculosCttController;

// Ruta solicitudes de Vehículos
Route::resource('solicitudesvehiculosctt', SolicitudVehiculosCttController::class)->except(['show']);// Excluir las rutas que no estás utilizando actualmente

// Ruta personalizada para exportar las solicitudes vehiculares a Excel
Route::get('solicitudesvehiculosctt/exportar', [SolicitudVehiculosCttController::class, 'export'])->name('solicitudesvehiculosctt.exportar');

// Ruta para descargar la plantilla de Excel
Route::get('descargar-plantilla/{id}', [SolicitudVehiculosCttController::class, 'descargarPlantilla'])->name('descargar.plantilla');
