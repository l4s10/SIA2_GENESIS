<?php

namespace App\Http\Controllers\Activos\Vehiculo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;
use Illuminate\Database\QueryException;

use App\Models\Vehiculo;
use App\Models\TipoVehiculo;
use App\Models\Ubicacion;
use App\Models\Departamento;
use App\Models\Oficina;




class VehiculoController extends Controller
{
    public function index()
    {
        try {
            // Obtiene la OFICINA_ID del usuario actual
            $oficinaIdUsuario = Auth::user()->OFICINA_ID;

            // Consulta SQL para obtener vehículos asociados a la oficina del usuario
            $vehiculos = Vehiculo::select('VEHICULOS.*')
                ->leftJoin('UBICACIONES', 'VEHICULOS.UBICACION_ID', '=', 'UBICACIONES.UBICACION_ID')
                ->leftJoin('DEPARTAMENTOS', 'VEHICULOS.DEPARTAMENTO_ID', '=', 'DEPARTAMENTOS.DEPARTAMENTO_ID')
                ->where(function($query) use ($oficinaIdUsuario) {
                    $query->where('UBICACIONES.OFICINA_ID', $oficinaIdUsuario)
                        ->whereNull('VEHICULOS.DEPARTAMENTO_ID');
                })
                ->orWhere(function($query) use ($oficinaIdUsuario) {
                    $query->where('DEPARTAMENTOS.OFICINA_ID', $oficinaIdUsuario)
                        ->whereNull('VEHICULOS.UBICACION_ID');
                })
                ->get();

            // Cargar en base a OFICINA_ID del usuario
            $tiposVehiculos = TipoVehiculo::where('OFICINA_ID', Auth::user()->OFICINA_ID)->get();
            $ubicaciones = Ubicacion::where('OFICINA_ID', Auth::user()->OFICINA_ID)->get();
            $departamentos = Departamento::where('OFICINA_ID', Auth::user()->OFICINA_ID)->get();

            return view('sia2.activos.modvehiculos.index', compact('vehiculos', 'tiposVehiculos', 'ubicaciones', 'departamentos'));
        } catch (Exception $e) {
            // Retornar a la pagina previa con un session error
            return back()->with('error', 'Error cargando los vehículos');
        }
    }

    public function getFilteredData(Request $request)
    {
        $query = Vehiculo::query();

        if ($request->filled('VEHICULO_PATENTE')) {
            $query->where('VEHICULO_PATENTE', 'like', '%' . $request->VEHICULO_PATENTE . '%');
        }

        if ($request->filled('VEHICULO_MARCA')) {
            $query->where('VEHICULO_MARCA', $request->VEHICULO_MARCA);
        }

        if ($request->filled('VEHICULO_MODELO')) {
            $query->where('VEHICULO_MODELO', $request->VEHICULO_MODELO);
        }

        if ($request->filled('VEHICULO_ANO')) {
            $query->where('VEHICULO_ANO', $request->VEHICULO_ANO);
        }

        if ($request->filled('VEHICULO_ESTADO')) {
            $query->where('VEHICULO_ESTADO', $request->VEHICULO_ESTADO);
        }

        if ($request->filled('VEHICULO_KILOMETRAJE')) {
            $query->where('VEHICULO_KILOMETRAJE', '>=', $request->VEHICULO_KILOMETRAJE);
        }

        if ($request->filled('VEHICULO_NIVEL_ESTANQUE')) {
            $query->where('VEHICULO_NIVEL_ESTANQUE', $request->VEHICULO_NIVEL_ESTANQUE);
        }

        if ($request->filled('TIPO_VEHICULO_ID')) {
            $query->where('TIPO_VEHICULO_ID', $request->TIPO_VEHICULO_ID);
        }

        // Filtro inclusivo para UBICACION_ID y DEPARTAMENTO_ID
        if ($request->filled('UBICACION_ID') && $request->filled('DEPARTAMENTO_ID')) {
            $query->where(function ($query) use ($request) {
                $query->where('UBICACION_ID', $request->UBICACION_ID)
                    ->orWhere('DEPARTAMENTO_ID', $request->DEPARTAMENTO_ID);
            });
        } else {
            if ($request->filled('UBICACION_ID')) {
                $query->where('UBICACION_ID', $request->UBICACION_ID);
            }

            if ($request->filled('DEPARTAMENTO_ID')) {
                $query->where('DEPARTAMENTO_ID', $request->DEPARTAMENTO_ID);
            }
        }

        $vehiculos = $query->get();

        // Cargar en base a OFICINA_ID del usuario
        $tiposVehiculos = TipoVehiculo::where('OFICINA_ID', Auth::user()->OFICINA_ID)->get();
        $ubicaciones = Ubicacion::where('OFICINA_ID', Auth::user()->OFICINA_ID)->get();
        $departamentos = Departamento::where('OFICINA_ID', Auth::user()->OFICINA_ID)->get();

        return view('sia2.activos.modvehiculos.index', compact('vehiculos', 'tiposVehiculos', 'ubicaciones', 'departamentos'));
    }

