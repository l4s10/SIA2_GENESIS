<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception;

// Importar modelos
use App\Models\Region;

class RegionesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // try-catch para manejar errores
        try {
            // Listar todas las regiones
            $regiones = Region::all();

            // Retornar vista con regiones
            return view('sia2.panel.regiones.index', compact('regiones'));
        } catch (Exception $e) {
            //Retornar vista con mensaje de error a traves de session
            return back()->with('error', 'Error al listar regiones');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // try-catch para manejar errores
        try {
            // Retornar vista con formulario para crear region
            return view('sia2.panel.regiones.create');
        } catch (Exception $e) {
            //Retornar vista con mensaje de error a traves de session
            return back()->with('error', 'Error al mostrar formulario para crear region');
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
            // Buscar region por id
            $region = Region::find($id);

            // Retornar vista con region
            return view('sia2.panel.regiones.show', compact('region'));
        } catch (Exception $e) {
            //Retornar vista con mensaje de error a traves de session
            return back()->with('error', 'Error al mostrar region');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // try-catch para manejar errores
        try {
            // Buscar region por id
            $region = Region::find($id);

            // Retornar vista con formulario para editar region
            return view('sia2.panel.regiones.edit', compact('region'));
        } catch (Exception $e) {
            //Retornar vista con mensaje de error a traves de session
            return back()->with('error', 'Error al mostrar formulario para editar region');
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
