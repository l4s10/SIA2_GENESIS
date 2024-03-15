<?php

namespace App\Http\Controllers\Directivos;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log; // Asegúrate de importar Log
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Exception;

use App\Models\Resolucion;
use App\Models\DelegaFacultad;
use App\Models\ObedeceResolucion;
use App\Models\Cargo;
use App\Models\TipoResolucion;
use App\Models\Facultad;



class ResolucionController extends Controller
{
    //Funcion para acceder a las rutas SOLO SI los usuarios estan logueados
    /*public function __construct(){
        $this->middleware('auth');
        // Roles que pueden ingresar a la url
        $this->middleware(function ($request, $next) {
            $user = Auth::user();

            if ($user->hasRole('ADMINISTRADOR') || $user->hasRole('JURIDICO') || $user->hasRole('INFORMATICA')) {
                return $next($request);
            } else {
                abort(403, 'Acceso no autorizado');
            }
        });
    }*/
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Obtener todos los cargos relacionados con la oficina del usuario autenticado
        $cargos = Cargo::where('OFICINA_ID', Auth::user()->OFICINA_ID)->pluck('CARGO_ID');
    
        // Obtener todas las obediencias relacionadas con los cargos obtenidos anteriormente
        $obediencias = ObedeceResolucion::whereIn('CARGO_ID', $cargos)->get();
    
        // Obtener todas las RESOLUCION_ID de las obediencias obtenidas anteriormente
        $resolucionIds = $obediencias->pluck('RESOLUCION_ID')->toArray();       
    
        // Obtener las instancias completas de las resoluciones que coinciden con las RESOLUCION_ID obtenidas
        $resoluciones = Resolucion::whereIn('RESOLUCION_ID', $resolucionIds)->get();
    
       // Obtener todas las delegaciones cuyas RESOLUCION_ID estén en las RESOLUCION_ID obtenidas
        $delegaciones = DelegaFacultad::whereIn('RESOLUCION_ID', $resoluciones->pluck('RESOLUCION_ID'))->get();
        
