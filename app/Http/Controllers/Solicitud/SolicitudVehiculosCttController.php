<?php

namespace App\Http\Controllers\Solicitud;

use App\Http\Controllers\Controller;
use App\Exports\VehiculosExport; // Importa la clase VehiculosExports correctamente
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Carbon\Carbon; // Importa la clase Carbon
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
use App\Models\Viaja;
use App\Models\Cargo;
use App\Models\Poliza;




class SolicitudVehiculosCttController extends Controller
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
            return view('sia2.solicitudes.vehiculosctt.index', compact('solicitudes'));
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
            // Obtener vehículos basados en la OFICINA_ID del usuario junto con su respectiva relación para tipos de vehículo.  
            $oficinaSesion = Auth::user()->OFICINA_ID;
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
            $jefesQueAutorizan = Cargo::where('OFICINA_ID', $oficinaSesion)
                ->where('CARGO_NOMBRE', 'like', 'JEFE %')
                ->get();

            /*// Obtener conductores que tienen pólizas y están en la misma oficina
            $conductores = User::whereHas('polizas', function ($query) use ($oficinaSesion) {
                $query->where('OFICINA_ID', $oficinaSesion);
            })->get();
            dd($conductores);*/
            dd($vehiculos);
        return view('sia2.solicitudes.vehiculosctt.create', compact('vehiculos','oficinas','ubicaciones','departamentos','regiones', 'comunas', 'users', 'jefesQueAutorizan'/*, 'conductores'*/));
        } catch (Exception $e) {
            // Manejar excepciones si es necesario
            return redirect()->route('solicitudesvehiculosctt.index')->with('error', 'Error al intentar crear la solicitud de vehículo.');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        dd($request);
        // Intentar guardar la solicitud en la base de datos
        try {
            // Validar los datos de entrada
            $validator = Validator::make($request->all(), [
                'VEHICULO_ID' => 'required|exists:vehiculos,VEHICULO_ID',
                //'RENDICION_ID' => 'nullable|unique:solicitudes_vehiculares,RENDICION_ID',
                'SOLICITUD_VEHICULO_COMUNA' => 'required|exists:comunas,COMUNA_ID',
                'SOLICITUD_VEHICULO_MOTIVO' => 'required|string|max:255',
                'SOLICITUD_VEHICULO_FECHA_HORA_INICIO_SOLICITADA' => 'required|date',
                'SOLICITUD_VEHICULO_FECHA_HORA_INICIO_ASIGNADA' => 'nullable|date',
                'SOLICITUD_VEHICULO_FECHA_HORA_TERMINO_SOLICITADA' => 'required|date|after_or_equal:SOLICITUD_VEHICULO_FECHA_HORA_INICIO_SOLICITADA',
                'SOLICITUD_VEHICULO_FECHA_HORA_TERMINO_ASIGNADA' => 'nullable|date|after:SOLICITUD_VEHICULO_FECHA_HORA_INICIO_ASIGNADA',
                'SOLICITUD_VEHICULO_HORA_INICIO_CONDUCCION' => 'required|date_format:H:i',
                'SOLICITUD_VEHICULO_HORA_TERMINO_CONDUCCION' => 'required|date_format:H:i|after_or_equal:SOLICITUD_VEHICULO_HORA_INICIO_CONDUCCION',
                'SOLICITUD_VEHICULO_JEFE_QUE_AUTORIZA' => 'nullable|string|max:128',
                'SOLICITUD_VEHICULO_VIATICO' => 'required',
            ], [
                'VEHICULO_ID.required' => 'El campo VEHICULO_ID es obligatorio.',
                'VEHICULO_ID.exists' => 'El vehículo seleccionado no es válido.',
                //'RENDICION_ID.unique' => 'El valor ingresado para RENDICION_ID ya ha sido registrado.',
                'SOLICITUD_VEHICULO_COMUNA.required' => 'El campo "comuna destino" es obligatorio.',
                'SOLICITUD_VEHICULO_COMUNA.exists' => 'La comuna seleccionada no es válida.',
                'SOLICITUD_VEHICULO_MOTIVO.required' => 'El motivo de la solicitud es obligatorio.',
                'SOLICITUD_VEHICULO_MOTIVO.string' => 'El motivo de la solicitud debe ser una cadena de caracteres.',
                'SOLICITUD_VEHICULO_MOTIVO.max' => 'El motivo de la solicitud no puede tener más de 255 caracteres.',
                'SOLICITUD_VEHICULO_FECHA_HORA_INICIO_SOLICITADA.required' => 'La fecha y hora de inicio de la solicitud es obligatoria.',
                'SOLICITUD_VEHICULO_FECHA_HORA_INICIO_SOLICITADA.date' => 'La fecha y hora de inicio de la solicitud debe ser válida.',
                'SOLICITUD_VEHICULO_FECHA_HORA_TERMINO_SOLICITADA.required' => 'El campo fecha y hora de término de solicitud es obligatorio.',
                'SOLICITUD_VEHICULO_FECHA_HORA_TERMINO_SOLICITADA.date' => 'El campo fecha y hora de término de solicitud debe ser una fecha válida.',
                'SOLICITUD_VEHICULO_FECHA_HORA_TERMINO_SOLICITADA.after_or_equal' => 'La fecha y hora de término de solicitud debe ser posterior o igual a la fecha y hora de inicio de solicitud.',
                'SOLICITUD_VEHICULO_FECHA_HORA_TERMINO_ASIGNADA.date' => 'La fecha y hora de término asignada debe ser válida.',
                'SOLICITUD_VEHICULO_FECHA_HORA_TERMINO_ASIGNADA.after' => 'La fecha y hora de término asignada debe ser posterior a la fecha y hora de inicio asignada.',
                'SOLICITUD_VEHICULO_HORA_INICIO_CONDUCCION.required' => 'La hora de inicio de conducción es obligatoria.',
                'SOLICITUD_VEHICULO_HORA_INICIO_CONDUCCION.date_format' => 'La hora de inicio de conducción debe tener un formato válido (HH:MM).',
                'SOLICITUD_VEHICULO_HORA_TERMINO_CONDUCCION.required' => 'El campo hora de término de conducción es obligatorio.',
                'SOLICITUD_VEHICULO_HORA_TERMINO_CONDUCCION.date_format' => 'El campo hora de término de conducción debe tener un formato de hora válido (HH:MM).',
                'SOLICITUD_VEHICULO_HORA_TERMINO_CONDUCCION.after_or_equal' => 'La hora de término de conducción debe ser posterior o igual a la hora de inicio de conducción.',
                'SOLICITUD_VEHICULO_JEFE_QUE_AUTORIZA.string' => 'El jefe que autoriza debe ser una cadena de caracteres.',
                'SOLICITUD_VEHICULO_JEFE_QUE_AUTORIZA.max' => 'El jefe que autoriza no puede tener más de 128 caracteres.',
                'SOLICITUD_VEHICULO_VIATICO.required' => 'Es obligatorio especificar si se requiere viático.',
                //'SOLICITUD_VEHICULO_VIATICO.in' => 'El campo SOLICITUD_VEHICULO_VIATICO debe ser uno de: SI, NO.',
            ]);
            
            // Manejar errores de validación
                if ($validator->fails()) {
                    dd($validator);
                    return redirect()->back()->withErrors($validator)->withInput();
                }

            // Crear una nueva instancia de SolicitudVehicular y asignar los valores
            $solicitud = new SolicitudVehicular();
            $solicitud->USUARIO_id = auth()->user()->id;
            $solicitud->VEHICULO_ID = $request->input('VEHICULO_ID');
            //$solicitud->RENDICION_ID = ;
            $solicitud->COMUNA_ID = $request->input('SOLICITUD_VEHICULO_COMUNA');
            $solicitud->SOLICITUD_VEHICULO_MOTIVO = strtoupper($request->input('SOLICITUD_VEHICULO_MOTIVO'));
            $solicitud->SOLICITUD_VEHICULO_ESTADO = 'INGRESADO'; // Valor por defecto
            $solicitud->SOLICITUD_VEHICULO_FECHA_HORA_INICIO_SOLICITADA = $request->input('SOLICITUD_VEHICULO_FECHA_HORA_INICIO_SOLICITADA');
            $solicitud->SOLICITUD_VEHICULO_FECHA_HORA_TERMINO_SOLICITADA = $request->input('SOLICITUD_VEHICULO_FECHA_HORA_TERMINO_SOLICITADA');
            $solicitud->SOLICITUD_VEHICULO_HORA_INICIO_CONDUCCION = $request->input('SOLICITUD_VEHICULO_HORA_INICIO_CONDUCCION');
            $solicitud->SOLICITUD_VEHICULO_HORA_TERMINO_CONDUCCION =  $request->input('SOLICITUD_VEHICULO_HORA_TERMINO_CONDUCCION');
            $solicitud->SOLICITUD_VEHICULO_JEFE_QUE_AUTORIZA = $request->input('SOLICITUD_VEHICULO_JEFE_QUE_AUTORIZA');
            $solicitud->SOLICITUD_VEHICULO_VIATICO = $request->input('SOLICITUD_VEHICULO_VIATICO');
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
            return redirect()->route('solicitudesvehiculosctt.index')->with('success', 'Solicitud creada exitosamente.');
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
    
            // Obtener los vehículos filtrados por el vehículo solicitado, en la oficina del solicitante y oficina del usuario en sesion
            $vehiculos = Vehiculo::where('VEHICULO_ID', $solicitud->vehiculo->Vehiculo->VEHICULO_ID)
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
            return view('sia2.solicitudes.vehiculosctt.edit', compact('solicitud', 'pasajeros', 'vehiculos'));
        } catch (ModelNotFoundException $e) {
            // Manejar excepción de modelo no encontrado
            return redirect()->route('solicitudesvehiculosctt.index')->with('error', 'Ocurrió un error inesperado.');
        } catch (Exception $e) {
            // Manejar excepciones si es necesario
            return redirect()->route('solicitudesvehiculosctt.index')->with('error', 'Error al cargar la solicitud de vehículo para editar: ' . $e->getMessage());
        }
    }
    

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            // Encontrar la solicitud por su ID
            $solicitud = SolicitudVehicular::findOrFail($id);
            
            // Actualizar los campos de la solicitud con los datos del formulario
            $solicitud->VEHICULO_ID = $request->input('VEHICULO_ID');
            $solicitud->SOLICITUD_VEHICULO_FECHA_HORA_INICIO_ASIGNADA = $request->input('SOLICITUD_VEHICULO_FECHA_HORA_INICIO_ASIGNADA');
            $solicitud->SOLICITUD_VEHICULO_FECHA_HORA_TERMINO_ASIGNADA = $request->input('SOLICITUD_VEHICULO_FECHA_HORA_TERMINO_ASIGNADA');
            // Puedes actualizar más campos si es necesario
            
            // Guardar los cambios en la base de datos
            $solicitud->save();
    
            // Redirigir a la vista de edición con un mensaje de éxito
            return redirect()->route('solicitudesvehiculosctt.edit', $id)->with('success', 'Solicitud actualizada correctamente.');
        } catch (ModelNotFoundException $e) {
            // Manejar excepción de modelo no encontrado
            return redirect()->route('solicitudesvehiculosctt.index')->with('error', 'Ocurrió un error inesperado.');
        } catch (Exception $e) {
            // Manejar excepciones si es necesario
            return redirect()->route('solicitudesvehiculosctt.index')->with('error', 'Error al actualizar la solicitud de vehículo.');
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