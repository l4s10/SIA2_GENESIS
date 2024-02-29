<?php

namespace App\Http\Controllers\Directivos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log; // Asegúrate de importar Log
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

use Exception;


use App\Models\Facultad;

class FacultadController extends Controller
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
        $facultades = Facultad::all();
        //dd($facultades);
        return view('sia2.directivos.facultades.index', compact('facultades'));
    }

    /**
     * Show the form for creating a new resource.
     *///Carga formulario de creacion
    public function create()
    {
        return view('sia2.directivos.facultades.create');
    }

    /**
     * Store a newly created resource in storage.
     *///Guarda los datos del formulario

    public function store(Request $request)
    {
        //dd($request);
        try {
            // Validar los datos del formulario de facultades.
            $validator = Validator::make($request->all(), [
                'FACULTAD_NUMERO' => 'required|integer|unique:facultades,FACULTAD_NUMERO',
                'FACULTAD_NOMBRE' => 'required|string',
                'FACULTAD_CONTENIDO' => 'required|string',
                'FACULTAD_LEY_ASOCIADA' => 'required|string|max:128',
                'FACULTAD_ART_LEY_ASOCIADA' => 'required|string|max:128',
            ],[
                'FACULTAD_NUMERO.required' => 'El campo "Número de facultad" es requerido.',
                'FACULTAD_NUMERO.integer' => 'El campo "Número de facultad" debe ser un número entero.',
                'FACULTAD_NUMERO.unique' => 'El campo "Número de facultad" ya ha sido registrado.',
                'FACULTAD_NOMBRE.required' => 'El campo "Nombre de facultad" es requerido.',
                'FACULTAD_NOMBRE.string' => 'El campo "Nombre de facultad" debe ser una cadena de caracteres.',
                'FACULTAD_CONTENIDO.required' => 'El campo "Contenido de facultad" es requerido.',
                'FACULTAD_CONTENIDO.string' => 'El campo "Contenido de facultad" debe ser una cadena de caracteres.',
                'FACULTAD_LEY_ASOCIADA.required' => 'El campo "Ley asociada de facultad" es requerido.',
                'FACULTAD_LEY_ASOCIADA.string' => 'El campo "Ley asociada de facultad" debe ser una cadena de caracteres.',
                'FACULTAD_LEY_ASOCIADA.max' => 'El campo "Ley asociada de facultad" no debe exceder 128 caracteres.',
                'FACULTAD_ART_LEY_ASOCIADA.required' => 'El campo "Artículo de ley asociada de facultad" es requerido.',
                'FACULTAD_ART_LEY_ASOCIADA.string' => 'El campo "Artículo de ley asociada de facultad" debe ser una cadena de caracteres.',
                'FACULTAD_ART_LEY_ASOCIADA.max' => 'El campo "Artículo de ley asociada de facultad" no debe exceder 128 caracteres.',
            ]);

             // Si la validación falla, redirecciona al formulario con los errores y el input antiguo
             if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $facultad = new Facultad();
            $facultad->FACULTAD_NUMERO = $request->input('FACULTAD_NUMERO');
            $facultad->FACULTAD_NOMBRE = $request->input('FACULTAD_NOMBRE');
            $facultad->FACULTAD_CONTENIDO = $request->input('FACULTAD_CONTENIDO');
            $facultad->FACULTAD_LEY_ASOCIADA = $request->input('FACULTAD_LEY_ASOCIADA');
            $facultad->FACULTAD_ART_LEY_ASOCIADA = $request->input('FACULTAD_ART_LEY_ASOCIADA');
            $facultad->save();

            // Redireccionar a la vista de solicitudes con un mensaje de éxito
            return redirect()->route('facultades.index')->with('success', 'Facultad creada exitosamente');
        }catch(Exception $e){
            // Manejar excepciones si es necesario
            return redirect()->back()->with('error', 'Error al crear la facultad.');
        }
    }
    /**
     * Display the specified resource.
     *///Accede a un único registro
   /* public function show(string $id)
    {
        try{
            $facultad = Facultad::findOrFail($id);
            return view('facultad.show', compact('facultad'));
        }catch(\Exception $e){
            session()->flash('error', 'Error al acceder a la facultad seleccionada, vuelva a intentarlo más tarde.');
            return redirect(route('facultades.index'));
        }
    }*/

    /**
     * Show the form for editing the specified resource.
     *///Carga el formulario de edicion
    public function edit(string $id)
    {
        // try-catch
        try{
            // Obtener la facultad
            $facultad = Facultad::findOrFail($id);
            // Retornar la vista con la solicitud
            return view('sia2.directivos.facultades.edit', compact('facultad'));
        }catch(Exception $e){
            // Manejar excepciones
            return redirect()->route('facultades.index')->with('error', 'Error al cargar la facultad.');
        }
    }

    public function update(Request $request, string $id)
    {
        // try-catch
        try{

            // Validar los datos del formulario de facultades.
            $validator = Validator::make($request->all(), [
                'FACULTAD_NUMERO' => ['required', 'integer', Rule::unique('facultades')->ignore($id, 'FACULTAD_ID')],
                'FACULTAD_NOMBRE' => 'required|string',
                'FACULTAD_CONTENIDO' => 'required|string',
                'FACULTAD_LEY_ASOCIADA' => 'required|string|max:128',
                'FACULTAD_ART_LEY_ASOCIADA' => 'required|string|max:128',
            ],[
                'FACULTAD_NUMERO.required' => 'El campo "Número de facultad" es requerido.',
                'FACULTAD_NUMERO.integer' => 'El campo "Número de facultad" debe ser un número entero.',
                'FACULTAD_NUMERO.unique' => 'El campo "Número de facultad" ya ha sido registrado.',
                'FACULTAD_NOMBRE.required' => 'El campo "Nombre de facultad" es requerido.',
                'FACULTAD_NOMBRE.string' => 'El campo "Nombre de facultad" debe ser una cadena de caracteres.',
                'FACULTAD_CONTENIDO.required' => 'El campo "Contenido de facultad" es requerido.',
                'FACULTAD_CONTENIDO.string' => 'El campo "Contenido de facultad" debe ser una cadena de caracteres.',
                'FACULTAD_LEY_ASOCIADA.required' => 'El campo "Ley asociada de facultad" es requerido.',
                'FACULTAD_LEY_ASOCIADA.string' => 'El campo "Ley asociada de facultad" debe ser una cadena de caracteres.',
                'FACULTAD_LEY_ASOCIADA.max' => 'El campo "Ley asociada de facultad" no debe exceder 128 caracteres.',
                'FACULTAD_ART_LEY_ASOCIADA.required' => 'El campo "Artículo de ley asociada de facultad" es requerido.',
                'FACULTAD_ART_LEY_ASOCIADA.string' => 'El campo "Artículo de ley asociada de facultad" debe ser una cadena de caracteres.',
                'FACULTAD_ART_LEY_ASOCIADA.max' => 'El campo "Artículo de ley asociada de facultad" no debe exceder 128 caracteres.',
            ]);

            // Si la validación falla, redirecciona al formulario con los errores y el input antiguo
            if ($validator->fails()) {
                //dd($validator);
                return redirect()->back()->withErrors($validator)->withInput();
            }


            // Obtener la facultad
            $facultad = Facultad::where('FACULTAD_ID', $id)->firstOrFail();

            // Asignar los nuevos valores a las propiedades del modelo
            $facultad->FACULTAD_NUMERO = $request->input('FACULTAD_NUMERO');
            $facultad->FACULTAD_NOMBRE = $request->input('FACULTAD_NOMBRE');
            $facultad->FACULTAD_CONTENIDO = $request->input('FACULTAD_CONTENIDO');
            $facultad->FACULTAD_LEY_ASOCIADA = $request->input('FACULTAD_LEY_ASOCIADA');
            $facultad->FACULTAD_ART_LEY_ASOCIADA = $request->input('FACULTAD_ART_LEY_ASOCIADA');
            
            // Guardar los cambios en la base de datos
            $facultad->save();
            // Redireccionar a la vista index de facultades con un mensaje de éxito
            return redirect()->route('facultades.index')->with('success', 'Facultad actualizada exitosamente');
        } catch(Exception $e) {
            return redirect()->route('facultades.index')->with('error', 'Error al actualizar la facultad');
        }
        
    }


    /**
     * Remove the specified resource from storage.
     */

     
    public function destroy(string $id)
    {
        try{
            // Encontrar la facultad por su ID
            $facultad = Facultad::findOrFail($id);

            // Eliminar el material
            $facultad->delete();
            return redirect()->route('facultades.index')->with('success', 'Facultad eliminada exitosamente.');
        } 
        catch(Exception $e) {// "Exeption" estaba mal escrito
            return redirect()->route('facultades.index')->with('error', 'Error al eliminar la facultad');
        }
    }
    
}