    public function create()
    {
        try {
            // Obtiene la OFICINA_ID del usuario actual
            $oficinaIdUsuario = Auth::user()->OFICINA_ID;
            // Obtener la entidad oficina asociada al usuario
            $oficinaAsociada = Oficina::findOrFail($oficinaIdUsuario);
            // Obtener ubicaciones locales
            $ubicacionesLocales = Ubicacion::where('OFICINA_ID', $oficinaIdUsuario)->get();
            // Obtener departamentos locales
            $departamentosLocales = Departamento::where('OFICINA_ID', $oficinaIdUsuario)->get();
            // Obtener tipos de vehículos locales
            $tiposVehiculos = TipoVehiculo::where('OFICINA_ID', $oficinaIdUsuario)->get();

            return view('sia2.activos.modvehiculos.create', compact('ubicacionesLocales','departamentosLocales','tiposVehiculos','oficinaAsociada'));
        } catch (ModelNotFoundException $e) {
            // Manejar excepción de modelo no encontrado
            return redirect()->route('vehiculos.index')->with('error', 'Ocurrió un error inesperado.');
        } catch (Exception $e) {
            // Manejar otras excepciones
            return redirect()->route('vehiculos.index')->with('error', 'Ocurrió un error inesperado.');
        }
    }

    public function store(Request $request)
    {

        try {

            // Reglas de validación y mensajes respectivos
            $validator = Validator::make($request->all(), [
                'VEHICULO_PATENTE' => 'required|string|max:7|unique:vehiculos,VEHICULO_PATENTE|regex:/^[A-Z0-9]{4}-[A-Z0-9]{2}$/i',
                'TIPO_VEHICULO_ID' => 'required|exists:tipos_vehiculos,TIPO_VEHICULO_ID',
                'VEHICULO_MARCA' => 'required|string|max:20',
                'VEHICULO_MODELO' => 'required|string|max:20',
                'VEHICULO_ANO' => 'required|integer|min:2000|max:' . date('Y'),
                'DEPENDENCIA_ID' => 'required',
                'VEHICULO_ESTADO' => 'required|string|in:DISPONIBLE,OCUPADO',
                'VEHICULO_KILOMETRAJE' => 'required|integer|min:0|max:400000',
                'VEHICULO_NIVEL_ESTANQUE' => 'required|string|max:128',
            ],  [
                'VEHICULO_PATENTE.required' => 'El campo Patente es obligatorio.',
                'VEHICULO_PATENTE.string' => 'El campo Patente debe ser una cadena de texto.',
                'VEHICULO_PATENTE.max' => 'El campo Patente no debe exceder los :max caracteres.',
                'VEHICULO_PATENTE.unique' => 'La patente ingresada ya existe.',
                'VEHICULO_PATENTE.regex' => 'El campo Patente debe tener el formato XXXX-XX, donde X es una letra o un número.',
                'TIPO_VEHICULO_ID.required' => 'El campo Tipo de Vehículo es obligatorio.',
                'TIPO_VEHICULO_ID.exists' => 'El Tipo de Vehículo seleccionado no es válido.',
                'VEHICULO_MARCA.required' => 'El campo Marca es obligatorio.',
                'VEHICULO_MARCA.string' => 'El campo Marca debe ser una cadena de texto.',
                'VEHICULO_MARCA.max' => 'El campo Marca no debe exceder los :max caracteres.',
                'VEHICULO_MODELO.required' => 'El campo Modelo es obligatorio.',
                'VEHICULO_MODELO.string' => 'El campo Modelo debe ser una cadena de texto.',
                'VEHICULO_MODELO.max' => 'El campo Modelo no debe exceder los :max caracteres.',
                'VEHICULO_ANO.required' => 'El campo Año es obligatorio.',
                'VEHICULO_ANO.integer' => 'El campo Año debe ser un número entero.',
                'VEHICULO_ANO.min' => 'El campo Año debe ser mayor o igual a 2000.',
                'VEHICULO_ANO.max' => 'El campo Año no debe ser mayor al año actual.',
                'DEPENDENCIA_ID.required' => 'El campo Ubicación/Departamento es obligatorio.',
                'VEHICULO_ESTADO.required' => 'El campo Estado es obligatorio.',
                'VEHICULO_ESTADO.string' => 'El campo Estado debe ser una cadena de texto.',
                'VEHICULO_ESTADO.in' => 'El campo Estado debe ser uno de: DISPONIBLE, OCUPADO.',
                'VEHICULO_KILOMETRAJE.required' => 'El campo Kilometraje es obligatorio.',
                'VEHICULO_KILOMETRAJE.integer' => 'El campo Kilometraje debe ser un número entero.',
                'VEHICULO_KILOMETRAJE.min' => 'El Kilometraje no puede ser negativo.',
                'VEHICULO_KILOMETRAJE.max' => 'El Kilometraje no puede exceder 400.000 kilómetros.',
                'VEHICULO_NIVEL_ESTANQUE.required' => 'El campo Nivel Estanque es obligatorio.',
                'VEHICULO_NIVEL_ESTANQUE.string' => 'El campo Nivel Estanque debe ser una cadena de texto.',
                'VEHICULO_NIVEL_ESTANQUE.max' => 'El campo Nivel Estanque no debe exceder los :max caracteres.',
            ]);

            $validator->after(function ($validator) use ($request) {
                $exists1 = Vehiculo::where([
                    'TIPO_VEHICULO_ID' => $request->input('TIPO_VEHICULO_ID'),
                    'VEHICULO_PATENTE' => $request->input('VEHICULO_PATENTE'),
                    'UBICACION_ID' => $request->input('DEPENDENCIA_ID'),
                    'VEHICULO_MARCA' => $request->input('VEHICULO_MARCA'),
                    'VEHICULO_MODELO' => $request->input('VEHICULO_MODELO'),
                ])->exists();

                if ($exists1) {
                    $validator->errors()->add('TIPO_VEHICULO_ID', 'Este tipo ya existe para la patente, ubicación, marca y modelo del vehículo seleccionados.');
                    $validator->errors()->add('VEHICULO_PATENTE', 'Esta patente ya existe para el tipo, ubicación, marca y modelo del vehículo seleccionados.');
                    $validator->errors()->add('DEPENDENCIA_ID', 'Esta ubicación ya está asociada para el tipo, patente, marca y modelo del vehículo seleccionados.');
                    $validator->errors()->add('VEHICULO_MARCA', 'Esta marca de vehiculo ya existe para la patente, tipo, ubicación y modelo del vehículo seleccionados.');
                    $validator->errors()->add('VEHICULO_MODELO', 'Este modelo de vehículo ya existe para el tipo, patente, ubicación y marca del vehículo seleccionados.');
                }

                $exists2 = Vehiculo::where([
                    'TIPO_VEHICULO_ID' => $request->input('TIPO_VEHICULO_ID'),
                    'VEHICULO_PATENTE' => $request->input('VEHICULO_PATENTE'),
                    'DEPARTAMENTO_ID' => $request->input('DEPENDENCIA_ID'),
                    'VEHICULO_MARCA' => $request->input('VEHICULO_MARCA'),
                    'VEHICULO_MODELO' => $request->input('VEHICULO_MODELO'),
                ])->exists();

                if ($exists2) {
                    $validator->errors()->add('TIPO_VEHICULO_ID', 'Este tipo ya existe para la patente, departamento, marca y modelo del vehículo seleccionados.');
                    $validator->errors()->add('VEHICULO_PATENTE', 'Esta patente ya existe para el tipo, departamento, marca y modelo del vehículo seleccionados.');
                    $validator->errors()->add('DEPENDENCIA_ID', 'Este departamento ya está asociada para el tipo, patente, marca y modelo del vehículo seleccionados.');
                    $validator->errors()->add('VEHICULO_MARCA', 'Esta marca de vehiculo ya existe para la patente, tipo, departamento y modelo del vehículo seleccionados.');
                    $validator->errors()->add('VEHICULO_MODELO', 'Este modelo de vehículo ya existe para el tipo, patente, departamento y marca del vehículo seleccionados.');
                }
            });


            // Validar y redirigir mensaje al blade, si falla
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            // Segunda validación. Buscar si la ubicación o el departamento seleccionado en el blade existe
            $ubicacionExists = Ubicacion::find($request->input('DEPENDENCIA_ID'));
            $departamentoExists = Departamento::find($request->input('DEPENDENCIA_ID'));

            // Verificar si no existen, se produce error con retorno de mensajes al blade
            if (!$ubicacionExists && !$departamentoExists) {
                return redirect()->back()->withErrors(['DEPENDENCIA_ID' => 'La Ubicación/Departamento seleccionado no es válido.'])->withInput();
            }


            // Crear un nuevo vehículo
            $vehiculo = Vehiculo::create([
                'TIPO_VEHICULO_ID' => $request->TIPO_VEHICULO_ID,
                'UBICACION_ID' => $ubicacionExists ? $ubicacionExists->UBICACION_ID : null,
                'DEPARTAMENTO_ID' => $departamentoExists ? $departamentoExists->DEPARTAMENTO_ID : null,
                'VEHICULO_PATENTE' => strtoupper($request->input('VEHICULO_PATENTE')),
                'VEHICULO_MARCA' => strtoupper($request->input('VEHICULO_MARCA')),
                'VEHICULO_MODELO' => strtoupper($request->input('VEHICULO_MODELO')),
                'VEHICULO_ANO' => strval($request->VEHICULO_ANO),
                'VEHICULO_ESTADO' => strtoupper($request->input('VEHICULO_ESTADO')),
                'VEHICULO_KILOMETRAJE' => $request->VEHICULO_KILOMETRAJE,
                'VEHICULO_NIVEL_ESTANQUE' => strtoupper($request->input('VEHICULO_NIVEL_ESTANQUE')),
            ]);


            if ($vehiculo) {
                return redirect()->route('vehiculos.index')->with('success', 'Vehículo creado exitosamente.');
            } else {
                session()->flash('error', 'Error al crear el vehículo');
            }
        } catch (Exception $e) {
            session()->flash('error', 'Error al crear el vehículo.');
            return redirect()->route('vehiculos.index');
        }
    }


