<?php

// namespace: Define el espacio de nombres en el que se encuentra el controlador
namespace App\Http\Controllers\Solicitud;

// Importar FACADES y elementos necesarios
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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
        try {
            // Query que a través de la relación has() filtra las solicitudes que SOLO tengan materiales asociados
            $solicitudes = Solicitud::has('materiales')->get();
        } catch (Exception $e) {
            // Manejar excepciones si es necesario
            return redirect()->back()->with('error', 'Error al cargar las solicitudes.');
        }

        // Retornar la vista con las solicitudes
        return view('sia2.solicitudes.materiales.index', compact('solicitudes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            // Función que lista materiales basados en la OFICINA_ID del usuario logueado
            $materiales = Material::where('OFICINA_ID', Auth::user()->OFICINA_ID)->get();

            // Obtener los elementos del carrito
            $cartItems = Cart::instance('carrito_materiales')->content();

        } catch (Exception $e) {
            // Manejar excepciones si es necesario
            return redirect()->route('solicitudes.index')->with('error', 'Error al cargar los materiales.');
        }

        // Retornar la vista del formulario con los materiales y el carrito
        return view('sia2.solicitudes.materiales.create', compact('materiales', 'cartItems'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Valida los datos del formulario
        $request->validate([
            'SOLICITUD_MOTIVO' => 'required|string|max:255',
            'SOLICITUD_FECHA_HORA_INICIO_SOLICITADA' => 'required|date',
            'SOLICITUD_FECHA_HORA_TERMINO_SOLICITADA' => 'required|date|after:SOLICITUD_FECHA_HORA_INICIO_SOLICITADA',
        ],[
            //Mensajes de error
            'required' => 'El campo :attribute es obligatorio.',
            'date' => 'El campo :attribute debe ser una fecha válida.',
            'after' => 'La fecha de término debe ser posterior a la fecha de inicio.',
            'string' => 'El campo :attribute debe ser una cadena de caracteres.'
        ]);

        // Crea la solicitud
        $solicitud = Solicitud::create([
            'USUARIO_id' => Auth::user()->id, // Asigna el ID del usuario autenticado
            'SOLICITUD_MOTIVO' => $request->input('SOLICITUD_MOTIVO'),
            'SOLICITUD_ESTADO' => 'INGRESADO', // Valor predeterminado
            'SOLICITUD_FECHA_HORA_INICIO_SOLICITADA' => $request->input('SOLICITUD_FECHA_HORA_INICIO_SOLICITADA'),
            'SOLICITUD_FECHA_HORA_TERMINO_SOLICITADA' => $request->input('SOLICITUD_FECHA_HORA_TERMINO_SOLICITADA'),
        ]);

        // Adjunta los materiales a la solicitud desde el carrito de compras correspondiente
        foreach (Cart::instance('carrito_materiales')->content() as $cartItem) {
            $material = Material::find($cartItem->id);

            // Agrega el material a la solicitud con la cantidad del carrito
            $solicitud->materiales()->attach($material, [
                'SOLICITUD_MATERIAL_CANTIDAD' => $cartItem->qty
            ]);
        }

        // Limpia el carrito después de agregar los materiales a la solicitud
        Cart::instance('carrito_materiales')->destroy();

        // Puedes agregar un mensaje de éxito si lo deseas
        return redirect()->route('solicitudesmateriales.index')->with('success', 'Solicitud creada exitosamente');

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Recuperar la solicitud con sus materiales asociados
        $solicitud = Solicitud::with('materiales.tipoMaterial')->findOrFail($id);

        return view('sia2.solicitudes.materiales.show', compact('solicitud'));
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
            // Busca la solicitud
            $solicitud = Solicitud::findOrFail($id);

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
