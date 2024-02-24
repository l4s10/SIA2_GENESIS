<?php

namespace App\Http\Controllers\Solicitud;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Gloudemans\Shoppingcart\Facades\Cart;
use Exception;
//Importamos modelos
use App\Models\Solicitud;
use App\Models\Formulario;
use App\Models\RevisionSolicitud;

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

            //Retornar la vista con las solicitudes
            return view('sia2.solicitudes.formularios.index', compact('solicitudes'));
        }
        catch(Exception $e)
        {
            return redirect()->back()->with('error', 'Error al cargar las solicitudes.');
        }
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

            // Retornar la vista del formulario con los materiales y el carrito
            return view('sia2.solicitudes.formularios.create', compact('formularios', 'cartItems'));
        }
        catch(Exception $e)
        {
            return redirect()->back()->with('error', 'Error al cargar los formularios.');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try{
            // Valida los datos del formulario de solicitud de formularios.
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

            // Validar que la instancia del carrito no esté vacía
            if (Cart::instance('carrito_formularios')->count() === 0) {
                return redirect()->back()->with('error', 'El carrito de formularios está vacío.');
            }

            // Si la validación falla, redirecciona al formulario con los errores y el input antiguo
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            // Crea la solicitud
            $solicitud = Solicitud::create([
                'USUARIO_id' => Auth::user()->id,
                'SOLICITUD_MOTIVO' => $request->input('SOLICITUD_MOTIVO'),
                'SOLICITUD_ESTADO' => 'INGRESADO', // Valor predeterminado
                'SOLICITUD_FECHA_HORA_INICIO_SOLICITADA' => $request->input('SOLICITUD_FECHA_HORA_INICIO_SOLICITADA'),
                'SOLICITUD_FECHA_HORA_TERMINO_SOLICITADA' => $request->input('SOLICITUD_FECHA_HORA_TERMINO_SOLICITADA'),
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
            return redirect()->route('solicitudes.formularios.index')->with('success', 'Solicitud creada correctamente.');
        }catch(Exception $e){
            // Manejo de excepciones
            return redirect()->route('solicitudes.formularios.index')->with('error', 'Error al crear la solicitud.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try{
            // Recuperar la solicitud
            $solicitud = Solicitud::has('formularios')->findOrFail($id);
            // Retornar la vista con la solicitud
            return view('sia2.solicitudes.formularios.show', compact('solicitud'));
        }catch(Exception $e){
            // Manejo de excepciones
            return redirect()->route('solicitudes.formularios.index')->with('error', 'Error al cargar la solicitud.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // try-catch
        try{
            // Recuperar la solicitud
            $solicitud = Solicitud::has('formularios')->findOrFail($id);
            // Retornar la vista con la solicitud
            return view('sia2.solicitudes.formularios.edit', compact('solicitud'));
        }catch(Exception $e){
            // Manejo de excepciones
            return redirect()->route('solicitudes.formularios.index')->with('error', 'Error al cargar la solicitud.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Obtener la solicitud a actualizar
        $solicitud = Solicitud::has('formularios')->findOrFail($id);

        // Validar los datos del formulario (valores a recibir: SOLICITUD_ESTADO, SOLICITUD_FECHA_HORA_INICIO_ASIGNADA, SOLICITUD_FECHA_HORA_TERMINO_ASIGNADA, REVISION_SOLICITUD_OBSERVACION, REVISION_SOLICITUD_FECHA_HORA_TRAMITACION)
        $validator = Validator::make($request->all(),[
            //Estados de la solcitud: INGRESADO, EN REVISION, APROBADO, RECHAZADO, TERMINADO
            // 'SOLICITUD_ESTADO' => 'required|string|max:255|in:INGRESADO,EN REVISION,APROBADO,RECHAZADO,TERMINADO',
            'SOLICITUD_FECHA_HORA_INICIO_ASIGNADA' => 'required|date',
            'SOLICITUD_FECHA_HORA_TERMINO_ASIGNADA' => 'required|date|after:SOLICITUD_FECHA_HORA_INICIO_ASIGNADA',
            'REVISION_SOLICITUD_OBSERVACION' => 'required|string|max:255',
        ], [
            //Mensajes de error
            'required' => 'El campo :attribute es requerido.',
            'date' => 'El campo :attribute debe ser una fecha.',
            'after' => 'El campo :attribute debe ser una fecha posterior a la fecha de inicio asignada.',
            'string' => 'El campo :attribute debe ser una cadena de caracteres.',
            'in' => 'El campo :attribute debe ser uno de los siguientes valores: INGRESADO, EN REVISION, APROBADO, RECHAZADO, TERMINADO',
        ]);

        // Si la validación falla, redirecciona al formulario con los errores y el input antiguo
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Determinar la acción basada en el botón presionado
        switch ($request->input('action')) {
            case 'guardar':
                // Lógica para guardar cambios
                $solicitud->update(['SOLICITUD_ESTADO' => 'EN REVISION']);
            break;

            case 'finalizar_revision':
                // Lógica para finalizar la revisión
                $solicitud->update(['SOLICITUD_ESTADO' => 'AUTORIZADO']);
            break;

            case 'rechazar':
                // Lógica para rechazar la solicitud
                $solicitud->update(['SOLICITUD_ESTADO' => 'RECHAZADO']);
            break;

            // default:
                // Lógica por defecto o para casos no contemplados
                // break;
        }
        // Actualizar la solicitud
        $solicitud->update([
            // 'SOLICITUD_ESTADO' => $request->input('SOLICITUD_ESTADO'),
            'SOLICITUD_FECHA_HORA_INICIO_ASIGNADA' => $request->input('SOLICITUD_FECHA_HORA_INICIO_ASIGNADA'),
            'SOLICITUD_FECHA_HORA_TERMINO_ASIGNADA' => $request->input('SOLICITUD_FECHA_HORA_TERMINO_ASIGNADA'),
        ]);

        // Llamar a la funcion createRevisionSolicitud para crear la revision de la solicitud
        $this->createRevisionSolicitud($request, $solicitud);

        // Redireccionar a la vista de solicitudes si ambas cosas se realizaron correctamente
        return redirect()->route('solicitudes.formularios.index')->with('success', 'Solicitud actualizada correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try
        {
            // Recuperar la solicitud
            $solicitud = Solicitud::has('formularios')->findOrFail($id);

            // Eliminar los formularios asociados a la solicitud
            $solicitud->formularios()->detach();

            // Eliminar la solicitud
            $solicitud->delete();

            // Redireccionar a la vista de solicitudes
            return redirect()->route('solicitudes.formularios.index')->with('success', 'Solicitud eliminada correctamente.');
        }
        catch(Exception $e)
        {
            // Manejo de excepciones
            return redirect()->back()->with('error', 'Error al eliminar la solicitud.');
        }
    }

    /**
    * Create a new revision for the solicitud.
    */
    private function createRevisionSolicitud(Request $request, Solicitud $solicitud)
    {
        // try-catch
        try
        {
            // Crear la revisión de la solicitud
            RevisionSolicitud::create([
                'USUARIO_id' => Auth::user()->id,
                'SOLICITUD_ID' => $solicitud->SOLICITUD_ID,
                'REVISION_SOLICITUD_OBSERVACION' => $request->input('REVISION_SOLICITUD_OBSERVACION'),
            ]);
        }
        catch(Exception $e)
        {
            // Manejo de excepciones
            return redirect()->route('solicitudes.formularios.index')->with('error', 'Error al crear la revisión de la solicitud.');
        }
    }
}
