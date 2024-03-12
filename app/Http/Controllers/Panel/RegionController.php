<?php

namespace App\Http\Controllers\Panel;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;

use App\Models\Region;



class RegionController extends Controller
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
        $regiones = Region::all();
        return view('sia2.panel.regiones.index',compact('regiones'));
    }

    /**
     * Show the form for creating a new resource.
     *///Carga formulario de creacion
    public function create()
    {
        try {
            // Retornamos la vista
            return view('sia2.panel.regiones.create');
        } catch (Exception $ex) {
            return redirect()->back()->with('error', 'Ha ocurrido un error al cargar la región');
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
                'REGION_NOMBRE' => 'required|string|max:200',
            ],[
                'required' => 'El campo "Nombre de la región" es requerido',
                'string' => 'El campo  "Nombre de la región" debe ser un texto',
                'max' => 'El campo  "Nombre de la región" no debe exceder los :max caracteres',
            ]);

            $validator->after(function ($validator) use ($request) {
                $exists = Region::where([
                    'REGION_NOMBRE' => $request->input('REGION_NOMBRE'),
                ])->exists();
            
                if ($exists) {
                    $validator->errors()->add('REGION_NOMBRE', 'Esta región ya existe en el registro de regiones.');
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
                Region::create([
                    'REGION_NOMBRE' => strtoupper($request->input('REGION_NOMBRE')),
                ]);
                // Retornamos la vista con el mensaje de exito
                return redirect()->route('panel.regiones.index')->with('success', 'Región agregada exitosamente');
            }

        }catch(\Exception $e){
            session()->flash('error','Hubo un error al agregar la región. Por favor, inténtelo nuevamente');
        }
    }

    /**
     * Display the specified resource.
     *///Accede a un único registro
    /*public function show(string $id)
    {
        try{
            $region = Region::find($id);
            return view('region.show', compact('region'));
        }catch(\Exception $e){
            session()->flash('error', 'Error al acceder a la región seleccionada, vuelva a intentarlo más tarde.');
            return view('region.index');
        }
    }*/

    /**
     * Show the form for editing the specified resource.
     *///Carga el formulario de edicion
    public function edit(string $id)
    {
        try {
            $region = Region::findOrFail($id);
            return view('sia2.panel.regiones.edit',compact('region'));         
        } catch (Exception $ex) {
            return redirect()->back()->with('error', 'Ha ocurrido un error al cargar la región');
        }    
    }

    /**
     * Update the specified resource in storage.
     *///Guarda el formulario de edicion en la bd
    public function update(Request $request, string $id)
    {
        // Manejo de excepciones
        try
        {
            // Reglas de validación
            $validator = Validator::make($request->all(), [
                'REGION_NOMBRE' => 'required|string|max:200',
            ],[
                'required' => 'El campo "Nombre de la región" es requerido',
                'string' => 'El campo  "Nombre de la región" debe ser un texto',
                'max' => 'El campo  "Nombre de la región" no debe exceder los :max caracteres',
            ]);

            $validator->after(function ($validator) use ($request, $id) {
                $exists = Region::where([
                    'REGION_NOMBRE' => $request->input('REGION_NOMBRE'),
                ])->where('REGION_ID', '!=', $id)->exists();
            
                if ($exists) {
                    $validator->errors()->add('REGION_NOMBRE', 'Esta región ya existe en el registro de regiones.');
                }
            });

            // Validacion y redireccion con mensajes de error
            if ($validator->fails())
            {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            else
            {
                try
                {
                    // Obtener la región
                    $region = Region::findOrFail($id);
                    // Actualizar la región
                    $region->update([
                        'REGION_NOMBRE' => strtoupper($request->input('REGION_NOMBRE')),
                    ]);

                    // Retornar a la vista index con el mensaje de éxito
                    return redirect()->route('panel.regiones.index')->with('success', 'Región actualizada exitosamente');
                }
                catch(Exception $ex)
                {
                    return redirect()->route('panel.regiones.index')->with('error', 'Ha ocurrido un error al actualizar la región');
                }
            }
        }
        catch (Exception $ex)
        {
            return redirect()->route('panel.regiones.index')->with('error', 'Ha ocurrido un error al actualizar la región');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $region = Region::find($id);
        try{
            $region->delete();
            session()->flash('success','La región ha sido eliminada correctamente.');
        }catch(\Exception $e){
            session()->flash('error','Error al eliminar la región seleccionada, vuelva a intentarlo nuevamente.');
        }
        return redirect(route('panel.regiones.index'));
    }
}

