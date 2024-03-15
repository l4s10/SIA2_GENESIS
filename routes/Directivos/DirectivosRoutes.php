<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Directivos\BusquedaAvanzadaController;
use App\Http\Controllers\Directivos\BusquedaFuncionarioController;


// Prefix para las rutas de cargos/

Route::prefix('directivos')->group(function () {

    //** Pagina de búsqueda por Resolucion */
    Route::get('/busqueda-avanzada', [BusquedaAvanzadaController::class, 'index'])
        ->name('directivos.indexBusquedaBasica')
        ->middleware('can:buscar_resoluciones');

    Route::get('/busqueda-avanzada/{resolucion}', [BusquedaAvanzadaController::class, 'buscarResoluciones'])
        ->name('directivos.buscarResoluciones')
        ->middleware('can:buscar_resoluciones');

    //** Pagina de búsqueda por Funcionario */
    // Página de búsqueda por Funcionario
    Route::get('/busqueda-funcionario', [BusquedaFuncionarioController::class, 'index'])
        ->name('directivos.indexBusquedaFuncionarios')
        ->middleware('can:buscar_resoluciones');
});

    // Ruta para buscar funcionarios mediante AJAX
    Route::get('/consultaAjax', [BusquedaFuncionarioController::class, 'buscarFuncionarios'])
        ->middleware('can:buscar_resoluciones'); //!! Revisa si no te da conflicto al ser una consulta AJAX (desconozco), si te da problema borra esta línea y dejala como antes @Rick1701 ~Kotch
