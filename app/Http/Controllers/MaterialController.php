<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

use App\Models\Material;
use App\Models\TipoMaterial;
use App\Models\Movimiento;




class MaterialController extends Controller
{
    public function index()
    {
        // Obtiene la OFICINA_ID del usuario actual
        $oficinaIdUsuario = Auth::user()->OFICINA_ID;

        // Función que lista materiales basados en la OFICINA_ID del usuario
        $materiales = Material::where('OFICINA_ID', $oficinaIdUsuario)->get();

        return view('materiales.index', compact('materiales'));
    }

    public function create()
    {
        // Obtiene la OFICINA_ID del usuario actual
        $oficinaIdUsuario = Auth::user()->OFICINA_ID;

        // Obtiene los tipos de materiales asociados a la oficina 'x' utilizando la relación indirecta del material y la oficina para hacer match con la oficina del usuario
        $tiposMaterial = TipoMaterial::where('OFICINA_ID', $oficinaIdUsuario)->get();

        return view('materiales.create', compact('tiposMaterial'));
    }

    public function store(Request $request)
    {   
        try {
            // Transformar caracteres a caracteres mayúsculas
            $request->merge([
                'MATERIAL_NOMBRE' => strtoupper($request->input('MATERIAL_NOMBRE')),
                'DETALLE_MOVIMIENTO' => strtoupper($request->input('DETALLE_MOVIMIENTO'))
            ]);

            // Validación de datos
            $request->validate([
                'TIPO_MATERIAL_ID' => 'required|exists:tipos_materiales,TIPO_MATERIAL_ID',
                'MATERIAL_NOMBRE' => 'required|string|max:128',
                'MATERIAL_STOCK' => 'required|integer|between:0,1000',
                'DETALLE_MOVIMIENTO' => 'required|string|max:255'
            ]);

            // Crear un nuevo material e instanciar en $material para acceder a sus atributos al realizar el respectivo movimiento
            $material = Material::create([
                'OFICINA_ID' => Auth::user()->OFICINA_ID,
                'TIPO_MATERIAL_ID' => $request->TIPO_MATERIAL_ID,
                'MATERIAL_NOMBRE' => $request->MATERIAL_NOMBRE,
                'MATERIAL_STOCK' => $request->MATERIAL_STOCK,
            ]);

            if ($material) {
                // Crear un nuevo movimiento asociado al material creado
                Movimiento::create([
                    'USUARIO_id' => Auth::user()->id,
                    'MATERIAL_ID' => $material->MATERIAL_ID,
                    'MOVIMIENTO_TITULAR' => Auth::user()->USUARIO_NOMBRES, 
                    'MOVIMIENTO_OBJETO' => 'MAT: '.$material->MATERIAL_NOMBRE,
                    'MOVIMIENTO_TIPO_OBJETO' => $material->tipoMaterial->TIPO_MATERIAL_NOMBRE, 
                    'MOVIMIENTO_TIPO' => 'INGRESO',
                    'MOVIMIENTO_STOCK_PREVIO' => 0, 
                    'MOVIMIENTO_CANTIDAD_A_MODIFICAR' => $material->MATERIAL_STOCK,
                    'MOVIMIENTO_STOCK_RESULTANTE' => $material->MATERIAL_STOCK,
                    'MOVIMIENTO_DETALLE' => $request->DETALLE_MOVIMIENTO,
                ]);

                session()->flash('success', 'El material fue creado exitosamente');
            } else {
                session()->flash('error', 'Error al crear el material');
            }
        } catch (\Exception $e) {
            // Loguea la excepción o maneja de otra manera
            Log::error('Error al crear el material: ' . $e->getMessage());
            session()->flash('error', 'Error al crear el material: ' . $e->getMessage());
        }

        return redirect()->route('materiales.index')->with('success', 'Material creado exitosamente.');
    }

    public function edit(string $id)
    {
        // Obtener el material a editar
        $material = Material::findOrFail($id);
        // Obtiene la OFICINA_ID del usuario actual
        $oficinaIdUsuario = Auth::user()->OFICINA_ID;
        // Otros datos necesarios para la vista de edición
        $tiposMateriales = TipoMaterial::where('OFICINA_ID', $oficinaIdUsuario)->get();

        // Retornar la vista con los datos
        return view('materiales.edit', compact('material', 'tiposMateriales'));
    }


