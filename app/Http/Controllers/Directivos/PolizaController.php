<?php

namespace App\Http\Controllers\Directivos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Exception;
use Illuminate\Validation\Rule;


use App\Models\User;
use App\Models\Poliza;
use App\Models\Ubicacion;
use App\Models\Departamento;



class PolizaController extends Controller
{
    //Funcion para acceder a las rutas SOLO SI los usuarios estan logueados
    /*public function __construct(){
        $this->middleware('auth');
        //Roles que pueden ingresar a la url
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
        $usuariosConductores = User::where('OFICINA_ID', Auth::user()->OFICINA_ID)
            ->with(['polizas' => function ($query) {
                $query->whereNotNull('USUARIO_id');
            }])
            ->get();
    
        return view('sia2.directivos.polizas.index', compact('usuariosConductores'));
    }

    /**
     * Show the form for creating a new resource.
     *///Carga formulario de creacion
    public function create()
    {
        try{   
            
            $users = User::where('OFICINA_ID', Auth::user()->OFICINA_ID)->get();
            $ubicaciones = Ubicacion::where('OFICINA_ID', Auth::user()->OFICINA_ID)->get();
            $departamentos = Departamento::where('OFICINA_ID', Auth::user()->OFICINA_ID)->get();
    
            return view('sia2.directivos.polizas.create', compact('users','ubicaciones','departamentos'));
        }catch(\Exception $e){
            session()->flash('error','Hubo un error al cargar la página. Vuelva a intentarlo nuevamente');
        }
    }

    /**
     * Store a newly created resource in storage.
     *///Guarda los datos del formulario
    public function store(Request $request)
    {
        try{   
            // Validación de los datos de entrada
            $validator = Validator::make($request->all(), [
                'USUARIO_id' => 'required|integer|unique:polizas,USUARIO_id',
                'POLIZA_FECHA_VENCIMIENTO_LICENCIA' => 'required|date',
                'POLIZA_NUMERO' => 'required|integer|unique:polizas,POLIZA_NUMERO',
            ], [
                'USUARIO_id.required' => 'El campo "Usuario" es requerido.',
                'USUARIO_id.integer' => 'El campo "Usuario" debe ser un número entero.',
                'USUARIO_id.unique' => 'El campo "Usuario" ya ha sido registrado.',
                'POLIZA_FECHA_VENCIMIENTO_LICENCIA.required' => 'El campo "Fecha de vencimiento de la póliza" es requerido.',
                'POLIZA_FECHA_VENCIMIENTO_LICENCIA.date' => 'El campo "Fecha de vencimiento de la póliza" debe ser una fecha válida.',
                'POLIZA_NUMERO.required' => 'El campo "Número de póliza" es requerido.',
                'POLIZA_NUMERO.integer' => 'El campo "Número de póliza" debe ser un número entero.',
                'POLIZA_NUMERO.unique' => 'El campo "Número de póliza" ya ha sido registrado.',
            ]);

            // Si la validación falla, redirecciona al formulario con los errores y los datos antiguos
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            // Crear una nueva instancia de Poliza y guardarla en la base de datos
            $poliza = new Poliza();
            $poliza->USUARIO_id = $request->input('USUARIO_id');
            $poliza->OFICINA_ID = Auth::user()->OFICINA_ID;
            $poliza->POLIZA_FECHA_VENCIMIENTO_LICENCIA = $request->input('POLIZA_FECHA_VENCIMIENTO_LICENCIA');
            $poliza->POLIZA_NUMERO = $request->input('POLIZA_NUMERO');
            $poliza->save();

            return redirect()->route('polizas.index')->with('success', 'Póliza creada exitosamente');
        }catch(\Exception $e){
            session()->flash('error','Hubo un error al agregar la póliza. Vuelva a intentarlo nuevamente');
        }
        return redirect(route('polizas.index'));
    }

