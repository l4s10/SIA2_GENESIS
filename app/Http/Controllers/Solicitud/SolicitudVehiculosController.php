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
use App\Models\Vehiculo;
use App\Models\Viaja;

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
                    dd($validator);
                    return redirect()->back()->withErrors($validator)->withInput();
                }

            // Crear una nueva instancia de SolicitudVehicular y asignar los valores
            $solicitud = new SolicitudVehicular();
            $solicitud->USUARIO_id = auth()->user()->id;
            $solicitud->TIPO_VEHICULO_ID = $request->input('TIPO_VEHICULO_ID');
            $solicitud->SOLICITUD_VEHICULO_COMUNA_ORIGEN = $request->input('SOLICITUD_VEHICULO_COMUNA_ORIGEN');
            $solicitud->SOLICITUD_VEHICULO_COMUNA_DESTINO = $request->input('SOLICITUD_VEHICULO_COMUNA_DESTINO');
            $solicitud->SOLICITUD_VEHICULO_MOTIVO = $request->input('SOLICITUD_VEHICULO_MOTIVO');
            $solicitud->SOLICITUD_VEHICULO_ESTADO = 'INGRESADO'; // Valor por defecto
            $solicitud->SOLICITUD_VEHICULO_FECHA_HORA_INICIO_SOLICITADA = $request->input('SOLICITUD_VEHICULO_FECHA_HORA_INICIO_SOLICITADA');
            $solicitud->SOLICITUD_VEHICULO_FECHA_HORA_TERMINO_SOLICITADA = $request->input('SOLICITUD_VEHICULO_FECHA_HORA_TERMINO_SOLICITADA');
            //dd($solicitud);
            $solicitud->save();
            //    dd($solicitud->SOLICITUD_VEHICULO_ID);
            // Obtener los pasajeros seleccionados y asociarlos con la solicitud vehicular
            $pasajeros = [];
            foreach ($request->all() as $key => $value) {
                if (strpos($key, 'PASAJERO_') === 0) {
                    $pasajeros[] = $value;
                }
            }

            foreach ($pasajeros as $pasajeroId) {
                $viaja = new Viaja();
                $viaja->USUARIO_id = $pasajeroId;
                $viaja->SOLICITUD_VEHICULO_ID = $solicitud->SOLICITUD_VEHICULO_ID;
                $viaja->save();
            }
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
    public function edit($id)
    {
        try {
            // Encontrar la solicitud por su ID
            $solicitud = SolicitudVehicular::findOrFail($id);
 
            // Obtener conductor y pasajeros que viajan en esta solicitud
            $pasajeros = $solicitud->viajan()->get();


            // Obtener los vehículos filtrados por el tipo de vehículo solicitado, en la oficina del solicitante y oficina del usuario en sesion
            $vehiculos = Vehiculo::where('TIPO_VEHICULO_ID', $solicitud->tipoVehiculo->TIPO_VEHICULO_ID)
            ->where(function ($query) use ($solicitud) {
                $query->whereHas('ubicacion', function ($subquery) use ($solicitud) {
                    $subquery->where('OFICINA_ID', $solicitud->user->OFICINA_ID); // comparación con oficina de solicitante para vehículos asociados con ubicaciones
                })->whereHas('departamento', function ($subquery) use ($solicitud) {
                    $subquery->where('OFICINA_ID', $solicitud->user->OFICINA_ID);  // comparación con oficina de solicitante para vehículos asociados con departamentos
                });
            })
            // Comparación entre la ubicación asociada al vehículo y la oficina del usuario en sesión activa
            ->whereHas('ubicacion', function ($subquery) {
                $subquery->where('OFICINA_ID', auth()->user()->OFICINA_ID);
            })
            // Comparación entre el departamento asociado al vehículo y la oficina del usuario en sesión activa
            ->orWhereHas('departamento', function ($subquery) {
                $subquery->where('OFICINA_ID', auth()->user()->OFICINA_ID);
            })
            ->get();

       
            // Retornar la vista de edición con los datos de la solicitud y los usuarios que viajan
            return view('sia2.solicitudes.vehiculos.edit', compact('solicitud', 'pasajeros','vehiculos'));
        } catch (ModelNotFoundException $e) {
            // Manejar excepción de modelo no encontrado
            return redirect()->route('solicitudesvehiculos.index')->with('error', 'Ocurrió un error inesperado.');
        } catch (Exception $e) {
            // Manejar excepciones si es necesario
            return redirect()->route('solicitudesvehiculos.index')->with('error', 'Error al cargar la solicitud de vehículo para editar.');
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