<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Reportes\ReportesController;

Route::prefix('reportes')->group(function () {
    Route::get('/home', [ReportesController::class, 'Home'])->name('reportes.home.index');
    Route::get('/materiales', [ReportesController::class, 'Materiales'])->name('reportes.materiales.index');
});
