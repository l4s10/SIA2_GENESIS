<?php

namespace App\Http\Controllers\Solicitud;

// Importar elementos necesarios
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;
use Illuminate\Support\Facades\Auth;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\DB;


// Importar modelos
use App\Models\Solicitud;
use App\Models\Sala;
use App\Models\TipoEquipo;
use App\Models\RevisionSolicitud;
use App\Models\SolicitudSala;
use App\Models\Equipo;
use App\Models\Movimiento;


class SolicitudSalasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            // SI el usuario es ADMINISTRADOR o INFORMATICA, mostrar todas las solicitudes de salas (filtrado por oficina)
            if (Auth::user()->hasRole('ADMINISTRADOR') || Auth::user()->hasRole('INFORMATICA')) {
                // Filtrar por OFICINA_ID del usuario logueado con la relacion solicitante
                $solicitudes = Solicitud::has('salas')
                                    ->whereHas('solicitante', function ($query) {
                                        $query->where('OFICINA_ID', Auth::user()->OFICINA_ID);
                                    })
                                    ->where('SOLICITUD_ESTADO', '!=', 'ELIMINADO')
                                    ->orderBy('created_at', 'desc')
                                    ->get();
            } else {
                // Si el usuario es otro tipo de usuario, mostrar solo sus solicitudes de salas a través de la relación solicitante y la sesión activa
                $solicitudes = Solicitud::has('salas')
                    ->where('USUARIO_id', Auth::user()->id)
                    ->where('SOLICITUD_ESTADO', '!=', 'ELIMINADO')
                    ->orderBy('created_at', 'desc')
                    ->get();
            }
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
                'SOLICITUD_MOTIVO.required' => 'El campo Motivo es requerido.',
                'SOLICITUD_MOTIVO.string' => 'El campo Motivo debe ser una cadena de caracteres.',
                'SOLICITUD_MOTIVO.max' => 'El campo Motivo no debe exceder los 255 caracteres.',
                'SOLICITUD_FECHA_HORA_INICIO_SOLICITADA.required' => 'El campo Fecha y Hora de Inicio Solicitada es requerido.',
                'SOLICITUD_FECHA_HORA_INICIO_SOLICITADA.date' => 'El campo Fecha y Hora de Inicio Solicitada debe ser una fecha.',
                'SOLICITUD_FECHA_HORA_TERMINO_SOLICITADA.required' => 'El campo Fecha y Hora de Término Solicitada es requerido.',
                'SOLICITUD_FECHA_HORA_TERMINO_SOLICITADA.date' => 'El campo Fecha y Hora de Término Solicitada debe ser una fecha.',
                'SOLICITUD_FECHA_HORA_TERMINO_SOLICITADA.after' => 'El campo Fecha y Hora de Término Solicitada debe ser una fecha posterior a la Fecha y Hora de Inicio Solicitada.',
                'SALA_ID.required' => 'El campo Sala es requerido.',
                'SALA_ID.exists' => 'La Sala seleccionada no existe.',
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
        DB::beginTransaction(); // Inicia una nueva transacción
        // try-catch
        try{
            // Obtener la solicitud
            $solicitud = Solicitud::has('salas')->findOrFail($id);

            // Determinar la acción basada en el botón presionado
            switch ($request->input('action')) {
                case 'guardar':
                    $validator = $this->validateGuardar($request);
                    if($validator->fails()){
                        DB::rollBack(); // Revierte la transacción
                        return redirect()->back()->withErrors($validator)->withInput();
                    }
                    $this->createRevisionSolicitud($request, $solicitud);
                    $this->updateSolicitud($request, $solicitud, 'EN REVISION');
                break;

                case 'finalizar_revision':
                    $validator = $this->validateFinalizarRevision($request);
                    if ($validator->fails()) {
                        DB::rollBack(); // Revierte la transacción antes de redirigir con errores
                        return redirect()->back()->withErrors($validator)->withInput();
                    }
                    if($solicitud::has('equipos')){
                        $resultadoAutorizacion = $this->autorizarEquipos($request, $solicitud);
                        if (is_array($resultadoAutorizacion) && isset($resultadoAutorizacion['errors'])) {
                            // Si hay errores específicos de autorización, revierte la transacción
                            DB::rollBack();
                            // Redirige de vuelta con los mensajes de error específicos
                            return redirect()->back()->withErrors($resultadoAutorizacion['errors'])->withInput();
                        }
                    }
                    $this->autorizarSala($request, $solicitud);
                    $this->updateSolicitud($request, $solicitud, 'APROBADO');
                    $this->createRevisionSolicitud($request, $solicitud);
                    break;


                case 'rechazar':
                    $validator = $this->validateRechazar($request);
                    if ($validator->fails()) {
                        DB::rollBack(); // Revierte la transacción antes de redirigir con errores
                        return redirect()->back()->withErrors($validator)->withInput();
                    }
                    $this->updateSolicitud($request, $solicitud, 'RECHAZADO');
                    $this->createRevisionSolicitud($request, $solicitud);
                    DB::commit(); // Guarda todos los cambios en la base de datos
                    return $this->redirectSuccess('Solicitud rechazada exitosamente');
                break;
            }

            DB::commit(); // Confirma la transacción
            return $this->redirectSuccess('Solicitud actualizada exitosamente');
        }catch(Exception $e){
            // Manejar excepciones
            DB:rollBack(); // Revierte la transacción
            return $this->redirectError('Error al procesar la solicitud: ' . $e->getMessage());
        }
    }


        /**
     * Validates the 'guardar' action of the solicitud.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Validation\Validator
     */
    private function validateGuardar($request)
    {
        // Code for validating 'guardar'
        // Validates at least one observation of the solicitud, optionally the assignment dates and authorized quantities of the materials (ONLY IF ENTERED)
        $validator = Validator::make($request->all(),[
            'REVISION_SOLICITUD_OBSERVACION' => 'nullable|string|max:255',
            'SOLICITUD_FECHA_HORA_INICIO_ASIGNADA' => 'nullable|date',
            'SOLICITUD_FECHA_HORA_TERMINO_ASIGNADA' => 'nullable|date|after:SOLICITUD_FECHA_HORA_INICIO_ASIGNADA',
            'autorizar.*' => 'nullable|numeric|min:0',
        ], [
            // Error messages
            'REVISION_SOLICITUD_OBSERVACION.string' => 'El campo de observación debe ser una cadena de texto.',
            'REVISION_SOLICITUD_OBSERVACION.max' => 'El campo de observación no debe exceder los 255 caracteres.',
            'SOLICITUD_FECHA_HORA_INICIO_ASIGNADA.date' => 'El campo debe ser una fecha.',
            'SOLICITUD_FECHA_HORA_TERMINO_ASIGNADA.date' => 'El campo debe ser una fecha.',
            'SOLICITUD_FECHA_HORA_TERMINO_ASIGNADA.after' => 'The End Date Assigned field must be a date after the requested start date.',
            'autorizar.*.numeric' => 'La cantidad autorizada debe ser un número.',
            'autorizar.*.min' => 'La cantidad autorizada no puede ser negativa.',
        ]);

        return $validator;
    }

    /**
     * Validates the 'finalizar_revision' action of the solicitud.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Validation\Validator
     */
    private function validateFinalizarRevision($request)
    {
        // Code for validating 'finalizar_revision'
        $validator = Validator::make($request->all(),[
            // 'SOLICITUD_ESTADO' => 'required|string|max:255|in:INGRESADO,EN REVISION,APROBADO,RECHAZADO,TERMINADO',
            'SOLICITUD_FECHA_HORA_INICIO_ASIGNADA' => 'required|date',
            'SOLICITUD_FECHA_HORA_TERMINO_ASIGNADA' => 'required|date|after:SOLICITUD_FECHA_HORA_INICIO_ASIGNADA',
            'REVISION_SOLICITUD_OBSERVACION' => 'nullable|string|max:255',
        ], [
            // Error messages
            'SOLICITUD_FECHA_HORA_INICIO_ASIGNADA.required' => 'La fecha de inicio asignada es requerida.',
            'SOLICITUD_FECHA_HORA_INICIO_ASIGNADA.date' => 'La fecha de inicio asignada debe ser una fecha.',
            'SOLICITUD_FECHA_HORA_TERMINO_ASIGNADA.required' => 'La fecha de término asignada es requerida.',
            'SOLICITUD_FECHA_HORA_TERMINO_ASIGNADA.date' => 'La fecha de término asignada debe ser una fecha.',
            'SOLICITUD_FECHA_HORA_TERMINO_ASIGNADA.after' => 'La fecha de término asignada debe ser una fecha posterior a la fecha de inicio asignada.',
            'REVISION_SOLICITUD_OBSERVACION.required' => 'Indique el motivo de la revisión.',
            'REVISION_SOLICITUD_OBSERVACION.string' => 'El campo Observación debe ser una cadena de caracteres.',
            'REVISION_SOLICITUD_OBSERVACION.max' => 'El campo Observación no debe exceder los 255 caracteres.',
        ]);

        return $validator;
    }

    /**
     * Validates the 'rechazar' action of the solicitud.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Validation\Validator
     */
    private function validateRechazar($request)
    {
        // Code for validating 'rechazar'
        // Should return true or false depending on the validation result
        // Verify at least one observation (reason for rejection) with validator
        $validator = Validator::make($request->all(),[
            'REVISION_SOLICITUD_OBSERVACION' => 'nullable|string|max:255',
            'SOLICITUD_FECHA_HORA_INICIO_ASIGNADA' => 'nullable|date',
            'SOLICITUD_FECHA_HORA_TERMINO_ASIGNADA' => 'nullable|date|after:SOLICITUD_FECHA_HORA_INICIO_ASIGNADA',
            'autorizar.*' => 'nullable|numeric|min:0',

        ], [
            // Error messages
            'REVISION_SOLICITUD_OBSERVACION.string' => 'El campo Observación debe ser una cadena de caracteres.',
            'REVISION_SOLICITUD_OBSERVACION.max' => 'El campo Observación no debe exceder los 255 caracteres.',
            'SOLICITUD_FECHA_HORA_INICIO_ASIGNADA.date' => 'La fecha de inicio asignada debe ser una fecha.',
            'SOLICITUD_FECHA_HORA_TERMINO_ASIGNADA.date' => 'La fecha de término asignada debe ser una fecha.',
            'SOLICITUD_FECHA_HORA_TERMINO_ASIGNADA.after' => 'La fecha de término asignada debe ser una fecha posterior a la fecha de inicio asignada.',
            'autorizar.*.nullable' => 'La Cantidad Autorizada debe ser nula o un número.',
            'autorizar.*.numeric' => 'La Cantidad Autorizada debe ser un número.',
            'autorizar.*.min' => 'La Cantidad Autorizada no puede ser negativa.',
        ]);

        return $validator;
    }

    /**
     * Updates the solicitud (request) with the given data and state.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Solicitud  $solicitud
     * @param  string  $estado
     * @return void
     */
    private function updateSolicitud($request, $solicitud, $estado)
    {
        // Common logic for updating the solicitud
        $solicitud->update([
            'SOLICITUD_ESTADO' => $estado,
            'SOLICITUD_FECHA_HORA_INICIO_ASIGNADA' => $request->input('SOLICITUD_FECHA_HORA_INICIO_ASIGNADA'),
            'SOLICITUD_FECHA_HORA_TERMINO_ASIGNADA' => $request->input('SOLICITUD_FECHA_HORA_TERMINO_ASIGNADA'),
        ]);
    }

    /**
     * Redirects to the index page for materials requests with a success message.
     *
     * @param string $message The success message to be displayed.
     * @return \Illuminate\Http\RedirectResponse The redirect response to the index page with the success message.
     */
    private function redirectSuccess($message)
    {
        // Redirigir con mensaje de éxito
        return redirect()->route('solicitudes.salas.index')->with('success', $message);
    }

    /**
     * Redirects to the index page of the materials request with an error message.
     *
     * @param string $message The error message to be displayed.
     * @return \Illuminate\Http\RedirectResponse
     */
    private function redirectError($message)
    {
        // Redirigir con mensaje de error
        return redirect()->route('solicitudes.salas.index')->with('error', $message);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try{
            // Recuperar la solicitud
            $solicitud = Solicitud::findOrFail($id);

            // Cambiar estado
            $solicitud->SOLICITUD_ESTADO = 'ELIMINADO';

            // Guardar la solicitud eliminada
            $solicitud->save();

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
                'REVISION_SOLICITUD_OBSERVACION' => $request->input('REVISION_SOLICITUD_OBSERVACION') ?: "Sin observaciones.",
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


    private function autorizarEquipos(Request $request, Solicitud $solicitud) {
        try {
            $autorizaciones = $request->input('autorizar', []);
            $errores = [];

            foreach ($autorizaciones as $tipoEquipoId => $cantidadAutorizada) {
                $tipoEquipo = TipoEquipo::findOrFail($tipoEquipoId);
                $equipos = Equipo::where('TIPO_EQUIPO_ID', $tipoEquipoId)->where('EQUIPO_STOCK', '>', 0)->get();

                if ($cantidadAutorizada > $equipos->sum('EQUIPO_STOCK')) {
                    $errores["autorizar.$tipoEquipoId"] = "La cantidad autorizada de " . $tipoEquipo->TIPO_EQUIPO_NOMBRE . " excede el stock disponible.";
                    continue;
                }

                foreach ($equipos as $equipo) {
                    if ($cantidadAutorizada <= 0) break;

                    $cantidadDisponible = $equipo->EQUIPO_STOCK;
                    $cantidadAUsar = min($cantidadAutorizada, $cantidadDisponible);

                    $equipo->decrement('EQUIPO_STOCK', $cantidadAUsar);

                    Movimiento::create([
                        'USUARIO_id' => Auth::user()->id,
                        'EQUIPO_ID' => $equipo->id, // Asegúrate de que este campo se llama 'id', no 'EQUIPO_ID', a menos que tu modelo lo especifique así.
                        'MOVIMIENTO_TITULAR' => (Auth::user()->USUARIO_NOMBRES.' '.Auth::user()->USUARIO_APELLIDOS),
                        'MOVIMIENTO_OBJETO' => 'EQUIPO: ' . $equipo->EQUIPO_MODELO,
                        'MOVIMIENTO_TIPO_OBJETO' => $equipo->tipoEquipo->TIPO_EQUIPO_NOMBRE,
                        'MOVIMIENTO_TIPO' => 'RESTA',
                        'MOVIMIENTO_STOCK_PREVIO' => $cantidadDisponible,
                        'MOVIMIENTO_CANTIDAD_A_MODIFICAR' => $cantidadAUsar,
                        'MOVIMIENTO_STOCK_RESULTANTE' => $cantidadDisponible - $cantidadAUsar,
                        'MOVIMIENTO_DETALLE' => 'Autorización de solicitud de equipos: ' . $solicitud->SOLICITUD_MOTIVO,
                    ]);

                    $cantidadAutorizada -= $cantidadAUsar;
                }

                if ($cantidadAutorizada > 0) {
                    $errores["autorizar.$tipoEquipoId"] .= " No se pudo autorizar la cantidad completa de " . $tipoEquipo->TIPO_EQUIPO_NOMBRE . ".";
                }
            }

            if (!empty($errores)) {
                return ['errors' => $errores];
            }

            return ['success' => true];
        } catch (Exception $e) {
            return ['errors' => ['general' => 'Error al autorizar los equipos, vuelva a intentarlo más tarde.']];
        }
    }

}
