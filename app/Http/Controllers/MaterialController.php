<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

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
            //Log::error('Error al crear el material: ' . $e->getMessage());
            session()->flash('error', 'Error al crear el material: ' . $e->getMessage());
        }

        return redirect()->route('materiales.index')->with('success', 'Material creado exitosamente.');
    }

    public function edit(string $id)
    {
        // Obtener el material a editar
        $material = Material::findOrFail($id);
        // Obtiener la OFICINA_ID del usuario actual
        $oficinaIdUsuario = Auth::user()->OFICINA_ID;
        // Obtener tipos de materiales según oficina_id del usuario en sesión
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
            $validator = Validator::make($request->all(), [
                'TIPO_MATERIAL_ID' => 'required|exists:tipos_materiales,TIPO_MATERIAL_ID',
                'MATERIAL_NOMBRE' => 'required|string|max:128',
                'MATERIAL_STOCK' => 'required|integer',
                'STOCK_NUEVO' => [
                    'required',
                    'integer',
                    'between:0,1100',
                    // Validación dinámica para "STOCK_NUEVO" en función de la selección del input "TIPO_MOVMIENTO"
                    function ($attribute, $value, $fail) use ($request) {
                        $stockNuevo = $request->input('STOCK_NUEVO');
                        $materialStock = $request->input('MATERIAL_STOCK');
                        $tipoMovimiento = $request->input('TIPO_MOVIMIENTO');

                        if ($tipoMovimiento === 'INGRESO' && ($stockNuevo <= 0 || $stockNuevo > 1000)) {
                            $fail('Para "Tipo de movimiento" INGRESO, la "Cantidad a modificar" debe estar entre 1 y 1000');
                        } elseif (($tipoMovimiento === 'TRASLADO' || $tipoMovimiento === 'MERMA') && ($stockNuevo <= 0 || $stockNuevo > $materialStock)) {
                            $fail('Para "Tipo de movimiento" TRASLADO o MERMA, la "Cantidad a modificar" debe estar entre 1 y '.$materialStock);
                        } elseif ($tipoMovimiento === 'OTRO' && ($stockNuevo > 0 || $stockNuevo < 0)) {
                            $fail('Para "Tipo de movimiento" OTRO, la "Cantidad a modificar" debe ser 0');
                        }
                    },
                ],
                'DETALLE_MOVIMIENTO' => 'required|string|max:255',
                'TIPO_MOVIMIENTO' => 'required|string|max:10',
            ]);

            if ($validator->fails()) {
                // Si la validación falla, devuélvete a la vista de edición con los errores
                return redirect()->route('materiales.edit', $material->MATERIAL_ID)->withErrors($validator)->withInput();
            }

            // Calcular el stock resultante según el tipo de movimiento
            if (($request->TIPO_MOVIMIENTO == 'INGRESO') || ($request->TIPO_MOVIMIENTO == 'OTRO')) {
                $stockResultante = $request->MATERIAL_STOCK + $request->STOCK_NUEVO;
            } else {
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
        // Encontrar el material por su ID 
        $material = Material::find($id);

        // Verificar si el material existe antes de intentar eliminarlo
        if ($material) {
            // Eliminar el material
            $material->delete();

            return redirect()->route('materiales.index')->with('success', 'Material eliminado exitosamente.');
        } else {
            // Si el material no existe, redireccionar con un mensaje de error
            return redirect()->route('materiales.index')->with('error', 'No se encontró el material.');
        }
    }

}
