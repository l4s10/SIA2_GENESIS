<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;

// Importar modelos
use App\Models\User; //Importamos user pero en este contexto lo llamamos Usuario.

class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //try-catch para manejar errores
        try {
            // Listar todos los usuarios que esten dentro de la misma direccion regional que el usuario logueado.
            $usuarios = User::where('OFICINA_ID', Auth::user()->OFICINA_ID)->get();

            // Retornar vista con usuarios
            return view('sia2.panel.usuarios.index', compact('usuarios'));
        } catch (Exception $e) {
            //Retornar vista con mensaje de error a traves de session
            return back()->with('error', 'Error al listar usuarios');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //try-catch para manejar errores
        try {
            // Retornar vista con formulario para crear usuario
            return view('sia2.panel.usuarios.create');
        } catch (Exception $e) {
            //Retornar vista con mensaje de error a traves de session
            return back()->with('error', 'Error al mostrar formulario para crear usuario');
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
            // Buscar usuario por id que este en la misma direccion regional
            $usuario = Usuario::where('OFICINA_ID', Auth::user()->OFICINA_ID)->find($id);

            // Retornar vista con usuario
            return view('sia2.panel.usuarios.show', compact('usuario'));
        } catch (Exception $e) {
            //Retornar vista con mensaje de error a traves de session
            return back()->with('error', 'Error al mostrar usuario');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // try-catch para manejar errores
        try {
            // Buscar usuario por id que este en la misma direccion regional
            $usuario = Usuario::where('OFICINA_ID', Auth::user()->OFICINA_ID)->find($id);

            // Retornar vista con formulario para editar usuario
            return view('sia2.panel.usuarios.edit', compact('usuario'));
        } catch (Exception $e) {
            //Retornar vista con mensaje de error a traves de session
            return back()->with('error', 'Error al mostrar formulario para editar usuario');
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
