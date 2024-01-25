<?php

namespace App\Http\Controllers\Solicitud;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Gloudemans\Shoppingcart\Facades\Cart;
use Exception;
use App\Models\Solicitud;
use App\Models\TipoEquipo;


class SolicitudEquiposController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
            // Query que a traves de la relacion has() filtra las solicitudes que SOLO tengan equipos asociados
            $solicitudes = Solicitud::has('equipos')->get();
        }catch(Exception $e){
            // Manejar excepciones si es necesario
            return redirect()->back()->with('error', 'Error al cargar las solicitudes.');
        }
        // Retornar la vista con las solicitudes
        return view('sia2.solicitudes.equipos.index', compact('solicitudes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try{
            // Funcion que lista tipos de equipos basados en la OFICINA_ID del usuario logueado
            $tiposEquipos = TipoEquipo::where('OFICINA_ID', Auth::user()->OFICINA_ID)->get();
            // Obtener los elementos del carrito
            $cartItems = Cart::instance('carrito_equipos')->content();
        }catch(Exception $e){
            // Manejar excepciones si es necesario
            return redirect()->route('solicitudes.index')->with('error', 'Error al cargar los tipos de equipo.');
        }
        // Retornar la vista del formulario con los tipos de equipo y el carrito\
        return view('sia2.solicitudes.equipos.create', compact('tiposEquipos', 'cartItems'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Valida los datos del formulario según tus necesidades
        $validator = Validator::make($request->all(),[
            'SOLICITUD_MOTIVO' => 'required|string|max:255',
            'SOLICITUD_FECHA_HORA_INICIO_SOLICITADA' => 'required|date',
            'SOLICITUD_FECHA_HORA_TERMINO_SOLICITADA' => 'required|date|after:SOLICITUD_FECHA_HORA_INICIO_SOLICITADA',
            // Agrega otras validaciones según tus campos
        ], [
            //Mensajes de error
            'required' => 'El campo :attribute es requerido.',
            'date' => 'El campo :attribute debe ser una fecha.',
            'after' => 'El campo :attribute debe ser una fecha posterior a la fecha de inicio solicitada.',
            'string' => 'El campo :attribute debe ser una cadena de caracteres.'
        ]);

        // Si la validación falla, redirecciona al formulario con los errores y el input antiguo
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Crea la solicitud
        $solicitud = Solicitud::create([
            'USUARIO_id' => Auth::user()->id, // Asigna el ID del usuario autenticado
            'SOLICITUD_MOTIVO' => $request->input('SOLICITUD_MOTIVO'),
            'SOLICITUD_ESTADO' => 'INGRESADO', // Valor predeterminado
            'SOLICITUD_FECHA_HORA_INICIO_SOLICITADA' => $request->input('SOLICITUD_FECHA_HORA_INICIO_SOLICITADA'),
            'SOLICITUD_FECHA_HORA_TERMINO_SOLICITADA' => $request->input('SOLICITUD_FECHA_HORA_TERMINO_SOLICITADA'),
        ]);

        // Si se crea la solicitud, asociar los equipos del carrito a la solicitud
        if ($solicitud) {
            // Obtener los elementos del carrito
            $cartItems = Cart::instance('carrito_equipos')->content();
            // Recorrer los elementos del carrito
            foreach ($cartItems as $item) {
                // Asociar el equipo a la solicitud
                $solicitud->equipos()->attach($item->id, [
                    'SOLICITUD_EQUIPOS_CANTIDAD' => $item->qty,
                    'SOLICITUD_EQUIPOS_CANTIDAD_AUTORIZADA' => 0 // Valor predeterminado
                ]);
            }
            // Limpiar el carrito
            Cart::instance('carrito_equipos')->destroy();
        }

        // Redireccionar a la vista de solicitudes con un mensaje de éxito
        return redirect()->route('solicitudesequipos.index')->with('success', 'Solicitud creada exitosamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Retornar la vista con la solicitud
        $solicitud = Solicitud::findOrFail($id);
        return view('sia2.solicitudes.equipos.show', compact('solicitud'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try{
            // Obtener la solicitud
            $solicitud = Solicitud::findOrFail($id);
            // Desacoplar los equipos de la solicitud
            $solicitud->equipos()->detach();
            // Eliminar la solicitud
            $solicitud->delete();
        }catch(Exception $e){
            // Manejar excepciones si es necesario
            return redirect()->back()->with('error', 'Error al eliminar la solicitud.');
        }
        // Redireccionar a la vista de solicitudes con un mensaje de éxito
        return redirect()->route('solicitudesequipos.index')->with('success', 'Solicitud eliminada exitosamente');
    }
}
