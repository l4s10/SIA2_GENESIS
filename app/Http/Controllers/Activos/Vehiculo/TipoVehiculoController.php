<?php

namespace App\Http\Controllers\Activos\Vehiculo;


use App\Models\Oficina;
use App\Models\TipoVehiculo;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;
use Illuminate\Database\QueryException;

class TipoVehiculoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // try-catch para el manejo de errores
        try
        {
            // Código para listar los tipos de vehículos
            // Obtener la OFICINA_ID del usuario
            $oficinaIdUsuario = Auth::user()->OFICINA_ID;

            // Función que lista los tipos de vehículos basados en la OFICINA_ID del usuario
            $tiposVehiculos = TipoVehiculo::where('OFICINA_ID', $oficinaIdUsuario)->get();

            // Retornar la vista con los tipos de vehículos
            return view('sia2.activos.modvehiculos.tiposvehiculos.index', compact('tiposVehiculos'));
        }
        catch (\Exception $e)
        {
            // Retornar a la pagina previa con un session error
            return back()->with('error', 'Error cargando los tipos de vehículos');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try{
            //Obtenemos la oficina del usuario actual
            $oficinaIdUsuario = Auth::user()->OFICINA_ID;
            //Obtenemos el objeto oficina asociada al usuario actual
            $oficina = Oficina::where('OFICINA_ID', $oficinaIdUsuario)->firstOrFail();

            return view('sia2.activos.modvehiculos.tiposvehiculos.create', compact('oficina'));
        }catch(ModelNotFoundException $e){
            //Manejar excepción de modelo no encontrado
            return redirect()->route('tiposvehiculos.index')->with('error', 'No se encontró la oficina del usuario.');
        }catch(Exception $e){
            //Manejar otras excepciones
            return redirect()->route('tiposvehiculos.index')->with('error', 'Ocurrió un error inesperado.');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try{
            //Validamos los datos
            $validator = Validator::make($request->all(), [
                'TIPO_VEHICULO_NOMBRE' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s]+$/'],
                'TIPO_VEHICULO_CAPACIDAD' => 'required|numeric|min:1'
            ],[
                'TIPO_VEHICULO_NOMBRE.required' => 'El nombre del tipo de vehículo es requerido.',
                'TIPO_VEHICULO_NOMBRE.string' => 'El nombre del tipo de vehículo debe ser una cadena de texto.',
                'TIPO_VEHICULO_NOMBRE.max' => 'El nombre del tipo de vehículo no debe exceder los 255 caracteres.',
                'TIPO_VEHICULO_CAPACIDAD.required' => 'La capacidad del tipo de vehículo es requerida.',
                'TIPO_VEHICULO_CAPACIDAD.numeric' => 'La capacidad del tipo de vehículo debe ser un número.',
                'TIPO_VEHICULO_CAPACIDAD.min' => 'La capacidad del tipo de vehículo debe ser mayor a 0.'
            ]);

            //Validar clave única compuesta
            $validator->after(function($validator) use ($request){
                $exists = TipoVehiculo::where([
                    'TIPO_VEHICULO_NOMBRE' => strtoupper($request->input('TIPO_VEHICULO_NOMBRE'))
                ])->exists();

                if($exists){
                    $validator->errors()->add('TIPO_VEHICULO_NOMBRE', 'El nombre del tipo de vehículo ya existe en la oficina seleccionada.');
                }
            });

            //Validar y redirigir en caso de error
            if($validator->fails()){
                return redirect()->back()->withErrors($validator)->withInput();
            }

            //Crear el nuevo tipo de vehículo
            TipoVehiculo::create([
                'TIPO_VEHICULO_NOMBRE' => strtoupper($request->input('TIPO_VEHICULO_NOMBRE')),
                'TIPO_VEHICULO_CAPACIDAD' => $request->input('TIPO_VEHICULO_CAPACIDAD'),
                'OFICINA_ID' => Auth::user()->OFICINA_ID
            ]);

            //Redirigir a la lista de tipos de vehículos con un mensaje de éxito
            return redirect()->route('tiposvehiculos.index')->with('success', 'Tipo de vehículo creado exitosamente.');
        }catch(Exception $e){
            //Manejar otras excepciones
            return redirect()->route('tiposvehiculos.index')->with('error', 'Ocurrió un error inesperado.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try{
            // Obtener el tipo de vehículo por ID
            $tipoVehiculo = TipoVehiculo::findOrFail($id);

            return view('sia2.activos.modvehiculos.tiposvehiculos.show', compact('tipoVehiculo'));
        }catch(Exception $e){
            //Manejar excepción de modelo no encontrado
            return redirect()->route('tiposvehiculos.index')->with('error', 'Error al cargar el tipo de vehículo.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try{
            //Obtener el tipo de vehículo por ID
            $tipoVehiculo = TipoVehiculo::findOrFail($id);
            //Obtener la OFICINA_ID del usuario actual
            $oficinaIdUsuario = Auth::user()->OFICINA_ID;
            //Obtener la información de la oficina del usuario
            $oficina = Oficina::where('OFICINA_ID', $oficinaIdUsuario)->firstOrFail();

            return view('sia2.activos.modvehiculos.tiposvehiculos.edit', compact('tipoVehiculo', 'oficina'));
        }catch(ModelNotFoundException $e){
            //Manejar excepción de modelo no encontrado
            return redirect()->route('tiposvehiculos.index')->with('error', 'Ocurrió un error inesperado.');
        }catch(Exception $e){
            //Manejar otras excepciones
            return redirect()->route('tiposvehiculos.index')->with('error', 'Ocurrió un error inesperado.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try{
            //Obtener el tipo de vehículo por ID
            $tipoVehiculo = TipoVehiculo::findOrFail($id);

            //Construir el validador
            $validator = Validator::make($request->all(), [
                'TIPO_VEHICULO_NOMBRE' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s]+$/'],
                'TIPO_VEHICULO_CAPACIDAD' => 'required|numeric|min:1'
            ],[
                'TIPO_VEHICULO_NOMBRE.required' => 'El nombre del tipo de vehículo es requerido.',
                'TIPO_VEHICULO_NOMBRE.string' => 'El nombre del tipo de vehículo debe ser una cadena de texto.',
                'TIPO_VEHICULO_NOMBRE.max' => 'El nombre del tipo de vehículo no debe exceder los 255 caracteres.',
                'TIPO_VEHICULO_CAPACIDAD.required' => 'La capacidad del tipo de vehículo es requerida.',
                'TIPO_VEHICULO_CAPACIDAD.numeric' => 'La capacidad del tipo de vehículo debe ser un número.',
                'TIPO_VEHICULO_CAPACIDAD.min' => 'La capacidad del tipo de vehículo debe ser mayor a 0.'
            ]);

            //Validar clave única compuesta
            $validator->after(function($validator) use ($request, $id){
                $exists = TipoVehiculo::where([
                    'OFICINA_ID' => Auth::user()->OFICINA_ID,
                    'TIPO_VEHICULO_NOMBRE' => strtoupper($request->input('TIPO_VEHICULO_NOMBRE'))
                ])->where('TIPO_VEHICULO_ID', '!=', $id)->exists();

                if($exists){
                    $validator->errors()->add('TIPO_VEHICULO_NOMBRE', 'El nombre del tipo de vehículo ya existe en la oficina seleccionada.');
                }
            });

            //Validar y redirigir en caso de error
            if($validator->fails()){
                return redirect()->back()->withErrors($validator)->withInput();
            }

            //Actualizar el tipo de tipo de vehículo
            $tipoVehiculo->update([
                'TIPO_VEHICULO_NOMBRE' => strtoupper($request->input('TIPO_VEHICULO_NOMBRE')),
                'TIPO_VEHICULO_CAPACIDAD' => $request->input('TIPO_VEHICULO_CAPACIDAD'),
                'OFICINA_ID' => Auth::user()->OFICINA_ID
            ]);

            //Redirigir a la lista de tipos de vehículos con un mensaje de éxito
            return redirect()->route('tiposvehiculos.index')->with('success', 'Tipo de vehículo actualizado exitosamente.');
        }catch(Exception $e){
            //Manejar otras excepciones
            return redirect()->route('tiposvehiculos.index')->with('error', 'Ocurrió un error inesperado.');
        }catch(Exception $e){
            //Manejar excepción de modelo no encontrado
            return redirect()->route('tiposvehiculos.index')->with('error', 'Ocurrió un error inesperado.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try{
            //Obtener el tipo de vehículo por ID
            $tipoVehiculo = TipoVehiculo::findOrFail($id);

            //Eliminar el tipo de vehículo
            $tipoVehiculo->delete();

            //Redirigir a la lista de tipos de vehículos con un mensaje de éxito
            return redirect()->route('tiposvehiculos.index')->with('success', 'Tipo de vehículo eliminado exitosamente.');
        }catch(ModelNotFoundException $e){
            //Manejar excepción de modelo no encontrado
            return redirect()->route('tiposvehiculos.index')->with('error', 'Ocurrió un error inesperado.');
        }catch(QueryException $e){
            //Manejar excepción de consulta
            return redirect()->route('tiposvehiculos.index')->with('error', 'No se puede eliminar el tipo de vehículo porque tiene vehículos asociados.');
        }catch(Exception $e){
            //Manejar otras excepciones
            return redirect()->route('tiposvehiculos.index')->with('error', 'Ocurrió un error inesperado.');
        }
    }
}
