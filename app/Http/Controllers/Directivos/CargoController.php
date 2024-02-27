<?php

namespace App\Http\Controllers\Directivos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cargo;
use App\Models\DireccionRegional;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log; // Asegúrate de importar Log
use Illuminate\Validation\Rule;



class CargoController extends Controller
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
        // Obtiene la dirección regional del funcionario con sesión activa
        $cargos = Cargo::where('OFICINA_ID', Auth::user()->OFICINA_ID)->get();
        return view('sia2.directivos.cargos.index', compact('cargos'));
    }

    /**
     * Show the form for creating a new resource.
     *///Carga formulario de creacion
    public function create()
    {

        $direccion = Auth::user()->oficina->OFICINA_NOMBRE;
        
        return view('sia2.directivos.cargos.create', compact('direccion'));

    }

    /**
     * Store a newly created resource in storage.
     *///Guarda los datos del formulario

    public function store(Request $request)
    {
        try {
            // Validación de los datos de entrada
            $validator = Validator::make($request->all(), [
                'CARGO_NOMBRE' => 'required|string|max:128|unique:cargos,CARGO_NOMBRE,NULL,NULL,OFICINA_ID,' . Auth::user()->OFICINA_ID,
            ], [
                'CARGO_NOMBRE.required' => 'El campo "Nombre del Cargo" es requerido.',
                'CARGO_NOMBRE.string' => 'El campo "Nombre del Cargo" debe ser una cadena de caracteres.',
                'CARGO_NOMBRE.max' => 'El campo "Nombre del Cargo" no puede tener más de 128 caracteres.',
                'CARGO_NOMBRE.unique' => 'El cargo ya ha sido registrado para esta oficina.',
            ]);

            // Si la validación falla, redirecciona al formulario con los errores y los datos antiguos
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            // Crear una nueva instancia de Cargo y guardarla en la base de datos
            $cargo = new Cargo();
            $cargo->CARGO_NOMBRE = $request->input('CARGO_NOMBRE');
            $cargo->OFICINA_ID = Auth::user()->OFICINA_ID;
            $cargo->save();

            return redirect()->route('cargos.index')->with('success', 'Cargo creado exitosamente');
        } catch (\Exception $e) {
            return redirect()->route('cargos.index')->with('error', 'Error al agregar el cargo. Vuelva a intentarlo nuevamente');
        }
    }

    /**
     * Display the specified resource.
     *///Accede a un único registro
   /*  public function show(string $id)
     {
         try{
             $cargo = Cargo::find($id);
             return view('cargo.show', compact('cargo'));
         }catch(\Exception $e){
             session()->flash('error', 'Error al acceder al cargo seleccionado, vuelva a intentarlo más tarde.');
             return view('cargo.show');
         }
     }
*/
    /**
     * Show the form for editing the specified resource.
     *///Carga el formulario de edicion
    public function edit(string $id)
    {
        try {
            $cargo = Cargo::findOrFail($id);
            return view('sia2.directivos.cargos.edit',compact('cargo'));
        } catch (\Exception $e) {
            return redirect()->route('cargos.index')->with('error', 'Error al editar el cargo. Vuelva a intentarlo nuevamente');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            // Validación de los datos de entrada
            $validator = Validator::make($request->all(), [
                'CARGO_NOMBRE' => 'required|string|max:128|unique:cargos,CARGO_NOMBRE,' . $id . ',CARGO_ID,OFICINA_ID,' . Auth::user()->OFICINA_ID,
            ], [
                'CARGO_NOMBRE.required' => 'El campo "Nombre del Cargo" es requerido.',
                'CARGO_NOMBRE.string' => 'El campo "Nombre del Cargo" debe ser una cadena de caracteres.',
                'CARGO_NOMBRE.max' => 'El campo "Nombre del Cargo" no puede tener más de 128 caracteres.',
                'CARGO_NOMBRE.unique' => 'El cargo ya ha sido registrado para esta oficina.',
            ]);
    
            // Si la validación falla, redirecciona al formulario con los errores y los datos antiguos
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
    
            // Buscar la instancia de Cargo a actualizar
            $cargo = Cargo::findOrFail($id);
            $cargo->CARGO_NOMBRE = $request->input('CARGO_NOMBRE');
            $cargo->OFICINA_ID = Auth::user()->OFICINA_ID;
            $cargo->save();
    
            return redirect()->route('cargos.index')->with('success', 'Cargo actualizado exitosamente');
        } catch (\Exception $e) {
            return redirect()->route('cargos.index')->with('error', 'Error al actualizar el cargo. Vuelva a intentarlo nuevamente');
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try{
            $cargo = Cargo::find($id);
            $cargo->delete();
            session()->flash('success','El cargo ha sido eliminado correctamente.');
        }catch(\Exception $e){
            session()->flash('error','Error al eliminar el cargo seleccionado, vuelva a intentarlo nuevamente.');
        }
        return redirect(route('cargos.index'));
    }
}

