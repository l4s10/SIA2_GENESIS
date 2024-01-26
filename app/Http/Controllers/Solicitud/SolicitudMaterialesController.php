<?php

// namespace: Define el espacio de nombres en el que se encuentra el controlador
namespace App\Http\Controllers\Solicitud;

// Importar FACADES y elementos necesarios
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;
use Illuminate\Support\Facades\Auth;
use Gloudemans\Shoppingcart\Facades\Cart;

// Importar modelos
use App\Models\Solicitud;
use App\Models\Material;

class SolicitudMaterialesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Try-catch para el manejo de excepciones
        try {
            // Query que a través de la relación has() filtra las solicitudes que SOLO tengan materiales asociados
            $solicitudes = Solicitud::has('materiales')->get();

            // Retornar la vista con las solicitudes
            return view('sia2.solicitudes.materiales.index', compact('solicitudes'));
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
        // Try-catch para el manejo de excepciones
        try {
            // Función que lista materiales basados en la OFICINA_ID del usuario logueado
            $materiales = Material::where('OFICINA_ID', Auth::user()->OFICINA_ID)->get();

            // Obtener los elementos del carrito
            $cartItems = Cart::instance('carrito_materiales')->content();

            // Retornar la vista del formulario con los materiales y el carrito
            return view('sia2.solicitudes.materiales.create', compact('materiales', 'cartItems'));
        } catch (Exception $e) {
            // Manejar excepciones si es necesario
            return redirect()->route('solicitudes.index')->with('error', 'Error al cargar los materiales.');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try{
            // Valida los datos del formulario de solicitud de materiales.
            $validator = Validator::make($request->all(),[
                'SOLICITUD_MOTIVO' => 'required|string|max:255',
                'SOLICITUD_FECHA_HORA_INICIO_SOLICITADA' => 'required|date',
                'SOLICITUD_FECHA_HORA_TERMINO_SOLICITADA' => 'required|date|after:SOLICITUD_FECHA_HORA_INICIO_SOLICITADA',
            ], [
                //Mensajes de error
                'required' => 'El campo :attribute es requerido.',
                'date' => 'El campo :attribute debe ser una fecha.',
                'after' => 'El campo :attribute debe ser una fecha posterior a la fecha de inicio solicitada.',
                'string' => 'El campo :attribute debe ser una cadena de caracteres.'
            ]);

            // Si la validación falla, redirige al formulario con los errores y el input antiguo
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            // Si la validacion es exitosa, crea y almacena la solicitud
            $solicitud = Solicitud::create([
                'USUARIO_id' => Auth::user()->id, // Asigna el ID del usuario autenticado
                'SOLICITUD_MOTIVO' => $request->input('SOLICITUD_MOTIVO'),
                'SOLICITUD_ESTADO' => 'INGRESADO', // Valor predeterminado
                'SOLICITUD_FECHA_HORA_INICIO_SOLICITADA' => $request->input('SOLICITUD_FECHA_HORA_INICIO_SOLICITADA'),
                'SOLICITUD_FECHA_HORA_TERMINO_SOLICITADA' => $request->input('SOLICITUD_FECHA_HORA_TERMINO_SOLICITADA'),
            ]);

            //Si se crea la solicitud correctamente, se asocia los materiales del carrito a la solicitud a traves de la relacion creada en el modelo.
            if($solicitud){
                // Llamamos a la instancia del carrito de materiales
                foreach (Cart::instance('carrito_materiales')->content() as $cartItem) {
                    $material = Material::find($cartItem->id);

                    // Agrega el material a la solicitud con la cantidad del carrito
                    $solicitud->materiales()->attach($material, [
                        'SOLICITUD_MATERIAL_CANTIDAD' => $cartItem->qty
                    ]);
                }
                // Limpia el carrito después de agregar los materiales a la solicitud
                Cart::instance('carrito_materiales')->destroy();
            }
            // Redireccion a la vista index de solicitud de materiales, con el mensaje de exito.
            return redirect()->route('solicitudesmateriales.index')->with('success', 'Solicitud creada exitosamente');
        }catch(Exception $e){
            // Manejo de excepciones
            return redirect()->route('solicitudesmateriales.index')->with('error', 'Error al crear la solicitud.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            // Recuperar la solicitud con sus materiales asociados
            $solicitud = Solicitud::has('materiales')->findOrFail($id);
            // Retornar la vista con la solicitud
            return view('sia2.solicitudes.materiales.show', compact('solicitud'));
        } catch (Exception $e) {
            // Manejar excepciones si la solicitud no se encuentra o hay algún error manejable
            return redirect()->route('solicitudesmateriales.index')->with('error', 'Error al mostrar la solicitud.');
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
        //Try catch
        try {
            // Busca la solicitud con sus materiales asociados
            $solicitud = Solicitud::has('materiales')->findOrFail($id);

            //Eliminar registros asociados a esta solicitud en la tabla solicitud_material (para no tener problemas de parent row not found)
            $solicitud->materiales()->detach();

            // Elimina la solicitud
            $solicitud->delete();

            // Puedes agregar un mensaje de éxito si lo deseas
            return redirect()->route('solicitudesmateriales.index')->with('success', 'Solicitud eliminada exitosamente');
        } catch (Exception $e) {
            // Manejar excepciones si es necesario
            return redirect()->route('solicitudesmateriales.index')->with('error', 'Error al eliminar la solicitud.');
        }
    }
}
