<?php

namespace App\Http\Controllers\Solicitud;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;
use Illuminate\Support\Facades\Auth;


// Importar modelos
use App\Models\SolicitudVehicular;
use App\Models\Oficina;
use App\Models\Region;
use App\Models\Comuna;
use App\Models\Ubicacion;
use App\Models\Departamento;
use App\Models\User;


use App\Models\TipoVehiculo;



class SolicitudVehiculosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Try-catch para el manejo de excepciones
        try {
            // Obtener la oficina del usuario actual
            $oficinaIdUsuario = Auth::user()->OFICINA_ID;

            // Obtener las solicitudes vehiculares realizadas por usuarios de la oficina correspondiente
            $solicitudes = SolicitudVehicular::whereHas('user', function ($query) use ($oficinaIdUsuario) {
                $query->where('OFICINA_ID', $oficinaIdUsuario);
            })->get();



            // Retornar la vista con las solicitudes
            return view('sia2.solicitudes.vehiculos.index', compact('solicitudes'));
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
            $oficinas = Oficina::all();
            $ubicaciones = Ubicacion::all();
            $departamentos = Departamento::all();
            $regiones = Region::all();
            $comunas = Comuna::all();
            $users = User::all();
            // Obtener tipos de vehículos basados en la OFICINA_ID del usuario
            $tiposVehiculos = TipoVehiculo::where('OFICINA_ID', Auth::user()->OFICINA_ID)->get();

            return view('sia2.solicitudes.vehiculos.create', compact('tiposVehiculos','oficinas','ubicaciones','departamentos','regiones', 'comunas', 'users'));
        } catch (Exception $e) {
            // Manejar excepciones si es necesario
            return redirect()->route('solicitudes.index')->with('error', 'Error al cargar la solicitud de vehículo.');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Intentar guardar la solicitud en la base de datos
        try {
            // Validar los datos de entrada
            $validator = Validator::make($request->all(), [
                'SOLICITUD_VEHICULO_COMUNA_ORIGEN' => 'required|exists:comunas,COMUNA_ID',
                'SOLICITUD_VEHICULO_COMUNA_DESTINO' => 'required|exists:comunas,COMUNA_ID',
                'SOLICITUD_VEHICULO_MOTIVO' => 'required|string|max:255',
                'SOLICITUD_VEHICULO_FECHA_HORA_INICIO_SOLICITADA' => 'required|date',
                'SOLICITUD_VEHICULO_FECHA_HORA_TERMINO_SOLICITADA' => 'required|date|after:SOLICITUD_VEHICULO_FECHA_HORA_INICIO_SOLICITADA',
            ], [
                'SOLICITUD_VEHICULO_COMUNA_ORIGEN.required' => 'El campo Comuna de Origen es obligatorio.',
                'SOLICITUD_VEHICULO_COMUNA_DESTINO.required' => 'El campo Comuna de Destino es obligatorio.',
                'SOLICITUD_VEHICULO_MOTIVO.required' => 'El campo Motivo es obligatorio.',
                'SOLICITUD_VEHICULO_MOTIVO.max' => 'El campo Motivo no puede tener más de 255 caracteres.',
                'SOLICITUD_VEHICULO_FECHA_HORA_INICIO_SOLICITADA.required' => 'El campo Fecha y Hora de Inicio Solicitada es obligatorio.',
                'SOLICITUD_VEHICULO_FECHA_HORA_TERMINO_SOLICITADA.required' => 'El campo Fecha y Hora de Término Solicitada es obligatorio.',
                'SOLICITUD_VEHICULO_FECHA_HORA_TERMINO_SOLICITADA.after' => 'El campo Fecha y Hora de Término Solicitada debe ser posterior a la Fecha y Hora de Inicio Solicitada.'
            ]);

            // Manejar errores de validación
                if ($validator->fails()) {
                    dd($request);
                    return redirect()->back()->withErrors($validator)->withInput();
                }

            // Crear una nueva instancia de SolicitudVehicular y asignar los valores
            $solicitud = new SolicitudVehicular();
            $solicitud->USUARIO_id = auth()->user()->id;
            $solicitud->SOLICITUD_VEHICULO_COMUNA_ORIGEN = $request->input('SOLICITUD_VEHICULO_COMUNA_ORIGEN');
            $solicitud->SOLICITUD_VEHICULO_COMUNA_DESTINO = $request->input('SOLICITUD_VEHICULO_COMUNA_DESTINO');
            $solicitud->SOLICITUD_VEHICULO_MOTIVO = $request->input('SOLICITUD_VEHICULO_MOTIVO');
            $solicitud->SOLICITUD_VEHICULO_ESTADO = 'INGRESADO'; // Valor por defecto
            $solicitud->SOLICITUD_VEHICULO_FECHA_HORA_INICIO_SOLICITADA = $request->input('SOLICITUD_VEHICULO_FECHA_HORA_INICIO_SOLICITADA');
            $solicitud->SOLICITUD_VEHICULO_FECHA_HORA_TERMINO_SOLICITADA = $request->input('SOLICITUD_VEHICULO_FECHA_HORA_TERMINO_SOLICITADA');
            //dd($solicitud);

            $solicitud->save();
            return redirect()->route('solicitudesvehiculos.index')->with('success', 'Solicitud creada exitosamente.');
        } catch (Exception $e) {
            dd($e);
            return redirect()->back()->with('error', 'Error al crear la solicitud. Inténtelo de nuevo.');
        }
    }

    /**
     * Display the specified resource.
     */
   /* public function show(string $id)
    {
        try {
            // Recuperar la solicitud con sus materiales asociados
            $solicitud = Solicitud::has('materiales')->findOrFail($id);
            // Retornar la vista con la solicitud
            return view('sia2.solicitudes.materiales.show', compact('solicitud'));
        } catch (Exception $e) {
            // Manejar excepciones si la solicitud no se encuentra o hay algún error manejable
            return redirect()->route('solicitudesmateriales.index')->with('error', 'Error al mostrar la solicitud.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
 /*   public function edit(string $id)
    {
        // Try-catch para el manejo de excepciones
        try {
            // Obtener la oficina del usuario actual
            $oficinaIdUsuario = Auth::user()->OFICINA_ID;

            // Consulta SQL para obtener vehículos asociados a la oficina del usuario
            $vehiculos = Vehiculo::select('VEHICULOS.*')
                ->leftJoin('UBICACIONES', 'VEHICULOS.UBICACION_ID', '=', 'UBICACIONES.UBICACION_ID')
                ->leftJoin('DEPARTAMENTOS', 'VEHICULOS.DEPARTAMENTO_ID', '=', 'DEPARTAMENTOS.DEPARTAMENTO_ID')
                ->where(function($query) use ($oficinaIdUsuario) {
                    $query->where('UBICACIONES.OFICINA_ID', $oficinaIdUsuario)
                        ->whereNull('VEHICULOS.DEPARTAMENTO_ID');
                })
                ->orWhere(function($query) use ($oficinaIdUsuario) {
                    $query->where('DEPARTAMENTOS.OFICINA_ID', $oficinaIdUsuario)
                        ->whereNull('VEHICULOS.UBICACION_ID');
                })
                ->get();
            // Retornar la vista del formulario con los materiales y el carrito
            return view('sia2.solicitudes.vehiculos.edit', compact('vehiculos'));
        } catch (Exception $e) {
            // Manejar excepciones si es necesario
            return redirect()->route('solicitudes.index')->with('error', 'Error al cargar los materiales.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
  /*  public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
 /*   public function destroy($id)
    {
        //Try catch
        try {
            // Busca la solicitud con sus materiales asociados
            $solicitud = Solicitud::has('materiales')->findOrFail($id);

            //Eliminar registros asociados a esta solicitud en la tabla solicitud_material (para no tener problemas de parent row not found)
            $solicitud->materiales()->detach();

            // Elimina la solicitud
            $solicitud->delete();

            // Puedes agregar un mensaje de éxito si lo deseas
            return redirect()->route('solicitudesmateriales.index')->with('success', 'Solicitud eliminada exitosamente');
        } catch (Exception $e) {
            // Manejar excepciones si es necesario
            return redirect()->route('solicitudesmateriales.index')->with('error', 'Error al eliminar la solicitud.');
        }
    }*/

}