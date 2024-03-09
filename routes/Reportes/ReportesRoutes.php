<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Reportes\ReportesController;

Route::prefix('reportes')->group(function () {
    // Rutas para (ADMINISTRADOR|SERVICIOS)
    Route::middleware(['role:ADMINISTRADOR|SERVICIOS'])->group(function () {
        Route::get('/vehiculos', [ReportesController::class, 'Vehiculos'])->name('reportes.vehiculos.index');
        Route::get('/materiales', [ReportesController::class, 'Materiales'])->name('reportes.materiales.index');
        Route::get('/reparaciones', [ReportesController::class, 'Reparaciones'])->name('reportes.reparaciones.index');
    });

    // Rutas para (ADMINISTRADOR|INFORMATICA)
    Route::middleware(['role:ADMINISTRADOR|INFORMATICA'])->group(function () {
        Route::get('/equipos', [ReportesController::class, 'Equipos'])->name('reportes.equipos.index');
        Route::get('/salas', [ReportesController::class, 'Salas'])->name('reportes.salas.index');
        Route::get('/bodegas', [ReportesController::class, 'Bodegas'])->name('reportes.bodegas.index');
    });

    // Rutas para (ADMINISTRADOR)
    Route::middleware(['role:ADMINISTRADOR'])->group(function () {
        Route::get('/sistema', [ReportesController::class, 'Sistema'])->name('reportes.sistema.index');
    });

    // Rutas para (ADMINISTRADOR|SERVICIOS|INFORMATICA)
    Route::middleware(['role:ADMINISTRADOR|SERVICIOS|INFORMATICA'])->group(function () {
        Route::get('/home', [ReportesController::class, 'Home'])->name('reportes.home.index');
    });
});
