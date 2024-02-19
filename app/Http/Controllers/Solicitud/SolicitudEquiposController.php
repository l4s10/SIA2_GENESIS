<?php

namespace App\Http\Controllers\Solicitud;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Gloudemans\Shoppingcart\Facades\Cart;
use Exception;
use App\Models\Solicitud;
use App\Models\TipoEquipo;
use App\Models\RevisionSolicitud;


class SolicitudEquiposController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
            // Query que a traves de la relacion has() filtra las solicitudes que SOLO tengan equipos asociados
            $solicitudes = Solicitud::has('equipos')->get();
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
            return redirect()->back()->with('error', 'Error al enviar la solicitud.'. $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try{
            // Retornar la vista con la solicitud
            $solicitud = Solicitud::has('equipos')->findOrFail($id);
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
        // try-catch
        try{
            // Valida los datos del formulario de solicitud de equipos.
            $validator = Validator::make($request->all(),[
                'SOLICITUD_ESTADO' => 'required|string|max:255|in:INGRESADO,EN REVISION,APROBADO,RECHAZADO,TERMINADO',
                'SOLICITUD_FECHA_HORA_INICIO_ASIGNADA' => 'required|date',
                'SOLICITUD_FECHA_HORA_TERMINO_ASIGNADA' => 'required|date|after:SOLICITUD_FECHA_HORA_INICIO_ASIGNADA',

                'REVISION_SOLICITUD_OBSERVACION' => 'required|string|max:255',
                'autorizar.*' => 'required|numeric|min:0', // Asegura que todos los valores en el array sean numéricos y no negativos
            ], [
                //Mensajes de error
                'required' => 'El campo :attribute es requerido.',
                'date' => 'El campo :attribute debe ser una fecha.',
                'after' => 'El campo :attribute debe ser una fecha posterior a la fecha de inicio solicitada.',
                'string' => 'El campo :attribute debe ser una cadena de caracteres.',
                'in' => 'El campo :attribute debe ser uno de los valores: INGRESADO, EN REVISION, APROBADO, RECHAZADO, TERMINADO',
                'numeric' => 'El campo :attribute debe ser un número.',
                'min' => 'El campo :attribute debe ser un número no negativo.'
            ]);

            // Si la validación falla, redirecciona al formulario con los errores y el input antiguo
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            // Obtener la solicitud
            $solicitud = Solicitud::has('equipos')->findOrFail($id);

            // Actualizar la solicitud
            $solicitud->update([
                'SOLICITUD_ESTADO' => $request->input('SOLICITUD_ESTADO'),
                'SOLICITUD_FECHA_HORA_INICIO_ASIGNADA' => $request->input('SOLICITUD_FECHA_HORA_INICIO_ASIGNADA'),
                'SOLICITUD_FECHA_HORA_TERMINO_ASIGNADA' => $request->input('SOLICITUD_FECHA_HORA_TERMINO_ASIGNADA'),
            ]);

            // Autorizar los equipos si corresponde
            $this->autorizarEquipos($request, $solicitud);

            // Crear la revisión de la solicitud
            $this->createRevisionSolicitud($request, $solicitud);

            // Redireccionar a la vista de solicitudes con un mensaje de éxito
            return redirect()->route('solicitudes.equipos.index')->with('success', 'Solicitud actualizada exitosamente');
        }catch(Exception $e){
            // Manejar excepciones
            return redirect()->route('solicitudes.equipos.index')->with('error', 'Error al cargar la solicitud.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try{
            // Obtener la solicitud
            $solicitud = Solicitud::findOrFail($id);
            // Desacoplar los equipos de la solicitud
            $solicitud->equipos()->detach();
            // Eliminar la solicitud
            $solicitud->delete();

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
                'REVISION_SOLICITUD_OBSERVACION' => $request->input('REVISION_SOLICITUD_OBSERVACION'),
            ]);
        }
        catch(Exception $e)
        {
            // Manejo de excepciones
            return redirect()->route('solicitudes.formularios.index')->with('error', 'Error al crear la revisión de la solicitud.');
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
