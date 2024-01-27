<?php

namespace App\Http\Controllers\Activos\Bodega;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception; 

use App\Models\Bodega;
use App\Models\Oficina;


class BodegaController extends Controller
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
            // Función que lista bodegas basadas en la OFICINA_ID del usuario
            $bodegas = Bodega::where('OFICINA_ID', $oficinaIdUsuario)->get();

            return view('sia2.activos.modbodegas.index', compact('bodegas'));
        }
        catch (Exception $e)
        {
            // Retornar a la pagina previa con un session error
            return back()->with('error', 'Error cargando las bodegas');
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

            return view('sia2.activos.modbodegas.create', compact('oficinaAsociada'));
        } catch (ModelNotFoundException $e) {
            // Manejar excepción de modelo no encontrado
            return redirect()->route('bodegas.index')->with('error', 'No se encontró la oficina del usuario.');
        } catch (Exception $e) {
            // Manejar otras excepciones
            return redirect()->route('bodegas.index')->with('error', 'Ocurrió un error inesperado.');
        }
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'BODEGA_NOMBRE' => 'required|string|max:40',
                'BODEGA_ESTADO' => 'required|string|max:40',
            ],[
                'BODEGA_NOMBRE.required' => 'El campo "Nombre" es obligatorio.',
                'BODEGA_NOMBRE.string' => 'El campo "Nombre" debe ser una cadena de texto.',
                'BODEGA_NOMBRE.max' => 'El campo "Nombre" no debe exceder los 128 caracteres.',
                'BODEGA_ESTADO.required' => 'El campo "Estado" es obligatorio.',
                'BODEGA_ESTADO.string' => 'El campo "Estado" debe ser una cadena de texto.',
                'BODEGA_ESTADO.max' => 'El campo "Estado" no debe exceder los 40 caracteres.',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            Bodega::create([
                'BODEGA_NOMBRE' => strtoupper($request->input('BODEGA_NOMBRE')),
                'BODEGA_ESTADO' => strtoupper($request->input('BODEGA_ESTADO')),
                'OFICINA_ID' => Auth::user()->OFICINA_ID,
            ]);

            return redirect()->route('bodegas.index')->with('success', 'Bbodega creada con éxito');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Error al crear bodega');
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
            // Obtener bodega por ID
            $bodega = Bodega::find($id);
            // Obtener la OFICINA_ID del usuario actual
            $oficinaIdUsuario = Auth::user()->OFICINA_ID;
            // Obtener la información de la oficina
            $oficinaAsociada = Oficina::where('OFICINA_ID', $oficinaIdUsuario)->firstOrFail();

            return view('sia2.activos.modbodegas.edit', compact('bodega','oficinaAsociada'));
        } catch (ModelNotFoundException $e) {
            // Manejar excepción de modelo no encontrado
            return redirect()->route('bodegas.index')->with('error', 'Ocurrió un error inesperado.');
        } catch (Exception $e) {
            // Manejar otras excepciones
            return redirect()->route('bodegas.index')->with('error', 'Ocurrió un error inesperado.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'BODEGA_NOMBRE' => 'required|string|max:40',
                'BODEGA_ESTADO' => 'required|string|max:40',
            ], [
                'BODEGA_NOMBRE.required' => 'El campo "Nombre" es obligatorio.',
                'BODEGA_NOMBRE.string' => 'El campo "Nombre" debe ser una cadena de texto.',
                'BODEGA_NOMBRE.max' => 'El campo "Nombre" no debe exceder los 128 caracteres.',
                'BODEGA_ESTADO.required' => 'El campo "Estado" es obligatorio.',
                'BODEGA_ESTADO.string' => 'El campo "Estado" debe ser una cadena de texto.',
                'BODEGA_ESTADO.max' => 'El campo "Estado" no debe exceder los 40 caracteres.',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $bodega = Bodega::findOrFail($id);
            $bodega->update([
                'BODEGA_NOMBRE' => strtoupper($request->input('BODEGA_NOMBRE')),
                'BODEGA_ESTADO' => strtoupper($request->input('BODEGA_ESTADO')),
                'OFICINA_ID' => Auth::user()->OFICINA_ID,
            ]);

            return redirect()->route('bodegas.index')->with('success', 'Bodega modificada con éxito');
        } catch (ModelNotFoundException $e) {
            // Manejar excepción de modelo no encontrado
            return redirect()->route('bodegas.index')->with('error', 'No se encontró la bodega.');
        } catch (\Exception $e) {
            // Manejar otras excepciones
            return redirect()->route('bodegas.index')->with('error', 'Ocurrió un error inesperado.');
        }
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            // Puedes implementar lógica para eliminar el recurso de la base de datos
            $bodega = Bodega::findOrFail($id);
            $bodega->delete();

            return redirect()->route('bodegas.index')->with('success', 'Bodega eliminada con éxito');
        } catch (ModelNotFoundException $e) {
            // Manejar excepción de modelo no encontrado
            return redirect()->route('bodegas.index')->with('error', 'Ocurrió un error inesperado al eliminar la bodega.');
        } catch (Exception $e) {
            // Manejar otras excepciones
            return redirect()->route('bodegas.index')->with('error', 'Ocurrió un error inesperado al eliminar la bodega.');
        }   
    }
}