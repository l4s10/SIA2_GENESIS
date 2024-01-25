<?php

namespace App\Http\Controllers\Activos\Material;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

use App\Models\TipoMaterial;
use App\Models\Oficina;




class TipoMaterialController extends Controller
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

            // Función que lista tipos de materiales basados en la OFICINA_ID del usuario
            $tiposMaterial = TipoMaterial::where('OFICINA_ID', $oficinaIdUsuario)->get();
        }
        catch (\Exception $e)
        {
            // Retornar a la pagina previa con un session error
            return back()->with('error', 'Error cargando los tipos de equipo');
        }
        return view('sia2.activos.modmateriales.tiposmateriales.index', compact('tiposMaterial'));
    }

    public function create()
    {
        try {
            // Obtener la OFICINA_ID del usuario actual
            $oficinaIdUsuario = Auth::user()->OFICINA_ID;
            // Obtener el objeto oficina asociada al usuario actual
            $oficina = Oficina::where('OFICINA_ID', $oficinaIdUsuario)->firstOrFail();

        } catch (ModelNotFoundException $e) {
            // Manejar excepción de modelo no encontrado
            return redirect()->route('tiposmateriales.index')->with('error', 'No se encontró la oficina del usuario.');
        } catch (Exception $e) {
            // Manejar otras excepciones
            return redirect()->route('tiposmateriales.index')->with('error', 'Ocurrió un error inesperado.');
        }
        return view('sia2.activos.modmateriales.tiposmateriales.create', compact('oficina'));
    }

    public function store(Request $request)
    {
        dd($request);
        try {

            // Validación de datos
            $validator = Validator::make($request->all(), [
                'TIPO_MATERIAL_NOMBRE' => ['required', 'string', 'max:128', 'regex:/^[a-zA-Z\s]+$/'],
            ], [
                'TIPO_MATERIAL_NOMBRE.required' => 'El campo "Nombre Tipo" es obligatorio.',
                'TIPO_MATERIAL_NOMBRE.string' => 'El campo "Nombre Tipo" debe ser una cadena de texto.',
                'TIPO_MATERIAL_NOMBRE.max' => 'El campo "Nombre Tipo" no debe exceder los :max caracteres.',
                'TIPO_MATERIAL_NOMBRE.regex' => 'El campo "Nombre Tipo" sólo debe contener letras y espacios.',
            ]);

            dd($request);
            // Validar y redirigir si falla
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            // Crear el nuevo tipo de material
            TipoMaterial::create([
                'TIPO_MATERIAL_NOMBRE' => strtoupper($request->input('TIPO_MATERIAL_NOMBRE')),
                'OFICINA_ID' => $request->input('OFICINA_ID'),
            ]);

        } catch (Exception $e) {
            // Manejar otras excepciones
            return redirect()->route('tiposmateriales.index')->with('error', 'Ocurrió un error inesperado.');
        }

        return redirect()->route('tiposmateriales.index')->with('success', 'Tipo de material creado exitosamente.');
    }


    public function edit(string $id)
    {
        try {
            // Obtener el tipo de material por ID
            $tipoMaterial = TipoMaterial::findOrFail($id);
            // Obtener la OFICINA_ID del usuario actual
            $oficinaIdUsuario = Auth::user()->OFICINA_ID;
            // Obtener la información de la oficina del usuario
            $oficina = Oficina::where('OFICINA_ID', $oficinaIdUsuario)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            // Manejar excepción de modelo no encontrado
            return redirect()->route('tiposmateriales.index')->with('error', 'Ocurrió un error inesperado.');
        } catch (Exception $e) {
            // Manejar otras excepciones
            return redirect()->route('tiposmateriales.index')->with('error', 'Ocurrió un error inesperado.');
        }

        return view('sia2.activos.modmateriales.tiposmateriales.edit', compact('tipoMaterial', 'oficina'));
    }


    public function update(Request $request, $id)
    {
        try {
            // Obtener el tipo de material por ID
            $tipoMaterial = TipoMaterial::findOrFail($id);



            // Construir el validador
            $validator = Validator::make($request->all(), [
                'TIPO_MATERIAL_NOMBRE' => ['required', 'string', 'max:128', 'regex:/^[a-zA-Z\s]+$/'],
            ], [
                // Mensajes de error
                'TIPO_MATERIAL_NOMBRE.required' => 'El campo "Nombre Tipo" es obligatorio.',
                'TIPO_MATERIAL_NOMBRE.string' => 'El campo "Nombre Tipo" debe ser una cadena de texto.',
                'TIPO_MATERIAL_NOMBRE.max' => 'El campo "Nombre Tipo" no debe exceder los :max caracteres.',
                'TIPO_MATERIAL_NOMBRE.regex' => 'El campo "Nombre Tipo" sólo debe contener letras y espacios.',
            ]);

            // Validar y redirigir si falla
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            // Actualizar el tipo de material
            $tipoMaterial->update([
                'TIPO_MATERIAL_NOMBRE' => strtoupper($request->input('TIPO_MATERIAL_NOMBRE')),
                'OFICINA_ID' => Auth::user()->OFICINA_ID
            ]);

        } catch (ModelNotFoundException $e) {
            // Manejar excepción de modelo no encontrado
            return redirect()->route('tiposmateriales.index')->with('error', 'Ocurrió un error inesperado.');
        } catch (Exception $e) {
            // Manejar otras excepciones
            return redirect()->route('tiposmateriales.index')->with('error', 'Ocurrió un error inesperado.');
        }

        return redirect()->route('tiposmateriales.index')->with('success', 'Tipo de material actualizado exitosamente.');

    }


    public function show($id)
    {
        try
        {
            // Obtener el tipo de material por ID
            $tipoMaterial = TipoMaterial::findOrFail($id);
        }
        catch(Exception $e)
        {
            return redirect()->route('tiposequipos.index')->with('error', 'Error al cargar el tipo de equipo');
        }
        return view('tiposmateriales.show', compact('tipoMaterial'));

    }

    public function destroy($id)
    {
        try {
            // Obtener el tipo de material por ID y eliminarlo
            $tipoMaterial = TipoMaterial::findOrFail($id);
            $tipoMaterial->delete();
        } catch (ModelNotFoundException $e) {
            // Manejar excepción de modelo no encontrado
            return redirect()->route('tiposmateriales.index')->with('error', 'El tipo de material no se encontró.');
        } catch (Exception $e) {
            // Manejar otras excepciones
            return redirect()->route('tiposmateriales.index')->with('error', 'Ocurrió un error inesperado al eliminar el tipo de material.');
        }
        return redirect()->route('tiposmateriales.index')->with('success', 'Tipo de material eliminado exitosamente.');

    }
}