    /**
     * Display the specified resource.
     *///Accede a un único registro
    // PolizaController@show
   /* public function show(string $id)
    {
        try {
            $poliza = Poliza::with('user')->find($id);
            return view('polizas.show', compact('poliza'));
        } catch (\Exception $e) {
            session()->flash('error', 'Error al acceder a la póliza seleccionada, vuelva a intentarlo más tarde.');
            return view('polizas.index');
        }
    }*/


    /**
     * Show the form for editing the specified resource.
     *///Carga el formulario de edicion
    public function edit(string $id)
    {
        try{   
            $poliza = Poliza::with('user')->find($id);
            $users = User::where('OFICINA_ID', Auth::user()->OFICINA_ID)->get();
            $ubicaciones = Ubicacion::where('OFICINA_ID', Auth::user()->OFICINA_ID)->get();
            $departamentos = Departamento::where('OFICINA_ID', Auth::user()->OFICINA_ID)->get();

            return view('sia2.directivos.polizas.edit',compact('poliza','users','ubicaciones','departamentos'));
        }catch(Exception $e){
            // Manejar excepciones
            return redirect()->route('polizas.index')->with('error', 'Error al cargar la póliza');
        }
        
    }

    /**
     * Update the specified resource in storage.
     *///Guarda el formulario de edicion en la bd
public function update(Request $request, $id)
{
    try {
        // Validación de los datos de entrada
        $validator = Validator::make($request->all(), [
            'USUARIO_id' => ['required', 'integer', Rule::unique('polizas')->ignore($id, 'POLIZA_ID')],
            'POLIZA_FECHA_VENCIMIENTO_LICENCIA' => 'required|date',
            'POLIZA_NUMERO' => ['required', 'integer', Rule::unique('polizas')->ignore($id, 'POLIZA_ID')],
        ], [
            'USUARIO_id.required' => 'El campo "Usuario" es requerido.',
            'USUARIO_id.integer' => 'El campo "Usuario" debe ser un número entero.',
            'USUARIO_id.unique' => 'El campo "Usuario" ya ha sido registrado.',
            'POLIZA_FECHA_VENCIMIENTO_LICENCIA.required' => 'El campo "Fecha de vencimiento de la póliza" es requerido.',
            'POLIZA_FECHA_VENCIMIENTO_LICENCIA.date' => 'El campo "Fecha de vencimiento de la póliza" debe ser una fecha válida.',
            'POLIZA_NUMERO.required' => 'El campo "Número de póliza" es requerido.',
            'POLIZA_NUMERO.integer' => 'El campo "Número de póliza" debe ser un número entero.',
            'POLIZA_NUMERO.unique' => 'El campo "Número de póliza" ya ha sido registrado.',
        ]);

        // Si la validación falla, redirecciona al formulario con los errores y los datos antiguos
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Buscar la póliza a actualizar
        $poliza = Poliza::findOrFail($id);

        // Actualizar los campos de la póliza
        $poliza->USUARIO_id = $request->input('USUARIO_id');
        $poliza->OFICINA_ID = Auth::user()->OFICINA_ID;
        $poliza->POLIZA_FECHA_VENCIMIENTO_LICENCIA = $request->input('POLIZA_FECHA_VENCIMIENTO_LICENCIA');
        $poliza->POLIZA_NUMERO = $request->input('POLIZA_NUMERO');
        $poliza->save();

        return redirect()->route('polizas.index')->with('success', 'Póliza actualizada exitosamente');
    } catch (\Illuminate\Database\QueryException $e) {
        // Si hay un error de base de datos debido a la restricción UNIQUE
        $errors = ['error' => 'Ya existe una póliza con ese número o usuario.'];
        return redirect()->back()->withInput()->withErrors($errors);
    } catch (\Exception $e) {
        // Manejar otras excepciones
        return redirect()->route('polizas.index')->with('error', 'Error al actualizar la póliza');
    }
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $polizas = Poliza::find($id);
        try{
            $polizas->delete();
            session()->flash('success','La póliza ha sido eliminada correctamente');
        }catch(\Exception $e){
            session()->flash('error','Error al eliminar la póliza seleccionada, vuelva a intentarlo nuevamente');
        }
        return redirect(route('polizas.index'));
    }
}

