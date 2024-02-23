<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Reportes\ReportesController;

Route::prefix('reportes')->group(function () {
    Route::get('/home', [ReportesController::class, 'Home'])->name('reportes.home.index');
    Route::get('/materiales', [ReportesController::class, 'Materiales'])->name('reportes.materiales.index');
    Route::get('/equipos', [ReportesController::class, 'Equipos'])->name('reportes.equipos.index');
    Route::get('/salas', [ReportesController::class, 'Salas'])->name('reportes.salas.index');
    Route::get('/bodegas', [ReportesController::class, 'Bodegas'])->name('reportes.bodegas.index');
    Route::get('/reparaciones', [ReportesController::class, 'Reparaciones'])->name('reportes.reparaciones.index');
    Route::get('/sistema', [ReportesController::class, 'Sistema'])->name('reportes.sistema.index');
});
