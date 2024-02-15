<?php

namespace App\Http\Controllers\Solicitud;

use App\Http\Controllers\Controller;
use App\Exports\VehiculosExport; 
use Illuminate\Http\Request;
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
            // Obtener tipos de vehículos basados en la OFICINA_ID del usuario
            $tiposVehiculos = TipoVehiculo::where('OFICINA_ID', Auth::user()->OFICINA_ID)->get();

            // Obtener los jefes que autorizan en la dirección regional
            $jefesQueAutorizan = Cargo::where('OFICINA_ID', Auth::user()->OFICINA_ID)
                ->where('CARGO_NOMBRE', 'like', 'JEFE %')
                ->get();

            /*// Obtener conductores que tienen pólizas y están en la misma oficina
            $conductores = User::whereHas('polizas', function ($query) use ($oficinaSesion) {
                $query->where('OFICINA_ID', $oficinaSesion);
            })->get();
            dd($conductores);*/
        return view('sia2.solicitudes.vehiculos.create', compact('tiposVehiculos','oficinas','ubicaciones','departamentos','regiones', 'comunas', 'users', 'jefesQueAutorizan'));
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
        //dd($request);
        // Intentar guardar la solicitud en la base de datos
        try {
            // Validar los datos de entrada
            $validator = Validator::make($request->all(), [
                'TIPO_VEHICULO_ID' => 'required|exists:tipos_vehiculos,TIPO_VEHICULO_ID',
                //'RENDICION_ID' => 'nullable|unique:solicitudes_vehiculares,RENDICION_ID',
                'SOLICITUD_VEHICULO_COMUNA' => 'required|exists:comunas,COMUNA_ID',
                'SOLICITUD_VEHICULO_MOTIVO' => 'required|string|max:255',
                'SOLICITUD_VEHICULO_FECHA_HORA_INICIO_SOLICITADA' => 'required|date',
                'SOLICITUD_VEHICULO_FECHA_HORA_TERMINO_SOLICITADA' => 'required|date|after_or_equal:SOLICITUD_VEHICULO_FECHA_HORA_INICIO_SOLICITADA',
                'SOLICITUD_VEHICULO_JEFE_QUE_AUTORIZA' => 'required|string|max:128',
            ], [
                'TIPO_VEHICULO_ID.required' => 'El campo "tipo vehículo" es obligatorio.',
                'TIPO_VEHICULO_ID.exists' => 'El "tipo vehículo" seleccionado no es válido.',
                //'RENDICION_ID.unique' => 'El valor ingresado para RENDICION_ID ya ha sido registrado.',
                'SOLICITUD_VEHICULO_COMUNA.required' => 'El campo "comuna destino" es obligatorio.',
                'SOLICITUD_VEHICULO_COMUNA.exists' => 'La comuna seleccionada no es válida.',
                'SOLICITUD_VEHICULO_MOTIVO.required' => 'El motivo de la solicitud es obligatorio.',
                'SOLICITUD_VEHICULO_MOTIVO.string' => 'El motivo de la solicitud debe ser una cadena de caracteres.',
                'SOLICITUD_VEHICULO_MOTIVO.max' => 'El motivo de la solicitud no puede tener más de 255 caracteres.',
                'SOLICITUD_VEHICULO_FECHA_HORA_INICIO_SOLICITADA.required' => 'La fecha y hora de inicio de la solicitud es obligatoria.',
                'SOLICITUD_VEHICULO_FECHA_HORA_INICIO_SOLICITADA.date' => 'La fecha y hora de inicio de la solicitud debe ser válida.',
                'SOLICITUD_VEHICULO_FECHA_HORA_TERMINO_SOLICITADA.required' => 'La fecha y hora de término de la solicitud es obligatoria.',
                'SOLICITUD_VEHICULO_FECHA_HORA_TERMINO_SOLICITADA.date' => 'El campo fecha y hora de término de solicitud debe ser una fecha válida.',
                'SOLICITUD_VEHICULO_FECHA_HORA_TERMINO_SOLICITADA.after_or_equal' => 'La fecha y hora de término de solicitud debe ser posterior o igual a la fecha y hora de inicio de solicitud.',
                'SOLICITUD_VEHICULO_JEFE_QUE_AUTORIZA.required' => 'El campo "Jefe que autoriza" es obligatorio.',
                'SOLICITUD_VEHICULO_JEFE_QUE_AUTORIZA.string' => 'El jefe que autoriza debe ser una cadena de caracteres.',
                'SOLICITUD_VEHICULO_JEFE_QUE_AUTORIZA.max' => 'El jefe que autoriza no puede tener más de 128 caracteres.',

            ]);
            
            // Manejar errores de validación
                if ($validator->fails()) {
                    //dd($validator);
                    return redirect()->back()->withErrors($validator)->withInput();
                }

            // Crear una nueva instancia de SolicitudVehicular y asignar los valores
            $solicitud = new SolicitudVehicular();
            $solicitud->USUARIO_id = Auth::user()->id;
            $solicitud->TIPO_VEHICULO_ID = $request->input('TIPO_VEHICULO_ID');
            $solicitud->COMUNA_ID = $request->input('SOLICITUD_VEHICULO_COMUNA');
            $solicitud->SOLICITUD_VEHICULO_TIPO = 'GENERAL'; // Valor por defecto
            $solicitud->SOLICITUD_VEHICULO_MOTIVO = strtoupper($request->input('SOLICITUD_VEHICULO_MOTIVO'));
            $solicitud->SOLICITUD_VEHICULO_ESTADO = 'INGRESADO'; // Valor por defecto
            $solicitud->SOLICITUD_VEHICULO_FECHA_HORA_INICIO_SOLICITADA = $request->input('SOLICITUD_VEHICULO_FECHA_HORA_INICIO_SOLICITADA');
            $solicitud->SOLICITUD_VEHICULO_FECHA_HORA_TERMINO_SOLICITADA = $request->input('SOLICITUD_VEHICULO_FECHA_HORA_TERMINO_SOLICITADA');
            $solicitud->SOLICITUD_VEHICULO_JEFE_QUE_AUTORIZA = $request->input('SOLICITUD_VEHICULO_JEFE_QUE_AUTORIZA');
            //dd($solicitud);
            $solicitud->save();
            //    dd($solicitud->SOLICITUD_VEHICULO_ID);
           
           
            // Obtener las IDs de los pasajeros de la solicitud
            $pasajerosIds = [];
            foreach ($request->all() as $key => $value) {
                if (strpos($key, 'PASAJERO_') === 0) {
                    $pasajerosIds[] = $value;
                }
            }

            // Asociar los pasajeros con la solicitud vehicular
            foreach ($pasajerosIds as $pasajeroId) {
                $pasajero = new Pasajero();
                $pasajero->USUARIO_id = $pasajeroId;
                $pasajero->SOLICITUD_VEHICULO_ID = $solicitud->SOLICITUD_VEHICULO_ID;
                $pasajero->save();
            }
            return redirect()->route('solicitudesvehiculos.index')->with('success', 'Solicitud creada exitosamente.');
        } catch (Exception $e) {
            //dd($e);
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
            $validator = Validator::make($request->all(), [
                'VEHICULO_ID' => 'required|exists:vehiculos,VEHICULO_ID',
                'CONDUCTOR_id' => 'required|exists:users,id',
                'SOLICITUD_VEHICULO_VIATICO' => 'required|string|max:4',
                'SOLICITUD_VEHICULO_FECHA_HORA_INICIO_ASIGNADA' => 'required|date',
                'SOLICITUD_VEHICULO_FECHA_HORA_TERMINO_ASIGNADA' => 'required|date|after_or_equal:SOLICITUD_VEHICULO_FECHA_HORA_INICIO_ASIGNADA',
                'SOLICITUD_VEHICULO_HORA_INICIO_CONDUCCION' => 'required|date_format:H:i',
                'SOLICITUD_VEHICULO_HORA_TERMINO_CONDUCCION' => 'required|date_format:H:i|after_or_equal:SOLICITUD_VEHICULO_HORA_INICIO_CONDUCCION',
            ]);
    
            // Mensajes de validación personalizados
            $validator->setAttributeNames([
                'VEHICULO_ID.required' => 'El campo Vehículo es obligatorio.',
                'VEHICULO_ID.exists' => 'El vehículo seleccionado no es válido.',
                'CONDUCTOR_id.required' => 'El campo Conductor es obligatorio.',
                'CONDUCTOR_id.exists' => 'El conductor seleccionado no es válido.',
                'SOLICITUD_VEHICULO_VIATICO.required' => 'El campo Viático es obligatorio.',
                'SOLICITUD_VEHICULO_VIATICO.string' => 'El campo Viático debe ser una cadena de caracteres.',
                'SOLICITUD_VEHICULO_VIATICO.max' => 'El campo Viático no puede tener más de :max caracteres.',
                'SOLICITUD_VEHICULO_FECHA_HORA_INICIO_ASIGNADA.required' => 'El campo Fecha y hora de inicio asignada es obligatorio.',
                'SOLICITUD_VEHICULO_FECHA_HORA_TERMINO_ASIGNADA.required' => 'El campo Fecha y hora de término asignada es obligatorio.',
                'SOLICITUD_VEHICULO_FECHA_HORA_TERMINO_ASIGNADA.after' => 'La fecha y hora de término asignada debe ser posterior a la fecha y hora de inicio asignada.',
                'SOLICITUD_VEHICULO_HORA_INICIO_CONDUCCION.required' => 'El campo Hora de inicio de conducción es obligatorio.',
                'SOLICITUD_VEHICULO_HORA_TERMINO_CONDUCCION.required' => 'El campo Hora de término de conducción es obligatorio.',
                'SOLICITUD_VEHICULO_HORA_TERMINO_CONDUCCION.after' => 'La hora de término de conducción debe ser posterior a la hora de inicio de conducción.',
            ]);
            //dd($request);
            // Manejo de errores de validación
            if ($validator->fails()) {

                dd($validator);
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
            if ($request->has('guardar')) {
                // Registrar la revisión de la solicitud
                $revision = new RevisionSolicitud();
                $revision->USUARIO_id = Auth::user()->id;
                $revision->SOLICITUD_VEHICULO_ID = $solicitud->SOLICITUD_VEHICULO_ID;
                $revision->REVISION_SOLICITUD_OBSERVACION = 'PRUEBA :D';
                $revision->save();
            }

            // Verificar si se envió el botón de autorizar
            if ($request->has('autorizar')) {
                // Registrar la autorización de la solicitud
                $autorizacion = new Autorizacion();
                $autorizacion->USUARIO_id =  Auth::user()->id;
                $autorizacion->SOLICITUD_VEHICULO_ID = $solicitud->SOLICITUD_VEHICULO_ID;     
                $solicitud->SOLICITUD_VEHICULO_ESTADO = 'POR AUTORIZAR'; 
                $autorizacion->save();          
            }
        
            $solicitud->save();
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
            $plantillaFilePath = storage_path('excel/Hoja Test.xlsx');
            $spreadsheet = IOFactory::load($plantillaFilePath);
            // Obtener la solicitud actual
            $solicitud = SolicitudVehicular::findOrFail($id);


            // Definir los datos a llenar en las celdas
            $datos = [
                // Fecha de creación solicitud
                ['E5', strtoupper(Carbon::parse($solicitud->created_at)->locale('es_ES')->isoFormat('dddd'))],
                ['F5', $solicitud->created_at->format('j')],
                ['G5', strtoupper(Carbon::parse($solicitud->created_at)->locale('es_ES')->isoFormat('MMMM'))],
                ['H5', $solicitud->created_at->format('Y')],

                ['C7', $solicitud->user->USUARIO_NOMBRES.' '.$solicitud->user->USUARIO_APELLIDOS],
                ['H7', $solicitud->user->cargo->CARGO_NOMBRE],
                ['C9', $solicitud->user->ubicacion ? $solicitud->user->ubicacion->UBICACION_NOMBRE : $solicitud->user->departamento->DEPARTAMENTO_NOMBRE],

                ['D11', $solicitud->SOLICITUD_VEHICULO_MOTIVO],
                //['D12', strtoupper($solicitud->comunaDestino->COMUNA_NOMBRE)],


                // Fecha y hora de inicio solicitada
                ['C13', strtoupper(Carbon::parse($solicitud->SOLICITUD_VEHICULO_FECHA_HORA_INICIO_SOLICITADA)->isoFormat('dddd'))],
                ['C14', Carbon::parse($solicitud->SOLICITUD_VEHICULO_FECHA_HORA_INICIO_SOLICITADA)->format('j')],
                ['D13', strtoupper(Carbon::parse($solicitud->SOLICITUD_VEHICULO_FECHA_HORA_INICIO_SOLICITADA)->isoFormat('MMMM'))],
                ['D14', Carbon::parse($solicitud->SOLICITUD_VEHICULO_FECHA_HORA_INICIO_SOLICITADA)->format('Y')],
                ['H13', strtoupper(Carbon::parse($solicitud->SOLICITUD_VEHICULO_FECHA_HORA_INICIO_SOLICITADA)->format('H:i'))],

                // Fecha y hora de término solicitada
                ['E13', strtoupper(Carbon::parse($solicitud->SOLICITUD_VEHICULO_FECHA_HORA_TERMINO_SOLICITADA)->isoFormat('dddd'))],
                ['E14', Carbon::parse($solicitud->SOLICITUD_VEHICULO_FECHA_HORA_TERMINO_SOLICITADA)->format('j')],
                ['F13', strtoupper(Carbon::parse($solicitud->SOLICITUD_VEHICULO_FECHA_HORA_TERMINO_SOLICITADA)->isoFormat('MMMM'))],
                ['F14', Carbon::parse($solicitud->SOLICITUD_VEHICULO_FECHA_HORA_TERMINO_SOLICITADA)->format('Y')],
                ['H14', strtoupper(Carbon::parse($solicitud->SOLICITUD_VEHICULO_FECHA_HORA_INICIO_SOLICITADA)->format('H:i'))],


            ];

            // Iterar sobre los datos y asignarlos a las celdas correspondientes
            foreach ($datos as $dato) {
                $celda = $dato[0]; // Coordenada de la celda, por ejemplo, 'C7'
                $valor = $dato[1]; // Valor a asignar a la celda
                $spreadsheet->getActiveSheet()->setCellValue($celda, $valor);
            }


            // Guardar los cambios en la plantilla Excel
            $writer = new Xlsx($spreadsheet);
            $tempFilePath = storage_path('app/public/Hoja_Test_Modificada.xlsx');
            $writer->save($tempFilePath);
            $this->exportToPdf($request, $id);

            // Descargar el archivo con los cambios realizados
            return response()->download($tempFilePath, 'Hoja Test Modificada.xlsx')->deleteFileAfterSend(false);

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