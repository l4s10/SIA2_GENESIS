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
use App\Models\RevisionSolicitud;
use App\Models\SolicitudSala;

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

            // Recuperar la tabla intermedia de la solicitud con las salas asociadas
            $tablaIntermedia = SolicitudSala::where('SOLICITUD_ID', $solicitud->SOLICITUD_ID)->first();

            // Verificar si se encontró la tabla intermedia
            if ($tablaIntermedia) {
                $salaAsignada = Sala::where('SALA_ID', $tablaIntermedia->SOLICITUD_SALA_ID_ASIGNADA)->first();
            } else {
                $salaAsignada = null;
            }

            // Retornar la vista con la solicitud
            return view('sia2.solicitudes.salas.show', compact('solicitud', 'salaAsignada'));
        }catch(Exception $e){
            return redirect()->back()->with('error', 'Error al cargar la pagina, vuelva a intentarlo mas tarde.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try
        {
            $solicitud = Solicitud::findOrFail($id);

            $salas = Sala::where('OFICINA_ID', Auth::user()->OFICINA_ID)->get();

            // Recuperar la tabla intermedia de la solicitud con las salas asociadas
            $tablaIntermedia = SolicitudSala::where('SOLICITUD_ID', $solicitud->SOLICITUD_ID)->first();

            // Verificar si se encontró la tabla intermedia
            if ($tablaIntermedia) {
                $salaAsignada = Sala::where('SALA_ID', $tablaIntermedia->SOLICITUD_SALA_ID_ASIGNADA)->first();
            } else {
                $salaAsignada = null;
            }

            return view('sia2.solicitudes.salas.edit', compact('solicitud', 'salas', 'salaAsignada'));
        }
        catch(Exception $e)
        {
            return redirect()->back()->with('error', 'Error al cargar la página, vuelva a intentarlo más tarde.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // try-catch
        try
        {
            // Validar los datos del formulario de edición de la solicitud
            $validator = Validator::make($request->all(),[
                // PARA ASIGNACION
                // 'SOLICITUD_ESTADO' => 'required|string|max:255|in:INGRESADO,EN REVISION,APROBADO,RECHAZADO,TERMINADO',
                'SOLICITUD_FECHA_HORA_INICIO_ASIGNADA' => 'required|date',
                'SOLICITUD_FECHA_HORA_TERMINO_ASIGNADA' => 'required|date|after:SOLICITUD_FECHA_HORA_INICIO_ASIGNADA',
                // PARA REVISION
                'SOLICITUD_SALA_ID_ASIGNADA' => 'required|exists:salas,SALA_ID', // Asegura que la sala asignada exista en la base de datos
                'REVISION_SOLICITUD_OBSERVACION' => 'required|string|max:255',
                'autorizar.*' => 'required|numeric|min:0', // Asegura que todos los valores en el array sean numéricos y no negativos
            ], [
                //Mensajes de error
                'required' => 'El campo :attribute es requerido.',
                'date' => 'El campo :attribute debe ser una fecha.',
                'after' => 'El campo :attribute debe ser una fecha posterior a la fecha de inicio solicitada.',
                'string' => 'El campo :attribute debe ser una cadena de caracteres.',
                'exists' => 'El campo :attribute no existe en la base de datos.',
                'in' => 'El campo :attribute debe ser uno de los siguientes valores: INGRESADO, EN REVISION, APROBADO, RECHAZADO.',
                'numeric' => 'El campo :attribute debe ser un número.',
                'min' => 'El campo :attribute debe ser un número positivo.',
            ]);

            // Si la validación falla, se redirecciona al formulario con los errores
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            // Recuperar la solicitud que solo tenga salas asociadas
            $solicitud = Solicitud::has('salas')->findOrFail($id);

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

            // actualizar la solicitud
            $solicitud->update([
                // 'SOLICITUD_ESTADO' => $request->input('SOLICITUD_ESTADO'),
                'SOLICITUD_FECHA_HORA_INICIO_ASIGNADA' => $request->input('SOLICITUD_FECHA_HORA_INICIO_ASIGNADA'),
                'SOLICITUD_FECHA_HORA_TERMINO_ASIGNADA' => $request->input('SOLICITUD_FECHA_HORA_TERMINO_ASIGNADA'),
            ]);

            // Llamar a la funcion para autorizar la sala
            $this->autorizarSala($request, $solicitud);

            // Llamar a la funcion para autorizar los equipos
            $this->autorizarEquipos($request, $solicitud);

            // Llamar a la funcion para crear la revision de la solicitud
            $this->createRevisionSolicitud($request, $solicitud);

            //redireccionar a la vista de solicitudes con un mensaje de éxito
            return redirect()->route('solicitudes.salas.index')->with('success', 'Solicitud actualizada correctamente.');

        }catch(Exception $e){
            return redirect()->back()->with('error', 'Error al cargar la pagina, vuelva a intentarlo mas tarde.');
        }
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


    /**
    * Crear una nueva revision para la solicitud.
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

    /**
     * Autorizar la sala para la solicitud.
     */
    private function autorizarSala(Request $request, Solicitud $solicitud){
        try {
            // Recuperar el ID de la sala a asignar desde el input
            $salaId = $request->input('SOLICITUD_SALA_ID_ASIGNADA');

            if (!empty($salaId)) {
                // Actualizar la sala asignada
                SolicitudSala::where('SOLICITUD_ID', $solicitud->SOLICITUD_ID)->update(['SOLICITUD_SALA_ID_ASIGNADA' => $salaId]);
            }
        } catch (Exception $e) {
            // Considera loguear el error para depuración
            return redirect()->back()->with('error', 'Error al autorizar la sala, vuelva a intentarlo más tarde.');
        }
    }


    // Funcion para autorizar los equipos si corresponde
    private function autorizarEquipos(Request $request, Solicitud $solicitud){
        try {
            $autorizaciones = $request->input('autorizar', []); // Obtiene el array de autorizaciones o un array vacío si no hay nada

            foreach ($autorizaciones as $tipoEquipoId => $cantidadAutorizada) {
                $solicitud->equipos()->updateExistingPivot($tipoEquipoId, ['SOLICITUD_EQUIPOS_CANTIDAD_AUTORIZADA' => $cantidadAutorizada]);
            }
        } catch (Exception $e) {
            // Considera loguear el error para depuración
            return redirect()->back()->with('error', 'Error al autorizar los equipos, vuelva a intentarlo más tarde.');
        }
    }

}
