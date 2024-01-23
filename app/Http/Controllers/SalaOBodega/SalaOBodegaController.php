<?php

namespace App\Http\Controllers\SalaOBodega;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\Rule;
use Exception; // Manejo de excepciones (falto importar)

use App\Models\Sala_O_Bodega;
use App\Models\Oficina;


class SalaOBodegaController extends Controller
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
            // Función que lista salas y bodegas basadas en la OFICINA_ID del usuario
            $salasobodegas = Sala_O_Bodega::where('OFICINA_ID', $oficinaIdUsuario)->get();
        }
        catch (Exception $e)
        {
            // Retornar a la pagina previa con un session error
            return back()->with('error', 'Error cargando las salas y bodegas');
        }
        return view('sia2.activos.modsalasbodegas.index', compact('salasobodegas'));

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
            $oficina = Oficina::where('OFICINA_ID', $oficinaIdUsuario)->firstOrFail();

        } catch (ModelNotFoundException $e) {
            // Manejar excepción de modelo no encontrado
            return redirect()->route('salasobodegas.index')->with('error', 'No se encontró la oficina del usuario.');
        } catch (Exception $e) {
            // Manejar otras excepciones
            return redirect()->route('salasobodegas.index')->with('error', 'Ocurrió un error inesperado.');
        }
        return view('sia2.activos.modsalasbodegas.create', compact('oficina'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'SALA_O_BODEGA_NOMBRE' => 'required|string|max:128',
                'SALA_O_BODEGA_CAPACIDAD' => 'nullable|integer|min:1|max:200',
                'SALA_O_BODEGA_ESTADO' => 'required|string|max:40',
                'SALA_O_BODEGA_TIPO' => ['required', 'string', 'max:20', Rule::in(['SALA', 'BODEGA'])],
            ],[
                'SALA_O_BODEGA_NOMBRE.required' => 'El campo "Nombre" es obligatorio.',
                'SALA_O_BODEGA_NOMBRE.string' => 'El campo "Nombre" debe ser una cadena de texto.',
                'SALA_O_BODEGA_NOMBRE.max' => 'El campo "Nombre" no debe exceder los 128 caracteres.',
                'SALA_O_BODEGA_CAPACIDAD.integer' => 'La "Capacidad" debe ser un número entero.',
                'SALA_O_BODEGA_CAPACIDAD.min' => 'La "Capacidad" debe ser como mínimo 1.',
                'SALA_O_BODEGA_CAPACIDAD.max' => 'La "Capacidad" no debe exceder los 200.',
                'SALA_O_BODEGA_ESTADO.required' => 'El campo "Estado" es obligatorio.',
                'SALA_O_BODEGA_ESTADO.string' => 'El campo "Estado" debe ser una cadena de texto.',
                'SALA_O_BODEGA_ESTADO.max' => 'El campo "Estado" no debe exceder los 40 caracteres.',
                'SALA_O_BODEGA_TIPO.required' => 'El campo "Tipo" es obligatorio.',
                'SALA_O_BODEGA_TIPO.string' => 'El campo "Tipo" debe ser una cadena de texto.',
                'SALA_O_BODEGA_TIPO.max' => 'El campo "Tipo" no debe exceder los 20 caracteres.',
                'SALA_O_BODEGA_TIPO.in' => 'El campo "Tipo" debe ser "SALA" o "BODEGA".'
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            Sala_O_Bodega::create([
                'SALA_O_BODEGA_NOMBRE' => strtoupper($request->input('SALA_O_BODEGA_NOMBRE')),
                'SALA_O_BODEGA_CAPACIDAD' => $request->input('SALA_O_BODEGA_CAPACIDAD'),
                'SALA_O_BODEGA_ESTADO' => strtoupper($request->input('SALA_O_BODEGA_ESTADO')),
                'SALA_O_BODEGA_TIPO' => $request->input('SALA_O_BODEGA_TIPO'),
                'OFICINA_ID' => Auth::user()->OFICINA_ID,
            ]);

        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Error al crear la sala o bodega: ' . $e->getMessage());
        }
        return redirect()->route('salasobodegas.index')->with('success', 'Sala o bodega creada con éxito');

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
            // Obtener la sala o bodega por ID
            $salaobodega = Sala_O_Bodega::find($id);
            // Obtener la OFICINA_ID del usuario actual
            $oficinaIdUsuario = Auth::user()->OFICINA_ID;
            // Obtener la información de la oficina
            $oficina = Oficina::where('OFICINA_ID', $oficinaIdUsuario)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            // Manejar excepción de modelo no encontrado
            return redirect()->route('salasobodegas.index')->with('error', 'Ocurrió un error inesperado.');
        } catch (Exception $e) {
            // Manejar otras excepciones
            return redirect()->route('salasobodegas.index')->with('error', 'Ocurrió un error inesperado.');
        }
        return view('sia2.activos.modsalasbodegas.edit', compact('salaobodega','oficina'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'SALA_O_BODEGA_NOMBRE' => 'required|string|max:128',
                'SALA_O_BODEGA_CAPACIDAD' => 'nullable|integer|min:1|max:200',
                'SALA_O_BODEGA_ESTADO' => 'required|string|max:40',
                'SALA_O_BODEGA_TIPO' => ['required', 'string', 'max:20', Rule::in(['SALA', 'BODEGA'])],
            ], [
                'SALA_O_BODEGA_NOMBRE.required' => 'El campo "Nombre" es obligatorio.',
                'SALA_O_BODEGA_NOMBRE.string' => 'El campo "Nombre" debe ser una cadena de texto.',
                'SALA_O_BODEGA_NOMBRE.max' => 'El campo "Nombre" no debe exceder los 128 caracteres.',
                'SALA_O_BODEGA_CAPACIDAD.integer' => 'La "Capacidad" debe ser un número entero.',
                'SALA_O_BODEGA_CAPACIDAD.min' => 'La "Capacidad" debe ser como mínimo 1.',
                'SALA_O_BODEGA_CAPACIDAD.max' => 'La "Capacidad" no debe exceder los 200.',
                'SALA_O_BODEGA_ESTADO.required' => 'El campo "Estado" es obligatorio.',
                'SALA_O_BODEGA_ESTADO.string' => 'El campo "Estado" debe ser una cadena de texto.',
                'SALA_O_BODEGA_ESTADO.max' => 'El campo "Estado" no debe exceder los 40 caracteres.',
                'SALA_O_BODEGA_TIPO.required' => 'El campo "Tipo" es obligatorio.',
                'SALA_O_BODEGA_TIPO.string' => 'El campo "Tipo" debe ser una cadena de texto.',
                'SALA_O_BODEGA_TIPO.max' => 'El campo "Tipo" no debe exceder los 20 caracteres.',
                'SALA_O_BODEGA_TIPO.in' => 'El campo "Tipo" debe ser "SALA" o "BODEGA".',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $salaobodega = Sala_O_Bodega::find($id);
            $salaobodega->update([
                'SALA_O_BODEGA_NOMBRE' => strtoupper($request->input('SALA_O_BODEGA_NOMBRE')),
                'SALA_O_BODEGA_CAPACIDAD' => $request->input('SALA_O_BODEGA_CAPACIDAD'),
                'SALA_O_BODEGA_ESTADO' => strtoupper($request->input('SALA_O_BODEGA_ESTADO')),
                'SALA_O_BODEGA_TIPO' => $request->input('SALA_O_BODEGA_TIPO'),
                'OFICINA_ID' => Auth::user()->OFICINA_ID,
            ]);

        } catch (ModelNotFoundException $e) {
            // Manejar excepción de modelo no encontrado
            return redirect()->route('salasobodegas.index')->with('error', 'La sala o bodega no se encontró.');
        } catch (\Exception $e) {
            // Manejar otras excepciones
            return redirect()->route('salasobodegas.index')->with('error', 'Ocurrió un error inesperado.');
        }
        return redirect()->route('salasobodegas.index')->with('success', 'Sala o bodega actualizada con éxito');

    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            // Puedes implementar lógica para eliminar el recurso de la base de datos
            $salaobodega = Sala_O_Bodega::find($id);
            $salaobodega->delete();
        } catch (ModelNotFoundException $e) {
            // Manejar excepción de modelo no encontrado
            return redirect()->route('tiposmateriales.index')->with('error', 'Ocurrió un error inesperado al eliminar la sala o bodega.');
        } catch (Exception $e) {
            // Manejar otras excepciones
            return redirect()->route('tiposmateriales.index')->with('error', 'Ocurrió un error inesperado al eliminar la sala o bodega.');
        }
        return redirect()->route('salasobodegas.index')->with('success', 'Sala o bodega eliminada con éxito');
   
    }
}
