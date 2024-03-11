<?php

namespace App\Http\Controllers\Activos\Formulario;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Gloudemans\Shoppingcart\Facades\Cart;

use App\Models\Formulario;

class FormularioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Manejo de excepciones
        try {
            // Obtenemos todos los formularios correspondientes a la oficina del usuario (Optimizacion de Query).
            $formularios = Formulario::where('OFICINA_ID', Auth::user()->OFICINA_ID)->get();
            // Retornamos la vista con los datos
            return view('sia2.activos.modformularios.index', compact('formularios'));
        } catch (Exception $ex) {
            // Retornamos la vista con el mensaje de error (concatenar mensaje con $ex para obtener detalles DEBUG)
            return redirect()->back()->with('error', 'Ha ocurrido un error al cargar los formularios');
        }
    }

    /**
     * Retrieves filtered data for the formularios.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function getFilteredData(Request $request)
    {
        $oficinaId = Auth::user()->OFICINA_ID;

        $query = Formulario::where('OFICINA_ID', $oficinaId);

        if ($request->filled('FORMULARIO_NOMBRE')) {
            $query->where('FORMULARIO_NOMBRE', 'like', '%' . $request->FORMULARIO_NOMBRE . '%');
        }

        if ($request->filled('FORMULARIO_TIPO')) {
            $query->where('FORMULARIO_TIPO', $request->FORMULARIO_TIPO);
        }

        $formularios = $query->get();

        return view('sia2.activos.modformularios.index', compact('formularios'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Manejo de excepciones
        try {
            // Retornamos la vista
            return view('sia2.activos.modformularios.create');
        } catch (Exception $ex) {
            // Retornamos la vista con el mensaje de error (concatenar mensaje con $ex para obtener detalles DEBUG)
            return redirect()->back()->with('error', 'Ha ocurrido un error al cargar el formulario');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // manejo de excepciones
        try
        {
            // Reglas de validación
            $validator = Validator::make($request->all(), [
                'FORMULARIO_NOMBRE' => 'required|string|max:128',
                'FORMULARIO_TIPO' => 'required|string|max:128',
            ],[
                'required' => 'El campo :attribute es requerido',
                'string' => 'El campo :attribute debe ser un texto',
                'max' => 'El campo :attribute no debe exceder los :max caracteres',
            ]);

            $validator->after(function ($validator) use ($request) {
                $exists = Formulario::where([
                    'OFICINA_ID' => Auth::user()->OFICINA_ID,
                    'FORMULARIO_NOMBRE' => $request->input('FORMULARIO_NOMBRE'),
                    'FORMULARIO_TIPO' => $request->input('FORMULARIO_TIPO'),
                ])->exists();

                if ($exists) {
                    $validator->errors()->add('FORMULARIO_NOMBRE', 'Este formulario ya existe en su dirección regional con este tipo.');
                    $validator->errors()->add('FORMULARIO_TIPO', 'Este tipo de formulario ya existe en su dirección regional para el nombre ingresado.');
                }
            });


            // Validacion y redireccion con mensajes de error
            if ($validator->fails())
            {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            else
            {
                // Creamos el formulario
                Formulario::create([
                    'FORMULARIO_NOMBRE' => $request->FORMULARIO_NOMBRE,
                    'FORMULARIO_TIPO' => $request->FORMULARIO_TIPO,
                    'OFICINA_ID' => Auth::user()->OFICINA_ID,
                ]);
                // Retornamos la vista con el mensaje de exito
                return redirect()->route('formularios.index')->with('success', 'Formulario creado exitosamente');
            }
        }
        catch(Exception $ex)
        {
            // Retornamos la vista con el mensaje de error (concatenar mensaje con $ex para obtener detalles DEBUG)
            return redirect()->route('formularios.index')->with('error', 'Ha ocurrido un error al crear el formulario');
        }
    }

    // show method
    public function show(string $id)
    {
        // Manejo de excepciones
        try {
            // Obtenemos el formulario
            $formulario = Formulario::findOrFail($id);
            // Retornamos la vista con los datos
            return view('sia2.activos.modformularios.show', compact('formulario'));
        } catch (Exception $ex) {
            // Retornamos la vista con el mensaje de error (concatenar mensaje con $ex para obtener detalles DEBUG)
            return redirect()->back()->with('error', 'Ha ocurrido un error al cargar el formulario seleccionado');
        }
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // Manejo de excepciones
        try {
            // Obtenemos el formulario
            $formulario = Formulario::findOrFail($id);
            // Retornamos la vista con los datos
            return view('sia2.activos.modformularios.edit', compact('formulario'));
        }
        catch (Exception $ex)
        {
            // Retornamos la vista con el mensaje de error (concatenar mensaje con $ex para obtener detalles DEBUG)
            return redirect()->back()->with('error', 'Ha ocurrido un error al cargar el formulario seleccionado');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Manejo de excepciones
        try
        {
            // Reglas de validación
            $validator = Validator::make($request->all(), [
                'FORMULARIO_NOMBRE' => 'required|string|max:128',
                'FORMULARIO_TIPO' => 'required|string|max:128',
            ],[
                'required' => 'El campo :attribute es requerido',
                'string' => 'El campo :attribute debe ser un texto',
                'max' => 'El campo :attribute no debe exceder los :max caracteres',
            ]);

            $validator->after(function ($validator) use ($request, $id) {
                $exists = Formulario::where([
                    'OFICINA_ID' => Auth::user()->OFICINA_ID,
                    'FORMULARIO_NOMBRE' => $request->input('FORMULARIO_NOMBRE'),
                    'FORMULARIO_TIPO' => $request->input('FORMULARIO_TIPO'),
                ])->where('FORMULARIO_ID', '!=', $id)->exists();

                if ($exists) {
                    $validator->errors()->add('FORMULARIO_NOMBRE', 'Este formulario ya existe en su dirección regional con este tipo.');
                    $validator->errors()->add('FORMULARIO_TIPO', 'Este tipo de formulario ya existe en su dirección regional para el nombre ingresado.');
                }
            });



            // Validacion y redireccion con mensajes de error
            if ($validator->fails())
            {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            else
            {
                try
                {
                    // Obtenemos el formulario
                    $formulario = Formulario::findOrFail($id);
                    // Actualizamos el formulario
                    $formulario->update([
                        'FORMULARIO_NOMBRE' => $request->FORMULARIO_NOMBRE,
                        'FORMULARIO_TIPO' => $request->FORMULARIO_TIPO,
                        'OFICINA_ID' => Auth::user()->OFICINA_ID,
                    ]);
                    // Retornamos la vista con el mensaje de exito
                    return redirect()->route('formularios.index')->with('success', 'Formulario actualizado exitosamente');
                }
                catch(ModelNotFoundException $ex)
                {
                    // Retornamos la vista con el mensaje de error (concatenar mensaje con $ex para obtener detalles DEBUG)
                    return redirect()->route('formularios.index')->with('error', 'Ha ocurrido un error al actualizar el formulario');
                }
            }
        }
        catch (Exception $ex)
        {
            // Retornamos la vista con el mensaje de error (concatenar mensaje con $ex para obtener detalles DEBUG)
            return redirect()->route('formularios.index')->with('error', 'Ha ocurrido un error al actualizar el formulario');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // try catch para manejo de excepciones
        try
        {
            // Obtenemos el formulario
            $formulario = Formulario::findOrFail($id);
            // Eliminamos el formulario
            $formulario->delete();
            // Retornamos la vista con el mensaje de exito
            return redirect()->route('formularios.index')->with('success', 'Formulario eliminado exitosamente');
        }
        catch(ModelNotFoundException $ex)
        {
            // Retornamos la vista con el mensaje de error (concatenar mensaje con $ex para obtener detalles DEBUG)
            return redirect()->back()->with('error', 'Ha ocurrido un error al eliminar el formulario');
        }
        catch(Exception $ex)
        {
            // Retornamos la vista con el mensaje de error (concatenar mensaje con $ex para obtener detalles DEBUG)
            return redirect()->back()->with('error', 'Ha ocurrido un error al eliminar el formulario');
        }
    }

    // Funcion para agregar un formulario al carrito
    public function addToCart(Formulario $formulario){

        // Creamos la instancia del carrito de formularios
        $carritoFormularios = Cart::instance('carrito_formularios');

        // Agregamos el formulario al carrito
        $carritoFormularios->add($formulario, 1);

        // Redireccionamos a la vista que mostrará el carrito
        return redirect()->back()->with('success', 'Formulario agregado exitosamente');
    }



    // Función para mostrar el contenido del carrito en la vista createSolicitud
    public function showCart()
    {
        // Obtén el contenido del carrito de formularios
        $cartItems = Cart::instance('carrito_formularios')->content();

        // Retornamos la vista de createSolicitud con el contenido del carrito
        return route('formularios.create', compact('cartItems'));
    }

    // Funcion para eliminar un formulario del carrito
    public function deleteFromCart($rowId){
        // Cargamos la instancia del carrito de formularios
        $carritoFormularios = Cart::instance('carrito_formularios');
        // Eliminamos el formulario del carrito
        $carritoFormularios->remove($rowId);
        // Redireccionamos a la vista del carrito
        return redirect()->back()->with('success', 'Formulario eliminado exitosamente');
    }
}
