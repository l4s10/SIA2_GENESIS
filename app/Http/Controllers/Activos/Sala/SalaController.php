<?php

namespace App\Http\Controllers\Activos\Sala;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

use App\Models\Sala;
use App\Models\Oficina;


class SalaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Código para el manejo de errores y retorno de vistas
        try
        {
            // Obtener la OFICINA_ID del usuario actual
            $oficinaIdUsuario = Auth::user()->OFICINA_ID;
            // Función que lista salas  basadas en la OFICINA_ID del usuario
            $salas = Sala::where('OFICINA_ID', $oficinaIdUsuario)->get();

            return view('sia2.activos.modsalas.index', compact('salas'));
        }
        catch (Exception $e)
        {
            // Retornar a la pagina previa con un session error
            return back()->with('error', 'Error cargando las salas');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            // Obtener la OFICINA_ID del usuario actual
            $oficinaIdUsuario = Auth::user()->OFICINA_ID;

            // Función que hace match entre las oficinas y la oficina del usuario
            $oficinaAsociada = Oficina::where('OFICINA_ID', $oficinaIdUsuario)->firstOrFail();

            return view('sia2.activos.modsalas.create', compact('oficinaAsociada'));
        } catch (ModelNotFoundException $e) {
            // Manejar excepción de modelo no encontrado
            return redirect()->route('salas.index')->with('error', 'No se encontró la oficina del usuario.');
        } catch (Exception $e) {
            // Manejar otras excepciones
            return redirect()->route('salas.index')->with('error', 'Ocurrió un error inesperado.');
        }
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'SALA_NOMBRE' => 'required|string|max:40',
                'SALA_CAPACIDAD' => 'nullable|integer|min:1|max:200',
                'SALA_ESTADO' => 'required|string|max:40',
            ],[
                'SALA_NOMBRE.required' => 'El campo "Nombre" es obligatorio.',
                'SALA_NOMBRE.string' => 'El campo "Nombre" debe ser una cadena de texto.',
                'SALA_NOMBRE.max' => 'El campo "Nombre" no debe exceder los 128 caracteres.',
                'SALA_CAPACIDAD.integer' => 'La "Capacidad" debe ser un número entero.',
                'SALA_CAPACIDAD.min' => 'La "Capacidad" debe ser como mínimo 1.',
                'SALA_CAPACIDAD.max' => 'La "Capacidad" no debe exceder los 200.',
                'SALA_ESTADO.required' => 'El campo "Estado" es obligatorio.',
                'SALA_ESTADO.string' => 'El campo "Estado" debe ser una cadena de texto.',
                'SALA_ESTADO.max' => 'El campo "Estado" no debe exceder los 40 caracteres.',
            ]);


            // Validar clave única compuesta
            $validator->after(function ($validator) use ($request) {
                $exists = Sala::where([
                    'OFICINA_ID' => Auth::user()->OFICINA_ID,
                    'SALA_NOMBRE' => strtoupper($request->input('SALA_NOMBRE'))
                ])->exists();

                if ($exists) {
                    $validator->errors()->add('SALA_NOMBRE', 'El nombre de la sala ya existe en su dirección regional.');
                }
            });


            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            Sala::create([
                'SALA_NOMBRE' => strtoupper($request->input('SALA_NOMBRE')),
                'SALA_CAPACIDAD' => $request->input('SALA_CAPACIDAD'),
                'SALA_ESTADO' => strtoupper($request->input('SALA_ESTADO')),
                'OFICINA_ID' => Auth::user()->OFICINA_ID,
            ]);

            return redirect()->route('salas.index')->with('success', 'Sala creada con éxito');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Error al crear la sala');
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
        try {
            // Obtener la sala por ID
            $sala = Sala::findOrFail($id);
            // Obtener la OFICINA_ID del usuario actual
            $oficinaIdUsuario = Auth::user()->OFICINA_ID;
            // Obtener la información de la oficina
            $oficinaAsociada = Oficina::where('OFICINA_ID', $oficinaIdUsuario)->firstOrFail();

            return view('sia2.activos.modsalas.edit', compact('sala','oficinaAsociada'));
        } catch (ModelNotFoundException $e) {
            // Manejar excepción de modelo no encontrado
            return redirect()->route('salas.index')->with('error', 'Ocurrió un error inesperado.');
        } catch (Exception $e) {
            // Manejar otras excepciones
            return redirect()->route('salas.index')->with('error', 'Ocurrió un error inesperado.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'SALA_NOMBRE' => 'required|string|max:40',
                'SALA_CAPACIDAD' => 'nullable|integer|min:1|max:200',
                'SALA_ESTADO' => 'required|string|max:40',
            ], [
                'SALA_NOMBRE.required' => 'El campo "Nombre" es obligatorio.',
                'SALA_NOMBRE.string' => 'El campo "Nombre" debe ser una cadena de texto.',
                'SALA_NOMBRE.max' => 'El campo "Nombre" no debe exceder los 128 caracteres.',
                'SALA_CAPACIDAD.integer' => 'La "Capacidad" debe ser un número entero.',
                'SALA_CAPACIDAD.min' => 'La "Capacidad" debe ser como mínimo 1.',
                'SALA_CAPACIDAD.max' => 'La "Capacidad" no debe exceder los 200.',
                'SALA_ESTADO.required' => 'El campo "Estado" es obligatorio.',
                'SALA_ESTADO.string' => 'El campo "Estado" debe ser una cadena de texto.',
                'SALA_ESTADO.max' => 'El campo "Estado" no debe exceder los 40 caracteres.',
            ]);

            // Validar clave única compuesta
            $validator->after(function ($validator) use ($request, $id) {
                $exists = Sala::where([
                    'OFICINA_ID' => Auth::user()->OFICINA_ID,
                    'SALA_NOMBRE' => strtoupper($request->input('SALA_NOMBRE'))
                ])->where('SALA_ID', '!=', $id)->exists();

                if ($exists) {
                    $validator->errors()->add('SALA_NOMBRE', 'El nombre de la sala ya existe en su dirección regional.');
                }
            });


            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $sala = Sala::findOrFail($id);
            $sala->update([
                'SALA_NOMBRE' => strtoupper($request->input('SALA_NOMBRE')),
                'SALA_CAPACIDAD' => $request->input('SALA_CAPACIDAD'),
                'SALA_ESTADO' => strtoupper($request->input('SALA_ESTADO')),
                'OFICINA_ID' => Auth::user()->OFICINA_ID,
            ]);

            return redirect()->route('salas.index')->with('success', 'Sala modificada con éxito');
        } catch (ModelNotFoundException $e) {
            // Manejar excepción de modelo no encontrado
            return redirect()->route('salas.index')->with('error', 'La sala no se encontró.');
        } catch (\Exception $e) {
            // Manejar otras excepciones
            return redirect()->route('salas.index')->with('error', 'Ocurrió un error inesperado.');
        }
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $sala = Sala::findOrFail($id);
            $sala->delete();
            return redirect()->route('salas.index')->with('success', 'Sala eliminada con éxito');
        } catch (ModelNotFoundException $e) {
            // Manejar excepción de modelo no encontrado
            return redirect()->route('salas.index')->with('error', 'Ocurrió un error inesperado al eliminar la sala.');
        } catch (Exception $e) {
            // Manejar otras excepciones
            return redirect()->route('salas.index')->with('error', 'Ocurrió un error inesperado al eliminar la sala.');
        }   
    }
}