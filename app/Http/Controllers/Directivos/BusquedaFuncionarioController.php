<?php

namespace App\Http\Controllers\Directivos;

use Illuminate\Support\Facades\Auth;
use App\Models\Cargo;
use App\Models\Resolucion;
use App\Models\User;
use App\Http\Controllers\Controller;


use Illuminate\Http\Request;

class BusquedaFuncionarioController extends Controller
{
    //Funcion para acceder a las rutas SOLO SI los usuarios estan logueados
    /*public function __construct(){
        $this->middleware('auth');
        // Roles que pueden ingresar a la url
        $this->middleware(function ($request, $next) {
            $user = Auth::user();

            if ($user->hasRole('ADMINISTRADOR') || $user->hasRole('JURIDICO') || $user->hasRole('INFORMATICA') || $user->hasRole('SERVICIOS')) {
                return $next($request);
            } else {
                abort(403, 'Acceso no autorizado');
            }
        });
    }*/
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Obtener la oficina del usuario autenticado
        $direccionRegionalAutenticada = Auth::user()->OFICINA_ID;
    
        // Obtener los parámetros de búsqueda del request
        $nombres = $request->input('NOMBRES');
        $apellidos = $request->input('APELLIDOS');
        $rut = $request->input('RUT');
        $idCargoFuncionario = $request->input('CARGO_ID');
        $idCargo = $request->input('OBEDIENTE_ID');
    
        // Inicializar variables
        $resoluciones = [];
        $cargoFuncionario = null; 
        $cargoResolucion = null;
        $rutRes = null;
        $busquedaResolucionCargo = null;
        $busquedaResolucionCargoFallida = null;
        $busquedaResolucionFuncionario = null;
        $busquedaResolucionFuncionarioFallida = null;
    
        // Verificar que los parámetros de búsqueda no estén vacíos
        if (!empty($nombres) && !empty($apellidos) && !empty($rut) && !empty($idCargoFuncionario)) {
            // Buscar al usuario que cumpla con los criterios
            $user = User::where([
                ['OFICINA_ID', $direccionRegionalAutenticada],
                ['USUARIO_NOMBRES', $nombres],
                ['USUARIO_APELLIDOS', $apellidos],
                ['USUARIO_RUT', $rut],
                ['CARGO_ID', $idCargoFuncionario]
            ])->first();        
    
            // Consultar las resoluciones asociadas al cargo del usuario
            if ($user) {
                // Consulta para obtener las resoluciones
                $resoluciones = Resolucion::whereHas('obedientes', function ($query) use ($idCargoFuncionario, $direccionRegionalAutenticada) {
                    $query->where('CARGO_ID', $idCargoFuncionario)
                        ->whereHas('cargo', function ($query) use ($direccionRegionalAutenticada) {
                            $query->where('OFICINA_ID', $direccionRegionalAutenticada);
                        });
                })->get();
                $cargoFuncionario = $user->cargo->CARGO_NOMBRE;
                $rutRes = $user->USUARIO_RUT;
                //dd($resoluciones);
                if ($resoluciones->isNotEmpty()) {
                    $busquedaResolucionFuncionario = true;
                } else {
                    $busquedaResolucionFuncionarioFallida = true;
                }
            }

            //dd($user,$resoluciones);
        } elseif ($idCargo) {
            // Consulta para obtener las resoluciones asociadas al cargo en la misma OFICINA_ID
            $resoluciones = Resolucion::with('tipoResolucion', 'firmante', 'obedientes.cargo', 'delegacion')
                ->whereHas('obedientes', function ($query) use ($idCargo, $direccionRegionalAutenticada) {
                    $query->where('CARGO_ID', $idCargo)
                        ->whereHas('cargo', function ($query) use ($direccionRegionalAutenticada) {
                            $query->where('OFICINA_ID', $direccionRegionalAutenticada);
                        });
                })
                ->get();
    
            
            if ($resoluciones->isNotEmpty()) {

                
                // Itera sobre cada resolución para obtener los nombres de los cargos
                foreach ($resoluciones as $resolucion) {
                    // Obtiene el nombre del primer cargo de la primera obedecencia
                    $cargoResolucion = $resolucion->obedientes->first()->cargo->CARGO_NOMBRE;
                    break;
                }

                // Ahora tienes los nombres de los cargos en $cargosResolucion
                $busquedaResolucionCargo = true;
            } else {
                $aux = Cargo::where('CARGO_ID', $idCargo)->first();
                $cargoResolucion = $aux->CARGO_NOMBRE;
                $busquedaResolucionCargoFallida = true;
            }
            //dd($cargoResolucion);
        }
    
