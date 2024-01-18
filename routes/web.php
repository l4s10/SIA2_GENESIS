<?php

use Illuminate\Support\Facades\Route;

// require

// rutas de gestión de activos:
require __DIR__.'/Activos/InventariosRoutes.php';
require __DIR__.'/Activos/MaterialesRoutes.php';
require __DIR__.'/Activos/EquiposRoutes.php';
require __DIR__.'/Activos/SalasOBodegasRoutes.php';
require __DIR__.'/Activos/FormulariosRoutes.php';


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


/*// Ruta menú de inventarios
Route::get('/inventarios', function(){
    return view ('inventarios.index');
})->name('inventarios.index')->middleware('auth');

// Ruta materiales
Route::resource('materiales','App\Http\Controllers\MaterialController');
// Ruta tipos de material
Route::resource('tiposmateriales','App\Http\Controllers\TipoMaterialController');
// Ruta salas o bodegas
Route::resource('salasobodegas','App\Http\Controllers\SalaOBodegaController');*/


