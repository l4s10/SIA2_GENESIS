<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Auth;

// Importar modelos
use App\Models\Ubicacion;

class UbicacionesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // try-catch para manejar errores
        try {
            // Listar todas las ubicaciones
            $ubicaciones = Ubicacion::where('OFICINA_ID', Auth::user()->OFICINA_ID)->get();

            // Retornar vista con ubicaciones
            return view('sia2.panel.ubicaciones.index', compact('ubicaciones'));
        } catch (Exception $e) {
            //Retornar vista con mensaje de error a traves de session
            return back()->with('error', 'Error al listar ubicaciones');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // try-catch para manejar errores
        try {
            // Retornar vista con formulario para crear ubicacion
            return view('sia2.panel.ubicaciones.create');
        } catch (Exception $e) {
            //Retornar vista con mensaje de error a traves de session
            return back()->with('error', 'Error al mostrar formulario para crear ubicacion');
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
        // try-catch para manejar errores
        try {
            // Buscar ubicacion por id que este en la misma direccion regional que el usuario logueado
            $ubicacion = Ubicacion::where('OFICINA_ID', Auth::user()->OFICINA_ID)->find($id);

            // Retornar vista con ubicacion
            return view('sia2.panel.ubicaciones.show', compact('ubicacion'));
        } catch (Exception $e) {
            //Retornar vista con mensaje de error a traves de session
            return back()->with('error', 'Error al mostrar ubicacion');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // try-catch para manejar errores
        try {
            // Buscar ubicacion por id que este en la misma direccion regional que el usuario logueado
            $ubicacion = Ubicacion::where('OFICINA_ID', Auth::user()->OFICINA_ID)->find($id);

            // Retornar vista con formulario para editar ubicacion
            return view('sia2.panel.ubicaciones.edit', compact('ubicacion'));
        } catch (Exception $e) {
            //Retornar vista con mensaje de error a traves de session
            return back()->with('error', 'Error al mostrar formulario para editar ubicacion');
        }
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
