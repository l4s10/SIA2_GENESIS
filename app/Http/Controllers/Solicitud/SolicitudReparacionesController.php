<?php

namespace App\Http\Controllers\Solicitud;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Exception;

// Importar modelos
use App\Models\SolicitudReparacion;
use App\Models\CategoriaReparacion;
use App\Models\Vehiculo;
use App\Models\RevisionSolicitud;

class SolicitudReparacionesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            // SI el usuario es ADMINISTRADOR o SERVICIOS, mostrar todas las solicitudes de reparaciones (filtrado por oficina)
            if (Auth::user()->hasRole('ADMINISTRADOR') || Auth::user()->hasRole('SERVICIOS')) {
                // Filtrar por OFICINA_ID del usuario logueado con la relacion solicitante
                $solicitudes = SolicitudReparacion::whereHas('solicitante', function ($query) {
                                        $query->where('OFICINA_ID', Auth::user()->OFICINA_ID);
                                    })
                                    ->where('SOLICITUD_REPARACION_ESTADO', '!=', 'ELIMINADO')
                                    ->orderBy('created_at', 'desc')
                                    ->get();
            } else {
                // Si el usuario es otro tipo de usuario, mostrar solo sus solicitudes de reparaciones de equipos a través de la relación solicitante y la sesión activa
                $solicitudes = SolicitudReparacion::where('USUARIO_id', Auth::user()->id)
                    ->where('SOLICITUD_REPARACION_ESTADO', '!=', 'ELIMINADO')
                    ->orderBy('created_at', 'desc')
                    ->get();
            }
            // Retornar la vista con las solicitudes
            return view('sia2.solicitudes.reparacionesmantenciones.index', compact('solicitudes'));
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
            // Cargar las categorias.
            $categorias = CategoriaReparacion::all();

            $vehiculos = Vehiculo::all();
            // Retornar la vista del formulario con las categorias
            return view('sia2.solicitudes.reparacionesmantenciones.create', compact('categorias','vehiculos'));
        }
        catch(Exception $e)
        {
            return redirect()->back()->with('error', 'Error al cargar las categorias.');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try{
            // Validar los datos del formulario de solicitud de reparaciones.
            $validator = Validator::make($request->all(),[
                'VEHICULO_ID' => 'nullable|exists:vehiculos,VEHICULO_ID',
                'SOLICITUD_REPARACION_TIPO' => 'required|string|max:20',
                'CATEGORIA_REPARACION_ID' => 'required|exists:categorias_reparaciones,CATEGORIA_REPARACION_ID',
                'SOLICITUD_REPARACION_MOTIVO' => 'required|string|max:255',
            ], [
                //Mensajes de error
                'required' => 'El campo :attribute es requerido.',
                'exists' => 'El campo :attribute no existe en la base de datos.',
                'string' => 'El campo :attribute debe ser una cadena de caracteres.',
                'max' => 'El campo :attribute debe tener un máximo de :max caracteres.',
            ]);

            // Si falla la validación, redirigir al formulario con los errores
            if($validator->fails()){
                return redirect()->back()->withErrors($validator)->withInput();
            }

            // Crear la solicitud
            SolicitudReparacion::create([
                'USUARIO_id' => Auth::user()->id, // Asigna el ID del usuario autenticado
                'VEHICULO_ID' => $request->input('VEHICULO_ID'),
                'SOLICITUD_REPARACION_TIPO' => $request->input('SOLICITUD_REPARACION_TIPO'),
                'CATEGORIA_REPARACION_ID' => $request->input('CATEGORIA_REPARACION_ID'),
                'SOLICITUD_REPARACION_MOTIVO' => $request->input('SOLICITUD_REPARACION_MOTIVO'),
                'SOLICITUD_REPARACION_ESTADO' => 'INGRESADO', // Valor predeterminado
            ]);

            // Redirigir a la vista de solicitudes con mensaje de éxito
            return redirect()->route('solicitudes.reparaciones.index')->with('success', 'Solicitud creada exitosamente.');
        }catch(Exception $e){
            return redirect()->back()->with('error', 'Error al crear la solicitud.' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try{
            // Buscar la solicitud por ID
            $solicitud = SolicitudReparacion::findOrFail($id);

            // Retornar la vista con la solicitud
            return view('sia2.solicitudes.reparacionesmantenciones.show', compact('solicitud'));
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
        try
        {
            // Buscar la solicitud por ID
            $solicitud = SolicitudReparacion::findOrFail($id);

            // Cargar las categorias
            // $categorias = CategoriaReparacion::all();

            // Retornar la vista del formulario con la solicitud y las categorias
            return view('sia2.solicitudes.reparacionesmantenciones.edit', compact('solicitud'));
        }
        catch(Exception $e)
        {
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
            $solicitud = SolicitudReparacion::findOrFail($id);

            // Validar los datos del formulario de solicitud de reparaciones.
            $validator = Validator::make($request->all(),[
                // 'SOLICITUD_REPARACION_ESTADO' => 'required|string|max:20',
                'SOLICITUD_REPARACION_FECHA_HORA_INICIO' => 'nullable|date',
                'SOLICITUD_REPARACION_FECHA_HORA_TERMINO' => 'nullable|date|after_or_equal:SOLICITUD_REPARACION_FECHA_HORA_INICIO',
                'REVISION_SOLICITUD_OBSERVACION' => 'required|string|max:255',
            ], [
                //Mensajes de error
                'SOLICITUD_REPARACION_FECHA_HORA_INICIO.date' => 'La fecha de inicio debe ser una fecha válida.',
                'SOLICITUD_REPARACION_FECHA_HORA_TERMINO.date' => 'La fecha de término debe ser una fecha válida.',
                'SOLICITUD_REPARACION_FECHA_HORA_TERMINO.after_or_equal' => 'La fecha de término debe ser mayor o igual a la fecha de inicio.',
            ]);

            // Si falla la validación, redirigir al formulario con los errores
            if($validator->fails()){
                return redirect()->back()->withErrors($validator)->withInput();
            }

            // Determinar la acción basada en el botón presionado
            switch ($request->input('action')) {
                case 'guardar':
                    // Lógica para guardar cambios
                    $solicitud->update(['SOLICITUD_REPARACION_ESTADO' => 'EN REVISION']);
                break;

                case 'finalizar_revision':
                    // verificar al menos que haya una observacion (motivo de aprobacion) con validator
                    $validator = Validator::make($request->all(),[
                        'REVISION_SOLICITUD_OBSERVACION' => 'required|string|max:255',
                        'SOLICITUD_REPARACION_FECHA_HORA_INICIO' => 'required|date',
                    ], [
                        //Mensajes de error
                        'REVISION_SOLICITUD_OBSERVACION.required' => 'El campo de Observación es obligatiorio.',
                        'REVISION_SOLICITUD_OBSERVACION.string' => 'El campo Observación debe ser una cadena de caracteres.',
                        'REVISION_SOLICITUD_OBSERVACION.max' => 'El campo Observación debe tener un máximo de :max caracteres.',
                        'SOLICITUD_REPARACION_FECHA_HORA_INICIO.required' => 'Indique la fecha y hora de cumplimiento de la solicitud.',
                        'SOLICITUD_REPARACION_FECHA_HORA_INICIO.date' => 'La fecha de cumplimiento debe ser una fecha válida.',
                    ]);
                    // Si la validación falla, se redirecciona al formulario con los errores
                    if ($validator->fails()) {
                        return redirect()->back()->withErrors($validator)->withInput();
                    }
                    // Lógica para finalizar la revisión
                    $solicitud->update(['SOLICITUD_REPARACION_ESTADO' => 'APROBADO']);
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
                    return redirect()->route('solicitudes.reparaciones.index')->with('success', 'Solicitud rechazada exitosamente.');
                break;

                // default:
                    // Lógica por defecto o para casos no contemplados
                    // break;
            }

            // Actualizar la solicitud
            $solicitud->update([
                // 'SOLICITUD_REPARACION_ESTADO' => $request->input('SOLICITUD_REPARACION_ESTADO'),
                'SOLICITUD_REPARACION_FECHA_HORA_INICIO' => $request->input('SOLICITUD_REPARACION_FECHA_HORA_INICIO'),
                'SOLICITUD_REPARACION_FECHA_HORA_TERMINO' => $request->input('SOLICITUD_REPARACION_FECHA_HORA_TERMINO'),
            ]);

            // Crear la revisión de la solicitud
            $this->createRevisionSolicitud($request, $solicitud);

            // Redirigir a la vista de solicitudes con mensaje de éxito
            return redirect()->route('solicitudes.reparaciones.index')->with('success', 'Solicitud actualizada exitosamente.');
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
            $solicitud = SolicitudReparacion::findOrFail($id);

            // Cambiar estado
            $solicitud->SOLICITUD_REPARACION_ESTADO = 'ELIMINADO';

            // Guardar la solicitud eliminada
            $solicitud->save();

            // Redirigir a la vista de solicitudes con mensaje de éxito
            return redirect()->route('solicitudes.reparaciones.index')->with('success', 'Solicitud eliminada exitosamente.');
        }catch(Exception $e){
            return redirect()->back()->with('error', 'Error al eliminar la solicitud.');
        }
    }

    /**
    * Crear una nueva revision para la solicitud.
    */
    private function createRevisionSolicitud(Request $request, SolicitudReparacion $solicitud)
    {
        // try-catch
        try
        {
            // Crear la revisión de la solicitud
            RevisionSolicitud::create([
                'USUARIO_id' => Auth::user()->id,
                'SOLICITUD_REPARACION_ID' => $solicitud->SOLICITUD_REPARACION_ID,
                'REVISION_SOLICITUD_OBSERVACION' => $request->input('REVISION_SOLICITUD_OBSERVACION'),
            ]);
        }
        catch(Exception $e)
        {
            // Manejo de excepciones
            return redirect()->route('solicitudes.reparaciones.index')->with('error', 'Error al crear la revisión de la solicitud.');
        }
    }
}
