<?php

namespace App\Http\Controllers\Equipo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

use App\Models\Equipo;
use App\Models\TipoEquipo;
use App\Models\Movimiento;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class EquipoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Obtenemos la oficina del usuario actual
        $oficinaIdUsuario = Auth::user()->OFICINA_ID;

        // Funcion que lista los materiales en funcion de la oficina del usuario
        $equipos = Equipo::where('OFICINA_ID', $oficinaIdUsuario)->get();

        return view('sia2.activos.modequipos.equipos.index', compact('equipos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Obtener la Oficina del usuario actual
        $oficinaIdUsuario = Auth::user()->OFICINA_ID;

        // Obtener los tipos de equipos asociados a la oficina 'x' utilizando la relacion indirecta del equipo y la oficina para hacer match con la oficina del usuario
        $tiposEquipos = TipoEquipo::where('OFICINA_ID', $oficinaIdUsuario)->get();

        // Retornar la vista con los tipos de equipos
        return view('sia2.activos.modequipos.equipos.create', compact('tiposEquipos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Manejo de excepciones
        try
        {
            //Transformar caracteres a caracteres mayusculas
            $request->merge([
                'DETALLE_MOVIMIENTO' => strtoupper($request->input('DETALLE_MOVIMIENTO')),
                'EQUIPO_MARCA' => strtoupper($request->input('EQUIPO_MARCA')),
                'EQUIPO_MODELO' => strtoupper($request->input('EQUIPO_MODELO')),
                'EQUIPO_ESTADO' => strtoupper($request->input('EQUIPO_ESTADO'))
            ]);

            //Validacion de datos
            $request->validate([
                'TIPO_EQUIPO_ID' => 'required|exists:tipos_equipos,TIPO_EQUIPO_ID',
                'EQUIPO_STOCK' => 'required|integer|between:0,1000',
                'EQUIPO_MARCA' => 'required|string|max:128',
                'EQUIPO_MODELO' => 'required|string|max:128',
                'EQUIPO_ESTADO' => 'required|string|max:128',
                'DETALLE_MOVIMIENTO' => 'required|string|max:1000'
            ]);

            //Crear un nuevo equipo e instanciar en $equipo para acceder a sus atributos al realizar el respectivo movimiento
            $equipo = Equipo::create([
                'OFICINA_ID' => Auth::user()->OFICINA_ID,
                'TIPO_EQUIPO_ID' => $request->TIPO_EQUIPO_ID,
                'EQUIPO_MARCA' => $request->EQUIPO_MARCA,
                'EQUIPO_MODELO' => $request->EQUIPO_MODELO,
                'EQUIPO_STOCK' => $request->EQUIPO_STOCK,
                'EQUIPO_ESTADO' => $request->EQUIPO_ESTADO,
            ]);

            if($equipo)
            {
                //Crear un nuevo movimiento asociado al equipo creado
                Movimiento::create([
                    'USUARIO_id' => Auth::user()->id,
                    'EQUIPO_ID' => $equipo->EQUIPO_ID,
                    'MOVIMIENTO_TITULAR' => Auth::user()->USUARIO_NOMBRES,
                    'MOVIMIENTO_OBJETO' => 'EQU: '.$equipo->EQUIPO_MODELO,
                    'MOVIMIENTO_TIPO_OBJETO' => $equipo->tipoEquipo->TIPO_EQUIPO_NOMBRE,
                    'MOVIMIENTO_TIPO' => 'INGRESO',
                    'MOVIMIENTO_STOCK_PREVIO' => 0,
                    'MOVIMIENTO_CANTIDAD_A_MODIFICAR' => $equipo->EQUIPO_STOCK,
                    'MOVIMIENTO_STOCK_RESULTANTE' => $equipo->EQUIPO_STOCK,
                    'MOVIMIENTO_DETALLE' => $request->DETALLE_MOVIMIENTO,
                ]);

                //Redireccionar a la vista index
                return redirect()->route('equipos.index')->with('success', 'Equipo creado correctamente');
            }
            else{
                //Redireccionar a la vista index
                return redirect()->route('equipos.index')->with('error', 'Error al crear el equipo');
            }
        }
        catch(Exception $e)
        {
            //Log::error('Error al crear el equipo: ' . $e->getMessage());
            return redirect()->route('equipos.index')->with('error', 'Error al crear el equipo: ' . $e->getMessage());
        }
        // redireccionar a la vista index
        return redirect()->route('equipos.index')->with('success', 'Equipo creado exitosamente.');
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
        //Verificacion de datos
        try
        {
            //obtener el equipo a editar
            $equipo = Equipo::findOrFail($id);
            //Obtener la OFICINA_ID del usuario actual
            $oficinaIdUsuario = Auth::user()->OFICINA_ID;
            //Obtener tipos de equipos según oficina_id del usuario en sesión
            $tiposEquipos = TipoEquipo::where('OFICINA_ID', $oficinaIdUsuario)->get();

            //retornar la vista con los datos
            return view('sia2.activos.modequipos.equipos.edit', compact('equipo', 'tiposEquipos'));
        }
        catch(ModelNotFoundException $e)
        {
            // Manejo de excepciones cuando no encuentre el modelo
            return redirect()->route('equipos.index')->with('error', 'No se encontró el equipo');
        }
        catch(Exception $e)
        {
            //Manejo para cualquier otra excepcion
            return redirect()->route('equipos.index')->with('error', 'Error al editar el equipo: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // manejo de excepciones
        try
        {
            //Obtener el equipo a actualizar
            $equipo = Equipo::findOrFail($id);

            //Transformar caracteres a caracteres mayúsculas
            $request->merge([
                'EQUIPO_MARCA' => strtoupper($request->input('EQUIPO_MARCA')),
                'EQUIPO_MODELO' => strtoupper($request->input('EQUIPO_MODELO')),
                'EQUIPO_ESTADO' => strtoupper($request->input('EQUIPO_ESTADO')),
                'DETALLE_MOVIMIENTO' => strtoupper($request->input('DETALLE_MOVIMIENTO'))
            ]);

            //Validacion de datos
            $request->validate([
                'TIPO_EQUIPO_ID' => 'required|exists:tipos_equipos,TIPO_EQUIPO_ID',
                'EQUIPO_STOCK' => 'required|integer|between:0,1000',
                'STOCK_NUEVO' => 'required|integer|between:0,1000',
                'EQUIPO_MARCA' => 'required|string|max:128',
                'EQUIPO_MODELO' => 'required|string|max:128',
                'EQUIPO_ESTADO' => 'required|string|max:128',
                'DETALLE_MOVIMIENTO' => 'required|string|max:255',
                'TIPO_MOVIMIENTO' => [
                    'required',
                    'string',
                    'max:10',
                    Rule::in(['INGRESO', 'TRASLADO', 'MERMA', 'OTRO']),
                    function ($attribute, $value, $fail) use ($request) {
                        $stockNuevo = $request->input('STOCK_NUEVO');
                        $equipoStock = $request->input('EQUIPO_STOCK');

                        if ($value === 'INGRESO' && ($stockNuevo <= 0 || $stockNuevo > 1000)) {
                            $fail('El STOCK_NUEVO debe ser mayor que 0 y menor o igual a 1000 para el tipo de movimiento INGRESO.');
                        } elseif (($value === 'TRASLADO' || $value === 'MERMA') && ($stockNuevo < 0 || $stockNuevo > $equipoStock)) {
                            $fail('El STOCK_NUEVO debe ser mayor o igual a 0 y menor o igual al Stock actual del material para los tipos de movimiento TRASLADO y MERMA.');
                        } elseif ($value === 'OTRO' && $stockNuevo !== 0) {
                            $fail('Para el tipo de movimiento OTRO, el STOCK_NUEVO debe ser 0.');
                        }
                    },
                ],
            ]);

            //calcular el stock resultante según el tipo de movimiento
            if($request->TIPO_MOVIMIENTO == 'INGRESO')
            {
                $stockResultante = $equipo->EQUIPO_STOCK + $request->STOCK_NUEVO;
            }
            elseif(($request->TIPO_MOVIMIENTO == 'TRASLADO') || ($request->TIPO_MOVIMIENTO == 'MERMA'))
            {
                $stockResultante = $equipo->EQUIPO_STOCK - $request->STOCK_NUEVO;
            }

            //Actualizar los atributos del equipo
            $equipo->update([
                'OFICINA_ID' => Auth::user()->OFICINA_ID,
                'TIPO_EQUIPO_ID' => $request->TIPO_EQUIPO_ID,
                'EQUIPO_MARCA' => $request->EQUIPO_MARCA,
                'EQUIPO_MODELO' => $request->EQUIPO_MODELO,
                'EQUIPO_ESTADO' => $request->EQUIPO_ESTADO,
                'EQUIPO_STOCK' => $stockResultante,
            ]);

            //Crear un nuevo movimiento asociado al equipo creado
            Movimiento::create([
                'USUARIO_id' => Auth::user()->id,
                'EQUIPO_ID' => $equipo->EQUIPO_ID,
                'MOVIMIENTO_TITULAR' => Auth::user()->USUARIO_NOMBRES,
                'MOVIMIENTO_OBJETO' => 'EQU: '.$equipo->EQUIPO_NOMBRE,
                'MOVIMIENTO_TIPO_OBJETO' => $equipo->tipoEquipo->TIPO_EQUIPO_NOMBRE,
                'MOVIMIENTO_TIPO' => $request->TIPO_MOVIMIENTO,
                'MOVIMIENTO_STOCK_PREVIO' => $equipo->EQUIPO_STOCK,
                'MOVIMIENTO_CANTIDAD_A_MODIFICAR' => $request->STOCK_NUEVO,
                'MOVIMIENTO_STOCK_RESULTANTE' => $stockResultante,
                'MOVIMIENTO_DETALLE' => $request->DETALLE_MOVIMIENTO,
            ]);

            //Redireccionar a la vista index
            return redirect()->route('equipos.index')->with('success', 'Equipo actualizado correctamente');
        }
        catch(ModelNotFoundException $e)
        {
            // Manejo de excepciones cuando no encuentre el modelo
            return redirect()->route('equipos.index')->with('error', 'No se encontró el equipo' . $e->getMessage());
        }
        catch(Exception $e)
        {
            //Manejo para cualquier otra excepcion
            return redirect()->route('equipos.index')->with('error', 'Error al actualizar el equipo: ' . $e->getMessage());
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

            $equipo->delete();

            //retornar a la vista index
            return redirect()->route('equipos.index')->with('success', 'Equipo eliminado correctamente');
        }
        catch(ModelNotFoundException $e)
        {
            // Manejo de excepciones cuando no encuentre el modelo
            return redirect()->route('equipos.index')->with('error', 'No se encontró el equipo');
        }
        catch(Exception $e)
        {
            //Manejo para cualquier otra excepcion
            return redirect()->route('equipos.index')->with('error', 'Error al eliminar el equipo: ' . $e->getMessage());
        }
    }
}