        // Obtener cargos asociados a la dirección regional del usuario autenticado
        $exclusionCargos = ['FUNCIONARIO', 'EXTERNO'];
        // Obtener los cargos de la misma oficina del usuario autenticado
        $cargosOficina = Cargo::where('OFICINA_ID', Auth::user()->OFICINA_ID)
        ->whereNotIn('CARGO_NOMBRE', $exclusionCargos)
        ->get();
        // Obtener el cargo 'DIRECTOR' independientemente de la oficina
        $cargoDirector = Cargo::where('CARGO_NOMBRE', 'DIRECTOR')->first();
        // Combinar los resultados en un solo array si se encontró el 'DIRECTOR'
        $cargos = $cargosOficina->push($cargoDirector);
       

    
        return view('sia2.directivos.directivos.busquedafuncionario.index', compact('resoluciones', 'cargos', 'nombres', 'apellidos', 'cargoFuncionario', 'rutRes', 'cargoResolucion', 'busquedaResolucionCargo', 'busquedaResolucionFuncionario', 'busquedaResolucionCargoFallida', 'busquedaResolucionFuncionarioFallida'));
    }
    

    public function buscarFuncionarios(Request $request)
    {
        $direccionRegionalAutenticada = Auth::user()->OFICINA_ID; // dirección regional sesión autenticada

        // Obtener los valores de nombres, apellidos, rut e idCargo desde la solicitud AJAX
        $nombres = strtolower($request->input('nombres'));
        $apellidos = strtolower($request->input('apellidos'));
        $rut = strtolower($request->input('rut'));
        $idCargoFuncionario = $request->input('idCargoFuncionario');

        // Realizar la búsqueda de funcionarios según los nombres, apellidos, rut y cargo registrados
        $funcionarios = User::query();

        if (!empty($nombres)) {
            $funcionarios->where('USUARIO_NOMBRES', 'LIKE', strtolower($nombres) . '%');
        }

        if (!empty($apellidos)) {
            $funcionarios->where('USUARIO_APELLIDOS', 'LIKE', strtolower($apellidos) . '%');
        }

        if (!empty($rut)) {
            $funcionarios->where('USUARIO_RUT', 'LIKE', strtolower($rut) . '%');
        }

        // Cargos a excluir de la búsqueda: ['FUNCIONARIO', 'EXTERNO'];

        // Función callback para aplicar condición de filtro al obtener colección de funcionarios
        $funcionarios = $funcionarios->whereHas('cargo', function ($query) {
            $query->whereNotIn('CARGO_NOMBRE', ['FUNCIONARIO', 'EXTERNO']);
        })->whereHas('cargo.oficina', function ($query) use ($direccionRegionalAutenticada) {
            $query->where('OFICINA_ID', $direccionRegionalAutenticada);
        });

        if (!empty($idCargoFuncionario)) {
            $funcionarios->where('CARGO_ID', $idCargoFuncionario);
        }

        //Obtención de colección de posibles funcionarios
        $funcionarios = $funcionarios->get();

        // Validación para controlar el mensaje de error de búsqueda de resoluciones
        // Luego cargamos la nueva variable "busquedaAjax"
        session()->put('busquedaAjax', true);
        // Retorna los resultados de búsqueda en formato JSON

        return response()->json($funcionarios);
    }


}
