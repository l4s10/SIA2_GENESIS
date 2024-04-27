<?php

namespace App\Http\Controllers\Solicitud;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Gloudemans\Shoppingcart\Facades\Cart;
use Exception;
use App\Models\Solicitud;
use App\Models\TipoEquipo;
use App\Models\Equipo;
use App\Models\RevisionSolicitud;
use App\Models\Movimiento;


class SolicitudEquiposController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
            // SI el usuario es ADMINISTRADOR o INFORMATICA, mostrar todas las solicitudes de equipos (filtrado por oficina)
            if (Auth::user()->hasRole('ADMINISTRADOR') || Auth::user()->hasRole('INFORMATICA')) {
                // Filtrar por OFICINA_ID del usuario logueado con la relacion solicitante
                $solicitudes = Solicitud::has('equipos')
                    ->whereDoesntHave('salas')
                    ->whereHas('solicitante', function ($query) {
                        $query->where('OFICINA_ID', Auth::user()->OFICINA_ID);
                })
                    ->where('SOLICITUD_ESTADO', '!=', 'ELIMINADO')
                    ->orderBy('created_at', 'desc')
                    ->get();
            } else {
                // Si el usuario es otro tipo de usuario, mostrar solo sus solicitudes de equipos a traves de la relacion solicitante y la sesion activa
                $solicitudes = Solicitud::has('equipos')
                    ->whereDoesntHave('salas')
                    ->where('USUARIO_id', Auth::user()->id)
                    ->where('SOLICITUD_ESTADO', '!=', 'ELIMINADO')
                    ->orderBy('created_at', 'desc')
                    ->get();
            }
            // Retornar la vista con las solicitudes
            return view('sia2.solicitudes.equipos.index', compact('solicitudes'));
        }catch(Exception $e){
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
            // Funcion que lista tipos de equipos basados en la OFICINA_ID del usuario logueado
            $tiposEquipos = TipoEquipo::where('OFICINA_ID', Auth::user()->OFICINA_ID)->get();
            // Obtener los elementos del carrito
            $cartItems = Cart::instance('carrito_equipos')->content();
            // Retornar la vista del formulario con los tipos de equipo y el carrito\
            return view('sia2.solicitudes.equipos.create', compact('tiposEquipos', 'cartItems'));
        }catch(Exception $e){
            // Manejar excepciones si es necesario
            return redirect()->back()->with('error', 'EError al cargar los tipos de equipo.');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try{
            // Valida los datos del formulario de solicitud de equipos.
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
            if (Cart::instance('carrito_equipos')->count() === 0) {
                return redirect()->back()->with('error', 'El carrito de equipos está vacío.');
            }

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
            return redirect()->route('solicitudes.equipos.index')->with('success', 'Solicitud creada exitosamente');
        }catch(Exception $e){
            // Manejar excepciones si es necesario
            return redirect()->back()->with('error', 'Error al enviar la solicitud.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try{
            // SI el usuario tiene rol ADMINISTRADOR o INFORMATICA, buscar la solicitud y mostrarla
            if (Auth::user()->hasRole('ADMINISTRADOR') || Auth::user()->hasRole('INFORMATICA')) {
                $solicitud = Solicitud::has('equipos')->whereDoesntHave('salas')->findOrFail($id);
            } else {
                // Si el usuario no tiene rol ADMINISTRADOR o INFORMATICA, buscar la solicitud y mostrarla solo si es el solicitante, en caso de que no sea el solicitante, redirigir a la vista index con mensaje de error.
                $solicitud = Solicitud::has('equipos')->where('USUARIO_id', Auth::user()->id)->findOrFail($id);
            }
            // Retornar la vista con la solicitud
            return view('sia2.solicitudes.equipos.show', compact('solicitud'));
        }catch(Exception $e){
            // Manejar excepciones
            return redirect()->route('solicitudes.equipos.index')->with('error', 'Error al cargar la solicitud.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // try-catch
        try{
            // Obtener la solicitud
            $solicitud = Solicitud::has('equipos')->findOrFail($id);
            // Retornar la vista con la solicitud
            return view('sia2.solicitudes.equipos.edit', compact('solicitud'));
        }catch(Exception $e){
            // Manejar excepciones
            return redirect()->route('solicitudes.equipos.index')->with('error', 'Error al cargar la solicitud.');
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
            $solicitud = Solicitud::has('equipos')->whereDoesntHave('salas')->findOrFail($id);

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
                    $resultadoAutorizacion = $this->autorizarEquipos($request, $solicitud);
                    if (is_array($resultadoAutorizacion) && isset($resultadoAutorizacion['errors'])) {
                        // Si hay errores específicos de autorización, revierte la transacción
                        DB::rollBack();
                        // Redirige de vuelta con los mensajes de error específicos
                        return redirect()->back()->withErrors($resultadoAutorizacion['errors'])->withInput();
                    }
                    $this->updateSolicitud($request, $solicitud, 'APROBADO');
                    $this->createRevisionSolicitud($request, $solicitud);
                    DB::commit(); // Confirma la transacción
                    return $this->redirectSuccess('Solicitud autorizada exitosamente y equipos descontados del stock.');
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
            'SOLICITUD_FECHA_HORA_INICIO_ASIGNADA' => 'required|date',
            'SOLICITUD_FECHA_HORA_TERMINO_ASIGNADA' => 'required|date|after:SOLICITUD_FECHA_HORA_INICIO_ASIGNADA',
            'autorizar.*' => 'nullable|numeric|min:0',
        ], [
            // Error messages
            'REVISION_SOLICITUD_OBSERVACION.string' => 'The Observation field must be a string.',
            'REVISION_SOLICITUD_OBSERVACION.max' => 'The Observation field must not exceed 255 characters.',
            'SOLICITUD_FECHA_HORA_INICIO_ASIGNADA.required' => 'The Start Date Assigned field is required.',
            'SOLICITUD_FECHA_HORA_INICIO_ASIGNADA.date' => 'The Start Date Assigned field must be a date.',
            'SOLICITUD_FECHA_HORA_TERMINO_ASIGNADA.required' => 'The End Date Assigned field is required.',
            'SOLICITUD_FECHA_HORA_TERMINO_ASIGNADA.date' => 'The End Date Assigned field must be a date.',
            'SOLICITUD_FECHA_HORA_TERMINO_ASIGNADA.after' => 'The End Date Assigned field must be a date after the requested start date.',
            'autorizar.*.nullable' => 'The Authorized Quantity field must be null or a number.',
            'autorizar.*.numeric' => 'The Authorized Quantity field must be a number.',
            'autorizar.*.min' => 'The Authorized Quantity field cannot be negative.',
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
            'autorizar.*' => 'required|numeric|min:0',
        ], [
            // Error messages
            'SOLICITUD_FECHA_HORA_INICIO_ASIGNADA.required' => 'La fecha de inicio asignada es requerida.',
            'SOLICITUD_FECHA_HORA_INICIO_ASIGNADA.date' => 'La fecha de inicio asignada debe ser una fecha.',
            'SOLICITUD_FECHA_HORA_TERMINO_ASIGNADA.required' => 'La fecha de término asignada es requerida.',
            'SOLICITUD_FECHA_HORA_TERMINO_ASIGNADA.date' => 'La fecha de término asignada debe ser una fecha.',
            'SOLICITUD_FECHA_HORA_TERMINO_ASIGNADA.after' => 'La fecha de término asignada debe ser una fecha posterior a la fecha de inicio asignada.',
            'REVISION_SOLICITUD_OBSERVACION.string' => 'El campo Observación debe ser una cadena de caracteres.',
            'REVISION_SOLICITUD_OBSERVACION.max' => 'El campo Observación no debe exceder los 255 caracteres.',
            'autorizar.*.required' => 'La Cantidad Autorizada es requerida.',
            'autorizar.*.numeric' => 'La Cantidad Autorizada debe ser un número.',
            'autorizar.*.min' => 'La cantidad autorizada no puede ser menor a 0.',
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
        return redirect()->route('solicitudes.equipos.index')->with('success', $message);
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
        return redirect()->route('solicitudes.equipos.index')->with('error', $message);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try{
            // Obtener la solicitud
            $solicitud = Solicitud::findOrFail($id);

            // Cambiar estado
            $solicitud->SOLICITUD_ESTADO = 'ELIMINADO';

            // Guardar la solicitud eliminada
            $solicitud->save();

            // Redireccionar a la vista de solicitudes con un mensaje de éxito
            return redirect()->route('solicitudes.equipos.index')->with('success', 'Solicitud eliminada exitosamente');
        }catch(Exception $e){
            // Manejo de excepciones
            return redirect()->back()->with('error', 'Error al eliminar la solicitud.');
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
                'REVISION_SOLICITUD_OBSERVACION' => $request->input('REVISION_SOLICITUD_OBSERVACION') ?: 'Sin observación.',
            ]);
        }
        catch(Exception $e)
        {
            // Manejo de excepciones
            return redirect()->route('solicitudes.formularios.index')->with('error', 'Error al crear la revisión de la solicitud.');
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