       // dd($delegaciones);
        return view('sia2.directivos.resoluciones.index', compact('resoluciones', 'delegaciones', 'obediencias'));
    }

    /**
    * Show the form for creating a new resource.
     *///Carga formulario de creacion
    
     public function create()
    {
        $tiposResoluciones = TipoResolucion::all();
        $facultades = Facultad::all();

        // Obtener los cargos de la misma oficina del usuario autenticado
        $cargosOficina = Cargo::where('OFICINA_ID', Auth::user()->OFICINA_ID)->get();

        // Obtener el cargo 'DIRECTOR' independientemente de la oficina
        $cargoDirector = Cargo::where('CARGO_NOMBRE', 'DIRECTOR')->first();

        // Combinar los resultados en un solo array si se encontró el 'DIRECTOR'
        $cargos = $cargosOficina->push($cargoDirector);

        //dd($cargos);
        

        return view('sia2.directivos.resoluciones.create', compact('tiposResoluciones', 'facultades','cargos'));
    }


    public function store(Request $request)
    {
        //dd($request);
        try {
            // Validar los datos del formulario de resoluciones.
            $validator = Validator::make($request->all(), [
                'RESOLUCION_NUMERO' => 'required|integer',
                'RESOLUCION_FECHA' => 'required|date',
                'TIPO_RESOLUCION_ID' => 'required|integer',
                'CARGO_ID' => 'required|integer',
                'DELEGADO_ID' => 'required|integer',
                'RESOLUCION_DOCUMENTO' => 'nullable|string|max:191',
                'RESOLUCION_OBSERVACIONES' => 'nullable|string|max:191',
                'FACULTAD_ID' => 'required|integer',
                'DELEGADO_ID' => 'required|integer',                
            ], [
                'RESOLUCION_NUMERO.required' => 'El campo "Número de resolución" es requerido.',
                'RESOLUCION_NUMERO.integer' => 'El campo "Número de resolución" debe ser un número entero.',
                'RESOLUCION_FECHA.required' => 'El campo "Fecha de resolución" es requerido.',
                'RESOLUCION_FECHA.date' => 'El campo "Fecha de resolución" debe ser una fecha válida.',
                'TIPO_RESOLUCION_ID.required' => 'El campo "Tipo de resolución" es requerido.',
                'TIPO_RESOLUCION_ID.integer' => 'El campo "Tipo de resolución" debe ser un número entero.',
                'CARGO_ID.required' => 'El campo "Firmante" es requerido.',
                'CARGO_ID.integer' => 'El campo "Firmante" debe ser un número entero.',
                'DELEGADO_ID.required' => 'El campo "Delegado" es requerido.',
                'DELEGADO_ID.integer' => 'El campo "Delegado" debe ser un número entero.',
                'RESOLUCION_DOCUMENTO.string' => 'El campo "Documento de resolución" debe ser una cadena de caracteres.',
                'RESOLUCION_DOCUMENTO.max' => 'El campo "Documento de resolución" no debe exceder 191 caracteres.',
                'RESOLUCION_OBSERVACIONES.string' => 'El campo "Observaciones de resolución" debe ser una cadena de caracteres.',
                'RESOLUCION_OBSERVACIONES.max' => 'El campo "Observaciones de resolución" no debe exceder 191 caracteres.',
            ]);
            
            // Si la validación falla, redirecciona al formulario con los errores y el input antiguo
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }


            $resolucion = new Resolucion();
            $resolucion->RESOLUCION_NUMERO = $request->input('RESOLUCION_NUMERO');
            $resolucion->RESOLUCION_FECHA = $request->input('RESOLUCION_FECHA');
            $resolucion->TIPO_RESOLUCION_ID = $request->input('TIPO_RESOLUCION_ID');
            $resolucion->CARGO_ID = $request->input('CARGO_ID');
            $resolucion->RESOLUCION_DOCUMENTO = $request->input('RESOLUCION_DOCUMENTO');
            $resolucion->RESOLUCION_OBSERVACIONES = $request->input('RESOLUCION_OBSERVACIONES');
            
            
    
            $data = $request->only('RESOLUCION_NUMERO', 'RESOLUCION_FECHA', 'TIPO_RESOLUCION_ID', 'CARGO_ID', 'RESOLUCION_OBSERVACIONES');

            if ($request->hasFile('RESOLUCION_DOCUMENTO')) {
                $documento = $request->file('RESOLUCION_DOCUMENTO');
            
                // Genera un nombre único para el archivo PDF
                $nombreArchivo = uniqid() . '.' . $documento->getClientOriginalExtension();
            
                // Guardar el archivo PDF en la carpeta 'resoluciones' dentro del disco 'public'
                $documento->storeAs('resoluciones', $nombreArchivo, 'public');

                // Asignar el nombre del archivo a la columna RESOLUCION_DOCUMENTO
                $data['RESOLUCION_DOCUMENTO'] = $nombreArchivo;
            }
            // Guardar el modelo actualizado
            Resolucion::create($data);

            //dd($resolucion);
            $delegaFacultad = new DelegaFacultad();
            $delegaFacultad->RESOLUCION_ID = $resolucion->RESOLUCION_ID;
            $delegaFacultad->FACULTAD_ID = $request->input('FACULTAD_ID');
            $delegaFacultad->save();

            $obedeceResolucion = new ObedeceResolucion();
            $obedeceResolucion->RESOLUCION_ID = $resolucion->RESOLUCION_ID;
            $obedeceResolucion->CARGO_ID = $request->input('DELEGADO_ID');
            $obedeceResolucion->save();

            // Redireccionar a la vista de solicitudes con un mensaje de éxito
            return redirect()->route('resoluciones.index')->with('success', 'Resolución creada exitosamente');
        }catch(Exception $e){
            dd($e);
            // Manejar excepciones si es necesario
            return redirect()->back()->with('error', 'Error al crear la resolución.');
        }
    }

    /**
     * Store a newly created resource in storage.
     *///Guarda los datos del formulario

    
    /**
     * Display the specified resource.
     *///Accede a un único registro
    /*public function show(string $id)
    {
        try{
            $resolucion = Resolucion::with('tipo', 'firmante', 'delegado', 'facultad')->find($id);
            return view('resolucion.show', compact('resolucion'));
        }catch(\Exception $e){
            session()->flash('error', 'Error al acceder a la resolución delegatoria seleccionada, vuelva a intentarlo más tarde.');
            return redirect(route('resolucion.index'));
        }
    }*/

    /**
     * Show the form for editing the specified resource.
     *///Carga el formulario de edicion
    public function edit(string $id)
    {
        try{
            $resolucion = Resolucion::findOrFail($id);
            $tiposResoluciones = TipoResolucion::all();
    
            $facultades = Facultad::all();
    
            // Obtener los cargos de la misma oficina del usuario autenticado
            $cargosOficina = Cargo::where('OFICINA_ID', Auth::user()->OFICINA_ID)->get();
    
            // Obtener el cargo 'DIRECTOR' independientemente de la oficina
            $cargoDirector = Cargo::where('CARGO_NOMBRE', 'DIRECTOR')->first();
    
            // Combinar los resultados en un solo array si se encontró el 'DIRECTOR'
            $cargos = $cargosOficina->add($cargoDirector);

            $delegacion = DelegaFacultad::where('RESOLUCION_ID', $resolucion->RESOLUCION_ID)->first();

            // Obtener todas las obediencias relacionadas con los cargos obtenidos anteriormente
            $obediencia = ObedeceResolucion::where('RESOLUCION_ID', $resolucion->RESOLUCION_ID)->first();

            //dd($resolucion);
            return view('sia2.directivos.resoluciones.edit', compact('resolucion', 'tiposResoluciones', 'facultades', 'cargos','delegacion','obediencia'));
        }catch(Exception $e){
            // Manejar excepciones
            return redirect()->route('resoluciones.index')->with('error', 'Error al cargar la resolución');
        }
       
    }

    public function update(Request $request, string $id)
    {
        //dd($request);

        try {
            $resolucion = Resolucion::findOrFail($id);
            // Validar los datos del formulario de resoluciones.

            $validator = Validator::make($request->all(), [
                'RESOLUCION_NUMERO' => 'required|integer',
                'RESOLUCION_FECHA' => 'required|date',
                'TIPO_RESOLUCION_ID' => 'required|integer',
                'CARGO_ID' => 'required|integer',
                'DELEGADO_ID' => 'required|integer',
                'RESOLUCION_DOCUMENTO' => 'nullable|string|max:191',
                'RESOLUCION_OBSERVACIONES' => 'nullable|string|max:191',
                'FACULTAD_ID' => 'required|integer',
                'DELEGADO_ID' => 'required|integer',                
            ], [
                'RESOLUCION_NUMERO.required' => 'El campo "Número de resolución" es requerido.',
                'RESOLUCION_NUMERO.integer' => 'El campo "Número de resolución" debe ser un número entero.',
                'RESOLUCION_FECHA.required' => 'El campo "Fecha de resolución" es requerido.',
                'RESOLUCION_FECHA.date' => 'El campo "Fecha de resolución" debe ser una fecha válida.',
                'TIPO_RESOLUCION_ID.required' => 'El campo "Tipo de resolución" es requerido.',
                'TIPO_RESOLUCION_ID.integer' => 'El campo "Tipo de resolución" debe ser un número entero.',
                'CARGO_ID.required' => 'El campo "Firmante" es requerido.',
                'CARGO_ID.integer' => 'El campo "Firmante" debe ser un número entero.',
                'DELEGADO_ID.required' => 'El campo "Delegado" es requerido.',
                'DELEGADO_ID.integer' => 'El campo "Delegado" debe ser un número entero.',
                'RESOLUCION_DOCUMENTO.string' => 'El campo "Documento de resolución" debe ser una cadena de caracteres.',
                'RESOLUCION_DOCUMENTO.max' => 'El campo "Documento de resolución" no debe exceder 191 caracteres.',
                'RESOLUCION_OBSERVACIONES.string' => 'El campo "Observaciones de resolución" debe ser una cadena de caracteres.',
                'RESOLUCION_OBSERVACIONES.max' => 'El campo "Observaciones de resolución" no debe exceder 191 caracteres.',
            ]);

            // Si la validación falla, redirecciona al formulario con los errores y el input antiguo
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }



            $resolucion = Resolucion::where('RESOLUCION_ID', $id)->firstOrFail();
            $resolucion->RESOLUCION_NUMERO = $request->input('RESOLUCION_NUMERO');
            $resolucion->RESOLUCION_FECHA = $request->input('RESOLUCION_FECHA');
            $resolucion->TIPO_RESOLUCION_ID = $request->input('TIPO_RESOLUCION_ID');
            $resolucion->CARGO_ID = $request->input('CARGO_ID');
            $resolucion->RESOLUCION_DOCUMENTO = $request->input('RESOLUCION_DOCUMENTO');
            $resolucion->RESOLUCION_OBSERVACIONES = $request->input('RESOLUCION_OBSERVACIONES');
            $resolucion->delegacion->FACULTAD_ID = $request->input('FACULTAD_ID');


            // Procesar el archivo adjunto si se ha seleccionado uno
            if ($request->hasFile('RESOLUCION_DOCUMENTO')) {
                //Borra el archivo existente, en caso de que exista
                if ($resolucion->DOCUMENTO) {
                    Storage::disk('public')->delete('resoluciones/' . $resolucion->DOCUMENTO);
                }
                $documento = $request->file('DOCUMENTO');

                // Genera un nombre único para el archivo PDF
                $nombreArchivo = uniqid() . '.' . $documento->getClientOriginalExtension();

                // Guarda el archivo PDF en la carpeta 'resoluciones' dentro del disco 'public'
                $documento->storeAs('resoluciones', $nombreArchivo, 'public');

                $resolucion->DOCUMENTO = $nombreArchivo;
            }

            // Verificar si se debe eliminar el archivo adjunto actual
            if ($request->has('ELIMINAR_DOCUMENTO')) {
                // Eliminar el archivo adjunto actual
                Storage::disk('public')->delete('resoluciones/' . $resolucion->DOCUMENTO);
                $resolucion->DOCUMENTO = null;
            }
            // Guardar el modelo actualizado
            $resolucion->save();
            //dd($resolucion);

            DelegaFacultad::where('RESOLUCION_ID', $resolucion->RESOLUCION_ID)->delete();
            
            // Eliminar las instancias existentes de ObedeceResolucion asociadas a la RESOLUCION_ID
            ObedeceResolucion::where('RESOLUCION_ID', $resolucion->RESOLUCION_ID)->delete();

            // Crear nuevas instancias de DelegaFacultad
            $delegaFacultad = new DelegaFacultad();
            $delegaFacultad->RESOLUCION_ID = $resolucion->RESOLUCION_ID;
            $delegaFacultad->FACULTAD_ID = $request->input('FACULTAD_ID');
            $delegaFacultad->save();

            // Crear nuevas instancias de ObedeceResolucion
            $obedeceResolucion = new ObedeceResolucion();
            $obedeceResolucion->RESOLUCION_ID = $resolucion->RESOLUCION_ID;
            $obedeceResolucion->CARGO_ID = $request->input('DELEGADO_ID');
            $obedeceResolucion->save();
            //$this->actualizarDelegacion($resolucion, $request);


          
            // Redireccionar a la vista index de resoluciones con un mensaje de éxito
            return redirect()->route('resoluciones.index')->with('success', 'Resolución actualizada exitosamente');
        } catch(Exception $e) {
            dd($e);
            return redirect()->route('resoluciones.index')->with('error', 'Error al actualizar la resolución');
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $resolucion = Resolucion::find($id);

            // Eliminar el documento asociado si existe
            if ($resolucion->DOCUMENTO) {
                Storage::disk('public')->delete('resoluciones/' . $resolucion->DOCUMENTO);
            }
            DelegaFacultad::where('RESOLUCION_ID', $resolucion->RESOLUCION_ID)->delete();
            
            // Eliminar las instancias existentes de ObedeceResolucion asociadas a la RESOLUCION_ID
            ObedeceResolucion::where('RESOLUCION_ID', $resolucion->RESOLUCION_ID)->delete();
            $resolucion->delete();
            session()->flash('success', 'La resolución ha sido eliminada correctamente.');
        } catch (\Exception $e) {
            session()->flash('error', 'Error al eliminar la resolución seleccionada, vuelva a intentarlo nuevamente.');
        }
        
        return redirect(route('resoluciones.index'));
    }

    //Mostrar pdf
    public function showDocumento($filename)
    {
        $path = public_path('resoluciones/' . $filename);

        if (file_exists($path)) {
            return response()->file($path);
        }

        abort(404);
    }
}

