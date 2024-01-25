<?php

namespace App\Http\Controllers\Solicitud;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Auth;
use Gloudemans\Shoppingcart\Facades\Cart;

//Importamos modelos
use App\Models\Solicitud;
use App\Models\Formulario;

class SolicitudFormulariosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try
        {
            // Query que a través de la relación has() filtra las solicitudes que SOLO tengan formularios asociados
            $solicitudes = Solicitud::has('formularios')->get();
        }
        catch(Exception $e)
        {
            return redirect()->back()->with('error', 'Error al cargar las solicitudes.');
        }

        //Retornar la vista con las solicitudes
        return view('sia2.solicitudes.formularios.index', compact('solicitudes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try
        {
            // Función que lista formularios basados en la OFICINA_ID del usuario logueado
            $formularios = Formulario::where('OFICINA_ID', Auth::user()->OFICINA_ID)->get();

            // Obtener los elementos del carrito, creando la instancia si no existe
            $cartItems = Cart::instance('carrito_formularios')->content();
        }
        catch(Exception $e)
        {
            return redirect()->back()->with('error', 'Error al cargar los formularios.');
        }
        // Retornar la vista del formulario con los materiales y el carrito
        return view('sia2.solicitudes.formularios.create', compact('formularios', 'cartItems'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Valida los datos del formulario según tus necesidades
        $request->validate([
            'SOLICITUD_MOTIVO' => 'required|string|max:255',
            'SOLICITUD_FECHA_HORA_INICIO_SOLICITADA' => 'required|date',
            'SOLICITUD_FECHA_HORA_TERMINO_SOLICITADA' => 'required|date|after:SOLICITUD_FECHA_HORA_INICIO_SOLICITADA',
            // Agrega otras validaciones según tus campos
        ]);

        // Crea la solicitud
        $solicitud = Solicitud::create([
            'USUARIO_id' => Auth::user()->id,
            'SOLICITUD_MOTIVO' => $request->input('SOLICITUD_MOTIVO'),
            'SOLICITUD_ESTADO' => 'INGRESADO', // Valor predeterminado
            'SOLICITUD_FECHA_HORA_INICIO_SOLICITADA' => $request->input('SOLICITUD_FECHA_HORA_INICIO_SOLICITADA'),
            'SOLICITUD_FECHA_HORA_TERMINO_SOLICITADA' => $request->input('SOLICITUD_FECHA_HORA_TERMINO_SOLICITADA'),
            // Otros campos...
        ]);

        // Agrega los formularios a la solicitud instanciando el carrito correspondiente
        foreach (Cart::instance('carrito_formularios')->content() as $item)
        {
            $formulario = Formulario::find($item->id);

            // Agrega el formulario a la solicitud
            $solicitud->formularios()->attach($formulario, [
                'SOLICITUD_FORMULARIOS_CANTIDAD' => $item->qty,
            ]);
        }

        // Elimina los elementos del carrito limpiandolo
        Cart::instance('carrito_formularios')->destroy();

        // Redirecciona a la vista de solicitudes
        return redirect()->route('solicitudesformularios.index')->with('success', 'Solicitud creada correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Recuperar la solicitud
        $solicitud = Solicitud::has('formularios')->findOrFail($id);

        return view('sia2.solicitudes.formularios.show', compact('solicitud'));
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
    public function destroy($id)
    {
        try
        {
            // Recuperar la solicitud
            $solicitud = Solicitud::findOrFail($id);

            // Eliminar los formularios asociados a la solicitud
            $solicitud->formularios()->detach();

            // Eliminar la solicitud
            $solicitud->delete();
        }
        catch(Exception $e)
        {
            return redirect()->back()->with('error', 'Error al eliminar la solicitud.');
        }

        // Redireccionar a la vista de solicitudes
        return redirect()->route('solicitudesformularios.index')->with('success', 'Solicitud eliminada correctamente.');
    }
}
