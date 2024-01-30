<?php

namespace App\Http\Controllers\Solicitud;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Exception;

// Importar modelos
use App\Models\SolicitudReparacion;
use App\Models\CategoriaReparacion;

class SolicitudReparacionesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
            // Cargar las solicitudes de reparaciones de la misma dirección del usuario logueado, haciendo 'match' con el USUARIO_id de la solicitud
            //!!TESTEAR QUERY.
            $solicitudes = SolicitudReparacion::whereHas('user', function($query){
                $query->where('OFICINA_ID', Auth::user()->OFICINA_ID);
            })->get();

            // Retornar la vista con las solicitudes
            return view('sia2.solicitudes.reparacionesmantenciones.index', compact('solicitudes'));
        }
        catch(Exception $e)
        {
            return redirect()->back()->with('error', 'Error al cargar las solicitudes.');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try{
            // Cargar las categorias.
            $categorias = CategoriaReparacion::all();

            // Retornar la vista del formulario con las categorias
            return view('sia2.solicitudes.reparacionesmantenciones.create', compact('categorias'));
        }
        catch(Exception $e)
        {
            return redirect()->back()->with('error', 'Error al cargar las categorias.');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
