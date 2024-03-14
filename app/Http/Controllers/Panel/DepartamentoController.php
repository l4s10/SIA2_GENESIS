<?php

namespace App\Http\Controllers\Panel;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

use Exception;

use App\Models\Departamento;
use App\Models\Oficina;

class DepartamentoController extends Controller
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
        //Obtener departamentos para la dirección regional en sesión
        $departamentos = Departamento::where('OFICINA_ID', Auth::user()->OFICINA_ID)->get();
        return view('sia2.panel.departamentos.index', compact('departamentos'));
    }

    /**
     * Show the form for creating a new resource.
     *///Carga formulario de creacion
     public function create()
    {
        try {
            $oficinas = Oficina::all();
            return view('sia2.panel.departamentos.create',compact('oficinas'));
        } catch (Exception $ex) {
            return redirect()->back()->with('error', 'Ha ocurrido un error al cargar el Departamento');
        }

    }

    /**
     * Store a newly created resource in storage.
     *///Guarda los datos del formulario
    public function store(Request $request)
    {
        try {
            // Reglas de validación
            $validator = Validator::make($request->all(), [
                'DEPARTAMENTO_NOMBRE' => 'required|string|max:128|unique:departamentos,DEPARTAMENTO_NOMBRE,NULL,id,OFICINA_ID,' . $request->OFICINA_ID,
                'OFICINA_ID' => 'required|exists:oficinas,OFICINA_ID',
            ], [
                'DEPARTAMENTO_NOMBRE.required' => 'El campo "Nombre" es requerido',
                'DEPARTAMENTO_NOMBRE.string' => 'El campo "Nombre" debe ser un texto',
                'DEPARTAMENTO_NOMBRE.max' => 'El campo "Nombre" no debe exceder los :max caracteres',
                'DEPARTAMENTO_NOMBRE.unique' => 'El campo "Nombre" ya se encuentra registrado en su dirección regional',
                'OFICINA_ID.required' => 'El campo "Dirección regional asociada" es requerido',
                'OFICINA_ID.exists' => 'El campo "Dirección regional asociada" no es válido.',
            ]);

            // Validación y redirección con mensajes de error
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            } else {
                // Crear el departamento
                Departamento::create([
                    'DEPARTAMENTO_NOMBRE' => strtoupper($request->input('DEPARTAMENTO_NOMBRE')),
                    'OFICINA_ID' => $request->OFICINA_ID,
                ]);

                // Retornamos la vista con el mensaje de éxito
                return redirect()->route('panel.departamentos.index')->with('success', 'Departamento agregado exitosamente');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Hubo un error al agregar el Departamento. Por favor, inténtelo nuevamente');
        }
    }
    /**
     * Display the specified resource.
     *///Accede a un único registro
    /* public function show(string $id)
     {
         try{
             $departamento = Departamento::find($id);
             return view('departamento.show', compact('departamento'));
         }catch(\Exception $e){
             session()->flash('error', 'Error al acceder en el Departamento seleccionada, vuelva a intentarlo más tarde.');
             return view('departamento.show');
         }
     }*/

    /**
     * Show the form for editing the specified resource.
     *///Carga el formulario de edicion
     public function edit(string $id)
    {
        try {
            $departamento = Departamento::find($id);
            $oficinas = Oficina::all();

            return view('sia2.panel.departamentos.edit',compact('departamento','oficinas'));

        } catch (Exception $ex) {
            return redirect()->back()->with('error', 'Ha ocurrido un error al cargar el Departamento');
        }
    }

    public function update(Request $request, string $id)
    {
        try {
            // Encontrar el Departamento que se está actualizando
            $departamento = Departamento::findOrFail($id);

            // Reglas de validación
            $validator = Validator::make($request->all(), [
                'DEPARTAMENTO_NOMBRE' => 'required|string|max:128|unique:departamentos,DEPARTAMENTO_NOMBRE,' . $id . ',DEPARTAMENTO_ID,OFICINA_ID,' . $request->OFICINA_ID,
                'OFICINA_ID' => 'required|exists:oficinas,OFICINA_ID',
            ],[
                'DEPARTAMENTO_NOMBRE.required' => 'El campo "Nombre" es requerido',
                'DEPARTAMENTO_NOMBRE.string' => 'El campo "Nombre" debe ser un texto',
                'DEPARTAMENTO_NOMBRE.max' => 'El campo "Nombre" no debe exceder los :max caracteres',
                'DEPARTAMENTO_NOMBRE.unique' => 'El campo "Nombre" ya se encuentra registrado para esta oficina',
                'OFICINA_ID.required' => 'El campo "Dirección regional asociada" es requerido',
                'OFICINA_ID.exists' => 'El campo "Dirección regional asociada" no es válido.',
            ]);

            // Si la validación falla, redirigir de vuelta con los errores
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            } else {
                // Actualizar el Departamento
                $departamento->update([
                    'DEPARTAMENTO_NOMBRE' => strtoupper($request->input('DEPARTAMENTO_NOMBRE')),
                    'OFICINA_ID' => $request->OFICINA_ID,
                ]);

                // Retornamos la vista con el mensaje de éxito
                return redirect()->route('panel.departamentos.index')->with('success', 'Departamento actualizado exitosamente');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Error al actualizar el Departamento');
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try{
            $departamento = Departamento::findOrFail($id);
            $departamento->delete();
            session()->flash('success','La Departamento ha sido eliminada correctamente.');
        }catch(\Exception $e){
            session()->flash('error','Error al eliminar el Departamento seleccionada, vuelva a intentarlo nuevamente.');
        }
        return redirect(route('panel.departamentos.index'));
    }


    //!! Método para obtener departamentos por oficina (TABLA DE CONTINGENCIA -- NO BORRAR!!)
    public function getDepartamentos($direccionId)
    {
        // Asume que tienes un modelo Ubicacion que tiene una relación con Direcciones
        $departamentos = Departamento::where('OFICINA_ID', $direccionId)->get();

        return response()->json($departamentos);
    }
}
