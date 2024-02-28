<?php

namespace App\Http\Controllers\Directivos;

use Illuminate\Support\Facades\Auth;

use App\Models\Cargo;
use App\Models\TipoResolucion;
use App\Models\Facultad;
use App\Models\Resolucion;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class BusquedaAvanzadaController extends Controller
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
    //Obtiene todas las resoluciones delegatorias con sus atributos.
    public function index(Request $request)
    {
        //Atributos para la vista
        $tipos = TipoResolucion::distinct()->get(['TIPO_RESOLUCION_ID', 'TIPO_RESOLUCION_NOMBRE']);
        $facultades = Facultad::all();
        $delegados = Cargo::where('OFICINA_ID', Auth::user()->OFICINA_ID)->get();
        $firmantes = Cargo::where('OFICINA_ID', Auth::user()->OFICINA_ID)->get();
        //$cargoDirector = Cargo::where('CARGO_NOMBRE', 'DIRECTOR');
        // Combinar los resultados en un solo array si se encontró el 'DIRECTOR'

        $leyesAsociadas = Facultad::pluck('FACULTAD_LEY_ASOCIADA')->unique();
        $articulosDeLeyAsociadas = Facultad::pluck('FACULTAD_ART_LEY_ASOCIADA')->unique();
        $fechas = Resolucion::distinct()->get(['RESOLUCION_FECHA']);
        $nros = Resolucion::distinct()->get(['RESOLUCION_NUMERO']);

        //dd($leyesAsociadas);
        // Obtengo '0' resoluciones, entonces, no muestro tabla en la vista
        $resoluciones = [];

        if ($request->has('buscar')) {
            // Llamar a la función buscarResoluciones solo si se presionó el botón "Buscar"
            $resoluciones = $this->buscarResoluciones($request);
        }
    return view('sia2.directivos.directivos.busquedaavanzada.index', compact('tipos', 'facultades', 'delegados', 'firmantes', 'fechas', 'nros', 'resoluciones', 'leyesAsociadas','articulosDeLeyAsociadas'));
    }

    public function buscarResoluciones(Request $request){
        //Request de la vista
        $tiposReq = $request->input('TIPO_RESOLUCION_ID');
        $facultadesReq = $request->input('FACULTAD_ID');
        $delegadosReq = $request->input('DELEGADO_ID');
        $firmantesReq = $request->input('CARGO_ID');
        $fechasReq = $request->input('RESOLUCION_FECHA');
        $nrosReq = $request->input('RESOLUCION_NUMERO');
        $artsReq = $request->input('FACULTAD_ART_LEY_ASOCIADA');
        $leyReq = $request->input('FACULTAD_LEY_ASOCIADA');

        $facultadesArtsReq = Facultad::where('FACULTAD_ART_LEY_ASOCIADA', $artsReq)->get();
        $facultadesLeyReq = Facultad::where('FACULTAD_LEY_ASOCIADA', $leyReq)->get();

       // dd($artsReq,$facultadesArtsReq);
        // Obtiene todos los checkboxes de seleciconados en la vista
        $selectedFilters = $request->input('selectedFilters');

        //dd($request);
        //Valido que dado cualquier selección en estos inputs desencadene la búesqueda de resoluciones en función de sus respectivos checkboxes
        if($tiposReq || $facultadesReq || $delegadosReq || $firmantesReq || $fechasReq || $nrosReq || $leyReq || $artsReq){
            $resoluciones = Resolucion::query();

            if ($tiposReq && isset($selectedFilters['TIPO_RESOLUCION_ID'])) {
                $resoluciones->where('TIPO_RESOLUCION_ID', $tiposReq);
            }
            if ($facultadesReq && isset($selectedFilters['FACULTAD_ID'])) {
                $resoluciones->where('FACULTAD_ID', $facultadesReq);
            }
            if ($delegadosReq && isset($selectedFilters['DELEGADO_ID'])) {
                $resoluciones->whereHas('obedientes', function ($query) use ($delegadosReq) {
                    $query->where('CARGO_ID', $delegadosReq);
                });
            }
            if ($firmantesReq && isset($selectedFilters['CARGO_ID'])) {
                $resoluciones->where('CARGO_ID', $firmantesReq);
            }
            if ($fechasReq && isset($selectedFilters['RESOLUCION_FECHA'])) {
                $resoluciones->where('RESOLUCION_FECHA', $fechasReq);
            }
            if ($nrosReq && isset($selectedFilters['RESOLUCION_NUMERO'])) {
                $resoluciones->where('RESOLUCION_NUMERO', $nrosReq);
            }
            if ($artsReq && isset($selectedFilters['FACULTAD_ART_LEY_ASOCIADA'])) {
                $resoluciones->whereHas('delegacion.facultad', function ($query) use ($facultadesArtsReq) {
                    $query->whereIn('FACULTAD_ID', $facultadesArtsReq->pluck('FACULTAD_ID')->all());
                });
            }

            if ($leyReq && isset($selectedFilters['FACULTAD_LEY_ASOCIADA'])) {
                $resoluciones->whereHas('delegacion.facultad', function ($query) use ($facultadesLeyReq) {
                    $query->whereIn('FACULTAD_ID', $facultadesLeyReq->pluck('FACULTAD_ID')->all());
                });
            }
            
            //dd($resoluciones);
            /*if ($leyReq && isset($selectedFilters['FACULTAD_LEY_ASOCIADA'])) {
                $resoluciones->where('FACULTAD_LEY_ASOCIADA', $leyReq);
            }*/
            //Obtengo colección de resoluciones según parámetros de búsqueda
            $resoluciones = $resoluciones->distinct()->get();
            return $resoluciones;
        }else{
            return $resoluciones = [];
        }
    }
}
