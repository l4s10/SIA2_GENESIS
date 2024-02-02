<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception;

// Importar modelos
use App\Models\Oficina;

class OficinasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // try-catch para manejar errores
        try {
            // Listar todas las oficinas
            $oficinas = Oficina::all();

            // Retornar vista con oficinas
            return view('sia2.panel.oficinas.index', compact('oficinas'));
        } catch (Exception $e) {
            //Retornar vista con mensaje de error a traves de session
            return back()->with('error', 'Error al listar oficinas');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // try-catch para manejar errores
        try {
            // Retornar vista con formulario para crear oficina
            return view('sia2.panel.oficinas.create');
        } catch (Exception $e) {
            //Retornar vista con mensaje de error a traves de session
            return back()->with('error', 'Error al mostrar formulario para crear oficina');
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
            // Buscar oficina por id
            $oficina = Oficina::find($id);

            // Retornar vista con oficina
            return view('sia2.panel.oficinas.show', compact('oficina'));
        } catch (Exception $e) {
            //Retornar vista con mensaje de error a traves de session
            return back()->with('error', 'Error al mostrar oficina');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // try-catch para manejar errores
        try {
            // Buscar oficina por id
            $oficina = Oficina::find($id);

            // Retornar vista con formulario para editar oficina
            return view('sia2.panel.oficinas.edit', compact('oficina'));
        } catch (Exception $e) {
            //Retornar vista con mensaje de error a traves de session
            return back()->with('error', 'Error al mostrar formulario para editar oficina');
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