    public function edit(string $id)
    {
        try {
            // Obtener el vehiculo a editar
            $vehiculo = Vehiculo::findOrFail($id);
            // Obtiener la OFICINA_ID del usuario actual
            $oficinaIdUsuario = Auth::user()->OFICINA_ID;
            // Obtener la entidad oficina asociada al usuario
            $oficinaAsociada = Oficina::find($oficinaIdUsuario);
            // Obtener ubicaciones locales
            $ubicacionesLocales = Ubicacion::where('OFICINA_ID', $oficinaIdUsuario)->get();
            // Obtener departamentos locales
            $departamentosLocales = Departamento::where('OFICINA_ID', $oficinaIdUsuario)->get();
            // Obtener tipos de vehículos locales
            $tiposVehiculos = TipoVehiculo::where('OFICINA_ID', $oficinaIdUsuario)->get();

            return view('sia2.activos.modvehiculos.edit', compact('vehiculo', 'tiposVehiculos', 'oficinaAsociada', 'ubicacionesLocales', 'departamentosLocales'));
        } catch (ModelNotFoundException $e) {
            // Manejar excepción de modelo no encontrado
            return redirect()->route('vehiculos.index')->with('error', 'Ocurrió un error inesperado.');
        } catch (Exception $e) {
            // Manejar otras excepciones
            return redirect()->route('vehiculos.index')->with('error', 'Ocurrió un error inesperado.');
        }
    }


