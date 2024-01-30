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

class SolicitudReparacionesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
            // Cargar las solicitudes de reparaciones de la misma dirección del usuario logueado, haciendo 'match' con el USUARIO_id de la solicitud
            //!!TESTEAR QUERY.
            $solicitudes = SolicitudReparacion::whereHas('solicitante', function($query){
                $query->where('OFICINA_ID', Auth::user()->OFICINA_ID);
            })->get();

            // Retornar la vista con las solicitudes
            return view('sia2.solicitudes.reparacionesmantenciones.index', compact('solicitudes'));
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
            $solicitud = SolicitudReparacion::findOrFail($id);

            // Eliminar la solicitud
            $solicitud->delete();

            // Redirigir a la vista de solicitudes con mensaje de éxito
            return redirect()->route('solicitudes.reparaciones.index')->with('success', 'Solicitud eliminada exitosamente.');
        }catch(Exception $e){
            return redirect()->back()->with('error', 'Error al eliminar la solicitud.');
        }
    }
}
