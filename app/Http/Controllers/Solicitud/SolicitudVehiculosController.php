<?php

namespace App\Http\Controllers\Solicitud;

use App\Http\Controllers\Controller;
use App\Exports\VehiculosExport; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; // Asegúrate de importar Log
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Carbon\Carbon; 
use Dompdf\Dompdf;


use Exception;


// Importar modelos
use App\Models\SolicitudVehicular;
use App\Models\Oficina;
use App\Models\Region;
use App\Models\Comuna;
use App\Models\Ubicacion;
use App\Models\Departamento;
use App\Models\User;
use App\Models\Vehiculo;
use App\Models\TipoVehiculo;
use App\Models\Pasajero;
use App\Models\Cargo;
use App\Models\RevisionSolicitud;
use App\Models\Autorizacion;
use App\Models\OrdenDeTrabajo;
use App\Models\Poliza;
use GuzzleHttp\Psr7\Message;

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
            $oficinaSesion =  Auth::user()->OFICINA_ID;

            // Obtener vehículos basados en la OFICINA_ID del usuario
            $vehiculos = Vehiculo::with('tipoVehiculo')->where(function ($query) use ($oficinaSesion) {
                $query->whereHas('ubicacion', function ($subquery) use ($oficinaSesion) {
                    $subquery->where('OFICINA_ID', $oficinaSesion);
                });
            })
            ->orWhere(function ($query) use ($oficinaSesion) {
                $query->whereHas('departamento', function ($subquery) use ($oficinaSesion) {
                    $subquery->where('OFICINA_ID', $oficinaSesion);
                });
            })
            ->get();
            // Obtener los jefes que autorizan en la dirección regional
            $jefesQueAutorizan = Cargo::where('OFICINA_ID', Auth::user()->OFICINA_ID)
                ->where('CARGO_NOMBRE', 'like', 'JEFE %')
                ->get();

            // Obtener los conductores (usuarios con pólizas) de la dirección regional.
            $conductores = User::whereHas('polizas', function ($query) use ($oficinaSesion) {
                $query->where('OFICINA_ID', $oficinaSesion);
            })->get();
            //dd($vehiculos);

        return view('sia2.solicitudes.vehiculos.create', compact('vehiculos','oficinas','ubicaciones','departamentos','regiones', 'comunas', 'users', 'jefesQueAutorizan', 'conductores'));
        } catch (Exception $e) {
            dd($e);
            // Manejar excepciones si es necesario
            return redirect()->route('solicitudesvehiculos.index')->with('error', 'Error al intentar crear la solicitud de vehículo.');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //dd($request);
        // Intentar guardar la solicitud en la base de datos
        try {
            // Validar los datos de entrada
            $validatorRules = [
                'VEHICULO_ID' => 'required|exists:vehiculos,VEHICULO_ID',
                'PASAJERO_1' => 'required|exists:users,id',
                'SOLICITUD_VEHICULO_COMUNA' => 'required|exists:comunas,COMUNA_ID',
                'SOLICITUD_VEHICULO_MOTIVO' => 'required|string|max:255',
                'SOLICITUD_VEHICULO_FECHA_HORA_INICIO_SOLICITADA' => 'required|date',
                'SOLICITUD_VEHICULO_FECHA_HORA_TERMINO_SOLICITADA' => 'required|date|after_or_equal:SOLICITUD_VEHICULO_FECHA_HORA_INICIO_SOLICITADA',
                'SOLICITUD_VEHICULO_JEFE_QUE_AUTORIZA' => 'required|string|max:128',
                'SOLICITUD_VEHICULO_VIATICO' => 'required|string|max:4',
                'SOLICITUD_VEHICULO_HORA_INICIO_CONDUCCION' => 'required|date_format:H:i',
                'SOLICITUD_VEHICULO_HORA_TERMINO_CONDUCCION' => 'required|date_format:H:i|after_or_equal:SOLICITUD_VEHICULO_HORA_INICIO_CONDUCCION',
            ];

            // Validar si los datos están presentes en la solicitud
            if ($request->has('TRABAJA_NUMERO_ORDEN_TRABAJO') && $request->has('TRABAJA_HORA_INICIO_ORDEN_TRABAJO') && $request->has('TRABAJA_HORA_TERMINO_ORDEN_TRABAJO')) {
                // Realizar la validación de los datos recibidos
                $validatorRules = array_merge($validatorRules, [
                    'TRABAJA_NUMERO_ORDEN_TRABAJO' => 'required|integer|min:0|max:999999',
                    'TRABAJA_HORA_INICIO_ORDEN_TRABAJO' => 'required|date_format:H:i',
                    'TRABAJA_HORA_TERMINO_ORDEN_TRABAJO' => 'required|date_format:H:i|after_or_equal:TRABAJA_HORA_INICIO_ORDEN_TRABAJO',
                ]);
            }
    
            $validator = Validator::make($request->all(), $validatorRules, [
                'SOLICITUD_VEHICULO_COMUNA.required' => 'El campo "comuna destino" es obligatorio.',
                'SOLICITUD_VEHICULO_COMUNA.exists' => 'La comuna seleccionada no es válida.',
                'SOLICITUD_VEHICULO_MOTIVO.required' => 'El motivo de la solicitud es obligatorio.',
                'SOLICITUD_VEHICULO_MOTIVO.string' => 'El motivo de la solicitud debe ser una cadena de caracteres.',
                'SOLICITUD_VEHICULO_MOTIVO.max' => 'El motivo de la solicitud no puede tener más de 255 caracteres.',
                'SOLICITUD_VEHICULO_FECHA_HORA_INICIO_SOLICITADA.required' => 'La fecha y hora de salida del vehículo es obligatoria.',
                'SOLICITUD_VEHICULO_FECHA_HORA_INICIO_SOLICITADA.date' => 'La fecha y hora de inicio de la solicitud debe ser válida.',
                'SOLICITUD_VEHICULO_FECHA_HORA_TERMINO_SOLICITADA.required' => 'La fecha y hora de reingreso del vehículo es obligatoria.',
                'SOLICITUD_VEHICULO_FECHA_HORA_TERMINO_SOLICITADA.date' => 'El campo fecha y hora de término de solicitud debe ser una fecha válida.',
                'SOLICITUD_VEHICULO_FECHA_HORA_TERMINO_SOLICITADA.after_or_equal' => 'La fecha y hora de término de solicitud debe ser posterior o igual a la fecha y hora de inicio de solicitud.',
                'SOLICITUD_VEHICULO_JEFE_QUE_AUTORIZA.required' => 'El campo "Jefe que autoriza" es obligatorio.',
                'SOLICITUD_VEHICULO_JEFE_QUE_AUTORIZA.string' => 'El jefe que autoriza debe ser una cadena de caracteres.',
                'SOLICITUD_VEHICULO_JEFE_QUE_AUTORIZA.max' => 'El jefe que autoriza no puede tener más de 128 caracteres.',
                'VEHICULO_ID.required' => 'El campo Vehículo es obligatorio.',
                'VEHICULO_ID.exists' => 'El vehículo seleccionado no es válido.',
                'PASAJERO_1.required' => 'El campo Conductor es obligatorio.',
                'PASAJERO_1.exists' => 'El conductor seleccionado no es válido.',
                'SOLICITUD_VEHICULO_VIATICO.required' => 'El campo Viático es obligatorio.',
                'SOLICITUD_VEHICULO_VIATICO.string' => 'El campo Viático debe ser una cadena de caracteres.',
                'SOLICITUD_VEHICULO_VIATICO.max' => 'El campo Viático no puede tener más de :max caracteres.',
                'SOLICITUD_VEHICULO_HORA_INICIO_CONDUCCION.required' => 'El campo Hora de inicio de conducción es obligatorio.',
                'SOLICITUD_VEHICULO_HORA_TERMINO_CONDUCCION.required' => 'El campo Hora de término de conducción es obligatorio.',
                'SOLICITUD_VEHICULO_HORA_TERMINO_CONDUCCION.after' => 'La hora de término de conducción debe ser posterior a la hora de inicio de conducción.',
                'TRABAJA_NUMERO_ORDEN_TRABAJO.required' => 'El número de orden de trabajo es obligatorio.',
                'TRABAJA_NUMERO_ORDEN_TRABAJO.integer' => 'El número de orden de trabajo debe ser un número entero.',
                'TRABAJA_NUMERO_ORDEN_TRABAJO.min' => 'El número de orden de trabajo debe ser mínimo 0.',
                'TRABAJA_NUMERO_ORDEN_TRABAJO.max' => 'El número de orden de trabajo debe ser máximo 999999.',
                'TRABAJA_HORA_INICIO_ORDEN_TRABAJO.required' => 'La hora de inicio de la orden de trabajo es obligatoria.',
                'TRABAJA_HORA_INICIO_ORDEN_TRABAJO.date_format' => 'El formato de la hora de inicio de la orden de trabajo no es válido.',
                'TRABAJA_HORA_TERMINO_ORDEN_TRABAJO.required' => 'La hora de término de la orden de trabajo es obligatoria.',
                'TRABAJA_HORA_TERMINO_ORDEN_TRABAJO.date_format' => 'El formato de la hora de término de la orden de trabajo no es válido.',
                'TRABAJA_HORA_TERMINO_ORDEN_TRABAJO.after_or_equal' => 'La hora de término de la orden de trabajo debe ser posterior a la hora de inicio de la orden de trabajo.',
            ]);
    
            // Manejar errores de validación
            if ($validator->fails()) {
                //dd($validator->errors()->all());
                return redirect()->back()->withErrors($validator)->withInput();
            }
        
            // Crear una nueva instancia de SolicitudVehicular y asignar los valores
            $solicitud = new SolicitudVehicular();
            $solicitud->USUARIO_id = Auth::user()->id;
            $solicitud->VEHICULO_ID = $request->input('VEHICULO_ID');
            $solicitud->CONDUCTOR_id = $request->input('PASAJERO_1');
            $solicitud->COMUNA_ID = $request->input('SOLICITUD_VEHICULO_COMUNA');
            $solicitud->SOLICITUD_VEHICULO_MOTIVO = strtoupper($request->input('SOLICITUD_VEHICULO_MOTIVO'));
            $solicitud->SOLICITUD_VEHICULO_ESTADO = 'INGRESADO'; // Valor por defecto
            // Formatear las fechas y horas usando Carbon
            $solicitud->SOLICITUD_VEHICULO_FECHA_HORA_INICIO_SOLICITADA = Carbon::createFromFormat('d-m-Y H:i', $request->input('SOLICITUD_VEHICULO_FECHA_HORA_INICIO_SOLICITADA'))->format('Y-m-d H:i:s');
            $solicitud->SOLICITUD_VEHICULO_FECHA_HORA_TERMINO_SOLICITADA = Carbon::createFromFormat('d-m-Y H:i', $request->input('SOLICITUD_VEHICULO_FECHA_HORA_TERMINO_SOLICITADA'))->format('Y-m-d H:i:s');
            $solicitud->SOLICITUD_VEHICULO_HORA_INICIO_CONDUCCION = Carbon::createFromFormat('H:i', $request->input('SOLICITUD_VEHICULO_HORA_INICIO_CONDUCCION'))->format('H:i:s');
            $solicitud->SOLICITUD_VEHICULO_HORA_TERMINO_CONDUCCION = Carbon::createFromFormat('H:i', $request->input('SOLICITUD_VEHICULO_HORA_TERMINO_CONDUCCION'))->format('H:i:s');
    
            $solicitud->SOLICITUD_VEHICULO_JEFE_QUE_AUTORIZA = $request->input('SOLICITUD_VEHICULO_JEFE_QUE_AUTORIZA');
            $solicitud->SOLICITUD_VEHICULO_VIATICO = $request->input('SOLICITUD_VEHICULO_VIATICO');
            $solicitud->save();
          
            // Validar si los datos están presentes en la solicitud
            if ($request->has('TRABAJA_NUMERO_ORDEN_TRABAJO') && $request->has('TRABAJA_HORA_INICIO_ORDEN_TRABAJO') && $request->has('TRABAJA_HORA_TERMINO_ORDEN_TRABAJO')) {
                $ordenDeTrabajo = new OrdenDeTrabajo();
                $ordenDeTrabajo->SOLICITUD_VEHICULO_ID = $solicitud->SOLICITUD_VEHICULO_ID;
                $ordenDeTrabajo->ORDEN_TRABAJO_NUMERO = $request->input('TRABAJA_NUMERO_ORDEN_TRABAJO');
                $ordenDeTrabajo->ORDEN_TRABAJO_HORA_INICIO = $request->input('TRABAJA_HORA_INICIO_ORDEN_TRABAJO');
                $ordenDeTrabajo->ORDEN_TRABAJO_HORA_TERMINO = $request->input('TRABAJA_HORA_TERMINO_ORDEN_TRABAJO');
                $ordenDeTrabajo->save();

            }
    

    
            // Obtener las IDs de los pasajeros de la solicitud
            $pasajerosIds = [];
            foreach ($request->all() as $key => $value) {
                if (strpos($key, 'PASAJERO_') === 0) {
                    $pasajerosIds[] = $value;
                }
            }
    
            // Omitir el primer elemento (conductor) del array
            $pasajerosIds = array_slice($pasajerosIds, 1);
    
            foreach ($pasajerosIds as $pasajeroId) {
                // Verificar si el pasajero ya está asociado a la solicitud
                $existePasajero = Pasajero::where('USUARIO_id', $pasajeroId)
                    ->where('SOLICITUD_VEHICULO_ID', $solicitud->SOLICITUD_VEHICULO_ID)
                    ->exists();
    
                if (!$existePasajero) {
                    // Crear un nuevo pasajero y asociarlo a la solicitud
                    $pasajero = new Pasajero();
                    $pasajero->USUARIO_id = $pasajeroId;
                    $pasajero->SOLICITUD_VEHICULO_ID = $solicitud->SOLICITUD_VEHICULO_ID;
                    $pasajero->save();
                }
            }
    
            return redirect()->route('solicitudesvehiculos.index')->with('success', 'Solicitud creada exitosamente.');
        } catch (Exception $e) {
            Log::error($e->getMessage());
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
            $pasajeros = $solicitud->pasajeros()->get();

            // Obtener la OFICINA_ID del usuario actual
            $oficinaIdUsuario = Auth::user()->OFICINA_ID;

            // Obtener todas las oficinas (en caso de que el conductor venga desde otra dirección regional)
            $oficinas = Oficina::all();
            
            // Obtener todas las ubicaciones (en caso de que el conductor venga desde otra dirección regional)
            $ubicaciones = Ubicacion::all();

            // Obtener todos los departamentos (en caso de que el conductor venga desde otra dirección regional)
            $departamentos = Departamento::all();

            // Obtener los conductores (usuarios con pólizas) de la dirección regional.
            $conductores = User::whereHas('polizas', function ($query) use ($oficinaIdUsuario) {
                $query->where('OFICINA_ID', $oficinaIdUsuario);
            })->get();

            // Obtener a todos los usuarios para seleccionar conductor (en caso de que el conductor venga desde otra dirección regional)
            $users = User::all();
            
            // Obtener la fecha de creación y formatear
            $fechaCreacionFormateada = strtoupper(Carbon::parse($solicitud->created_at)->isoFormat('dddd, DD [DE] MMMM [DE] YYYY'));
            // Obtener el nombre del cargo del jefe que autoriza
            $cargoJefeQueAutoriza = Cargo::where('CARGO_ID', $solicitud->SOLICITUD_VEHICULO_JEFE_QUE_AUTORIZA)->firstOrFail();
            //dd($cargoJefeQueAutoriza);
            // Consulta SQL para obtener vehículos asociados a la oficina del usuario en sesión y también al usuario que realizó la solicitud (para maximizar la seguridad del módulo).
            $vehiculos = Vehiculo::select('VEHICULOS.*')
            ->leftJoin('UBICACIONES', 'VEHICULOS.UBICACION_ID', '=', 'UBICACIONES.UBICACION_ID')
            ->leftJoin('DEPARTAMENTOS', 'VEHICULOS.DEPARTAMENTO_ID', '=', 'DEPARTAMENTOS.DEPARTAMENTO_ID')
            ->where(function($query) use ($oficinaIdUsuario, $solicitud) {
                $query->where('UBICACIONES.OFICINA_ID', $oficinaIdUsuario)
                    ->whereNull('VEHICULOS.DEPARTAMENTO_ID');
            })
            ->orWhere(function($query) use ($oficinaIdUsuario, $solicitud) {
                $query->where('DEPARTAMENTOS.OFICINA_ID', $oficinaIdUsuario)
                    ->whereNull('VEHICULOS.UBICACION_ID');
            })
            ->where(function($query) use ($solicitud) {
                $query->where(function($query) use ($solicitud) {
                    $query->where('UBICACIONES.OFICINA_ID', $solicitud->user->OFICINA_ID)
                        ->orWhere('DEPARTAMENTOS.OFICINA_ID', $solicitud->user->OFICINA_ID);
                });
            })
            ->get();
        
            //dd($solicitud);
            // Retornar la vista de edición con los datos de la solicitud y los usuarios que viajan
            return view('sia2.solicitudes.vehiculos.edit', compact('solicitud', 'pasajeros', 'vehiculos','cargoJefeQueAutoriza','oficinas','ubicaciones','departamentos','users', 'fechaCreacionFormateada', 'conductores'));
        } catch (ModelNotFoundException $e) {
            // Manejar excepción de modelo no encontrado
            return redirect()->route('solicitudesvehiculos.index')->with('error', 'Ocurrió un error inesperado.');
        } catch (Exception $e) {
            // Manejar excepciones si es necesario
            return redirect()->route('solicitudesvehiculos.index')->with('error', 'Error al cargar la solicitud de vehículo para editar: ');
        }
    }
    

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        //dd($request);
        try {
            // Encontrar la solicitud por su ID
            $solicitud = SolicitudVehicular::findOrFail($id);
            
            // Validación de datos
            $validatorRules = [
                'VEHICULO_ID' => 'required|exists:vehiculos,VEHICULO_ID',
                'CONDUCTOR_id' => 'required|exists:users,id',
                'SOLICITUD_VEHICULO_VIATICO' => 'required|string|max:4',
                'SOLICITUD_VEHICULO_FECHA_HORA_INICIO_ASIGNADA' => 'required|date',
                'SOLICITUD_VEHICULO_FECHA_HORA_TERMINO_ASIGNADA' => 'required|date|after_or_equal:SOLICITUD_VEHICULO_FECHA_HORA_INICIO_ASIGNADA',
                'SOLICITUD_VEHICULO_HORA_INICIO_CONDUCCION' => 'required|date_format:H:i',
                'SOLICITUD_VEHICULO_HORA_TERMINO_CONDUCCION' => 'required|date_format:H:i|after_or_equal:SOLICITUD_VEHICULO_HORA_INICIO_CONDUCCION',
                'REVISION_SOLICITUD_OBSERVACION' => 'required|string|max:255'
            ];
            
            // Validar si los datos están presentes en la solicitud
            if ($request->has('TRABAJA_NUMERO_ORDEN_TRABAJO') && $request->has('TRABAJA_HORA_INICIO_ORDEN_TRABAJO') && $request->has('TRABAJA_HORA_TERMINO_ORDEN_TRABAJO')) {
                // Realizar la validación de los datos recibidos
                $validatorRules = array_merge($validatorRules, [
                    'TRABAJA_NUMERO_ORDEN_TRABAJO' => 'required|integer|min:0|max:999999',
                    'TRABAJA_HORA_INICIO_ORDEN_TRABAJO' => 'required|date_format:H:i',
                    'TRABAJA_HORA_TERMINO_ORDEN_TRABAJO' => 'required|date_format:H:i|after_or_equal:TRABAJA_HORA_INICIO_ORDEN_TRABAJO',
                ]);
            }


            $validator = Validator::make($request->all(), $validatorRules, [
                'VEHICULO_ID.required' => 'El campo Vehículo es obligatorio.',
                'VEHICULO_ID.exists' => 'El vehículo seleccionado no es válido.',
                'CONDUCTOR_id.required' => 'El campo Conductor es obligatorio.',
                'CONDUCTOR_id.exists' => 'El conductor seleccionado no es válido.',
                'SOLICITUD_VEHICULO_VIATICO.required' => 'El campo Viático es obligatorio.',
                'SOLICITUD_VEHICULO_VIATICO.string' => 'El campo Viático debe ser una cadena de caracteres.',
                'SOLICITUD_VEHICULO_VIATICO.max' => 'El campo Viático no puede tener más de :max caracteres.',
                'SOLICITUD_VEHICULO_FECHA_HORA_INICIO_ASIGNADA.required' => 'La fecha y hora de salida asignada es obligatorio.',
                'SOLICITUD_VEHICULO_FECHA_HORA_TERMINO_ASIGNADA.required' => 'La fecha y hora de regreso asignada es obligatorio.',
                'SOLICITUD_VEHICULO_FECHA_HORA_TERMINO_ASIGNADA.after_or_equal' => 'La fecha y hora de regreso asignada debe ser posterior a la fecha y hora de inicio asignada.',
                'SOLICITUD_VEHICULO_HORA_INICIO_CONDUCCION.required' => 'El campo Hora de inicio de conducción es obligatorio.',
                'SOLICITUD_VEHICULO_HORA_TERMINO_CONDUCCION.required' => 'El campo Hora de término de conducción es obligatorio.',
                'SOLICITUD_VEHICULO_HORA_TERMINO_CONDUCCION.after_or_equal' => 'La hora de término de conducción debe ser posterior a la hora de inicio de conducción.',
                'REVISION_SOLICITUD_OBSERVACION.required' => 'El campo Observaciones revisión es obligatorio.',
                'REVISION_SOLICITUD_OBSERVACION.string' => 'El campo Observaciones revisión debe ser una cadena de caracteres.',
                'REVISION_SOLICITUD_OBSERVACION.max' => 'El campo Observaciones revisión no puede tener más de :max caracteres.',
                'TRABAJA_NUMERO_ORDEN_TRABAJO.required' => 'El número de orden de trabajo es obligatorio.',
                'TRABAJA_NUMERO_ORDEN_TRABAJO.integer' => 'El número de orden de trabajo debe ser un número entero.',
                'TRABAJA_NUMERO_ORDEN_TRABAJO.min' => 'El número de orden de trabajo debe ser mínimo 0.',
                'TRABAJA_NUMERO_ORDEN_TRABAJO.max' => 'El número de orden de trabajo debe ser máximo 999999.',
                'TRABAJA_HORA_INICIO_ORDEN_TRABAJO.required' => 'La hora de inicio de la orden de trabajo es obligatoria.',
                'TRABAJA_HORA_INICIO_ORDEN_TRABAJO.date_format' => 'El formato de la hora de inicio de la orden de trabajo no es válido.',
                'TRABAJA_HORA_TERMINO_ORDEN_TRABAJO.required' => 'La hora de término de la orden de trabajo es obligatoria.',
                'TRABAJA_HORA_TERMINO_ORDEN_TRABAJO.date_format' => 'El formato de la hora de término de la orden de trabajo no es válido.',
                'TRABAJA_HORA_TERMINO_ORDEN_TRABAJO.after_or_equal' => 'La hora de término de la orden de trabajo debe ser posterior a la hora de inicio de la orden de trabajo.',
            ]);

            // Verificar si los datos de la orden de trabajo están presentes en la solicitud
            if ($request->has('TRABAJA_NUMERO_ORDEN_TRABAJO') && $request->has('TRABAJA_HORA_INICIO_ORDEN_TRABAJO') && $request->has('TRABAJA_HORA_TERMINO_ORDEN_TRABAJO')) {
                // Buscar la orden de trabajo asociada a la solicitud, si existe
                $ordenDeTrabajo = OrdenDeTrabajo::where('SOLICITUD_VEHICULO_ID', $solicitud->SOLICITUD_VEHICULO_ID)->first();
                
                if ($ordenDeTrabajo) {
                    // Si la orden de trabajo existe, actualiza sus campos
                    $ordenDeTrabajo->ORDEN_TRABAJO_NUMERO = $request->input('TRABAJA_NUMERO_ORDEN_TRABAJO');
                    $ordenDeTrabajo->ORDEN_TRABAJO_HORA_INICIO = $request->input('TRABAJA_HORA_INICIO_ORDEN_TRABAJO');
                    $ordenDeTrabajo->ORDEN_TRABAJO_HORA_TERMINO = $request->input('TRABAJA_HORA_TERMINO_ORDEN_TRABAJO');
                    $ordenDeTrabajo->save();
                } /*else {
                    // Si la orden de trabajo no existe, puedo crear una nueva (EN CASO DE QUE LO PIDAN) al revisar la solicitud.
                    $ordenDeTrabajo = new OrdenDeTrabajo();
                    $ordenDeTrabajo->SOLICITUD_VEHICULO_ID = $solicitud->SOLICITUD_VEHICULO_ID;
                    $ordenDeTrabajo->ORDEN_TRABAJO_NUMERO = $request->input('TRABAJA_NUMERO_ORDEN_TRABAJO');
                    $ordenDeTrabajo->ORDEN_TRABAJO_HORA_INICIO = $request->input('TRABAJA_HORA_INICIO_ORDEN_TRABAJO');
                    $ordenDeTrabajo->ORDEN_TRABAJO_HORA_TERMINO = $request->input('TRABAJA_HORA_TERMINO_ORDEN_TRABAJO');
                    $ordenDeTrabajo->save();
                }*/
            }

            //dd($request);
            // Manejo de errores de validación
            if ($validator->fails()) {

                //dd($validator);
                return redirect()->back()->withErrors($validator)->withInput();
            }
    
            // Actualizar los campos de la solicitud con los datos asociados a la revisión
            $solicitud->VEHICULO_ID = $request->input('VEHICULO_ID');
            $solicitud->CONDUCTOR_id = $request->input('CONDUCTOR_id');
            $solicitud->SOLICITUD_VEHICULO_FECHA_HORA_INICIO_ASIGNADA = $request->input('SOLICITUD_VEHICULO_FECHA_HORA_INICIO_ASIGNADA');
            $solicitud->SOLICITUD_VEHICULO_FECHA_HORA_TERMINO_ASIGNADA = $request->input('SOLICITUD_VEHICULO_FECHA_HORA_TERMINO_ASIGNADA');
            $solicitud->SOLICITUD_VEHICULO_VIATICO = strtoupper($request->input('SOLICITUD_VEHICULO_VIATICO'));
            $solicitud->SOLICITUD_VEHICULO_HORA_INICIO_CONDUCCION = $request->input('SOLICITUD_VEHICULO_HORA_INICIO_CONDUCCION');
            $solicitud->SOLICITUD_VEHICULO_HORA_TERMINO_CONDUCCION = $request->input('SOLICITUD_VEHICULO_HORA_TERMINO_CONDUCCION');
            // Guardar los cambios en la base de datos

    
            // Verificar si se envió el botón de autorizar
            if ($request->has('autorizar')) {
                // Registrar la autorización de la solicitud
                $autorizacion = new Autorizacion();
                $autorizacion->USUARIO_id =  Auth::user()->id;
                $autorizacion->SOLICITUD_VEHICULO_ID = $solicitud->SOLICITUD_VEHICULO_ID;     
                $autorizacion->save();          
            }

            // Verificar si se envió el botón de autorizar
            if ($request->has('guardar')) {
                // Registrar la revisión de la solicitud
                $revision = new RevisionSolicitud();
                $revision->USUARIO_id = Auth::user()->id;
                $revision->SOLICITUD_VEHICULO_ID = $solicitud->SOLICITUD_VEHICULO_ID;
                $revision->REVISION_SOLICITUD_OBSERVACION = $request->input('REVISION_SOLICITUD_OBSERVACION');
                $solicitud->SOLICITUD_VEHICULO_ESTADO = 'EN REVISIÓN'; 
                $revision->save();
            }
            $solicitud->save();




            // Actualizar los pasajeros asociados a la solicitud
            $this->actualizarPasajeros($solicitud, $request);
            //dd($solicitud);
            // Redirigir a la vista de edición con un mensaje de éxito
            return redirect()->route('solicitudesvehiculos.index')->with('success', 'Solicitud actualizada correctamente.');
        } catch (ModelNotFoundException $e) {
            // Manejar excepción de modelo no encontrado
            return redirect()->route('solicitudesvehiculos.index')->with('error', 'Ocurrió un error inesperado.');
        } catch (\Exception $e) {

            dd($e);
            // Manejar excepciones generales
            return redirect()->route('solicitudesvehiculos.index')->with('error', 'Error al actualizar la solicitud de vehículo.');
        }
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

    
    private function actualizarPasajeros($solicitud, $request)
    {
        $pasajerosIdsNuevos = collect();
    
        // Obtener los IDs de los pasajeros proporcionados en la solicitud actual
        foreach ($request->all() as $key => $value) {
            if (strpos($key, 'pasajero_') === 0) {
                $pasajerosIdsNuevos->push($value);
            }
        }
    
        // Eliminar todos los pasajeros asociados actualmente a la solicitud
        $solicitud->pasajeros()->delete();
    
        // Crear y asociar nuevos pasajeros a la solicitud
        foreach ($pasajerosIdsNuevos as $nuevoPasajeroId) {
            $solicitud->pasajeros()->create(['USUARIO_id' => $nuevoPasajeroId]);
        }
    }

    public function export()
    {
        try {
            // Obtener las solicitudes vehiculares para exportar
            $solicitudes = SolicitudVehicular::all();

            // Devolver un archivo Excel con los datos de las solicitudes
            return Excel::download(new VehiculosExport($solicitudes), 'solicitudes_vehiculares.xlsx'); // Utiliza el nombre correcto de la clase
        } catch (Exception $e) {
            // Manejar excepciones si es necesario
            return redirect()->back()->with('error', 'Error al exportar las solicitudes vehiculares.');
        }
    }

    public function descargarPlantilla(Request $request, $id)
    {
        try {
            // Cargar la plantilla Excel existente
            $plantillaFilePath = storage_path('excel/Hoja de salida.xlsx');
            $spreadsheet = IOFactory::load($plantillaFilePath);
            // Obtener la solicitud actual
            $solicitud = SolicitudVehicular::findOrFail($id);
            // Obtener orden de trabajo en caso de que exista:
            if ($solicitud->ordenTrabajo) {
                $ordenDeTrabajo = $solicitud->ordenTrabajo;
            }    




            // Definir los datos a llenar en las celdas
            $datos = [
                // Fecha de creación solicitud
                ['D5', strtoupper(Carbon::parse($solicitud->created_at)->locale('es_ES')->isoFormat('dddd'))],
                ['E5', $solicitud->created_at->format('j')],
                ['F5', strtoupper(Carbon::parse($solicitud->created_at)->locale('es_ES')->isoFormat('MMMM'))],
                ['G5', $solicitud->created_at->format('Y')],

                ['C7', $solicitud->user->USUARIO_NOMBRES.' '.$solicitud->user->USUARIO_APELLIDOS],
                ['H7', $solicitud->user->cargo->CARGO_NOMBRE],
                ['C8', $solicitud->user->ubicacion ? $solicitud->user->ubicacion->UBICACION_NOMBRE : $solicitud->user->departamento->DEPARTAMENTO_NOMBRE],
                ['H8', $solicitud->user->oficina->OFICINA_NOMBRE],



                ['C9', $solicitud->SOLICITUD_VEHICULO_MOTIVO],
                ['C10', strtoupper($solicitud->comunaDestino->COMUNA_NOMBRE)],


                // Fecha y hora de inicio solicitada
                ['C11', strtoupper(Carbon::parse($solicitud->SOLICITUD_VEHICULO_FECHA_HORA_INICIO_SOLICITADA)->isoFormat('dddd'))],
                ['D11', Carbon::parse($solicitud->SOLICITUD_VEHICULO_FECHA_HORA_INICIO_SOLICITADA)->format('j')],
                ['E11', strtoupper(Carbon::parse($solicitud->SOLICITUD_VEHICULO_FECHA_HORA_INICIO_SOLICITADA)->isoFormat('MMMM'))],
                ['F11', Carbon::parse($solicitud->SOLICITUD_VEHICULO_FECHA_HORA_INICIO_SOLICITADA)->format('Y')],
                ['H11', strtoupper(Carbon::parse($solicitud->SOLICITUD_VEHICULO_FECHA_HORA_INICIO_SOLICITADA)->format('H:i'))],

                // Fecha y hora de término solicitada
                ['C12', strtoupper(Carbon::parse($solicitud->SOLICITUD_VEHICULO_FECHA_HORA_TERMINO_SOLICITADA)->isoFormat('dddd'))],
                ['D12', Carbon::parse($solicitud->SOLICITUD_VEHICULO_FECHA_HORA_TERMINO_SOLICITADA)->format('j')],
                ['E12', strtoupper(Carbon::parse($solicitud->SOLICITUD_VEHICULO_FECHA_HORA_TERMINO_SOLICITADA)->isoFormat('MMMM'))],
                ['F12', Carbon::parse($solicitud->SOLICITUD_VEHICULO_FECHA_HORA_TERMINO_SOLICITADA)->format('Y')],
                ['H12', strtoupper(Carbon::parse($solicitud->SOLICITUD_VEHICULO_FECHA_HORA_INICIO_SOLICITADA)->format('H:i'))],


                // HORAS INICIO Y TERMINO CONDUCCIÓN
                ['H13', strtoupper(Carbon::parse($solicitud->SOLICITUD_VEHICULO_HORA_INICIO_CONDUCCION)->format('H:i'))],
                ['H14', strtoupper(Carbon::parse($solicitud->SOLICITUD_VEHICULO_HORA_TERMINO_CONDUCCION)->format('H:i'))],
                ['J14', strtoupper($solicitud->SOLICITUD_VEHICULO_VIATICO)],


                /*// ORDEN DE TRABAJO
                ['J11', $ordenDeTrabajo->ORDEN_TRABAJO_NUMERO],
                ['J12', strtoupper(Carbon::parse($ordenDeTrabajo->ORDEN_TRABAJO_HORA_INICIO)->format('H:i'))],
                ['J13', strtoupper(Carbon::parse($ordenDeTrabajo->ORDEN_TRABAJO_HORA_TERMINO)->format('H:i'))],*/


                // OBTENER PASAJEROS E IMPLEMENTAR LLENADO DE EXCEL PARA ELLOS.

                // FALTA INCORPORAR POLIZA PARA CONDUCTOR
                ['B28', strtoupper($solicitud->conductor->USUARIO_NOMBRES .' '. $solicitud->conductor->USUARIO_APELLIDOS)],
                // JEFE QUE AUTORIZA
                ['G28', strtoupper($solicitud->SOLICITUD_VEHICULO_JEFE_QUE_AUTORIZA)],
                
                // PATENTE
                ['G33', strtoupper($solicitud->vehiculo->VEHICULO_PATENTE)],
                // TIPO VEHICULO
                ['I33', strtoupper($solicitud->vehiculo->tipoVehiculo->TIPO_VEHICULO_NOMBRE)],



                // FOLIO
                ['J3', $solicitud->SOLICITUD_VEHICULO_ID]


            ];

            // Iterar sobre los datos y asignarlos a las celdas correspondientes
            foreach ($datos as $dato) {
                $celda = $dato[0]; // Coordenada de la celda, por ejemplo, 'C7'
                $valor = $dato[1]; // Valor a asignar a la celda
                $spreadsheet->getActiveSheet()->setCellValue($celda, $valor);
            }


            // Guardar los cambios en la plantilla Excel
            $writer = new Xlsx($spreadsheet);
            $tempFilePath = storage_path('app/public/Hoja de salida.xlsx');
            $writer->save($tempFilePath);
            $this->exportToPdf($request, $id);

            // Descargar el archivo con los cambios realizados
            return response()->download($tempFilePath, 'Hoja de salida.xlsx')->deleteFileAfterSend(false);

        } catch (Exception $e) {
            // Manejar errores si ocurre algún problema
            return redirect()->back()->with('error', 'Error al descargar la plantilla de Excel.');
        }
    }

    
    public function exportToPdf(Request $request, $id)
    {
        try {
            // Ruta del archivo Excel generado
            $excelFilePath = storage_path('app/public/Hoja_Test_Modificada.xlsx');
            // Verificar la existencia del archivo Excel
            if (!file_exists($excelFilePath)) {
                throw new \Exception('El archivo Excel no existe en la ruta especificada.');
            }
    
            // Nombre del archivo PDF de salida
            $pdfFileName = 'Hoja_Test_Modificada.pdf';
            
            // Cargar el archivo Excel
            $spreadsheet = IOFactory::load($excelFilePath);
            
            // Crear una instancia de Dompdf
            $dompdf = new Dompdf();
            
            // Crear un escritor HTML para el archivo Excel
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Html($spreadsheet);
            $htmlContent = $writer->generateSheetData();
    
            // Verificar el contenido HTML generado
            //dd($htmlContent);
    
            // Cargar el contenido HTML generado a partir del archivo Excel
            $dompdf->loadHtml($htmlContent);
            
            // Renderizar el PDF
            $dompdf->render();

            // Obtener los datos binarios del PDF
            $pdfOutput = $dompdf->output();

            // Guardar el PDF en el almacenamiento
            $pdfFilePath = storage_path('app/public/' . $pdfFileName);
            file_put_contents($pdfFilePath, $pdfOutput);

            // Descargar el archivo PDF
            return response()->download($pdfFilePath, $pdfFileName)->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            dd($e);
            // Manejar errores si ocurre algún problema
            return redirect()->back()->with('error', 'Error al exportar el archivo a PDF');
        }
    }
    



}


