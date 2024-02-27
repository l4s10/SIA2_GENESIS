<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Directivos\BusquedaAvanzadaController;
use App\Http\Controllers\Directivos\BusquedaFuncionarioController;


// Prefix para las rutas de cargos/

Route::prefix('directivos')->group(function () {
    
    //** Pagina de búsqueda por Resolucion */
    Route::get('/busqueda-avanzada', [BusquedaAvanzadaController::class, 'index'])
        ->name('directivos.indexBusquedaBasica');
    
    Route::get('/busqueda-avanzada/{resolucion}', [BusquedaAvanzadaController::class, 'buscarResoluciones'])
        ->name('directivos.buscarResoluciones');

    //** Pagina de búsqueda por Funcionario */
    // Página de búsqueda por Funcionario
    Route::get('/busqueda-funcionario', [BusquedaFuncionarioController::class, 'index'])
    ->name('directivos.indexBusquedaFuncionarios');

    // Ruta para buscar funcionarios mediante AJAX
    Route::post('/busqueda-funcionario/buscar', [BusquedaFuncionarioController::class, 'buscarFuncionarios'])
     ->name('directivos.buscarFuncionarios');



});
