<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;

// Importar modelos
use App\Models\Departamento;


class DepartamentosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // try-catch para manejar errores
        try {
            // Listar departamentos de la misma direccion regional que el usuario logueado
            $departamentos = Departamento::where('OFICINA_ID', Auth::user()->OFICINA_ID)->get();

            // Retornar vista con departamentos
            return view('sia2.panel.departamentos.index', compact('departamentos'));
        } catch (Exception $e) {
            //Retornar vista con mensaje de error a traves de session
            return back()->with('error', 'Error al listar departamentos');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // try-catch para manejar errores
        try {
            // Retornar vista con formulario para crear departamento
            return view('sia2.panel.departamentos.create');
        } catch (Exception $e) {
            //Retornar vista con mensaje de error a traves de session
            return back()->with('error', 'Error al mostrar formulario para crear departamento');
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
        //try-catch para manejar errores
        try {
            // Buscar departamento por id
            $departamento = Departamento::find($id);

            // Retornar vista con departamento
            return view('sia2.panel.departamentos.show', compact('departamento'));
        } catch (Exception $e) {
            //Retornar vista con mensaje de error a traves de session
            return back()->with('error', 'Error al mostrar departamento');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //try-catch para manejar errores
        try {
            // Buscar departamento por id
            $departamento = Departamento::find($id);

            // Retornar vista con formulario para editar departamento
            return view('sia2.panel.departamentos.edit', compact('departamento'));
        } catch (Exception $e) {
            //Retornar vista con mensaje de error a traves de session
            return back()->with('error', 'Error al mostrar formulario para editar departamento');
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

    //!! Método para obtener departamentos por oficina (TABLA DE CONTINGENCIA -- NO BORRAR!!)
    public function getDepartamentos($direccionId)
    {
        // Asume que tienes un modelo Ubicacion que tiene una relación con Direcciones
        $departamentos = Departamento::where('OFICINA_ID', $direccionId)->get();

        return response()->json($departamentos);
    }
}