    public function update(Request $request, $id)
    {
        try {
            // Obtener el vehículo a actualizar
            $vehiculo = Vehiculo::findOrFail($id);

            // Reglas de validación y mensajes respectivos
            $validator = Validator::make($request->all(), [
                'VEHICULO_PATENTE' => 'required|string|max:7|regex:/^[A-Z0-9]{4}-[A-Z0-9]{2}$/i|unique:vehiculos,VEHICULO_PATENTE,' . $id . ',VEHICULO_ID',
                'TIPO_VEHICULO_ID' => 'required|exists:tipos_vehiculos,TIPO_VEHICULO_ID',
                'VEHICULO_MARCA' => 'required|string|max:128',
                'VEHICULO_MODELO' => 'required|string|max:191',
                'VEHICULO_ANO' => 'required|integer|min:2000|max:' . date('Y'),
                'DEPENDENCIA_ID' => 'required',
                'VEHICULO_ESTADO' => 'required|string|in:DISPONIBLE,OCUPADO',
                'VEHICULO_KILOMETRAJE' => 'required|integer|min:0|max:400000',
                'VEHICULO_NIVEL_ESTANQUE' => 'required|string|max:128',
            ],  [
                'VEHICULO_PATENTE.required' => 'El campo Patente es obligatorio.',
                'VEHICULO_PATENTE.string' => 'El campo Patente debe ser una cadena de texto.',
                'VEHICULO_PATENTE.max' => 'El campo Patente no debe exceder los :max caracteres.',
                'VEHICULO_PATENTE.unique' => 'La patente ingresada ya existe.',
                'VEHICULO_PATENTE.regex' => 'El campo Patente debe tener el formato XXXX-XX, donde X es una letra o un número.',
                'TIPO_VEHICULO_ID.required' => 'El campo Tipo de Vehículo es obligatorio.',
                'TIPO_VEHICULO_ID.exists' => 'El Tipo de Vehículo seleccionado no es válido.',
                'VEHICULO_MARCA.required' => 'El campo Marca es obligatorio.',
                'VEHICULO_MARCA.string' => 'El campo Marca debe ser una cadena de texto.',
                'VEHICULO_MARCA.max' => 'El campo Marca no debe exceder los :max caracteres.',
                'VEHICULO_MODELO.required' => 'El campo Modelo es obligatorio.',
                'VEHICULO_MODELO.string' => 'El campo Modelo debe ser una cadena de texto.',
                'VEHICULO_MODELO.max' => 'El campo Modelo no debe exceder los :max caracteres.',
                'VEHICULO_ANO.required' => 'El campo Año es obligatorio.',
                'VEHICULO_ANO.integer' => 'El campo Año debe ser un número entero.',
                'VEHICULO_ANO.min' => 'El campo Año debe ser mayor o igual a 2000.',
                'VEHICULO_ANO.max' => 'El campo Año no debe ser mayor al año actual.',
                'DEPENDENCIA_ID.required' => 'El campo Ubicación/Departamento es obligatorio.',
                'VEHICULO_ESTADO.required' => 'El campo Estado es obligatorio.',
                'VEHICULO_ESTADO.string' => 'El campo Estado debe ser una cadena de texto.',
                'VEHICULO_ESTADO.in' => 'El campo Estado debe ser uno de: DISPONIBLE, OCUPADO.',
                'VEHICULO_KILOMETRAJE.required' => 'El campo Kilometraje es obligatorio.',
                'VEHICULO_KILOMETRAJE.integer' => 'El campo Kilometraje debe ser un número entero.',
                'VEHICULO_KILOMETRAJE.min' => 'El Kilometraje no puede ser negativo.',
                'VEHICULO_KILOMETRAJE.max' => 'El Kilometraje no puede exceder 400.000 kilómetros.',
                'VEHICULO_NIVEL_ESTANQUE.required' => 'El campo Nivel Estanque es obligatorio.',
                'VEHICULO_NIVEL_ESTANQUE.string' => 'El campo Nivel Estanque debe ser una cadena de texto.',
                'VEHICULO_NIVEL_ESTANQUE.max' => 'El campo Nivel Estanque no debe exceder los :max caracteres.',
            ]);

            $validator->after(function ($validator) use ($request, $id) {
                $exists1 = Vehiculo::where([
                    'TIPO_VEHICULO_ID' => $request->input('TIPO_VEHICULO_ID'),
                    'VEHICULO_PATENTE' => $request->input('VEHICULO_PATENTE'),
                    'UBICACION_ID' => $request->input('DEPENDENCIA_ID'),
                    'VEHICULO_MARCA' => $request->input('VEHICULO_MARCA'),
                    'VEHICULO_MODELO' => $request->input('VEHICULO_MODELO'),
                ])->where('VEHICULO_ID', '!=', $id)->exists();

                if ($exists1) {
                    $validator->errors()->add('TIPO_VEHICULO_ID', 'Este tipo ya existe para la patente, ubicación, marca y modelo del vehículo seleccionados.');
                    $validator->errors()->add('VEHICULO_PATENTE', 'Esta patente ya existe para el tipo, ubicación, marca y modelo del vehículo seleccionados.');
                    $validator->errors()->add('DEPENDENCIA_ID', 'Esta ubicación ya está asociada para el tipo, patente, marca y modelo del vehículo seleccionados.');
                    $validator->errors()->add('VEHICULO_MARCA', 'Esta marca de vehiculo ya existe para la patente, tipo, ubicación y modelo del vehículo seleccionados.');
                    $validator->errors()->add('VEHICULO_MODELO', 'Este modelo de vehículo ya existe para el tipo, patente, ubicación y marca del vehículo seleccionados.');
                }

                $exists2 = Vehiculo::where([
                    'TIPO_VEHICULO_ID' => $request->input('TIPO_VEHICULO_ID'),
                    'VEHICULO_PATENTE' => $request->input('VEHICULO_PATENTE'),
                    'DEPARTAMENTO_ID' => $request->input('DEPENDENCIA_ID'),
                    'VEHICULO_MARCA' => $request->input('VEHICULO_MARCA'),
                    'VEHICULO_MODELO' => $request->input('VEHICULO_MODELO'),
                ])->where('VEHICULO_ID', '!=', $id)->exists();

                if ($exists2) {
                    $validator->errors()->add('TIPO_VEHICULO_ID', 'Este tipo ya existe para la patente, departamento, marca y modelo del vehículo seleccionados.');
                    $validator->errors()->add('VEHICULO_PATENTE', 'Esta patente ya existe para el tipo, departamento, marca y modelo del vehículo seleccionados.');
                    $validator->errors()->add('DEPENDENCIA_ID', 'Este departamento ya está asociada para el tipo, patente, marca y modelo del vehículo seleccionados.');
                    $validator->errors()->add('VEHICULO_MARCA', 'Esta marca de vehiculo ya existe para la patente, tipo, departamento y modelo del vehículo seleccionados.');
                    $validator->errors()->add('VEHICULO_MODELO', 'Este modelo de vehículo ya existe para el tipo, patente, departamento y marca del vehículo seleccionados.');
                }
            });



            // Validar y redirigir mensaje al blade, si falla
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            // Segunda validación. Buscar si la ubicación o el departamento seleccionado en el blade existe
            $ubicacionExists = Ubicacion::find($request->input('DEPENDENCIA_ID'));
            $departamentoExists = Departamento::find($request->input('DEPENDENCIA_ID'));

            // Verificar si no existen, se produce error con retorno de mensajes al blade
            if (!$ubicacionExists && !$departamentoExists) {
                return redirect()->back()->withErrors(['DEPENDENCIA_ID' => 'La Ubicación/Departamento seleccionado no es válido.'])->withInput();
            }

            // Actualizar el vehículo
            $vehiculo->update([
                'TIPO_VEHICULO_ID' => $request->TIPO_VEHICULO_ID,
                'UBICACION_ID' => $ubicacionExists ? $ubicacionExists->UBICACION_ID : null,
                'DEPARTAMENTO_ID' => $departamentoExists ? $departamentoExists->DEPARTAMENTO_ID : null,
                'VEHICULO_PATENTE' => strtoupper($request->input('VEHICULO_PATENTE')),
                'VEHICULO_MARCA' => strtoupper($request->input('VEHICULO_MARCA')),
                'VEHICULO_MODELO' => strtoupper($request->input('VEHICULO_MODELO')),
                'VEHICULO_ANO' => strval($request->VEHICULO_ANO),
                'VEHICULO_ESTADO' => strtoupper($request->input('VEHICULO_ESTADO')),
                'VEHICULO_KILOMETRAJE' => $request->VEHICULO_KILOMETRAJE,
                'VEHICULO_NIVEL_ESTANQUE' => strtoupper($request->input('VEHICULO_NIVEL_ESTANQUE')),
            ]);

            return redirect()->route('vehiculos.index')->with('success', 'Vehículo actualizado exitosamente.');
        } catch (Exception $e) {
            session()->flash('error', 'Error al actualizar el vehículo.');
            return redirect()->route('vehiculos.index');
        }
    }



    public function destroy(string $id)
    {
        try{
            // Encontrar el vehículo por su ID
            $vehiculo = Vehiculo::find($id);

            // Eliminar el vehiculo
            $vehiculo->delete();

            return redirect()->route('vehiculos.index')->with('success', 'Vehículo eliminado exitosamente.');
        } catch(ModelNotFoundException) {
            // Manejo de excepciones cuando no encuentre el material
            return redirect()->route('vehiculos.index')->with('error', 'Error al eliminar el vehículo');
        }
        catch(QueryException $ex)
        {
            // Retornamos la vista con el mensaje de error (concatenar mensaje con $ex para obtener detalles DEBUG)
            return redirect()->back()->with('error', 'No se puede eliminar el vehículo porque tiene registros relacionados');
        }
        catch(Exception $e) {// "Exeption" estaba mal escrito
            return redirect()->route('vehiculos.index')->with('error', 'No se encontró el vehículo.');
        }
    }

}
