<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Solicitud\SolicitudFormulariosController;

// Rutas solicitudes de formularios
// Route::resource('solicitudesformularios', SolicitudFormulariosController::class);

// Prefix para las rutas de formularios solicitudes/
Route::prefix('solicitudes')->group(function () {
    //Definir cada ruta de solicitud de formularios con nombre "solicitudesformularios.{nombre_ruta}"
    //** Listar solicitudes */
    Route::get('/formularios', [SolicitudFormulariosController::class, 'index'])->name('solicitudes.formularios.index');

    //** Crear solicitud */
    Route::get('/formularios/create', [SolicitudFormulariosController::class, 'create'])->name('solicitudes.formularios.create');

    //** Guardar solicitud */
    Route::post('/formularios', [SolicitudFormulariosController::class, 'store'])->name('solicitudes.formularios.store');

    //** Mostrar solicitud */
    Route::get('/formularios/{solicitudesformulario}', [SolicitudFormulariosController::class, 'show'])->name('solicitudes.formularios.show');

    //** Editar solicitud */
    Route::get('/formularios/{solicitudesformulario}/edit', [SolicitudFormulariosController::class, 'edit'])->name('solicitudes.formularios.edit');

    //** Actualizar solicitud */
    Route::put('/formularios/{solicitudesformulario}', [SolicitudFormulariosController::class, 'update'])->name('solicitudes.formularios.update');

    //** Eliminar solicitud */
    Route::delete('/formularios/{solicitudesformulario}', [SolicitudFormulariosController::class, 'destroy'])->name('solicitudes.formularios.destroy');
});
