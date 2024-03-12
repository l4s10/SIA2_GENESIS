<?php

namespace App\Http\Controllers\Panel;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;

use App\Models\Oficina;
use App\Models\Region;
use App\Models\Comuna;



class OficinaController extends Controller
{
    //Funcion para acceder a las rutas SOLO SI los usuarios estan logueados
    /*public function __construct(){
        $this->middleware('auth');
        //Roles que pueden ingresar a la url
        $this->middleware(['roleAdminAndSupport']);
    }*/
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $oficinas = Oficina::all();
        return view('sia2.panel.oficinas.index',compact('oficinas'));
    }

    /**
     * Show the form for creating a new resource.
     *///Carga formulario de creacion
    public function create()
    {
        try {
            $regiones = Region::all();
            $comunas = Comuna::all();
            return view('sia2.panel.oficinas.create',compact('regiones','comunas'));
        } catch (Exception $ex) {
            return redirect()->back()->with('error', 'Ha ocurrido un error al cargar la dirección regional');
        }    
    }

    /**
     * Store a newly created resource in storage.
     *///Guarda los datos del formulario
    public function store(Request $request)
    {
        try{
            // Reglas de validación
            $validator = Validator::make($request->all(), [
                'OFICINA_NOMBRE' => 'required|string|max:128|unique:oficinas',
                'COMUNA_ID' => 'required|exists:comunas,COMUNA_ID',
            ],[
                'OFICINA_NOMBRE.required' => 'El campo "Nombre de la dirección regional" es requerido',
                'OFICINA_NOMBRE.string' => 'El campo  "Nombre de la dirección regional" debe ser un texto',
                'OFICINA_NOMBRE.max' => 'El campo  "Nombre de la dirección regional" no debe exceder los :max caracteres',
                'OFICINA_NOMBRE.unique' => 'Esta dirección regional ya se encuentra registrada.',
                'COMUNA_ID.required' => 'El campo "Comuna" es requerido',
                'exists' => 'El campo "Comuna" no es válida.',
            ]);

            $validator->after(function ($validator) use ($request) {
                $exists = Oficina::where([
                    'OFICINA_NOMBRE' => $request->input('OFICINA_NOMBRE'),
                ])->exists();
            
                if ($exists) {
                    $validator->errors()->add('OFICINA_NOMBRE', 'Esta dirección regional ya se encuentra registrada.');
                }
            });
            
            // Validacion y redireccion con mensajes de error
            if ($validator->fails())
            {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            else
            {
                // Crear la región
                Oficina::create([
                    'OFICINA_NOMBRE' => strtoupper($request->input('OFICINA_NOMBRE')),
                    'COMUNA_ID' => $request->COMUNA_ID,
                ]);
                // Retornamos la vista con el mensaje de exito
                return redirect()->route('panel.oficinas.index')->with('success', 'Dirección Regional agregada exitosamente');
            }

        }catch(\Exception $e){
            session()->flash('error','Hubo un error al agregar la Dirección Regional. Por favor, inténtelo nuevamente');
        }
    }

    /**
     * Display the specified resource.
     *///Accede a un único registro
    /*public function show(string $id)
    {
        try{
            $direcciones = DireccionRegional::find($id);
            $region = $direcciones->region->REGION;
            return view('direccionregional.show', compact('direcciones','region'));
        }catch(\Exception $e){
            session()->flash('error', 'Error al acceder a la dirección regional seleccionada, vuelva a intentarlo más tarde.');
            return view('direccionregional.index');
        }
    }*/

    /**
     * Show the form for editing the specified resource.
     *///Carga el formulario de edicion
    public function edit(string $id)
    {
        try {
            $oficina = Oficina::findOrFail($id);
            $regiones = Region::all();
            $comunas = Comuna::all();
            return view('sia2.panel.oficinas.edit',compact('comunas','regiones','oficina'));

        } catch (Exception $ex) {
            return redirect()->back()->with('error', 'Ha ocurrido un error al cargar la Dirección Regional');
        }    
    }


    /**
     * Update the specified resource in storage.
     *///Guarda el formulario de edicion en la bd
    public function update(Request $request, string $id)
    {
        try {
            // Encontrar la oficina que se está actualizando
            $oficina = Oficina::findOrFail($id);

            // Validar los datos de entrada
            $validator = Validator::make($request->all(), [
                'OFICINA_NOMBRE' => 'required|string|max:128|unique:oficinas',
                'COMUNA_ID' => 'required|exists:comunas,COMUNA_ID',
            ],[
                'OFICINA_NOMBRE.required' => 'El campo "Nombre de la dirección regional" es requerido',
                'OFICINA_NOMBRE.string' => 'El campo  "Nombre de la dirección regional" debe ser un texto',
                'OFICINA_NOMBRE.max' => 'El campo  "Nombre de la dirección regional" no debe exceder los :max caracteres',
                'OFICINA_NOMBRE.unique' => 'Esta dirección regional ya se encuentra registrada.',
                'COMUNA_ID.required' => 'El campo "Comuna" es requerido',
                'exists' => 'El campo "Comuna" no es válida.',
            ]);

            // Agregar regla de unicidad para el nombre de la oficina, excluyendo la oficina actual
            $validator->after(function ($validator) use ($request, $id) {
                $exists = Oficina::where([
                    'OFICINA_NOMBRE' => $request->input('OFICINA_NOMBRE'),
                    'COMUNA_ID' => $request->input('COMUNA_ID'),
                ])->where('OFICINA_ID', '!=', $id)->exists();
            
                if ($exists) {
                    $validator->errors()->add('OFICINA_NOMBRE', 'Esta Dirección Regional ya se encuentra registrada.');
                }
            });

            // Si la validación falla, redirigir de vuelta con los errores
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            } else {
                // Encontrar la oficina a actualizar
                $oficina = Oficina::findOrFail($id);
                
                // Actualizar la oficina
                $oficina->update([
                    'OFICINA_NOMBRE' => strtoupper($request->input('OFICINA_NOMBRE')),
                    'COMUNA_ID' => $request->COMUNA_ID,
                ]);
                
                // Retornamos la vista con el mensaje de exito
                return redirect()->route('panel.oficinas.index')->with('success', 'Dirección Regional actualizada exitosamente');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Error al crear la Dirección Regional');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $oficina = Oficina::findOrFail($id);
        try{
            $oficina->delete();
            session()->flash('success','La Dirección Regional ha sido eliminada correctamente.');
        }catch(\Exception $e){
            session()->flash('error','Error al eliminar la Dirección Regional. Por favor, inténtelo nuevamente.');
        }
        return redirect(route('panel.oficinas.index'));
    }
    
    
    /*public function getDireccion($ubicacionId)
    {
        $direccion = DireccionRegional::where('ID_DIRECCION', '=', Ubicacion::find($ubicacionId)->ID_DIRECCION)->first();
        return response()->json($direccion);
    }
    public function getDirecciones($id)
    {
        $direcciones = DireccionRegional::where('ID_REGION', $id)->get();
        return response()->json($direcciones);
    }*/
}

