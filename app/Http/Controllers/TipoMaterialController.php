<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Exception;
use Illuminate\Support\Str;
use App\Models\TipoMaterial;
use App\Models\Oficina;




class TipoMaterialController extends Controller
{
    public function index()
    {
        // Obtiene la OFICINA_ID del usuario actual
        $oficinaIdUsuario = Auth::user()->OFICINA_ID;

        // Función que lista tipos de materiales basados en la OFICINA_ID del usuario
        $tiposMaterial = TipoMaterial::where('OFICINA_ID', $oficinaIdUsuario)->get();

        return view('tiposmateriales.index', compact('tiposMaterial'));
    }

    public function create()
    {
        try {
            // Obtiene la OFICINA_ID del usuario actual
            $oficinaIdUsuario = Auth::user()->OFICINA_ID;
    
            // Función que hace match entre las oficinas y la oficina del usuario
            $oficina = Oficina::where('OFICINA_ID', $oficinaIdUsuario)->firstOrFail();
            
            return view('tiposmateriales.create', compact('oficina'));
        } catch (ModelNotFoundException $e) {
            // Manejar excepción de modelo no encontrado
            return redirect()->route('error.page')->with('error', 'La oficina del usuario no se encontró.');
        } catch (Exception $e) {
            // Manejar otras excepciones
            return redirect()->route('error.page')->with('error', 'Ocurrió un error inesperado.');
        }
    }

    public function store(Request $request)
    {
        try {
            // Validación de datos
            $request->validate([
                'TIPO_MATERIAL_NOMBRE' => ['required', 'string', 'max:128', 'regex:/^[a-zA-Z\s]+$/'],
                'OFICINA_ID' => 'required'
            ], [ // Mensajes de error 
                'TIPO_MATERIAL_NOMBRE.regex' => 'El campo "Nombre Tipo" sólo debe contener letras y espacios.',
            ]);

            // Transformar a mayúsculas antes de crear el nuevo tipo de material
            $tipoMaterialNombre = Str::upper($request->input('TIPO_MATERIAL_NOMBRE'));
    
            // Crear el nuevo tipo de material
            TipoMaterial::create([
                'TIPO_MATERIAL_NOMBRE' => $tipoMaterialNombre,
                'OFICINA_ID' => $request->input('OFICINA_ID'),
            ]);
    
            return redirect()->route('tiposmateriales.index')->with('success', 'Tipo de material creado exitosamente.');
        } catch (ValidationException $e) {
            // Manejar excepción de validación
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (Exception $e) {
            // Manejar otras excepciones
            return redirect()->route('error.page')->with('error', 'Ocurrió un error inesperado.');
        }
    }

    public function edit(string $id)
    {
        try {
            // Obtener el tipo de material por ID con la relación "oficina"
            $tipoMaterial = TipoMaterial::findOrFail($id);
    
            // Obtener la OFICINA_ID del usuario actual
            $oficinaIdUsuario = Auth::user()->OFICINA_ID;
    
            // Obtener la información de la oficina
            $oficina = Oficina::where('OFICINA_ID', $oficinaIdUsuario)->firstOrFail();
    
            return view('tiposmateriales.edit', compact('tipoMaterial', 'oficina'));
        } catch (ModelNotFoundException $e) {
            // Manejar excepción de modelo no encontrado
            return redirect()->route('error.page')->with('error', 'El tipo de material no se encontró.');
        } catch (Exception $e) {
            // Manejar otras excepciones
            return redirect()->route('error.page')->with('error', 'Ocurrió un error inesperado.');
        }
    }


    public function update(Request $request, $id)
    {
        try {
            // Obtener el tipo de material por ID
            $tipoMaterial = TipoMaterial::findOrFail($id);
    
            // Validación de datos
            $request->validate([
                'TIPO_MATERIAL_NOMBRE' => ['required', 'string', 'max:128', 'regex:/^[a-zA-Z\s]+$/'],
                'OFICINA_ID' => 'required'
            ], [ // Mensajes de error 
                'TIPO_MATERIAL_NOMBRE.regex' => 'El campo "Nombre Tipo" sólo debe contener letras y espacios.',
            ]);
            // Transformar a mayúsculas antes de actualizar el tipo de material
            $tipoMaterialNombre = Str::upper($request->input('TIPO_MATERIAL_NOMBRE'));

            // Actualizar el tipo de material
            $tipoMaterial->update([
                'TIPO_MATERIAL_NOMBRE' => $tipoMaterialNombre,
                // Agrega otros campos según sea necesario
            ]);
    
            return redirect()->route('tiposmateriales.index')->with('success', 'Tipo de material actualizado exitosamente.');
        } catch (ValidationException $e) {
            // Manejar excepción de validación
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (ModelNotFoundException $e) {
            // Manejar excepción de modelo no encontrado
            return redirect()->route('error.page')->with('error', 'El tipo de material no se encontró.');
        } catch (Exception $e) {
            // Manejar otras excepciones
            return redirect()->route('error.page')->with('error', 'Ocurrió un error inesperado.');
        }
    }


    public function show($id)
    {
        // Obtener el tipo de material por ID
        $tipoMaterial = TipoMaterial::findOrFail($id);

        return view('tiposmateriales.show', compact('tipoMaterial'));
    }

    public function destroy($id)
    {
        try {
            // Obtener el tipo de material por ID y eliminarlo
            $tipoMaterial = TipoMaterial::findOrFail($id);
            $tipoMaterial->delete();
    
            return redirect()->route('tiposmateriales.index')->with('success', 'Tipo de material eliminado exitosamente.');
        } catch (ModelNotFoundException $e) {
            // Manejar excepción de modelo no encontrado
            return redirect()->route('error.page')->with('error', 'El tipo de material no se encontró.');
        } catch (Exception $e) {
            // Manejar otras excepciones
            return redirect()->route('error.page')->with('error', 'Ocurrió un error inesperado al eliminar el tipo de material.');
        }
    }
}