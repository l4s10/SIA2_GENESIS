<?php

namespace App\Http\Controllers\Activos\Equipo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Gloudemans\Shoppingcart\Facades\Cart;

use Exception;

use App\Models\TipoEquipo;
use App\Models\Oficina;




class TipoEquipoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Código para el manejo de errores y retorno de vistas
        try
        {
            $oficinaIdUsuario = Auth::user()->OFICINA_ID;

            // Función que lista tipos de materiales basados en la OFICINA_ID del usuario
            $tiposEquipo = TipoEquipo::where('OFICINA_ID', $oficinaIdUsuario)->get();

            // Retornar la vista con los datos
            return view('sia2.activos.modequipos.tiposequipos.index', compact('tiposEquipo'));
        }
        catch (\Exception $e)
        {
            // Retornar a la pagina previa con un session error
            return back()->with('error', 'Error cargando los tipos de equipo');
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
            // Obtener el objeto oficina asociada al usuario actual
            $oficina = Oficina::where('OFICINA_ID', $oficinaIdUsuario)->firstOrFail();

            return view('sia2.activos.modequipos.tiposequipos.create', compact('oficina'));
        } catch (ModelNotFoundException $e) {
            // Manejar excepción de modelo no encontrado
            return redirect()->route('tiposequipos.index')->with('error', 'No se encontró la oficina del usuario.');
        } catch (Exception $e) {
            // Manejar otras excepciones
            return redirect()->route('tiposequipos.index')->with('error', 'Ocurrió un error inesperado.');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try
        {
            // Validación de los datos
            $validator = Validator::make($request->all(), [
                'TIPO_EQUIPO_NOMBRE' => ['required','string','max:128'],
            ], [
                'TIPO_EQUIPO_NOMBRE.required' => 'El campo "Nombre Tipo" es obligatorio.',
                'TIPO_EQUIPO_NOMBRE.string' => 'El campo "Nombre Tipo" debe ser una cadena de texto.',
                'TIPO_EQUIPO_NOMBRE.max' => 'El campo "Nombre Tipo" no debe exceder los :max caracteres.'
            ]);

            // Validar clave única compuesta
            $validator->after(function ($validator) use ($request) {
                $exists = TipoEquipo::where([
                    'OFICINA_ID' => Auth::user()->OFICINA_ID,
                    'TIPO_EQUIPO_NOMBRE' => strtoupper($request->input('TIPO_EQUIPO_NOMBRE'))
                ])->exists();

                if ($exists) {
                    $validator->errors()->add('TIPO_EQUIPO_NOMBRE', 'El nombre del tipo de equipo ya existe en su dirección regional.');
                }
            });


            // Validar y redirigir si falla
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            // Crear el nuevo tipo de equipo
            TipoEquipo::create([
                'TIPO_EQUIPO_NOMBRE' => strtoupper($request->input('TIPO_EQUIPO_NOMBRE')),
                'OFICINA_ID' => Auth::user()->OFICINA_ID,
            ]);

            // Retornar a la vista con un mensaje de éxito
            return redirect()->route('tiposequipos.index')->with('success', 'Tipo de equipo creado exitosamente');
        }
        catch(\Exception $e)
        {
            // Retornar a la vista con un mensaje de error
            return redirect()->route('tiposequipos.index')->with('error', 'Error al crear el tipo de equipo');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Manejo de errores
        try
        {
            $tipoEquipo = TipoEquipo::findOrFail($id);
            return view('sia2.activos.modequipos.tiposequipos.show', compact('tipoEquipo'));
        }
        catch(Exception $e)
        {
            return redirect()->route('tiposequipos.index')->with('error', 'Error al cargar el tipo de equipo');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Manejo de errores
        try
        {
            // Obtenemos el tipo de equipo por ID con la relacion "oficina"
            $tipoEquipo = TipoEquipo::findOrFail($id);

            // Obtenemos la OFICINA_ID del usuario actual
            $oficinaIdUsuario = Auth::user()->OFICINA_ID;

            // Obtenemos la informacion de la oficina
            $oficina = Oficina::where('OFICINA_ID', $oficinaIdUsuario)->firstOrFail();

            // Retornar a la vista con los datos compactados
            return view('sia2.activos.modequipos.tiposequipos.edit', compact('tipoEquipo', 'oficina'));
        }
        catch( ModelNotFoundException $e)
        {
            // Manjejar excepcion de modelo no encontrado
            return redirect()->route('tiposequipos.index')->with('error', 'Ocurrió un error inesperado.');
        }
        catch(Exception $e)
        {
            // Manejar otras excepciones
            return redirect()->route('tiposequipos.index')->with('error', 'Ocurrió un error inesperado.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try
        {
            // Obtener el tipo de equipo por ID
            $tipoEquipo = TipoEquipo::findOrFail($id);

            // Construir el validador
            $validator = Validator::make($request->all(), [
                'TIPO_EQUIPO_NOMBRE' => ['required', 'string', 'max:128'],
            ], [
                // Mensajes de error
                'TIPO_EQUIPO_NOMBRE.required' => 'El campo "Nombre Tipo" es obligatorio.',
                'TIPO_EQUIPO_NOMBRE.string' => 'El campo "Nombre Tipo" debe ser una cadena de texto.',
                'TIPO_EQUIPO_NOMBRE.max' => 'El campo "Nombre Tipo" no debe exceder los :max caracteres.'
            ]);

            
            // Validar clave única compuesta
            $validator->after(function ($validator) use ($request, $id) {
                $exists = TipoEquipo::where([
                    'OFICINA_ID' => Auth::user()->OFICINA_ID,
                    'TIPO_EQUIPO_NOMBRE' => strtoupper($request->input('TIPO_EQUIPO_NOMBRE'))
                ])->where('TIPO_EQUIPO_ID', '!=', $id)->exists();

                if ($exists) {
                    $validator->errors()->add('TIPO_EQUIPO_NOMBRE', 'El nombre del tipo de equipo ya existe en su dirección regional.');
                }
            });

            // Validar y redirigir si falla
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            // Actualizar el tipo de equipo
            $tipoEquipo->update([
                'TIPO_EQUIPO_NOMBRE' => strtoupper($request->input('TIPO_EQUIPO_NOMBRE')),
                'OFICINA_ID' => Auth::user()->OFICINA_ID,
            ]);

            // Retornar a la vista con un mensaje de éxito
            return redirect()->route('tiposequipos.index')->with('success', 'Tipo de equipo actualizado exitosamente');
        } catch (ModelNotFoundException $e) {
            // Manejar la excepción de modelo no encontrado
            return redirect()->route('tiposequipos.index')->with('error', 'Error al actualizar el tipo de equipo');
        } catch (Exception $e) {
            // Manejar otras excepciones
            return redirect()->route('tiposequipos.index')->with('error', 'Error al actualizar el tipo de equipo');
        }
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Manejo de errores
        try
        {
            //Obtener el tipo de equipo por ID
            $tipoEquipo = TipoEquipo::findOrFail($id);
            $tipoEquipo->delete();

            //Retornar a la vista con un mensaje de éxito
            return redirect()->route('tiposequipos.index')->with('success', 'Tipo de equipo eliminado exitosamente');
        } catch(ModelNotFoundException $e) {
            //Manejar la excepcion de modelo no encontrado
            return redirect()->route('tiposequipos.index')->with('error', 'El tipo de equipo no se encontró');
        } catch(Exception $e) {
            //Manejar otras excepciones
            return redirect()->route('tiposequipos.index')->with('error', 'Error al eliminar el tipo de equipo');
        }
    }

    // Funciones para agregar y tipos de equipo al carrito
    public function addToCart(TipoEquipo $tipoequipo)
    {
        // Llamamos a la instancia del carrito de compras para los equipos
        $carritoEquipos = Cart::instance('carrito_equipos');

        // Agregamos el tipo de equipo al carrito
        $carritoEquipos->add($tipoequipo, 1);

        // Retornar a la vista con un mensaje de éxito y mantener los datos de entrada del formulario
        return redirect()->back()->with('success', 'Tipo de equipo agregado exitosamente')->withInput();
    }

    // Función para eliminar un tipo de equipo del carrito
    public function removeFromCart($rowId)
    {
        // Llamamos a la instancia del carrito de compras para los equipos
        $carritoEquipos = Cart::instance('carrito_equipos');

        // Eliminamos el tipo de equipo del carrito
        $carritoEquipos->remove($rowId);

        // Retornar a la vista con un mensaje de éxito
        return redirect()->back()->with('success', 'Tipo de equipo eliminado exitosamente')->withInput();
    }
}
