<?php

namespace App\Http\Controllers\Solicitud;

// Importar elementos necesarios
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;
use Illuminate\Support\Facades\Auth;

// Importar modelos
use App\Models\Solicitud;
use App\Models\Bodega;
use App\Models\RevisionSolicitud;
use App\Models\SolicitudBodega;

class SolicitudBodegasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            // SI el usuario es ADMINISTRADOR o SERVICIOS, mostrar todas las solicitudes de bodegas (filtrado por oficina)
            if (Auth::user()->hasRole('ADMINISTRADOR') || Auth::user()->hasRole('SERVICIOS')) {
                // Filtrar por OFICINA_ID del usuario logueado con la relacion solicitante
                $solicitudes = Solicitud::has('bodegas')
                    ->whereHas('solicitante', function ($query) {
                        $query->where('OFICINA_ID', Auth::user()->OFICINA_ID);
                })
                    ->where('SOLICITUD_ESTADO', '!=', 'ELIMINADO')
                    ->orderBy('created_at', 'desc')
                    ->get();
            } else {
                // Si el usuario es otro tipo de usuario, mostrar solo sus solicitudes de bodegas a traves de la relacion solicitante y la sesion activa
                $solicitudes = Solicitud::has('bodegas')
                    ->where('USUARIO_id', Auth::user()->id)
                    ->where('SOLICITUD_ESTADO', '!=', 'ELIMINADO')
                    ->orderBy('created_at', 'desc')
                    ->get();
            }
            // Retornar la vista con las solicitudes
            return view('sia2.solicitudes.bodegas.index', compact('solicitudes'));
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
        // Funcion que lista bodegas basados en la OFICINA_ID del usuario logueado
        $bodegas = Bodega::where('OFICINA_ID', Auth::user()->OFICINA_ID)->get();

        //Retornar la vista del formulario con las bodegas
        return view('sia2.solicitudes.bodegas.create', compact('bodegas'));
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
                'BODEGA_ID' => 'required|exists:bodegas,BODEGA_ID',
            ], [
                //Mensajes de error
                'SOLICITUD_MOTIVO.required' => 'El campo Motivo es requerido.',
                'SOLICITUD_MOTIVO.string' => 'El campo Motivo debe ser una cadena de caracteres.',
                'SOLICITUD_FECHA_HORA_INICIO_SOLICITADA.required' => 'El campo Fecha y Hora de Inicio Solicitada es requerido.',
                'SOLICITUD_FECHA_HORA_INICIO_SOLICITADA.date' => 'El campo Fecha y Hora de Inicio Solicitada debe ser una fecha.',
                'SOLICITUD_FECHA_HORA_TERMINO_SOLICITADA.required' => 'El campo Fecha y Hora de Término Solicitada es requerido.',
                'SOLICITUD_FECHA_HORA_TERMINO_SOLICITADA.date' => 'El campo Fecha y Hora de Término Solicitada debe ser una fecha.',
                'SOLICITUD_FECHA_HORA_TERMINO_SOLICITADA.after' => 'El campo Fecha y Hora de Término Solicitada debe ser una fecha posterior a la Fecha y Hora de Inicio Solicitada.',
                'BODEGA_ID.required' => 'El campo Bodega es requerido.',
                'BODEGA_ID.exists' => 'La bodega seleccionada no existe en la base de datos.',
            ]);

            // Si falla la validación, redirigir al formulario con los errores
            if($validator->fails()){
                return redirect()->back()->withErrors($validator)->withInput();
            }

            // Crear la solicitud
            $solicitud = Solicitud::create([
                'USUARIO_id' => Auth::user()->id, // Asigna el ID del usuario autenticado
                'SOLICITUD_MOTIVO' => $request->input('SOLICITUD_MOTIVO'),
                'SOLICITUD_ESTADO' => 'INGRESADO', // Valor predeterminado
                'SOLICITUD_FECHA_HORA_INICIO_SOLICITADA' => $request->input('SOLICITUD_FECHA_HORA_INICIO_SOLICITADA'),
                'SOLICITUD_FECHA_HORA_TERMINO_SOLICITADA' => $request->input('SOLICITUD_FECHA_HORA_TERMINO_SOLICITADA'),
            ]);

            // Asociar la bodega a la solicitud
            $solicitud->bodegas()->attach($request->input('BODEGA_ID'));

            // Redirigir a la vista de solicitudes con mensaje de éxito
            return redirect()->route('solicitudes.bodegas.index')->with('success', 'Solicitud creada exitosamente.');
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
            // Buscar la solicitud por ID
            $solicitud = Solicitud::has('bodegas')->findOrFail($id);

            // Recuperar la tabla intermedia de la solicitud de bodegas asociaddas
            $tablaIntermedia = SolicitudBodega::where('SOLICITUD_ID', $solicitud->SOLICITUD_ID)->first();

            // Verificar si se encontró la tabla intermedia
            if ($tablaIntermedia) {
                $bodegaAsignada = Bodega::where('BODEGA_ID', $tablaIntermedia->SOLICITUD_BODEGA_ID_ASIGNADA)->first();
            } else {
                $bodegaAsignada = null;
            }

            // Retornar la vista con la solicitud
            return view('sia2.solicitudes.bodegas.show', compact('solicitud','bodegaAsignada'));
        }catch(Exception $e){
            return redirect()->back()->with('error', 'Error al cargar la solicitud.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // try-catch
        try{
            // Buscar la solicitud por ID
            $solicitud = Solicitud::has('bodegas')->findOrFail($id);

            // Buscar las bodegas por OFICINA_ID del usuario logueado
            $bodegas = Bodega::where('OFICINA_ID', Auth::user()->OFICINA_ID)->get();

            // Recuperar la tabla intermedia de la solicitud de bodegas asociaddas
            $tablaIntermedia = SolicitudBodega::where('SOLICITUD_ID', $solicitud->SOLICITUD_ID)->first();

            // Verificar si se encontró la tabla intermedia
            if ($tablaIntermedia) {
                $bodegaAsignada = Bodega::where('BODEGA_ID', $tablaIntermedia->SOLICITUD_BODEGA_ID_ASIGNADA)->first();
            } else {
                $bodegaAsignada = null;
            }

            // Retornar la vista con la solicitud
            return view('sia2.solicitudes.bodegas.edit', compact('solicitud','bodegas','bodegaAsignada'));
        }catch(Exception $e){
            return redirect()->back()->with('error', 'Error al cargar la solicitud.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // try-catch
        try{
            // Buscar la solicitud por ID
            $solicitud = Solicitud::has('bodegas')->findOrFail($id);

            // Determinar la acción basada en el botón presionado
            switch ($request->input('action')) {
                case 'guardar':
                    // Lógica para guardar cambios
                    $solicitud->update(['SOLICITUD_ESTADO' => 'EN REVISION']);
                break;

                case 'finalizar_revision':
                    // Lógica para finalizar la revisión
                    $solicitud->update(['SOLICITUD_ESTADO' => 'APROBADO']);
                break;

                case 'rechazar':
                    // verificar al menos que haya una observacion (motivo del rechazo) con validator
                    $validator = Validator::make($request->all(),[
                        'REVISION_SOLICITUD_OBSERVACION' => 'required|string|max:255',
                    ], [
                        //Mensajes de error
                        'REVISION_SOLICITUD_OBSERVACION.required' => 'Indique el motivo del rechazo.',
                        'REVISION_SOLICITUD_OBSERVACION.string' => 'El campo Observación debe ser una cadena de caracteres.',
                    ]);
                    // Si la validación falla, se redirecciona al formulario con los errores
                    if ($validator->fails()) {
                        return redirect()->back()->withErrors($validator)->withInput();
                    }
                    // Lógica para rechazar la solicitud
                    $solicitud->update(['SOLICITUD_ESTADO' => 'RECHAZADO']);
                    // Guardar la observacion del rechazo
                    $this->createRevisionSolicitud($request, $solicitud);
                    // redireccionar a la vista de solicitudes con un mensaje de éxito
                    return redirect()->route('solicitudes.bodegas.index')->with('success', 'Solicitud rechazada exitosamente.');
                break;

                // default:
                    // Lógica por defecto o para casos no contemplados
                    // break;
            }

            // Validar los datos del formulario de edición de la solicitud
            $validator = Validator::make($request->all(),[
                // PARA ASIGNACION
                // 'SOLICITUD_ESTADO' => 'required|string|max:255|in:INGRESADO,EN REVISION,APROBADO,RECHAZADO,TERMINADO',
                'SOLICITUD_FECHA_HORA_INICIO_ASIGNADA' => 'required|date',
                'SOLICITUD_FECHA_HORA_TERMINO_ASIGNADA' => 'required|date|after:SOLICITUD_FECHA_HORA_INICIO_ASIGNADA',
                'REVISION_SOLICITUD_OBSERVACION' => 'required|string|max:255',
            ], [
                //Mensajes de error
                'required' => 'El campo :attribute es requerido.',
                'date' => 'El campo :attribute debe ser una fecha.',
                'after' => 'El campo :attribute debe ser una fecha posterior a la fecha de inicio solicitada.',
                'string' => 'El campo :attribute debe ser una cadena de caracteres.',
                'exists' => 'El campo :attribute no existe en la base de datos.',
                'in' => 'El campo :attribute debe ser uno de los siguientes valores: INGRESADO, EN REVISION, APROBADO, RECHAZADO.',
            ]);

            // Si falla la validación, redirigir al formulario con los errores
            if($validator->fails()){
                return redirect()->back()->withErrors($validator)->withInput();
            }

            // Actualizar la solicitud
            $solicitud->update([
                // 'SOLICITUD_ESTADO' => $request->input('SOLICITUD_ESTADO'),
                'SOLICITUD_FECHA_HORA_INICIO_ASIGNADA' => $request->input('SOLICITUD_FECHA_HORA_INICIO_ASIGNADA'),
                'SOLICITUD_FECHA_HORA_TERMINO_ASIGNADA' => $request->input('SOLICITUD_FECHA_HORA_TERMINO_ASIGNADA'),
            ]);

            // Crear una nueva revisión para la solicitud
            $this->createRevisionSolicitud($request, $solicitud);

            // Redirigir a la vista de solicitudes con mensaje de éxito
            return redirect()->route('solicitudes.bodegas.index')->with('success', 'Solicitud actualizada exitosamente.');

        }catch(Exception $e){
            return redirect()->back()->with('error', 'Error al actualizar la solicitud.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try{
            // Buscar la solicitud por ID
            $solicitud = Solicitud::has('bodegas')->findOrFail($id);

            // Cambiar estado
            $solicitud->SOLICITUD_ESTADO = 'ELIMINADO';

            // Guardar solicitud eliminada
            $solicitud->save();

            // Redirigir a la vista de solicitudes con mensaje de éxito
            return redirect()->route('solicitudes.bodegas.index')->with('success', 'Solicitud eliminada exitosamente.');
        }catch(Exception $e){
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
