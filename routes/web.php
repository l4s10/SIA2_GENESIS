<?php

use Illuminate\Support\Facades\Route;

//Proteccion de rutas de aplicacion (Se requiere autentificacion)
Route::group(['middleware' => 'auth'], function (){
    // rutas de gestión de activos:
    require __DIR__.'/Activos/InventariosRoutes.php';
    require __DIR__.'/Activos/MaterialesRoutes.php';
    require __DIR__.'/Activos/EquiposRoutes.php';
    require __DIR__.'/Activos/SalasRoutes.php';
    require __DIR__.'/Activos/BodegasRoutes.php';
    require __DIR__.'/Activos/FormulariosRoutes.php';
    require __DIR__.'/Activos/VehiculosRoutes.php';

    // rutas para solicitudes
    require __DIR__.'/Solicitudes/SolMaterialesRoutes.php';
    require __DIR__.'/Solicitudes/SolFormulariosRoutes.php';
    require __DIR__.'/Solicitudes/SolEquiposRoutes.php';
    require __DIR__.'/Solicitudes/SolVehiculosRoutes.php';
    require __DIR__.'/Solicitudes/SolSalasRoutes.php';
    require __DIR__.'/Solicitudes/SolBodegasRoutes.php';
    require __DIR__.'/Solicitudes/SolReparacionesRoutes.php';

    // rutas para movimientos
    require __DIR__.'/Movimientos/MovimientosRoutes.php';

    // rutas para carrito
    require __DIR__.'/CarritoRoutes/CarritoRoutes.php';

    // rutas para reportes
    require __DIR__.'/Reportes/ReportesRoutes.php';

    // rutas para directivos
    require __DIR__.'/Directivos/DirectivosRoutes.php';
    require __DIR__.'/Directivos/FacultadesRoutes.php';
    require __DIR__.'/Directivos/ResolucionesRoutes.php';
    require __DIR__.'/Directivos/PolizasRoutes.php';
    require __DIR__.'/Directivos/CargosRoutes.php';



    // rutas para panel de control
    require __DIR__.'/Panel/RegionesRoutes.php';
    require __DIR__.'/Panel/ComunasRoutes.php';
    require __DIR__.'/Panel/DepartamentosRoutes.php';
    require __DIR__.'/Panel/OficinasRoutes.php';
    require __DIR__.'/Panel/UbicacionesRoutes.php';
    require __DIR__.'/Panel/UsuariosRoutes.php';
});
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
    return view('auth.login');
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


