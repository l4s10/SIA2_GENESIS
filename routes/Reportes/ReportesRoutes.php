<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Reportes\ReportesController;

Route::get('reportes/home',[ReportesController::class, 'Home'])->name('reportes.home.index');

Route::get('reportes/materiales', [ReportesController::class, 'Materiales'])->name('reportes.materiales.index');
