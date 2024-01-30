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

class SolicitudBodegasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
            // Query que a través de la relación has() filtra las solicitudes que SOLO tengan bodegas asociadas
            $solicitudes = Solicitud::has('bodegas')->get();

            // Retornar la vista con las solicitudes
            return view('sia2.solicitudes.bodegas.index', compact('solicitudes'));
        }catch(Exception $e){
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
                'required' => 'El campo :attribute es requerido.',
                'date' => 'El campo :attribute debe ser una fecha.',
                'after' => 'El campo :attribute debe ser una fecha posterior a la fecha de inicio solicitada.',
                'string' => 'El campo :attribute debe ser una cadena de caracteres.',
                'exists' => 'El campo :attribute no existe en la base de datos.'
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

            // Retornar la vista con la solicitud
            return view('sia2.solicitudes.bodegas.show', compact('solicitud'));
        }catch(Exception $e){
            return redirect()->back()->with('error', 'Error al cargar la solicitud.');
        }
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
        try{
            // Buscar la solicitud por ID
            $solicitud = Solicitud::has('bodegas')->findOrFail($id);

            // Eliminar asociacion de bodegas
            $solicitud->bodegas()->detach();

            // Eliminar la solicitud
            $solicitud->delete();

            // Redirigir a la vista de solicitudes con mensaje de éxito
            return redirect()->route('solicitudes.bodegas.index')->with('success', 'Solicitud eliminada exitosamente.');
        }catch(Exception $e){
            return redirect()->back()->with('error', 'Error al eliminar la solicitud.');
        }
    }
}
