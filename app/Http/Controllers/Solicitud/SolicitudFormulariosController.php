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
        try {
            // SI el usuario es ADMINISTRADOR o INFORMATICA, mostrar todas las solicitudes de formularios (filtrado por oficina)
            if (Auth::user()->hasRole('ADMINISTRADOR') || Auth::user()->hasRole('SERVICIOS')) {
                // Filtrar por OFICINA_ID del usuario logueado con la relacion solicitante
                $solicitudes = Solicitud::has('formularios')
                    ->whereHas('solicitante', function ($query) {
                        $query->where('OFICINA_ID', Auth::user()->OFICINA_ID);
                    })
                    ->where('SOLICITUD_ESTADO', '!=', 'ELIMINADO')
                    ->orderBy('created_at', 'desc')
                    ->get();
            } else {
                // Si el usuario es otro tipo de usuario, mostrar solo sus solicitudes de formularios a traves de la relacion solicitante y la sesion activa
                $solicitudes = Solicitud::has('formularios')
                    ->where('USUARIO_id', Auth::user()->id)
                    ->where('SOLICITUD_ESTADO', '!=', 'ELIMINADO')
                    ->orderBy('created_at', 'desc')
                    ->get();
            }
            // Retornar la vista con las solicitudes
            return view('sia2.solicitudes.formularios.index', compact('solicitudes'));
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
                'SOLICITUD_FECHA_HORA_INICIO_SOLICITADA' => 'nullable|date'
                // 'SOLICITUD_FECHA_HORA_TERMINO_SOLICITADA' => 'required|date|after:SOLICITUD_FECHA_HORA_INICIO_SOLICITADA',
            ], [
                //Mensajes de error
                'SOLICITUD_MOTIVO.required' => 'El campo Motivo es requerido.',
                'SOLICITUD_MOTIVO.string' => 'El campo Motivo debe ser una cadena de caracteres.',
                'SOLICITUD_MOTIVO.max' => 'El campo Motivo no debe exceder los 255 caracteres.',
                'SOLICITUD_FECHA_HORA_INICIO_SOLICITADA.date' => 'La fecha de inicio solicitada debe ser una fecha.',
                'SOLICITUD_FECHA_HORA_INICIO_SOLICITADA.date' => 'La fecha de inicio solicitada debe ser una fecha.',
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
                'SOLICITUD_FECHA_HORA_INICIO_SOLICITADA' => $request->input('SOLICITUD_FECHA_HORA_INICIO_SOLICITADA') ?: null,
                'SOLICITUD_FECHA_HORA_TERMINO_SOLICITADA' => null, // Valor predeterminado
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
        try{
            // Obtener la solicitud a actualizar
            $solicitud = Solicitud::has('formularios')->findOrFail($id);

            // Determinar la acción basada en el botón presionado
            switch ($request->input('action')) {
                case 'guardar':
                    // Validar los datos del formulario (valores a recibir SOLICITUD_FECHA_HORA_INICIO_ASIGNADA, REVISON_SOLICITUD_OBSERVACION)
                    $validator = Validator::make($request->all(),[
                        'SOLICITUD_FECHA_HORA_INICIO_ASIGNADA' => 'nullable|date',
                        'REVISION_SOLICITUD_OBSERVACION' => 'nullable|string|max:255',
                    ], [
                        //Mensajes de error
                        'SOLICITUD_FECHA_HORA_INICIO_ASIGNADA.date' => 'La fecha de entrega debe ser una fecha.',
                        'REVISION_SOLICITUD_OBSERVACION.string' => 'El campo Observación debe ser una cadena de caracteres.',
                    ]);
                    // Lógica para guardar cambios
                    $solicitud->update(['SOLICITUD_ESTADO' => 'EN REVISION']);
                break;

                case 'finalizar_revision':
                    // Validar los datos del formulario (valores a recibir SOLICITUD_FECHA_HORA_INICIO_ASIGNADA)
                    $validator = Validator::make($request->all(),[
                        'SOLICITUD_FECHA_HORA_INICIO_ASIGNADA' => 'required|date',
                    ], [
                        //Mensajes de error
                        'SOLICITUD_FECHA_HORA_INICIO_ASIGNADA.required' => 'La fecha de entrega es requerida.',
                        'SOLICITUD_FECHA_HORA_INICIO_ASIGNADA.date' => 'La fecha de entrega debe ser una fecha.',
                    ]);
                    // Si la validación falla, se redirecciona al formulario con los errores
                    if ($validator->fails()) {
                        return redirect()->back()->withErrors($validator)->withInput();
                    }
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
                    return redirect()->route('solicitudes.formularios.index')->with('success', 'Solicitud rechazada correctamente.');
                break;

                // default:
                    // Lógica por defecto o para casos no contemplados
                    // break;
            }

            // Actualizar la solicitud
            $solicitud->update([
                // 'SOLICITUD_ESTADO' => $request->input('SOLICITUD_ESTADO'),
                'SOLICITUD_FECHA_HORA_INICIO_ASIGNADA' => $request->input('SOLICITUD_FECHA_HORA_INICIO_ASIGNADA'),
                // 'SOLICITUD_FECHA_HORA_TERMINO_ASIGNADA' => $request->input('SOLICITUD_FECHA_HORA_TERMINO_ASIGNADA'),
            ]);

            // Llamar a la funcion createRevisionSolicitud para crear la revision de la solicitud
            $this->createRevisionSolicitud($request, $solicitud);

            // Redireccionar a la vista de solicitudes si ambas cosas se realizaron correctamente
            return redirect()->route('solicitudes.formularios.index')->with('success', 'Solicitud actualizada correctamente.');
        }catch(Exception $e){
            // Manejo de excepciones
            return redirect()->route('solicitudes.formularios.index')->with('error', 'Error al actualizar la solicitud.');
        }
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

            // Cambiar estado
            $solicitud->SOLICITUD_ESTADO = 'ELIMINADO';

            // Guardar la solicitud eliminada
            $solicitud->save();


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
                'REVISION_SOLICITUD_OBSERVACION' => $request->input('REVISION_SOLICITUD_OBSERVACION') ?: 'Sin observación.',
            ]);
        }
        catch(Exception $e)
        {
            // Manejo de excepciones
            return redirect()->route('solicitudes.formularios.index')->with('error', 'Error al crear la revisión de la solicitud.');
        }
    }
}
