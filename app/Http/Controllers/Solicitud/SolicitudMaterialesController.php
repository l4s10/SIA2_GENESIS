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
use Illuminate\Support\Facades\DB;

// Importar modelos
use App\Models\Solicitud;
use App\Models\Material;
use App\Models\Movimiento;
use App\Models\RevisionSolicitud;

class SolicitudMaterialesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            // SI el usuario es ADMINISTRADOR o INFORMATICA, mostrar todas las solicitudes de materiales (filtrado por oficina)
            if (Auth::user()->hasRole('ADMINISTRADOR') || Auth::user()->hasRole('SERVICIOS')) {
                // Filtrar por OFICINA_ID del usuario logueado con la relacion solicitante
                $solicitudes = Solicitud::has('materiales')
                    ->whereHas('solicitante', function ($query) {
                        $query->where('OFICINA_ID', Auth::user()->OFICINA_ID);
                    })
                    ->where('SOLICITUD_ESTADO', '!=', 'ELIMINADO')
                    ->orderBy('created_at', 'desc')
                    ->get();
            } else {
                // Si el usuario es otro tipo de usuario, mostrar solo sus solicitudes de materiales a traves de la relacion solicitante y la sesion activa
                $solicitudes = Solicitud::has('materiales')
                    ->where('USUARIO_id', Auth::user()->id)
                    ->where('SOLICITUD_ESTADO', '!=', 'ELIMINADO')
                    ->orderBy('created_at', 'desc')
                    ->get();
            }
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
                'SOLICITUD_FECHA_HORA_INICIO_SOLICITADA' => 'required|date'
                // 'SOLICITUD_FECHA_HORA_TERMINO_SOLICITADA' => 'required|date|after:SOLICITUD_FECHA_HORA_INICIO_SOLICITADA',
            ], [
                //Mensajes de error
                'required' => 'El campo :attribute es requerido.',
                'date' => 'El campo :attribute debe ser una fecha.',
                'after' => 'El campo :attribute debe ser una fecha posterior a la fecha de inicio solicitada.',
                'string' => 'El campo :attribute debe ser una cadena de caracteres.'
            ]);

            // Validar que la instancia del carrito no esté vacía
            if (Cart::instance('carrito_materiales')->count() === 0) {
                return redirect()->back()->with('error', 'El carrito de materiales está vacío.');
            }
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
                'SOLICITUD_FECHA_HORA_TERMINO_SOLICITADA' => null, // Valor predeterminado
            ]);

            //Si se crea la solicitud correctamente, se asocia los materiales del carrito a la solicitud a traves de la relacion creada en el modelo.
            if($solicitud){
                // Llamamos a la instancia del carrito de materiales
                foreach (Cart::instance('carrito_materiales')->content() as $cartItem) {
                    $material = Material::find($cartItem->id);

                    // Agrega el material a la solicitud con la cantidad del carrito
                    $solicitud->materiales()->attach($material, [
                        'SOLICITUD_MATERIAL_CANTIDAD' => $cartItem->qty,
                        'SOLICITUD_MATERIAL_CANTIDAD_AUTORIZADA' => 0 // Valor predeterminado
                    ]);
                }
                // Limpia el carrito después de agregar los materiales a la solicitud
                Cart::instance('carrito_materiales')->destroy();
            }
            // Redireccion a la vista index de solicitud de materiales, con el mensaje de exito.
            return redirect()->route('solicitudes.materiales.index')->with('success', 'Solicitud creada exitosamente');
        }catch(Exception $e){
            // Manejo de excepciones
            return redirect()->route('solicitudes.materiales.index')->with('error', 'Error al crear la solicitud.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            // SI el usuario tiene rol ADMINISTRADOR o SERVICIOS, buscar la solicitud y mostrarla
            if (Auth::user()->hasRole('ADMINISTRADOR') || Auth::user()->hasRole('SERVICIOS')) {
                $solicitud = Solicitud::has('materiales')->findOrFail($id);
            } else {
                // Si el usuario no tiene rol ADMINISTRADOR o SERVICIOS, buscar la solicitud y mostrarla solo si es el solicitante
                $solicitud = Solicitud::has('materiales')->where('USUARIO_id', Auth::user()->id)->findOrFail($id);
            }

            // Retornar la vista con la solicitud
            return view('sia2.solicitudes.materiales.show', compact('solicitud'));
        } catch (Exception $e) {
            // Manejar excepciones si la solicitud no se encuentra o hay algún error manejable
            return redirect()->route('solicitudes.materiales.index')->with('error', 'Error al mostrar la solicitud.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // try-catch para el manejo de excepciones
        try {
            // Recuperar la solicitud con sus materiales asociados
            $solicitud = Solicitud::has('materiales')->findOrFail($id);
            // Retornar la vista con la solicitud
            return view('sia2.solicitudes.materiales.edit', compact('solicitud'));
        } catch (Exception $e) {
            // Manejar excepciones si la solicitud no se encuentra o hay algún error manejable
            return redirect()->route('solicitudes.materiales.index')->with('error', 'Error al mostrar la solicitud.');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, string $id)
    {
        DB::beginTransaction(); // Inicia una nueva transacción

        try {
            $solicitud = Solicitud::has('materiales')->findOrFail($id);

            switch ($request->input('action')) {
                case 'guardar':
                    $validator = $this->validateGuardar($request);
                    if ($validator->fails()) {
                        DB::rollBack(); // Revierte la transacción antes de redirigir con errores
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
                    $resultadoAutorizacion = $this->autorizarMateriales($request, $solicitud);
                    if ($resultadoAutorizacion !== true) {
                        DB::rollBack(); // Revierte la transacción si la autorización falla
                        return $resultadoAutorizacion; // Esto debería ser un RedirectResponse con errores
                    }
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

            DB::commit(); // Guarda todos los cambios en la base de datos
            return $this->redirectSuccess('Solicitud actualizada exitosamente');
        } catch (Exception $e) {
            DB::rollBack(); // Asegura que se revierten las operaciones si ocurre un error inesperado
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
            'REVISION_SOLICITUD_OBSERVACION' => 'required|string|max:255',
            'SOLICITUD_FECHA_HORA_INICIO_ASIGNADA' => 'required|date',
            // 'SOLICITUD_FECHA_HORA_TERMINO_ASIGNADA' => 'required|date|after:SOLICITUD_FECHA_HORA_INICIO_ASIGNADA',
            'autorizar.*' => 'nullable|numeric|min:0',
        ], [
            // Error messages
            'REVISION_SOLICITUD_OBSERVACION.required' => 'Indicate the reason for the revision.',
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
            // 'SOLICITUD_FECHA_HORA_TERMINO_ASIGNADA' => 'required|date|after:SOLICITUD_FECHA_HORA_INICIO_ASIGNADA',
            'REVISION_SOLICITUD_OBSERVACION' => 'required|string|max:255',
        ], [
            // Error messages
            'SOLICITUD_FECHA_HORA_INICIO_ASIGNADA.required' => 'La fecha de inicio asignada es requerida.',
            'SOLICITUD_FECHA_HORA_INICIO_ASIGNADA.date' => 'La fecha de inicio asignada debe ser una fecha.',
            'SOLICITUD_FECHA_HORA_TERMINO_ASIGNADA.required' => 'La fecha de término asignada es requerida.',
            // 'SOLICITUD_FECHA_HORA_TERMINO_ASIGNADA.date' => 'La fecha de término asignada debe ser una fecha.',
            // 'SOLICITUD_FECHA_HORA_TERMINO_ASIGNADA.after' => 'La fecha de término asignada debe ser una fecha posterior a la fecha de inicio asignada.',
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
            'REVISION_SOLICITUD_OBSERVACION' => 'required|string|max:255',
            'SOLICITUD_FECHA_HORA_INICIO_ASIGNADA' => 'nullable|date',
            // 'SOLICITUD_FECHA_HORA_TERMINO_ASIGNADA' => 'nullable|date|after:SOLICITUD_FECHA_HORA_INICIO_ASIGNADA',
            'autorizar.*' => 'nullable|numeric|min:0',

        ], [
            // Error messages
            'REVISION_SOLICITUD_OBSERVACION.required' => 'Indique el motivo del rechazo.',
            'REVISION_SOLICITUD_OBSERVACION.string' => 'El campo Observación debe ser una cadena de caracteres.',
            'REVISION_SOLICITUD_OBSERVACION.max' => 'El campo Observación no debe exceder los 255 caracteres.',
            'SOLICITUD_FECHA_HORA_INICIO_ASIGNADA.date' => 'La fecha de inicio asignada debe ser una fecha.',
            // 'SOLICITUD_FECHA_HORA_TERMINO_ASIGNADA.date' => 'La fecha de término asignada debe ser una fecha.',
            // 'SOLICITUD_FECHA_HORA_TERMINO_ASIGNADA.after' => 'La fecha de término asignada debe ser una fecha posterior a la fecha de inicio asignada.',
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
            // 'SOLICITUD_FECHA_HORA_TERMINO_ASIGNADA' => $request->input('SOLICITUD_FECHA_HORA_TERMINO_ASIGNADA'),
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
        return redirect()->route('solicitudes.materiales.index')->with('success', $message);
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
        return redirect()->route('solicitudes.materiales.index')->with('error', $message);
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

            // Cambiar estado
            $solicitud->SOLICITUD_ESTADO = 'ELIMINADO';

            // Guardar la solicitud eliminada
            $solicitud->save();

            // Puedes agregar un mensaje de éxito si lo deseas
            return redirect()->route('solicitudes.materiales.index')->with('success', 'Solicitud eliminada exitosamente');
        } catch (Exception $e) {
            // Manejar excepciones si es necesario
            return redirect()->route('solicitudes.materiales.index')->with('error', 'Error al eliminar la solicitud.');
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
     * Autorizar materiales
     */
    private function autorizarMateriales(Request $request, Solicitud $solicitud)
    {
        // try-catch
        try {
            $autorizaciones = $request->input('autorizar', []);
            $errores = [];

            foreach ($autorizaciones as $materialId => $cantidadAutorizada) {
                $material = Material::findOrFail($materialId);
                $validator = Validator::make(
                    ['autorizar' => $cantidadAutorizada],
                    ['autorizar' => 'required|numeric|min:0|max:'.$material->MATERIAL_STOCK],
                    [
                        'required' => 'La cantidad autorizada es requerida.',
                        'numeric' => 'La cantidad autorizada debe ser un número.',
                        'min' => 'La cantidad autorizada no puede ser negativa.',
                        'max' => 'La cantidad autorizada no puede ser mayor al stock disponible.',
                    ]
                );

                if ($validator->fails()) {
                    // Cambio aquí: asociar directamente los mensajes de error con cada ID de material
                    // para asegurar que se muestren correctamente en la vista.
                    $errores["autorizar.$materialId"] = $validator->errors()->get('autorizar');
                } else {
                    //?? Si las validaciones pasan, actualiza la cantidad autorizada del material en la solicitud
                    // Actualiza la cantidad autorizada del material en la solicitud
                    $solicitud->materiales()->updateExistingPivot($materialId, ['SOLICITUD_MATERIAL_CANTIDAD_AUTORIZADA' => $cantidadAutorizada]);

                    // Actualiza el stock del material
                    $stockPrevio = $material->MATERIAL_STOCK;
                    $stockResultante = $stockPrevio - $cantidadAutorizada;
                    $material->update(['MATERIAL_STOCK' => $stockResultante]);

                    // Registra el movimiento
                    Movimiento::create([
                        'USUARIO_id' => Auth::user()->id,
                        'MATERIAL_ID' => $material->MATERIAL_ID,
                        'MOVIMIENTO_TITULAR' => (Auth::user()->USUARIO_NOMBRES.' '.Auth::user()->USUARIO_APELLIDOS),
                        'MOVIMIENTO_OBJETO' => 'MATERIAL: ' . $material->MATERIAL_NOMBRE,
                        'MOVIMIENTO_TIPO_OBJETO' => $material->tipoMaterial->TIPO_MATERIAL_NOMBRE,
                        'MOVIMIENTO_TIPO' => 'RESTA',
                        'MOVIMIENTO_STOCK_PREVIO' => $stockPrevio,
                        'MOVIMIENTO_CANTIDAD_A_MODIFICAR' => $cantidadAutorizada,
                        'MOVIMIENTO_STOCK_RESULTANTE' => $stockResultante,
                        'MOVIMIENTO_DETALLE' => 'Solicitud de materiales: ' . $solicitud->SOLICITUD_MOTIVO,
                    ]);
                }
            }

            if (!empty($errores)) {
                // Cambio aquí: pasar el array $errores tal como está al método withErrors.
                // Laravel automáticamente manejará este array asociativo y mapeará los errores
                // a los campos respectivos en el formulario basado en las claves del array.
                return redirect()->back()->withErrors($errores)->withInput();
            }

            // Continúa con la lógica de éxito si no hay errores, sin necesidad de redirigir aquí
            return true; // Indica éxito sin errores
        } catch (Exception $e) {
            // Considera loguear el error para depuración
            return false; // Indica error
        }
    }


    public function confirmar($id)
    {
        try {
            $solicitud = Solicitud::findOrFail($id);
            // Verificar si el usuario autenticado es el solicitante y si la solicitud no está ya terminada
            if (Auth::user()->USUARIO_ID == $solicitud->SOLICITUD_USUARIO_ID && $solicitud->SOLICITUD_ESTADO == 'APROBADO') {
                $solicitud->SOLICITUD_ESTADO = 'TERMINADO';
                $solicitud->save();

                return redirect()->route('solicitudes.materiales.index')->with('success', 'Solicitud confirmada con éxito.');
            } else {
                return redirect()->route('solicitudes.materiales.index')->with('error', 'No se puede confirmar la solicitud.');
            }
        } catch (\Exception $e) {
            return redirect()->route('solicitudes.materiales.index')->with('error', 'Error al confirmar la solicitud: ' . $e->getMessage());
        }
    }
}
