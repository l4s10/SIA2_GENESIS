<?php

namespace App\Http\Controllers\Solicitud;

// Importar elementos necesarios
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;
use Illuminate\Support\Facades\Auth;
use Gloudemans\Shoppingcart\Facades\Cart;

// Importar modelos
use App\Models\Solicitud;
use App\Models\Sala;
use App\Models\TipoEquipo;

class SolicitudSalasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // try-catch para el manejo de excepciones
        try {
            // Query que a través de la relación has() filtra las solicitudes que SOLO tengan salas asociadas
            $solicitudes = Solicitud::has('salas')->get();

            // Retornar la vista con las solicitudes
            return view('sia2.solicitudes.salas.index', compact('solicitudes'));
        } catch (Exception $e) {
            // Manejar excepciones si es necesario
            return redirect()->back()->with('error', 'Error al cargar las solicitudes.');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try{
            // Función que lista salas basados en la OFICINA_ID del usuario logueado
            $salas = Sala::where('OFICINA_ID', Auth::user()->OFICINA_ID)->get();

            // En caso de que el funcionario solicite un equipo, se debe listar los equipos disponibles.
            $tiposEquipos = TipoEquipo::where('OFICINA_ID', Auth::user()->OFICINA_ID)->get();

            // Llamamos a la instancia de carrito pero solo para equipos.
            $cartItems = Cart::instance('carrito_equipos')->content();

            // Retornar la vista del formulario con las salas, los equipos y el carrito
            return view('sia2.solicitudes.salas.create', compact('salas', 'tiposEquipos', 'cartItems'));
        }catch(Exception $e){
            return redirect()->route('solicitudes.salas.index')->with('error', 'Error al cargar la pagina, vuelva a intentarlo mas tarde.'. $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try{
            // Validar los datos del formulario de solicitud de salas.
            $validator = Validator::make($request->all(),[
                'SOLICITUD_MOTIVO' => 'required|string|max:255',
                'SOLICITUD_FECHA_HORA_INICIO_SOLICITADA' => 'required|date',
                'SOLICITUD_FECHA_HORA_TERMINO_SOLICITADA' => 'required|date|after:SOLICITUD_FECHA_HORA_INICIO_SOLICITADA',
                'SALA_ID' => 'required|exists:salas,SALA_ID',
            ], [
                //Mensajes de error
                'required' => 'El campo :attribute es requerido.',
                'date' => 'El campo :attribute debe ser una fecha.',
                'after' => 'El campo :attribute debe ser una fecha posterior a la fecha de inicio solicitada.',
                'string' => 'El campo :attribute debe ser una cadena de caracteres.',
                'exists' => 'El campo :attribute no existe en la base de datos.'
            ]);

            // Si la validación falla, se redirecciona al formulario con los errores
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            // Si la verificacion es exitosa, se crea la solicitud
            $solicitud = Solicitud::create([
                'USUARIO_id' => Auth::user()->id, // Asigna el ID del usuario autenticado
                'SOLICITUD_MOTIVO' => $request->input('SOLICITUD_MOTIVO'),
                'SOLICITUD_ESTADO' => 'INGRESADO', // Valor predeterminado
                'SOLICITUD_FECHA_HORA_INICIO_SOLICITADA' => $request->input('SOLICITUD_FECHA_HORA_INICIO_SOLICITADA'),
                'SOLICITUD_FECHA_HORA_TERMINO_SOLICITADA' => $request->input('SOLICITUD_FECHA_HORA_TERMINO_SOLICITADA'),
            ]);

            // Si la solicitud se crea correctamente, verificamos que hayan equipos pedidos (este es un caso opcional en el que los funcionarios pueden solicitar equipos junto a las salas)
            if(Cart::instance('carrito_equipos')->count() > 0){
                // Recorremos los elementos del carrito
                foreach(Cart::instance('carrito_equipos')->content() as $cartItem){
                    // Creamos la relación entre la solicitud y el equipo
                    $solicitud->equipos()->attach($cartItem->id, ['SOLICITUD_EQUIPOS_CANTIDAD' => $cartItem->qty]);
                }
                // Vaciamos el carrito
                Cart::instance('carrito_equipos')->destroy();
            }

            // Creamos la relación entre la solicitud y la sala solicitada
            $solicitud->salas()->attach($request->input('SALA_ID'));

            // Redireccionamos a la vista de solicitudes con un mensaje de éxito.
            return redirect()->route('solicitudes.salas.index')->with('success', 'Solicitud creada correctamente.');
        }catch(Exception $e){
            return redirect()->back()->with('error', 'Error al cargar la pagina, vuelva a intentarlo mas tarde.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try{
            // Recuperar la solicitud con sus salas asociadas
            $solicitud = Solicitud::findOrFail($id);

            // Retornar la vista con la solicitud
            return view('sia2.solicitudes.salas.show', compact('solicitud'));
        }catch(Exception $e){
            return redirect()->back()->with('error', 'Error al cargar la pagina, vuelva a intentarlo mas tarde.');
        }
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
        try{
            // Recuperar la solicitud
            $solicitud = Solicitud::findOrFail($id);

            // Eliminar la relación entre la solicitud y las salas y los equipos
            $solicitud->salas()->detach();
            //verificar si tiene equipos asociados (ya que es opcional la eleccion de equipos)
            if($solicitud->equipos()->count() > 0){
                $solicitud->equipos()->detach();
            }

            // Eliminar la solicitud
            $solicitud->delete();

            // Redireccionar a la vista de solicitudes con un mensaje de éxito
            return redirect()->route('solicitudes.salas.index')->with('success', 'Solicitud eliminada correctamente.');
        }catch(Exception $e){
            return redirect()->back()->with('error', 'Error al cargar la pagina, vuelva a intentarlo mas tarde.');
        }
    }
}
