<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Solicitud\SolicitudFormulariosController;

// Rutas solicitudes de formularios
// Route::resource('solicitudesformularios', SolicitudFormulariosController::class);

// Prefix para las rutas de formularios solicitudes/
Route::prefix('solicitudes')->group(function () {
    //Definir cada ruta de solicitud de formularios con nombre "solicitudesformularios.{nombre_ruta}"
    //** Listar solicitudes */
    Route::get('/formularios', [SolicitudFormulariosController::class, 'index'])->name('solicitudesformularios.index');

    //** Crear solicitud */
    Route::get('/formularios/create', [SolicitudFormulariosController::class, 'create'])->name('solicitudesformularios.create');

    //** Guardar solicitud */
    Route::post('/formularios', [SolicitudFormulariosController::class, 'store'])->name('solicitudesformularios.store');

    //** Mostrar solicitud */
    Route::get('/formularios/{solicitudesformulario}', [SolicitudFormulariosController::class, 'show'])->name('solicitudesformularios.show');

    //** Editar solicitud */
    Route::get('/formularios/{solicitudesformulario}/edit', [SolicitudFormulariosController::class, 'edit'])->name('solicitudesformularios.edit');

    //** Actualizar solicitud */
    Route::put('/formularios/{solicitudesformulario}', [SolicitudFormulariosController::class, 'update'])->name('solicitudesformularios.update');

    //** Eliminar solicitud */
    Route::delete('/formularios/{solicitudesformulario}', [SolicitudFormulariosController::class, 'destroy'])->name('solicitudesformularios.destroy');
});
