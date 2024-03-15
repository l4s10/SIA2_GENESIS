<?php

namespace App\Http\Controllers\Solicitud;

use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use App\Exports\VehiculosExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; // Asegúrate de importar Log
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Hash; // Importa la clase Hash


use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Pdf\Dompdf;

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
use App\Models\Rendicion;

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

            // Formatear las fechas created_at en DD:MM:AA
            foreach ($solicitudes as $solicitud) {
                $solicitud->formatted_created_at = Carbon::parse($solicitud->created_at)->format('d-m-y H:i');
            }


            // Retornar la vista con las solicitudes
            return view('sia2.solicitudes.vehiculos.index', compact('solicitudes'));
        } catch (Exception $e) {
            // Manejar excepciones si es necesario
            return redirect()->back()->with('error', 'Error al cargar las solicitudes.');
        }
    }
    public function indexPorAprobar()
    {
        try {
            // Obtener la oficina del usuario actual
            $oficinaIdUsuario = Auth::user()->OFICINA_ID;

            // Inicializar la variable $solicitudes
            $solicitudes = null;

            // Verificar si el usuario es el jefe del departamento de administración
            if (Auth::user()->cargo->CARGO_NOMBRE == 'JEFE DE DEPARTAMENTO DE ADMINISTRACION') {
                // Si es el jefe de departamento de administración, obtener todas las solicitudes por aprobar de la misma oficina
                $solicitudes = SolicitudVehicular::whereHas('user', function ($query) use ($oficinaIdUsuario) {
                    $query->where('OFICINA_ID', $oficinaIdUsuario);
                })
                ->where(function ($query) {
                    $query->where('SOLICITUD_VEHICULO_ESTADO', 'POR APROBAR')
                    ->where('SOLICITUD_VEHICULO_JEFE_QUE_AUTORIZA', Auth::user()->cargo->CARGO_ID)
                          ->orWhere(function ($query) {
                            $query->where('SOLICITUD_VEHICULO_ESTADO', 'POR AUTORIZAR');
                          });
                })
                ->get();
            } elseif (strpos(Auth::user()->cargo->CARGO_NOMBRE, 'JEFE') === 0) {
                // Si es otro jefe que autoriza, obtener las solicitudes por aprobar de la misma oficina y asignadas al jefe que autoriza
                $solicitudes = SolicitudVehicular::where('SOLICITUD_VEHICULO_JEFE_QUE_AUTORIZA', Auth::user()->cargo->CARGO_ID)
                    ->whereHas('user', function ($query) use ($oficinaIdUsuario) {
                        $query->where('OFICINA_ID', $oficinaIdUsuario);
                    })
                    ->where('SOLICITUD_VEHICULO_ESTADO', 'POR APROBAR')
                    ->get();
            }

            // Formatear las fechas created_at en DD:MM:AA
            foreach ($solicitudes as $solicitud) {
                $solicitud->formatted_created_at = Carbon::parse($solicitud->created_at)->format('d-m-y H:i');
            }
            // Retornar la vista con las solicitudes
            return view('sia2.solicitudes.vehiculos.indexPorAprobar', compact('solicitudes'));
        } catch (Exception $e) {
            // Manejar excepciones si es necesario
            return redirect()->back()->with('error', 'Error al cargar las solicitudes.');
        }
    }

    public function indexPorRendir()
    {
        try {
            // Obtener la oficina del usuario actual
            $oficinaIdUsuario = Auth::user()->OFICINA_ID;

            // Obtener el ID del usuario autenticado
            $userId = Auth::user()->id;

            $solicitudes = SolicitudVehicular::whereHas('user', function ($query) use ($oficinaIdUsuario) {
                    $query->where('OFICINA_ID', $oficinaIdUsuario);
                })
                ->where('SOLICITUD_VEHICULO_ESTADO', 'POR RENDIR')
                ->whereHas('conductor', function ($query) use ($userId) {
                    $query->where('CONDUCTOR_id', $userId);
                })
                ->get();


            // Formatear las fechas created_at en DD:MM:AA
            foreach ($solicitudes as $solicitud) {
                $solicitud->formatted_created_at = Carbon::parse($solicitud->created_at)->format('d-m-y H:i');
            }

            // Retornar la vista con las solicitudes
            return view('sia2.solicitudes.vehiculos.indexPorRendir', compact('solicitudes'));
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

        return view('sia2.solicitudes.vehiculos.create', compact('vehiculos','oficinas','ubicaciones','departamentos','regiones', 'comunas', 'users', 'jefesQueAutorizan', 'conductores'));
        } catch (Exception $e) {
            // Manejar excepciones si es necesario
            return redirect()->route('solicitudesvehiculos.index')->with('error', 'Error al intentar crear la solicitud de vehículo.');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Intentar guardar la solicitud en la base de datos
        try {
            //if ( $request->)
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
            if ($request->has('TRABAJA_NUMERO_ORDEN_TRABAJO') || $request->has('TRABAJA_HORA_INICIO_ORDEN_TRABAJO') || $request->has('TRABAJA_HORA_TERMINO_ORDEN_TRABAJO')) {
                // Realizar la validación de los datos recibidos
                $validatorRules = array_merge($validatorRules, [
                    'TRABAJA_NUMERO_ORDEN_TRABAJO' => 'required|integer|min:0|max:999999|unique:ordenes_de_trabajo,ORDEN_TRABAJO_NUMERO',
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
                'TRABAJA_NUMERO_ORDEN_TRABAJO.unique' => 'El número de orden de trabajo fue registrado previamente.',
                'TRABAJA_HORA_INICIO_ORDEN_TRABAJO.required' => 'La hora de inicio de la orden de trabajo es obligatoria.',
                'TRABAJA_HORA_INICIO_ORDEN_TRABAJO.date_format' => 'El formato de la hora de inicio de la orden de trabajo no es válido.',
                'TRABAJA_HORA_TERMINO_ORDEN_TRABAJO.required' => 'La hora de término de la orden de trabajo es obligatoria.',
                'TRABAJA_HORA_TERMINO_ORDEN_TRABAJO.date_format' => 'El formato de la hora de término de la orden de trabajo no es válido.',
                'TRABAJA_HORA_TERMINO_ORDEN_TRABAJO.after_or_equal' => 'La hora de término de la orden de trabajo debe ser posterior a la hora de inicio de la orden de trabajo.',
            ]);

            // Manejar errores de validación
            if ($validator->fails()) {
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
            $solicitud->SOLICITUD_VEHICULO_HORA_INICIO_CONDUCCION = $request->input('SOLICITUD_VEHICULO_HORA_INICIO_CONDUCCION');
            $solicitud->SOLICITUD_VEHICULO_HORA_TERMINO_CONDUCCION = $request->input('SOLICITUD_VEHICULO_HORA_TERMINO_CONDUCCION');

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
    public function timeline($id)
    {
        try {
            $solicitud = SolicitudVehicular::findOrFail($id);
            $revisiones = RevisionSolicitud::where('SOLICITUD_VEHICULO_ID', $id)->get();
            return view('sia2.solicitudes.vehiculos.show', compact('solicitud', 'revisiones'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Error al cargar la línea de tiempo de la solicitud.');
        }
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        try {
            // Determinar la fuente de la solicitud a través de la URL
            $source = request()->input('source');


            // Encontrar la solicitud por su ID
            $solicitud = SolicitudVehicular::findOrFail($id);
            $solicitud->SOLICITUD_VEHICULO_FECHA_HORA_INICIO_SOLICITADA = Carbon::parse($solicitud->SOLICITUD_VEHICULO_FECHA_HORA_INICIO_SOLICITADA)->format('d-m-Y H:i');
            $solicitud->SOLICITUD_VEHICULO_FECHA_HORA_TERMINO_SOLICITADA = Carbon::parse($solicitud->SOLICITUD_VEHICULO_FECHA_HORA_TERMINO_SOLICITADA)->format('d-m-Y H:i');
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

            // Consulta SQL para obtener vehículos asociados a la oficina del usuario en sesión y también al usuario que realizó la solicitud (para maximizar la seguridad del módulo).
            $vehiculos = Vehiculo::select('vehiculos.*')
            ->leftJoin('ubicaciones', 'vehiculos.UBICACION_ID', '=', 'ubicaciones.UBICACION_ID')
            ->leftJoin('departamentos', 'vehiculos.DEPARTAMENTO_ID', '=', 'departamentos.DEPARTAMENTO_ID')
            ->where(function($query) use ($oficinaIdUsuario, $solicitud) {
                $query->where('ubicaciones.OFICINA_ID', $oficinaIdUsuario)
                    ->whereNull('vehiculos.DEPARTAMENTO_ID');
            })
            ->orWhere(function($query) use ($oficinaIdUsuario, $solicitud) {
                $query->where('departamentos.OFICINA_ID', $oficinaIdUsuario)
                    ->whereNull('vehiculos.UBICACION_ID');
            })
            ->where(function($query) use ($solicitud) {
                $query->where(function($query) use ($solicitud) {
                    $query->where('ubicaciones.OFICINA_ID', $solicitud->user->OFICINA_ID)
                        ->orWhere('departamentos.OFICINA_ID', $solicitud->user->OFICINA_ID);
                });
            })
            ->get();

            // Determinar la vista a retornar
            if ($source === 'indexPorAprobar') {
                // Retornar la vista editPorAprobar
                return view('sia2.solicitudes.vehiculos.editPorAprobar', compact('solicitud', 'pasajeros', 'vehiculos','cargoJefeQueAutoriza','oficinas','ubicaciones','departamentos','users', 'fechaCreacionFormateada', 'conductores'));
            } else if ($source === 'indexPorRendir') {
                // Retornar la vista editPorAprobar
                return view('sia2.solicitudes.vehiculos.editPorRendir', compact('solicitud', 'pasajeros', 'vehiculos','cargoJefeQueAutoriza','oficinas','ubicaciones','departamentos','users', 'fechaCreacionFormateada', 'conductores'));
            }else {
                // Retornar la vista edit
                return view('sia2.solicitudes.vehiculos.edit', compact('solicitud', 'pasajeros', 'vehiculos','cargoJefeQueAutoriza','oficinas','ubicaciones','departamentos','users', 'fechaCreacionFormateada', 'conductores'));
            }
        } catch (ModelNotFoundException $e) {
            // Determinar la vista a retornar
            if ($source === 'indexPorAprobar') {
                // Manejar excepción de modelo no encontrado

                return redirect()->route('solicitudesvehiculos.indexPorAprobar')->with('error', 'Ocurrió un error inesperado.');
            } else if ($source === 'indexPorRendir') {

                return redirect()->route('solicitudesvehiculos.indexPorRendir')->with('error', 'Ocurrió un error inesperado.');

            }else {
                // Manejar excepción de modelo no encontrado

                return redirect()->route('solicitudesvehiculos.index')->with('error', 'Ocurrió un error inesperado.');
            }
        } catch (Exception $e) {
            // Determinar la vista a retornar
            if ($source === 'indexPorAprobar') {
                // Manejar excepciones si es necesario
                return redirect()->route('solicitudesvehiculos.indexPorAprobar')->with('error', 'Error al cargar la solicitud de vehículo por aprobar o autorizar. ');
            } else if ($source === 'indexPorRendir') {
                return redirect()->route('solicitudesvehiculos.indexPorRendir')->with('error', 'Error al cargar la solicitud de vehículo por rendir. ');
             }else {
                // Manejar excepciones si es necesario
                return redirect()->route('solicitudesvehiculos.index')->with('error', 'Error al cargar la solicitud de vehículo para editar. ');
            }
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

            if (  $request->has('rechazarSolicitud')) {
                $solicitud->SOLICITUD_VEHICULO_ESTADO = ('RECHAZADO');
                $solicitud->save();
                return redirect()->route('solicitudesvehiculos.index')->with('success', 'Solicitud rechazada correctamente.');
            } else {




                // Validación de datos
                $validatorRules = [
                    'VEHICULO_ID' => 'required|exists:vehiculos,VEHICULO_ID',
                    'CONDUCTOR_id' => 'required|exists:users,id',
                    'SOLICITUD_VEHICULO_VIATICO' => 'required|string|max:4',
                    'SOLICITUD_VEHICULO_FECHA_HORA_INICIO_ASIGNADA' => 'required|date',
                    'SOLICITUD_VEHICULO_FECHA_HORA_TERMINO_ASIGNADA' => 'required|date|after_or_equal:SOLICITUD_VEHICULO_FECHA_HORA_INICIO_ASIGNADA',
                    'SOLICITUD_VEHICULO_HORA_INICIO_CONDUCCION' => 'required|date_format:H:i',
                    'SOLICITUD_VEHICULO_HORA_TERMINO_CONDUCCION' => 'required|date_format:H:i|after_or_equal:SOLICITUD_VEHICULO_HORA_INICIO_CONDUCCION',
                ];

                // Validar si los datos están presentes en la solicitud
                if ($request->has('TRABAJA_NUMERO_ORDEN_TRABAJO') || $request->has('TRABAJA_HORA_INICIO_ORDEN_TRABAJO') || $request->has('TRABAJA_HORA_TERMINO_ORDEN_TRABAJO')) {
                    // Realizar la validación de los datos recibidos
                    $validatorRules = array_merge($validatorRules, [
                        'TRABAJA_NUMERO_ORDEN_TRABAJO' => 'required|integer|min:0|max:999999',
                        'TRABAJA_HORA_INICIO_ORDEN_TRABAJO' => 'required|date_format:H:i:s',
                        'TRABAJA_HORA_TERMINO_ORDEN_TRABAJO' => 'required|date_format:H:i:s|after_or_equal:TRABAJA_HORA_INICIO_ORDEN_TRABAJO',
                    ]);
                }

                if (($request->input('accionGuardar') == 1) || ($request->input('accionTerminar') == 1)) {
                    $validatorRules = array_merge($validatorRules, [
                        'REVISION_SOLICITUD_OBSERVACION' => 'required|string|max:255'
                    ]);
                }

                if  ($request->has('botonRendir'))  {
                    $validatorRules = array_merge($validatorRules, [
                        'RENDICION_NUMERO_BITACORA' => 'required|integer|unique:rendiciones',
                        'RENDICION_FECHA_HORA_LLEGADA' => 'required|date',
                        'RENDICION_KILOMETRAJE_INICIO' => 'required|integer|min:1|max:999999',
                        'RENDICION_KILOMETRAJE_TERMINO' => 'required|integer|min:1|max:999999|gt:RENDICION_KILOMETRAJE_INICIO',
                        'RENDICION_NIVEL_ESTANQUE' => 'required|string|max:15',
                        'RENDICION_ABASTECIMIENTO' => 'required|string|max:4',
                        'RENDICION_TOTAL_HORAS' => 'required|integer|min:1|max:999999',
                        'RENDICION_OBSERVACIONES' => 'nullable|string|max:255',
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

                    'RENDICION_NUMERO_BITACORA.required' => 'El número de bitácora es requerido.',
                    'RENDICION_NUMERO_BITACORA.integer' => 'El número de bitácora debe ser un número entero.',
                    'RENDICION_NUMERO_BITACORA.unique' => 'El número de bitácora ya ha sido registrado.',
                    'RENDICION_FECHA_HORA_LLEGADA.required' => 'La fecha y hora de llegada son requeridas.',
                    'RENDICION_FECHA_HORA_LLEGADA.date' => 'La fecha y hora de llegada deben ser una fecha válida.',
                    'RENDICION_KILOMETRAJE_INICIO.required' => 'El kilometraje de inicio es requerido.',
                    'RENDICION_KILOMETRAJE_INICIO.integer' => 'El kilometraje de inicio debe ser un número entero.',
                    'RENDICION_KILOMETRAJE_INICIO.min' => 'El kilometraje de inicio debe ser mayor que cero.',
                    'RENDICION_KILOMETRAJE_INICIO.max' => 'El kilometraje de inicio no debe ser mayor a :max.',
                    'RENDICION_KILOMETRAJE_TERMINO.required' => 'El kilometraje de término es requerido.',
                    'RENDICION_KILOMETRAJE_TERMINO.integer' => 'El kilometraje de término debe ser un número entero.',
                    'RENDICION_KILOMETRAJE_TERMINO.min' => 'El kilometraje de término debe ser mayor que cero.',
                    'RENDICION_KILOMETRAJE_TERMINO.max' => 'El kilometraje de término no debe ser mayor a :max.',
                    'RENDICION_KILOMETRAJE_TERMINO.gt' => 'El kilometraje de término debe ser mayor que el kilometraje de inicio.',
                    'RENDICION_NIVEL_ESTANQUE.required' => 'El nivel de estanque es requerido.',
                    'RENDICION_NIVEL_ESTANQUE.string' => 'El nivel de estanque debe ser una cadena de caracteres.',
                    'RENDICION_NIVEL_ESTANQUE.max' => 'El nivel de estanque no debe exceder los :max caracteres.',
                    'RENDICION_ABASTECIMIENTO.required' => 'El abastecimiento es requerido.',
                    'RENDICION_ABASTECIMIENTO.string' => 'El abastecimiento debe ser una cadena de caracteres.',
                    'RENDICION_ABASTECIMIENTO.max' => 'El abastecimiento no debe exceder los :max caracteres.',
                    'RENDICION_TOTAL_HORAS.required' => 'El total de horas es requerido.',
                    'RENDICION_TOTAL_HORAS.integer' => 'El total de horas debe ser un número entero.',
                    'RENDICION_TOTAL_HORAS.min' => 'El total de horas debe ser mayor que cero.',
                    'RENDICION_TOTAL_HORAS.max' => 'El total de horas no debe ser mayor a :max.',
                    'RENDICION_OBSERVACIONES.string' => 'Las observaciones deben ser una cadena de caracteres.',
                    'RENDICION_OBSERVACIONES.max' => 'Las observaciones no deben exceder los :max caracteres.'
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

                // Manejo de errores de validación
                if ($validator->fails()) {

                    return redirect()->back()->withErrors($validator)->withInput();
                }

                // Actualizar los campos de la solicitud con los datos asociados a la revisión
                $solicitud->VEHICULO_ID = $request->input('VEHICULO_ID');
                $solicitud->CONDUCTOR_id = $request->input('CONDUCTOR_id');
                $solicitud->SOLICITUD_VEHICULO_FECHA_HORA_INICIO_ASIGNADA = date('Y-m-d H:i:s', strtotime($request->input('SOLICITUD_VEHICULO_FECHA_HORA_INICIO_ASIGNADA')));
                $solicitud->SOLICITUD_VEHICULO_FECHA_HORA_TERMINO_ASIGNADA = date('Y-m-d H:i:s', strtotime($request->input('SOLICITUD_VEHICULO_FECHA_HORA_TERMINO_ASIGNADA')));
                $solicitud->SOLICITUD_VEHICULO_VIATICO = strtoupper($request->input('SOLICITUD_VEHICULO_VIATICO'));
                $solicitud->SOLICITUD_VEHICULO_HORA_INICIO_CONDUCCION = $request->input('SOLICITUD_VEHICULO_HORA_INICIO_CONDUCCION');
                $solicitud->SOLICITUD_VEHICULO_HORA_TERMINO_CONDUCCION = $request->input('SOLICITUD_VEHICULO_HORA_TERMINO_CONDUCCION');
                // Guardar los cambios en la base de datos

                $solicitud->save();

                /*// Verificar si se envió el botón de autorizar
                if ($request->has('autorizar')) {
                    // Registrar la autorización de la solicitud
                    $autorizacion = new Autorizacion();
                    $autorizacion->USUARIO_id =  Auth::user()->id;
                    $autorizacion->SOLICITUD_VEHICULO_ID = $solicitud->SOLICITUD_VEHICULO_ID;
                    $solicitud->SOLICITUD_VEHICULO_ESTADO = 'POR RENDIR';

                    $autorizacion->save();
                }*/

                // Verificar si se envió el botón de autorizar
                if ($request->has('botonAutorizar')) {
                    if ((Auth::user()->cargo->CARGO_ID == $solicitud->SOLICITUD_VEHICULO_JEFE_QUE_AUTORIZA) && (Auth::user()->cargo->CARGO_NOMBRE !== 'JEFE DE DEPARTAMENTO DE ADMINISTRACIÓN') ) {
                        // Si es el 'JEFE QUE AUTORIZA'
                        $existeAutorizacion = Autorizacion::where('SOLICITUD_VEHICULO_ID', $solicitud->SOLICITUD_VEHICULO_ID)
                            ->where('USUARIO_id', Auth::user()->id)
                            ->exists();

                        if ($existeAutorizacion) {
                            return redirect()->back()->with('error', 'La solicitud ya registra una autorización con su firma.');
                        } else {
                            // Registrar la autorización de la solicitud
                            $autorizacionJefe = new Autorizacion();
                            $autorizacionJefe->USUARIO_id = Auth::user()->id;
                            $autorizacionJefe->SOLICITUD_VEHICULO_ID = $solicitud->SOLICITUD_VEHICULO_ID;
                            $autorizacionJefe->save();
                            // Cambiar el estado de la solicitud
                            $solicitud->SOLICITUD_VEHICULO_ESTADO = 'POR AUTORIZAR';
                        }
                    } else {

                        // Si es el 'JEFE DE DEPARTAMENTO DE ADMINISTRACION'
                        $autorizacionJefeDpto = new Autorizacion();
                        $autorizacionJefeDpto->USUARIO_id = Auth::user()->id;
                        $autorizacionJefeDpto->SOLICITUD_VEHICULO_ID = $solicitud->SOLICITUD_VEHICULO_ID;
                        $autorizacionJefeDpto->save();


                        // Cambiar el estado de la solicitud
                        $solicitud->SOLICITUD_VEHICULO_ESTADO = 'POR RENDIR';

                        // Contar las autorizaciones existentes para la solicitud
                        $cantidadAutorizaciones = Autorizacion::where('SOLICITUD_VEHICULO_ID', $solicitud->SOLICITUD_VEHICULO_ID)->count();

                        // Verificar si sólo ha firmado el jefe, entonces asignar como jefe que autoriza
                        if ($cantidadAutorizaciones == 1) {
                            $solicitud->SOLICITUD_VEHICULO_JEFE_QUE_AUTORIZA = Auth::user()->cargo->CARGO_ID;
                        }
                    }
                    $solicitud->save();
                }

                /* OTRA OPCION
                            // Verificar si se envió el botón de revisar
                if (($request->has('guardarRevision'))|| ($request->has('finalizarRevisiones'))) {
                    if($request->has('finalizarRevisiones')) {
                        $solicitud->SOLICITUD_VEHICULO_ESTADO = 'POR APROBAR';
                    } else {
                        $solicitud->SOLICITUD_VEHICULO_ESTADO = 'EN REVISIÓN';

                    }
                    // Registrar la revisión de la solicitud
                    $revision = new RevisionSolicitud();
                    $revision->USUARIO_id = Auth::user()->id;
                    $revision->SOLICITUD_VEHICULO_ID = $solicitud->SOLICITUD_VEHICULO_ID;
                    $revision->REVISION_SOLICITUD_OBSERVACION = $request->input('REVISION_SOLICITUD_OBSERVACION');
                    $revision->save();
                }
                $solicitud->save();  */

                if ($request->has('botonRendir')) {
                    $rendicion = new Rendicion();
                    $rendicion->RENDICION_NUMERO_BITACORA = $request->input('RENDICION_NUMERO_BITACORA');
                    $rendicion->RENDICION_FECHA_HORA_LLEGADA = date('Y-m-d H:i:s', strtotime($request->input('RENDICION_FECHA_HORA_LLEGADA')));
                    $rendicion->RENDICION_KILOMETRAJE_INICIO = $request->input('RENDICION_KILOMETRAJE_INICIO');
                    $rendicion->RENDICION_KILOMETRAJE_TERMINO = $request->input('RENDICION_KILOMETRAJE_TERMINO');
                    $rendicion->RENDICION_NIVEL_ESTANQUE = $request->input('RENDICION_NIVEL_ESTANQUE');
                    $rendicion->RENDICION_ABASTECIMIENTO = $request->input('RENDICION_ABASTECIMIENTO');
                    $rendicion->RENDICION_TOTAL_HORAS = $request->input('RENDICION_TOTAL_HORAS');
                    $rendicion->RENDICION_OBSERVACIONES = $request->input('RENDICION_OBSERVACIONES');
                    $rendicion->USUARIO_id = Auth::user()->id;
                    $rendicion->SOLICITUD_VEHICULO_ID = $solicitud->SOLICITUD_VEHICULO_ID;
                    $rendicion->save();

                    $solicitud->SOLICITUD_VEHICULO_ESTADO = 'TERMINADO';
                    $solicitud->save();

                }

                // Verificar si se envió el botón de revisar
                if (($request->input('accionGuardar') == 1)) {
                    $solicitud->SOLICITUD_VEHICULO_ESTADO = 'EN REVISIÓN';
                    // Registrar la revisión de la solicitud
                    $revision = new RevisionSolicitud();
                    $revision->USUARIO_id = Auth::user()->id;
                    $revision->SOLICITUD_VEHICULO_ID = $solicitud->SOLICITUD_VEHICULO_ID;
                    $revision->REVISION_SOLICITUD_OBSERVACION = $request->input('REVISION_SOLICITUD_OBSERVACION');
                    $revision->save();
                } else if (($request->input('accionTerminar') == 1)) {
                    $solicitud->SOLICITUD_VEHICULO_ESTADO = 'POR APROBAR';
                    // Registrar la revisión de la solicitud
                    $revision = new RevisionSolicitud();
                    $revision->USUARIO_id = Auth::user()->id;
                    $revision->SOLICITUD_VEHICULO_ID = $solicitud->SOLICITUD_VEHICULO_ID;
                    $revision->REVISION_SOLICITUD_OBSERVACION = $request->input('REVISION_SOLICITUD_OBSERVACION');
                    $revision->save();
                }
                $solicitud->save();



                // Actualizar los pasajeros asociados a la solicitud
                $this->actualizarPasajeros($solicitud, $request);
                // Redirigir a la vista de edición con un mensaje de éxito
                if (($request->input('accionGuardar') == 1)) {
                    return redirect()->route('solicitudesvehiculos.index')->with('success', 'Revisión guardada correctamente');
                } else if (($request->input('accionTerminar') == 1)) {
                    return redirect()->route('solicitudesvehiculos.index')->with('success', 'Revisiones de solicitud finalizadas');
                } else if ($request->has('botonAutorizar')) {
                    return redirect()->route('solicitudesvehiculos.indexPorAprobar')->with('success', 'Solicitud firmada con éxito');
                } else if ($request->has('botonRendir')) {
                    return redirect()->route('solicitudesvehiculos.indexPorRendir')->with('success', 'Solicitud firmada con éxito');
                }
            }
        } catch (ModelNotFoundException $e) {
            // Manejar excepción de modelo no encontrado
            return redirect()->route('solicitudesvehiculos.index')->with('error', 'Ocurrió un error inesperado.');
        } catch (\Exception $e) {

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
            // Obtener datos del conductor
            $polizaConductor = Poliza::where('USUARIO_id', $solicitud->conductor->id)->firstOrFail();
            if( $polizaConductor ) {
                $fechaVencimientoLicencia = Carbon::parse($polizaConductor->POLIZA_FECHA_VENCIMIENTO_LICENCIA)->format('d/m/y');
            }

            // Obtener orden de trabajo en caso de que exista
            $ordenDeTrabajo = null;
            if ($solicitud->ordenTrabajo) {
                $ordenDeTrabajo = $solicitud->ordenTrabajo;
            }

            // Obtener cargo del jefe que autoriza
            $autoriza = Cargo::findOrFail($solicitud->SOLICITUD_VEHICULO_JEFE_QUE_AUTORIZA);
            $jefeQueAutoriza = $autoriza->CARGO_NOMBRE;
            // Obtener los pasajeros asociados a la solicitud
            $pasajeros = $solicitud->pasajeros;
            // Definir el rango de celdas para los pasajeros en la columna C y filas 15 a 22
            $inicioFilaPasajeros = 14;
            $finFilaPasajeros = 21;


            // Autorizaciones para recuperar firmas
            $autorizaciones = Autorizacion::where('SOLICITUD_VEHICULO_ID', $solicitud->SOLICITUD_VEHICULO_ID)->get();

            // Definir el rango de celdas para las firmas de autorización
            $rangoFirmas = [
                'jefeAutoriza' => 'H22',
                'jefeAdmin' => 'D27',
            ];

            // Rendicion para recuperar firma
            $rendicion = Rendicion::where('SOLICITUD_VEHICULO_ID', $solicitud->SOLICITUD_VEHICULO_ID)->first();
            $totalKmsRecorridos= 0;
            // Verificar si se encontró la rendición
            if ($rendicion) {
                $totalKmsRecorridos=($rendicion->RENDICION_KILOMETRAJE_TERMINO - $rendicion->RENDICION_KILOMETRAJE_INICIO);
                // Acceder a la firma del conductor desde la rendición
                $firmaConductor = ( 'Firmado digitalmente por: ' . "\n" . $rendicion->user->USUARIO_NOMBRES.' '.$rendicion->user->USUARIO_APELLIDOS . "\n" . ' Rut: '.$rendicion->user->USUARIO_RUT . "\n" . 'Fecha: '. Carbon::parse($rendicion->created_at)->format('d-m-Y H:i') );

                $spreadsheet->getActiveSheet()->setCellValue('C31', strtoupper(Carbon::parse($rendicion->RENDICION_FECHA_HORA_LLEGADA)->isoFormat('dddd')));
                $spreadsheet->getActiveSheet()->setCellValue('D31', Carbon::parse($rendicion->RENDICION_FECHA_HORA_LLEGADA)->format('j'));
                $spreadsheet->getActiveSheet()->setCellValue('E31', strtoupper(Carbon::parse($rendicion->RENDICION_FECHA_HORA_LLEGADA)->isoFormat('MMMM')));
                $spreadsheet->getActiveSheet()->setCellValue('F31', Carbon::parse($rendicion->RENDICION_FECHA_HORA_LLEGADA)->format('Y'));
                $spreadsheet->getActiveSheet()->setCellValue('C32', strtoupper(Carbon::parse($rendicion->RENDICION_FECHA_HORA_LLEGADA)->format('H:i')));
                $spreadsheet->getActiveSheet()->setCellValue('C33', $rendicion->RENDICION_TOTAL_HORAS);
                $spreadsheet->getActiveSheet()->setCellValue('C34', $rendicion->RENDICION_OBSERVACIONES);
                $spreadsheet->getActiveSheet()->setCellValue('J31', $rendicion->RENDICION_NUMERO_BITACORA);
                $spreadsheet->getActiveSheet()->setCellValue('J32', $rendicion->RENDICION_ABASTECIMIENTO);
                $spreadsheet->getActiveSheet()->setCellValue('J33', $rendicion->RENDICION_NIVEL_ESTANQUE);
                $spreadsheet->getActiveSheet()->setCellValue('H31', $rendicion->RENDICION_KILOMETRAJE_INICIO);
                $spreadsheet->getActiveSheet()->setCellValue('H32', $rendicion->RENDICION_KILOMETRAJE_TERMINO);
                $spreadsheet->getActiveSheet()->setCellValue('H33', $totalKmsRecorridos);

                // DATOS DE RENDICION
                /*['C32', strtoupper(Carbon::parse($rendicion->RENDICION_FECHA_HORA_LLEGADA)->isoFormat('dddd'))],
                ['D32', Carbon::parse($rendicion->RENDICION_FECHA_HORA_LLEGADA)->format('j')],
                ['E32', strtoupper(Carbon::parse($rendicion->RENDICION_FECHA_HORA_LLEGADA)->isoFormat('MMMM'))],
                ['F32', Carbon::parse($rendicion->RENDICION_FECHA_HORA_LLEGADA)->format('Y')],
                ['C33', strtoupper(Carbon::parse($rendicion->RENDICION_FECHA_HORA_LLEGADA)->format('H:i'))],
                ['C34', $rendicion->RENDICION_TOTAL_HORAS],
                ['C35', $rendicion->RENDICION_OBSERVACIONES],
                ['J32', $rendicion->RENDICION_NUMERO_BITACORA ],
                ['J33', $rendicion->RENDICION_ABASTECIMIENTO],
                ['J34', $rendicion->RENDICION_NIVEL_ESTANQUE],
                ['H32', $rendicion->RENDICION_KILOMETRAJE_INICIO],
                ['H33', $rendicion->RENDICION_KILOMETRAJE_TERMINO],
                ['H34', $totalKmsRecorridos]*/

            } else {
                $firmaConductor = " ";
            }
            // Verificar si existe solo una autorización y la solicitud está en el estado adecuado
            if ($autorizaciones->count() === 1 && ($solicitud->SOLICITUD_VEHICULO_ESTADO === 'POR RENDIR' || $solicitud->SOLICITUD_VEHICULO_ESTADO === 'TERMINADO')) {
                // Obtener la autorización única
                $autorizacionUnica = $autorizaciones->first();

                // Asignar la firma del jefe de administración como la firma del jefe que autoriza
                $spreadsheet->getActiveSheet()->setCellValue($rangoFirmas['jefeAutoriza'], 'Firmado digitalmente por: ' . "\n" . $autorizacionUnica->user->USUARIO_NOMBRES.' '.$autorizacionUnica->user->USUARIO_APELLIDOS . "\n" . ' Rut: '.$autorizacionUnica->user->USUARIO_RUT . "\n" . 'Fecha: '. Carbon::parse($autorizacionUnica->created_at)->format('d-m-Y H:i') );
                $spreadsheet->getActiveSheet()->setCellValue($rangoFirmas['jefeAdmin'], 'Firmado digitalmente por: ' . "\n" . $autorizacionUnica->user->USUARIO_NOMBRES.' '.$autorizacionUnica->user->USUARIO_APELLIDOS . "\n" . 'Rut: '.$autorizacionUnica->user->USUARIO_RUT . "\n" . 'Fecha: '. Carbon::parse($autorizacionUnica->created_at)->format('d-m-Y H:i') );
                $jefeQueAutoriza = 'JEFE DE DEPARTAMENTO DE ADMINISTRACION';

            } else {
                // Asignar las firmas de autorización normalmente
                foreach ($autorizaciones as $autorizacion) {
                    if ($autorizacion->user->cargo->CARGO_ID == $solicitud->SOLICITUD_VEHICULO_JEFE_QUE_AUTORIZA && $autorizacion->user->cargo->CARGO_NOMBRE !== 'JEFE DE DEPARTAMENTO DE ADMINISTRACION') {
                        // Si es el 'JEFE QUE AUTORIZA'
                        $spreadsheet->getActiveSheet()->setCellValue($rangoFirmas['jefeAutoriza'], 'Firmado digitalmente por: ' . "\n" . $autorizacion->user->USUARIO_NOMBRES.' '.$autorizacion->user->USUARIO_APELLIDOS . "\n" . ' Rut: '.$autorizacion->user->USUARIO_RUT . "\n" . 'Fecha: '. Carbon::parse($autorizacion->created_at)->format('d-m-Y H:i') );
                    } else {
                        // Si es el 'JEFE DE DEPARTAMENTO DE ADMINISTRACION'
                        $spreadsheet->getActiveSheet()->setCellValue($rangoFirmas['jefeAdmin'], 'Firmado digitalmente por: ' . "\n" . $autorizacion->user->USUARIO_NOMBRES.' '.$autorizacion->user->USUARIO_APELLIDOS . "\n" . 'Rut: '.$autorizacion->user->USUARIO_RUT . "\n" . 'Fecha: '. Carbon::parse($autorizacion->created_at)->format('d-m-Y H:i') );
                    }
                }
            }


            // Registro de celdas en excel

            // Fusionar los arreglos de datos de la orden de trabajo y la solicitud estándar
            $datos = array_merge(
                $ordenDeTrabajo ? [
                    ['H9', $ordenDeTrabajo->ORDEN_TRABAJO_NUMERO],
                    ['J9', strtoupper(Carbon::parse($ordenDeTrabajo->ORDEN_TRABAJO_HORA_INICIO)->format('H:i'))],
                    ['J10', strtoupper(Carbon::parse($ordenDeTrabajo->ORDEN_TRABAJO_HORA_TERMINO)->format('H:i'))],
                ] : [],

                // Fecha y hora asignada
                $solicitud->SOLICITUD_VEHICULO_FECHA_HORA_INICIO_ASIGNADA !== null ? [
                    ['C11', strtoupper(Carbon::parse($solicitud->SOLICITUD_VEHICULO_FECHA_HORA_INICIO_ASIGNADA)->isoFormat('dddd'))],
                    ['D11', Carbon::parse($solicitud->SOLICITUD_VEHICULO_FECHA_HORA_INICIO_ASIGNADA)->format('j')],
                    ['E11', strtoupper(Carbon::parse($solicitud->SOLICITUD_VEHICULO_FECHA_HORA_INICIO_ASIGNADA)->isoFormat('MMMM'))],
                    ['F11', Carbon::parse($solicitud->SOLICITUD_VEHICULO_FECHA_HORA_INICIO_ASIGNADA)->format('Y')],
                    ['H11', strtoupper(Carbon::parse($solicitud->SOLICITUD_VEHICULO_FECHA_HORA_INICIO_ASIGNADA)->format('H:i'))],
                ] : [
                    ['C11', ' '], // Espacio en blanco si no está definido
                    ['D11', ' '],
                    ['E11', ' '],
                    ['F11', ' '],
                    ['H11', ' '],
                ],

                $solicitud->SOLICITUD_VEHICULO_FECHA_HORA_TERMINO_ASIGNADA !== null ? [
                    ['C12', strtoupper(Carbon::parse($solicitud->SOLICITUD_VEHICULO_FECHA_HORA_TERMINO_ASIGNADA)->isoFormat('dddd'))],
                    ['D12', Carbon::parse($solicitud->SOLICITUD_VEHICULO_FECHA_HORA_TERMINO_ASIGNADA)->format('j')],
                    ['E12', strtoupper(Carbon::parse($solicitud->SOLICITUD_VEHICULO_FECHA_HORA_TERMINO_ASIGNADA)->isoFormat('MMMM'))],
                    ['F12', Carbon::parse($solicitud->SOLICITUD_VEHICULO_FECHA_HORA_TERMINO_ASIGNADA)->format('Y')],
                    ['H12', strtoupper(Carbon::parse($solicitud->SOLICITUD_VEHICULO_FECHA_HORA_TERMINO_ASIGNADA)->format('H:i'))],
                ] : [
                    ['C12', ' '], // Espacio en blanco si no está definido
                    ['D12', ' '],
                    ['E12', ' '],
                    ['F12', ' '],
                    ['H12', ' '],
                ],
                [
                    // Fecha de creación solicitud
                    ['E4', strtoupper(Carbon::parse($solicitud->created_at)->locale('es_ES')->isoFormat('dddd'))],
                    ['F4', $solicitud->created_at->format('j')],
                    ['G4', strtoupper(Carbon::parse($solicitud->created_at)->locale('es_ES')->isoFormat('MMMM'))],
                    ['H4', $solicitud->created_at->format('Y')],


                    // Datos del solicitante
                    ['C7', $solicitud->user->USUARIO_NOMBRES.' '.$solicitud->user->USUARIO_APELLIDOS],
                    ['C8', $solicitud->user->oficina->OFICINA_NOMBRE],
                    ['C9', $solicitud->user->ubicacion ? $solicitud->user->ubicacion->UBICACION_NOMBRE : $solicitud->user->departamento->DEPARTAMENTO_NOMBRE],
                    ['C10', $solicitud->user->cargo->CARGO_NOMBRE],
                    ['H7', strtoupper($solicitud->comunaDestino->COMUNA_NOMBRE)],
                    ['H8', $solicitud->SOLICITUD_VEHICULO_MOTIVO],




                    // HORAS INICIO Y TERMINO CONDUCCIÓN, Y VIÁTICO
                    ['J11', strtoupper(Carbon::parse($solicitud->SOLICITUD_VEHICULO_HORA_INICIO_CONDUCCION)->format('H:i'))],
                    ['J12', strtoupper(Carbon::parse($solicitud->SOLICITUD_VEHICULO_HORA_TERMINO_CONDUCCION)->format('H:i'))],
                    ['H10', strtoupper($solicitud->SOLICITUD_VEHICULO_VIATICO)],




                    // OBTENER PASAJEROS E IMPLEMENTAR LLENADO DE EXCEL PARA ELLOS.

                    // POLIZA PARA CONDUCTOR
                    ['B24', strtoupper($solicitud->conductor->USUARIO_NOMBRES . ' ' . $solicitud->conductor->USUARIO_APELLIDOS . ' | Venc Licencia: ' . $fechaVencimientoLicencia . ' | N° Póliza: ' . $polizaConductor->POLIZA_NUMERO)],
                    // JEFE QUE AUTORIZA
                    ['H24', strtoupper($jefeQueAutoriza)],

                    // PATENTE
                    ['G28', strtoupper($solicitud->vehiculo->VEHICULO_PATENTE)],
                    // TIPO VEHICULO
                    ['I28', strtoupper($solicitud->vehiculo->tipoVehiculo->TIPO_VEHICULO_NOMBRE)],



                    // FOLIO
                    ['J3', $solicitud->SOLICITUD_VEHICULO_ID],


                    // FIRMA DE CONDUCTOR ASIGNADO
                    ['D22', $firmaConductor],

                ]
            );
            // Iterar sobre los pasajeros y asignar sus datos a las celdas correspondientes
            foreach ($pasajeros as $index => $pasajero) {
                // Calcular la fila para cada pasajero
                $filaPasajero = $inicioFilaPasajeros + $index;

                // Asignar el nombre del pasajero a la celda correspondiente en la columna C y la fila calculada
                $spreadsheet->getActiveSheet()->setCellValue('C' . $filaPasajero, $pasajero->usuario->USUARIO_NOMBRES . ' ' . $pasajero->usuario->USUARIO_APELLIDOS);

                // Agregar la dirección regional del pasajero
                $spreadsheet->getActiveSheet()->setCellValue('H' . $filaPasajero, $pasajero->usuario->ubicacion ? $pasajero->usuario->ubicacion->UBICACION_NOMBRE : $pasajero->usuario->departamento->DEPARTAMENTO_NOMBRE . ' | ' . ($pasajero->usuario->oficina ? $pasajero->usuario->oficina->OFICINA_NOMBRE : ''));

            }

            // Limpiar las celdas sobrantes si hay menos de 8 pasajeros
            for ($i = $inicioFilaPasajeros + count($pasajeros); $i <= $finFilaPasajeros; $i++) {
                $spreadsheet->getActiveSheet()->setCellValue('C' . $i, '');
                $spreadsheet->getActiveSheet()->setCellValue('H' . $i, '');
            }


            // Iterar sobre los datos y asignarlos a las celdas correspondientes
            foreach ($datos as $dato) {
                $celda = $dato[0]; // Coordenada de la celda, por ejemplo, 'C7'
                $valor = $dato[1]; // Valor a asignar a la celda
                $spreadsheet->getActiveSheet()->setCellValue($celda, $valor);
            }


            /*// Guardar los cambios en la plantilla Excel
            $writer = new Xlsx($spreadsheet);
            $tempFilePath = storage_path('app/public/Hoja de salida.xlsx');
            $writer->save($tempFilePath);*/

            // Guardar los cambios en la plantilla Excel
            $tempFilePath = storage_path('app/public/Hoja de salida.xlsx');
            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
            $writer->save($tempFilePath);

            // Convertir el archivo Excel a PDF
            $pdfWriter = new Dompdf($spreadsheet);
            $pdfTempFilePath = storage_path('app/public/Hoja de salida.pdf');
            $pdfWriter->save($pdfTempFilePath);

                // Después de que el PDF esté listo para descargar, redirecciona al usuario a la descarga
                return response()->download($pdfTempFilePath);


        } catch (Exception $e) {
            // Manejar errores si ocurre algún problema
            return redirect()->back()->with('error', 'Error al descargar la plantilla de Excel.');
        }
    }

    // Método para verificar la contraseña del usuario
    public function verificarContrasena(Request $request)
    {
        /*dd($request);

        $password = $request->input('password');

        // Lógica para verificar la contraseña
        if ($password === 'contraseña_correcta') {
            return response()->json(['message' => 'Contraseña correcta'], 200);
        } else {
            return response()->json(['message' => 'Contraseña incorrecta'], 401);
        }

        if (!Hash::check(request('password'), Auth::user()->password)) {
            return redirect()->back()->with('error', 'La contraseña proporcionada no es correcta');
        } else {
            return view('sia2.solicitudes.vehiculos.editPorAprobar')->with('success', 'Firma(s) realizada(s) con éxito');

        }*/



        // Verificar si el usuario está autenticado
        if (Auth::check()) {
            // Verificar la contraseña proporcionada por el usuario
            if (Hash::check($request->password, Auth::user()->password)) {
                $response = ['message' => 'Contraseña correcta'];
                return response()->json($response, 200);
            } else {
                $response = ['message' => 'Contraseña incorrecta'];
                return response()->json($response, 401);
            }
        } else {
            $response = ['message' => 'Usuario no autenticado'];
            return response()->json($response, 400);
        }
    }




}


