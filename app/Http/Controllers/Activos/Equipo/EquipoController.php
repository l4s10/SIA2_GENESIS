<?php

namespace App\Http\Controllers\Activos\Equipo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception; // Libreria faltante

use App\Models\Equipo;
use App\Models\TipoEquipo;
use App\Models\Movimiento;
use App\Models\Oficina;



class EquipoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            // Obtiene la OFICINA_ID del usuario actual
            $oficinaIdUsuario = Auth::user()->OFICINA_ID;
            // Función que lista equipos basados en la OFICINA_ID del usuario
            $equipos = Equipo::where('OFICINA_ID', $oficinaIdUsuario)->get();

            // Retorna la vista con los equipos
            return view('sia2.activos.modequipos.equipos.index', compact('equipos'));
        } catch (\Exception $e) {
            // Maneja la excepción y muestra un mensaje de error
            return back()->with('error', 'Error cargando los equipos: ' . $e->getMessage());
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

            // Obtener los tipos de equipos asociados a la oficina del usuario
            $tiposEquipos = TipoEquipo::where('OFICINA_ID', $oficinaIdUsuario)->get();

            // Obtener el objeto oficina asociada al usuario actual
            $oficina = Oficina::where('OFICINA_ID', $oficinaIdUsuario)->firstOrFail();

            // Retornar la vista con los tipos de equipos y la oficina
            return view('sia2.activos.modequipos.equipos.create', compact('tiposEquipos', 'oficina'));
        } catch (ModelNotFoundException $e) {
            // Manejar excepción de modelo no encontrado
            return redirect()->route('equipos.index')->with('error', 'Ocurrió un error inesperado.');
        } catch (Exception $e) {
            // Manejar otras excepciones
            return redirect()->route('equipos.index')->with('error', 'Ocurrió un error inesperado.');
        }
    }



    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        try {
            // Reglas de validación y mensajes respectivos
            $validator = Validator::make($request->all(), [
                'TIPO_EQUIPO_ID' => 'required|exists:tipos_equipos,TIPO_EQUIPO_ID',
                'EQUIPO_STOCK' => 'required|integer|between:0,1000',
                'EQUIPO_MARCA' => 'required|string|max:128',
                'EQUIPO_MODELO' => 'required|string|max:128',
                'EQUIPO_ESTADO' => 'required|string|max:40',
                'DETALLE_MOVIMIENTO' => 'required|string|max:1000',
            ], [
                'TIPO_EQUIPO_ID.required' => 'El campo Tipo de Equipo es obligatorio.',
                'TIPO_EQUIPO_ID.exists' => 'El Tipo de Equipo seleccionado no es válido.',
                'EQUIPO_STOCK.required' => 'El campo Stock es obligatorio.',
                'EQUIPO_STOCK.integer' => 'El campo Stock debe ser un número entero.',
                'EQUIPO_STOCK.between' => 'El campo Stock debe estar entre :min y :max.',
                'EQUIPO_MARCA.required' => 'El campo Marca es obligatorio.',
                'EQUIPO_MARCA.string' => 'El campo Marca debe ser una cadena de texto.',
                'EQUIPO_MARCA.max' => 'El campo Marca no debe exceder los :max caracteres.',
                'EQUIPO_MODELO.required' => 'El campo Modelo es obligatorio.',
                'EQUIPO_MODELO.string' => 'El campo Modelo debe ser una cadena de texto.',
                'EQUIPO_MODELO.max' => 'El campo Modelo no debe exceder los :max caracteres.',
                'EQUIPO_ESTADO.required' => 'El campo Estado es obligatorio.',
                'EQUIPO_ESTADO.string' => 'El campo Estado debe ser una cadena de texto.',
                'EQUIPO_ESTADO.max' => 'El campo Estado no debe exceder los :max caracteres.',
                'DETALLE_MOVIMIENTO.required' => 'El campo Detalle de Movimiento es obligatorio.',
                'DETALLE_MOVIMIENTO.string' => 'El campo Detalle de Movimiento debe ser una cadena de texto.',
                'DETALLE_MOVIMIENTO.max' => 'El campo Detalle de Movimiento no debe exceder los :max caracteres.',
            ]);

            // Validar y redirigir mensaje al blade si falla
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            // Crear un nuevo equipo e instanciar en $equipo para acceder a sus atributos al realizar el respectivo movimiento
            $equipo = Equipo::create([
                'OFICINA_ID' => Auth::user()->OFICINA_ID,
                'TIPO_EQUIPO_ID' => $request->TIPO_EQUIPO_ID,
                'EQUIPO_MARCA' => strtoupper($request->input('EQUIPO_MARCA')),
                'EQUIPO_MODELO' => strtoupper($request->input('EQUIPO_MODELO')),
                'EQUIPO_STOCK' => $request->EQUIPO_STOCK,
                'EQUIPO_ESTADO' => strtoupper($request->input('EQUIPO_ESTADO'))
            ]);

            if ($equipo) {
                // Crear un nuevo movimiento asociado al equipo creado
                Movimiento::create([
                    'USUARIO_id' => Auth::user()->id,
                    'EQUIPO_ID' => $equipo->EQUIPO_ID,
                    'MOVIMIENTO_TITULAR' => Auth::user()->USUARIO_NOMBRES,
                    'MOVIMIENTO_OBJETO' => 'EQUIPO: ' . $equipo->EQUIPO_MODELO,
                    'MOVIMIENTO_TIPO_OBJETO' => $equipo->tipoEquipo->TIPO_EQUIPO_NOMBRE,
                    'MOVIMIENTO_TIPO' => 'INGRESO',
                    'MOVIMIENTO_STOCK_PREVIO' => 0,
                    'MOVIMIENTO_CANTIDAD_A_MODIFICAR' => $equipo->EQUIPO_STOCK,
                    'MOVIMIENTO_STOCK_RESULTANTE' => $equipo->EQUIPO_STOCK,
                    'MOVIMIENTO_DETALLE' => strtoupper($request->input('DETALLE_MOVIMIENTO'))
                ]);

                // Redireccionar a la vista index
                return redirect()->route('equipos.index')->with('success', 'Equipo creado exitosamente.');
            } else {
                // Redireccionar a la vista index
                return redirect()->route('equipos.index')->with('error', 'Error al crear el equipo');
            }
        } catch (Exception $e) {
            // Log::error('Error al crear el equipo: ' . $e->getMessage());
            return redirect()->route('equipos.index')->with('error', 'Error al crear el equipo');
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
    public function edit($id)
    {
        try {
            // Obtener el equipo a editar
            $equipo = Equipo::findOrFail($id);
            // Obtener la OFICINA_ID del usuario actual
            $oficinaIdUsuario = Auth::user()->OFICINA_ID;
            // Obtener tipos de equipos según OFICINA_ID del usuario en sesión
            $tiposEquipos = TipoEquipo::where('OFICINA_ID', $oficinaIdUsuario)->get();
            // Obtener la información de la oficina del usuario
            $oficina = Oficina::where('OFICINA_ID', $oficinaIdUsuario)->firstOrFail();

            // Retornar la vista con los datos
            return view('sia2.activos.modequipos.equipos.edit', compact('equipo', 'tiposEquipos', 'oficina'));
        } catch (ModelNotFoundException $e) {
            // Manejo de excepciones cuando no encuentra el modelo
            return redirect()->route('equipos.index')->with('error', 'No se encontró el equipo');
        } catch (Exception $e) {
            // Manejo para cualquier otra excepción
            return redirect()->route('equipos.index')->with('error', 'Error al editar el equipo');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            // Obtener el equipo a actualizar
            $equipo = Equipo::findOrFail($id);
            // Reglas de validación y mensajes respectivos
            $validator = Validator::make($request->all(), [
                'TIPO_EQUIPO_ID' => 'required|exists:tipos_equipos,TIPO_EQUIPO_ID',
                'EQUIPO_STOCK' => 'required|integer',
                'STOCK_NUEVO' =>    ['required','integer','between:0,1000',
                                    // Validación dinámica para "STOCK_NUEVO" en función de la selección del input "TIPO_MOVMIENTO"
                                        function ($attribute, $value, $fail) use ($request) {
                                            $stockNuevo = $request->input('STOCK_NUEVO');
                                            $equipoStock = $request->input('EQUIPO_STOCK');
                                            $tipoMovimiento = $request->input('TIPO_MOVIMIENTO');

                                            if ($tipoMovimiento === 'INGRESO' && ($stockNuevo <= 0 || $stockNuevo > 1000)) {
                                                $fail('Para "Tipo de movimiento" INGRESO, la "Cantidad a modificar" debe estar entre 1 y 1000');
                                            } elseif (($tipoMovimiento === 'TRASLADO' || $tipoMovimiento === 'MERMA') && ($stockNuevo <= 0 || $stockNuevo > $equipoStock)) {
                                                $fail('Para "Tipo de movimiento" TRASLADO o MERMA, la "Cantidad a modificar" debe estar entre 1 y '.$equipoStock);
                                            } elseif ($tipoMovimiento === 'OTRO' && ($stockNuevo > 0 || $stockNuevo < 0)) {
                                                $fail('Para "Tipo de movimiento" OTRO, la "Cantidad a modificar" debe ser 0');
                                            }
                                        },
                                    ],
                'EQUIPO_MARCA' => 'required|string|max:128',
                'EQUIPO_MODELO' => 'required|string|max:128',
                'EQUIPO_ESTADO' => 'required|string|max:40',
                'DETALLE_MOVIMIENTO' => 'required|string|max:1000',
                'TIPO_MOVIMIENTO' => 'required|string|max:10',
            ], [
                'TIPO_EQUIPO_ID.required' => 'El campo "Tipo de Equipo" es obligatorio.',
                'TIPO_EQUIPO_ID.exists' => 'El "Tipo de Equipo" seleccionado no es válido.',
                'EQUIPO_STOCK.required' => 'El campo "Stock Actual" es obligatorio.',
                'EQUIPO_STOCK.integer' => 'El campo "Stock Actual" debe ser un número entero.',
                'STOCK_NUEVO.required' => 'El campo "Cantidad a Modificar" es obligatorio.',
                'STOCK_NUEVO.integer' => 'El campo "Cantidad a Modificar" debe ser un número entero.',
                'STOCK_NUEVO.between' => 'La "Cantidad a Modificar" debe estar entre :min y :max.',
                'EQUIPO_MARCA.required' => 'El campo "Marca" es obligatorio.',
                'EQUIPO_MARCA.string' => 'El campo "Marca" debe ser una cadena de texto.',
                'EQUIPO_MARCA.max' => 'El campo "Marca" no debe exceder los :max caracteres.',
                'EQUIPO_MODELO.required' => 'El campo "Modelo" es obligatorio.',
                'EQUIPO_MODELO.string' => 'El campo "Modelo" debe ser una cadena de texto.',
                'EQUIPO_MODELO.max' => 'El campo "Modelo" no debe exceder los :max caracteres.',
                'EQUIPO_ESTADO.required' => 'El campo "Estado" es obligatorio.',
                'EQUIPO_ESTADO.string' => 'El campo "Estado" debe ser una cadena de texto.',
                'EQUIPO_ESTADO.max' => 'El campo "Estado" no debe exceder los :max caracteres.',
                'DETALLE_MOVIMIENTO.required' => 'El campo "Detalle de Movimiento" es obligatorio.',
                'DETALLE_MOVIMIENTO.string' => 'El campo "Detalle de Movimiento" debe ser una cadena de texto.',
                'DETALLE_MOVIMIENTO.max' => 'El campo "Detalle de Movimiento" no debe exceder los :max caracteres.',
                'TIPO_MOVIMIENTO.required' => 'El campo "Tipo de Movimiento" es obligatorio.',
                'TIPO_MOVIMIENTO.string' => 'El campo "Tipo de Movimiento" debe ser una cadena de texto.',
                'TIPO_MOVIMIENTO.max' => 'El campo "Tipo de Movimiento" no debe exceder los :max caracteres.'
            ]);

            // Validar y redirigir mensaje al blade, si falla
            if ($validator->fails()) {
                return redirect()->route('equipos.edit', $equipo->EQUIPO_ID)->withErrors($validator)->withInput();
            }

            // Calcular el stock resultante según el tipo de movimiento
            if (($request->TIPO_MOVIMIENTO == 'INGRESO') || ($request->TIPO_MOVIMIENTO == 'OTRO')) {
                $stockResultante = $request->EQUIPO_STOCK + $request->STOCK_NUEVO;
            } else {
                $stockResultante = $request->EQUIPO_STOCK - $request->STOCK_NUEVO;
            }

            // Actualizar los atributos del equipo
            $equipo->update([
                'OFICINA_ID' => Auth::user()->OFICINA_ID,
                'TIPO_EQUIPO_ID' => $request->TIPO_EQUIPO_ID,
                'EQUIPO_MARCA' => strtoupper($request->input('EQUIPO_MARCA')),
                'EQUIPO_MODELO' => strtoupper($request->input('EQUIPO_MODELO')),
                'EQUIPO_ESTADO' => strtoupper($request->input('EQUIPO_ESTADO')),
                'EQUIPO_STOCK' => $stockResultante
            ]);


            // Crear un nuevo movimiento asociado al equipo modificado
            $movimiento = Movimiento::create([
                'USUARIO_id' => Auth::user()->id,
                'EQUIPO_ID' => $equipo->EQUIPO_ID,
                'MOVIMIENTO_TITULAR' => (Auth::user()->USUARIO_NOMBRES.' '.Auth::user()->USUARIO_APELLIDOS),
                'MOVIMIENTO_OBJETO' => 'EQUIPO: ' . $equipo->EQUIPO_MODELO,
                'MOVIMIENTO_TIPO_OBJETO' => $equipo->tipoEquipo->TIPO_EQUIPO_NOMBRE,
                'MOVIMIENTO_TIPO' => $request->TIPO_MOVIMIENTO,
                'MOVIMIENTO_STOCK_PREVIO' => $equipo->EQUIPO_STOCK,
                'MOVIMIENTO_CANTIDAD_A_MODIFICAR' => $request->STOCK_NUEVO,
                'MOVIMIENTO_STOCK_RESULTANTE' => $stockResultante,
                'MOVIMIENTO_DETALLE' => strtoupper($request->input('DETALLE_MOVIMIENTO'))
            ]);
            
            //retornar a la vista index
            return redirect()->route('equipos.index')->with('success', 'Equipo actualizado correctamente');
        } catch (ModelNotFoundException $e) {
            // Manejo de excepciones cuando no encuentre el modelo
            return redirect()->route('equipos.index')->with('error', 'No se encontró el equipo ');
        } catch (Exception $e) {
            // Manejo para cualquier otra excepción
            return redirect()->route('equipos.index')->with('error', 'Error al actualizar el equipo: ');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try
        {
            // Encontrar el equipo por su ID
            $equipo = Equipo::findOrfail($id);

            if($equipo) {

                // Crear un nuevo movimiento asociado al equipo eliminado
                Movimiento::create([
                    'USUARIO_id' => Auth::user()->id,
                    'MATERIAL_ID' => $equipo->EQUIPO_ID,
                    'MOVIMIENTO_TITULAR' => (Auth::user()->USUARIO_NOMBRES.' '.Auth::user()->USUARIO_APELLIDOS),
                    'MOVIMIENTO_OBJETO' => 'EQUIPO: ' . $equipo->EQUIPO_MODELO,
                    'MOVIMIENTO_TIPO_OBJETO' => $equipo->tipoEquipo->TIPO_EQUIPO_NOMBRE,
                    'MOVIMIENTO_TIPO' => 'ELIMINACIÓN',
                    'MOVIMIENTO_STOCK_PREVIO' => $equipo->EQUIPO_STOCK,
                    'MOVIMIENTO_CANTIDAD_A_MODIFICAR' => $equipo->EQUIPO_STOCK,
                    'MOVIMIENTO_STOCK_RESULTANTE' => ($equipo->EQUIPO_STOCK-$equipo->EQUIPO_STOCK),
                    'MOVIMIENTO_DETALLE' => 'ELIMINACIÓN DEL EQUIPO EN EL INVENTARIO',
                ]);
                // Eliminar el equipo
                $equipo->delete();
            }

            //retornar a la vista index
            return redirect()->route('equipos.index')->with('success', 'Equipo eliminado correctamente');
        } catch(ModelNotFoundException $e) {
            // Manejo de excepciones cuando no encuentre el modelo
            return redirect()->route('equipos.index')->with('error', 'Error al eliminar el equipo');
        } catch(Exception $e) {
            //Manejo para cualquier otra excepcion
            return redirect()->route('equipos.index')->with('error', 'Error al eliminar el equipo');
        }
    }
}