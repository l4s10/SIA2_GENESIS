<?php

namespace App\Http\Controllers\Panel;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

use Exception;

use App\Models\Ubicacion;
use App\Models\Oficina;

class UbicacionController extends Controller
{
    //Funcion para acceder a las rutas SOLO SI los usuarios estan logueados
    /*public function __construct(){
        $this->middleware('auth');
        //Tambien aqui podremos agregar que roles son los que pueden ingresar
        $this->middleware(['roleAdminAndSupport']);
    }*/
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //Obtener ubicaciones para la dirección regional en sesión
        $ubicaciones = Ubicacion::where('OFICINA_ID', Auth::user()->OFICINA_ID)->get();
        return view('sia2.panel.ubicaciones.index', compact('ubicaciones'));
    }

    /**
     * Show the form for creating a new resource.
     *///Carga formulario de creacion
     public function create()
    {
        try {   
            $oficinas = Oficina::all();  
            return view('sia2.panel.ubicaciones.create',compact('oficinas'));
        } catch (Exception $ex) {
            return redirect()->back()->with('error', 'Ha ocurrido un error al cargar la ubicación');
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
                'UBICACION_NOMBRE' => 'required|string|max:128|unique:ubicaciones,UBICACION_NOMBRE,NULL,id,OFICINA_ID,' . $request->OFICINA_ID,
                'OFICINA_ID' => 'required|exists:oficinas,OFICINA_ID',
            ],[
                'UBICACION_NOMBRE.required' => 'El campo "Nombre" es requerido',
                'UBICACION_NOMBRE.string' => 'El campo  "Nombre" debe ser un texto',
                'UBICACION_NOMBRE.max' => 'El campo "Nombre" no debe exceder los :max caracteres',
                'UBICACION_NOMBRE.unique' => 'El campo "Nombre" ya se encuentra registrado en su dirección regional.',
                'OFICINA_ID.required' => 'El campo "Dirección regional asociada" es requerido',
                'exists' => 'El campo "Dirección regional asociada" no es válido.',
            ]);

            
            // Validacion y redireccion con mensajes de error
            if ($validator->fails())
            {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            else
            {
                // Crear la región
                Ubicacion::create([
                    'UBICACION_NOMBRE' => strtoupper($request->input('UBICACION_NOMBRE')),
                    'OFICINA_ID' => $request->OFICINA_ID,
                ]);
                // Retornamos la vista con el mensaje de exito
                return redirect()->route('panel.ubicaciones.index')->with('success', 'Ubicación agregada exitosamente');
            }

        }catch(\Exception $e){
            //dd($e);
            session()->flash('error','Hubo un error al agregar la ubicación. Por favor, inténtelo nuevamente');
        }
    }

    /**
     * Display the specified resource.
     *///Accede a un único registro
    /* public function show(string $id)
     {
         try{
             $ubicacion = Ubicacion::find($id);
             return view('ubicacion.show', compact('ubicacion'));
         }catch(\Exception $e){
             session()->flash('error', 'Error al acceder en la ubicación seleccionada, vuelva a intentarlo más tarde.');
             return view('ubicacion.show');
         }
     }*/

    /**
     * Show the form for editing the specified resource.
     *///Carga el formulario de edicion
     public function edit(string $id)
    {
        try {
            $ubicacion = Ubicacion::find($id);
            $oficinas = Oficina::all();
    
            return view('sia2.panel.ubicaciones.edit',compact('ubicacion','oficinas'));

        } catch (Exception $ex) {
            return redirect()->back()->with('error', 'Ha ocurrido un error al cargar la ubicación');
        }    
    }

    public function update(Request $request, string $id)
    {
        try {
            // Encontrar la ubicación que se está actualizando
            $ubicacion = Ubicacion::findOrFail($id);

            // Reglas de validación
            $validator = Validator::make($request->all(), [
                'UBICACION_NOMBRE' => 'required|string|max:128|unique:ubicaciones,UBICACION_NOMBRE,' . $id . ',UBICACION_ID,OFICINA_ID,' . $request->OFICINA_ID,
                'OFICINA_ID' => 'required|exists:oficinas,OFICINA_ID',
            ],[
                'UBICACION_NOMBRE.required' => 'El campo "Nombre" es requerido',
                'UBICACION_NOMBRE.string' => 'El campo  "Nombre" debe ser un texto',
                'UBICACION_NOMBRE.max' => 'El campo "Nombre" no debe exceder los :max caracteres',
                'UBICACION_NOMBRE.unique' => 'El campo "Nombre" ya se encuentra registrado en su dirección regional.',
                'OFICINA_ID.required' => 'El campo "Dirección regional asociada" es requerido',
                'exists' => 'El campo "Dirección regional asociada" no es válido.',
            ]);

           // Si la validación falla, redirigir de vuelta con los errores
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            } else {
                // Actualizar la ubicación
                $ubicacion->update([
                    'UBICACION_NOMBRE' => strtoupper($request->input('UBICACION_NOMBRE')),
                    'OFICINA_ID' => $request->OFICINA_ID,
                ]);
                
                // Retornamos la vista con el mensaje de éxito
                return redirect()->route('panel.ubicaciones.index')->with('success', 'Ubicación actualizada exitosamente');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Error al actualizar la ubicación');
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try{
            $ubicacion = Ubicacion::findOrFail($id);
            $ubicacion->delete();
            session()->flash('success','La ubicación ha sido eliminada correctamente.');
        }catch(\Exception $e){
            session()->flash('error','Error al eliminar la ubicación seleccionada, vuelva a intentarlo nuevamente.');
        }
        return redirect(route('panel.ubicaciones.index'));
    }
    /*public function getUbicaciones($direccionId)
    {
        // Asume que tienes un modelo Ubicacion que tiene una relación con Direcciones
        $ubicaciones = Ubicacion::where('ID_DIRECCION', $direccionId)->get();

       return response()->json($ubicaciones);
    }*/
}
