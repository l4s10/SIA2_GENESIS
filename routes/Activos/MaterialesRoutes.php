<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Activos\Material\TipoMaterialController;
use App\Http\Controllers\Activos\Material\MaterialController;

Route::prefix('materiales')->group(function () {
    Route::get('/', [MaterialController::class, 'index'])
        ->name('materiales.index')
        ->middleware('can:ver_activos');
    Route::get('/create', [MaterialController::class, 'create'])
        ->name('materiales.create')
        ->middleware('can:crear_activo');
    Route::post('/', [MaterialController::class, 'store'])
        ->name('materiales.store')
        ->middleware('can:crear_activo');
    Route::get('/{material}', [MaterialController::class, 'show'])
        ->name('materiales.show')
        ->middleware('can:ver_activos');
    Route::get('/{material}/edit', [MaterialController::class, 'edit'])
        ->name('materiales.edit')
        ->middleware('can:editar_activo');
    Route::put('/{material}', [MaterialController::class, 'update'])
        ->name('materiales.update')
        ->middleware('can:actualizar_activo');
    Route::delete('/{material}', [MaterialController::class, 'destroy'])
        ->name('materiales.destroy')
        ->middleware('can:eliminar_activo');
});

Route::prefix('tiposmateriales')->group(function () {
    Route::get('/', [TipoMaterialController::class, 'index'])
        ->name('tiposmateriales.index')
        ->middleware('can:ver_activos');
    Route::get('/create', [TipoMaterialController::class, 'create'])
        ->name('tiposmateriales.create')
        ->middleware('can:crear_activo');
    Route::post('/', [TipoMaterialController::class, 'store'])
        ->name('tiposmateriales.store')
        ->middleware('can:crear_activo');
    Route::get('/{tipomaterial}', [TipoMaterialController::class, 'show'])
        ->name('tiposmateriales.show')
        ->middleware('can:ver_activos');
    Route::get('/{tipomaterial}/edit', [TipoMaterialController::class, 'edit'])
        ->name('tiposmateriales.edit')
        ->middleware('can:editar_activo');
    Route::put('/{tipomaterial}', [TipoMaterialController::class, 'update'])
        ->name('tiposmateriales.update')
        ->middleware('can:actualizar_activo');
    Route::delete('/{tipomaterial}', [TipoMaterialController::class, 'destroy'])
        ->name('tiposmateriales.destroy')
        ->middleware('can:eliminar_activo');
});

// Ruta para exportar a excel
Route::get('materiales/exportables/excel', [MaterialController::class, 'exportExcel'])->name('exportar-materiales-excel');

// Ruta para exportar a PDF
Route::get('materiales/exportables/pdf', [MaterialController::class, 'exportPdf'])->name('exportar-materiales-pdf');

// Ruta para exportar auditoria a PDF
Route::get('materiales/exportables/pdf/auditoria', [MaterialController::class, 'exportAuditoriaPdf'])->name('exportar-material-auditoria-pdf');