    public function update(Request $request, $id)
    {
        try {
            // Obtener el material a actualizar
            $material = Material::findOrFail($id);

            // Transformar caracteres a caracteres mayúsculas
            $request->merge([
                'MATERIAL_NOMBRE' => strtoupper($request->input('MATERIAL_NOMBRE')),
                'DETALLE_MOVIMIENTO' => strtoupper($request->input('DETALLE_MOVIMIENTO'))
            ]);

            // Validación de datos
            $request->validate([
                'TIPO_MATERIAL_ID' => 'required|exists:tipos_materiales,TIPO_MATERIAL_ID',
                'MATERIAL_NOMBRE' => 'required|string|max:128',
                'MATERIAL_STOCK' => 'required|integer|between:0,1000',
                'DETALLE_MOVIMIENTO' => 'required|string|max:255',
                'TIPO_MOVIMIENTO' => [
                    'required',
                    'string',
                    'max:10',
                    Rule::in(['INGRESO', 'TRASLADO', 'MERMA', 'OTRO']),
                    function ($attribute, $value, $fail) use ($request) {
                        $stockNuevo = $request->input('STOCK_NUEVO');
                        $materialStock = $request->input('MATERIAL_STOCK');

                        if ($value === 'INGRESO' && ($stockNuevo <= 0 || $stockNuevo > 1000)) {
                            $fail('El STOCK_NUEVO debe ser mayor que 0 y menor o igual a 1000 para el tipo de movimiento INGRESO.');
                        } elseif (($value === 'TRASLADO' || $value === 'MERMA') && ($stockNuevo < 0 || $stockNuevo > $materialStock)) {
                            $fail('El STOCK_NUEVO debe ser mayor o igual a 0 y menor o igual al Stock actual del material para los tipos de movimiento TRASLADO y MERMA.');
                        } elseif ($value === 'OTRO' && $stockNuevo !== 0) {
                            $fail('Para el tipo de movimiento OTRO, el STOCK_NUEVO debe ser 0.');
                        }
                    },
                ],
            ]);

            
            // Calcular el stock resultante según el tipo de movimiento
            if ($request->TIPO_MOVIMIENTO == 'INGRESO') {
                $stockResultante = $request->MATERIAL_STOCK + $request->STOCK_NUEVO;
            } elseif (($request->TIPO_MOVIMIENTO == 'TRASLADO') || ($request->TIPO_MOVIMIENTO == 'MERMA')) {
                $stockResultante = $request->MATERIAL_STOCK - $request->STOCK_NUEVO;
            }

            // Actualizar los atributos del material
            $material->update([
                'TIPO_MATERIAL_ID' => $request->TIPO_MATERIAL_ID,
                'MATERIAL_NOMBRE' => $request->MATERIAL_NOMBRE,
                'MATERIAL_STOCK' => $stockResultante,
            ]);

            // Crear un nuevo movimiento asociado al material creado
            Movimiento::create([
                'USUARIO_id' => Auth::user()->id,
                'MATERIAL_ID' => $material->MATERIAL_ID,
                'MOVIMIENTO_TITULAR' => Auth::user()->USUARIO_NOMBRES,
                'MOVIMIENTO_OBJETO' => 'MAT: ' . $material->MATERIAL_NOMBRE,
                'MOVIMIENTO_TIPO_OBJETO' => $material->tipoMaterial->TIPO_MATERIAL_NOMBRE,
                'MOVIMIENTO_TIPO' => $request->TIPO_MOVIMIENTO,
                'MOVIMIENTO_STOCK_PREVIO' => $request->MATERIAL_STOCK,
                'MOVIMIENTO_CANTIDAD_A_MODIFICAR' => $request->STOCK_NUEVO,
                'MOVIMIENTO_STOCK_RESULTANTE' => $stockResultante,
                'MOVIMIENTO_DETALLE' => $request->DETALLE_MOVIMIENTO,
            ]);

            return redirect()->route('materiales.index')->with('success', 'Material actualizado exitosamente.');
        } catch (ModelNotFoundException $e) {
            return redirect()->route('materiales.index')->with('error', 'No se encontró el material con el ID proporcionado.');
        } catch (\Exception $e) {
            return redirect()->route('materiales.index')->with('error', 'Error al actualizar el material: ' . $e->getMessage());
        }
    }

    
    public function destroy(string $id)
    {
        // Encuentra el material por su ID y elimínalo
        $material = Material::find($id);

        // Verifica si el material existe antes de intentar eliminarlo
        if ($material) {
            // Elimina el material
            $material->delete();

            // También puedes eliminar el movimiento asociado si lo deseas
            // $material->movimiento->delete();

            return redirect()->route('materiales.index')->with('success', 'Material eliminado exitosamente.');
        } else {
            // Si el material no existe, redirecciona con un mensaje de error
            return redirect()->route('materiales.index')->with('error', 'No se encontró el material.');
        }
    }

}
