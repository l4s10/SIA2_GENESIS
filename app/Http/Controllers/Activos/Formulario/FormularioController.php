<?php

namespace App\Http\Controllers\Activos\Formulario;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Support\Facades\Validator;

use App\Models\Formulario;

class FormularioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Manejo de excepciones
        try {
            // Obtenemos todos los formularios correspondientes a la oficina del usuario (Optimizacion de Query).
            $formularios = Formulario::where('OFICINA_ID', Auth::user()->OFICINA_ID)->get();
            // Retornamos la vista con los datos
            return view('sia2.activos.formularios.index', compact('formularios'));
        } catch (Exception $ex) {
            // Retornamos la vista con el mensaje de error (concatenar mensaje con $ex para obtener detalles DEBUG)
            return redirect()->back()->with('error', 'Ha ocurrido un error al cargar los formularios');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Manejo de excepciones
        try {
            // Retornamos la vista
            return view('sia2.activos.modformularios.create');
        } catch (Exception $ex) {
            // Retornamos la vista con el mensaje de error (concatenar mensaje con $ex para obtener detalles DEBUG)
            return redirect()->back()->with('error', 'Ha ocurrido un error al cargar el formulario');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // manejo de excepciones
        try
        {
            // Reglas de validación
            $validator = Validator::make($request->all(), [
                'FORMULARIO_NOMBRE' => 'required|string|max:128',
                'FORMULARIO_TIPO' => 'required|string|max:128',
            ],[
                'required' => 'El campo :attribute es requerido',
                'string' => 'El campo :attribute debe ser un texto',
                'max' => 'El campo :attribute no debe exceder los :max caracteres',
            ]);

            // Validacion y redireccion con mensajes de error
            if ($validator->fails())
            {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            else
            {
                // Creamos el formulario
                Formulario::create([
                    'FORMULARIO_NOMBRE' => $request->FORMULARIO_NOMBRE,
                    'FORMULARIO_TIPO' => $request->FORMULARIO_TIPO,
                    'OFICINA_ID' => Auth::user()->OFICINA_ID,
                ]);
                // Retornamos la vista con el mensaje de exito
                return redirect()->back()->with('success', 'Formulario creado exitosamente');
            }
        }
        catch(Exception $ex)
        {
            // Retornamos la vista con el mensaje de error (concatenar mensaje con $ex para obtener detalles DEBUG)
            return redirect()->back()->with('error', 'Ha ocurrido un error al crear el formulario');
        }
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
