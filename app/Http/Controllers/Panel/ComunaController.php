<?php

namespace App\Http\Controllers\Panel;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;

use App\Models\Comuna;
use App\Models\Oficina;
use App\Models\Region;

class ComunaController extends Controller
{
    //Funcion para acceder a las rutas SOLO SI los usuarios estan logueados
    /*public function __construct(){
        $this->middleware('auth');
        // Roles que pueden ingresar a la url
        $this->middleware(['roleAdminAndSupport']);
    }*/
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $comunas = Comuna::all();
        return view('sia2.panel.comunas.index',compact('comunas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            $regiones = Region::all();
            return view('sia2.panel.comunas.create',compact('regiones'));
        } catch (Exception $ex) {
            return redirect()->back()->with('error', 'Ha ocurrido un error al cargar la comuna');
        }    
    }
    
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try{
            // Reglas de validación
            $validator = Validator::make($request->all(), [
                'COMUNA_NOMBRE' => 'required|string|max:128',
                'REGION_ID' => 'required|exists:regiones,REGION_ID',
            ],[
                'COMUNA_NOMBRE.required' => 'El campo "Nombre de la comuna" es requerido',
                'COMUNA_NOMBRE.string' => 'El campo  "Nombre de la comuna" debe ser un texto',
                'COMUNA_NOMBRE.max' => 'El campo  "Nombre de la comuna" no debe exceder los :max caracteres',
                'REGION_ID.required' => 'El campo "Región asociada" es requerido',
                'exists' => 'El campo "Región asociada" seleccionado no es válida.',
            ]);

            $validator->after(function ($validator) use ($request) {
                $exists = Comuna::where([
                    'COMUNA_NOMBRE' => $request->input('COMUNA_NOMBRE'),
                    'REGION_ID' => $request->input('REGION_ID'),
                ])->exists();
            
                if ($exists) {
                    $validator->errors()->add('COMUNA_NOMBRE', 'Esta comuna ya existe en la región seleccionada.');
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
                Comuna::create([
                    'COMUNA_NOMBRE' => strtoupper($request->input('COMUNA_NOMBRE')),
                    'REGION_ID' => $request->REGION_ID,
                ]);
                // Retornamos la vista con el mensaje de exito
                return redirect()->route('panel.comunas.index')->with('success', 'Comuna agregada exitosamente');
            }

        }catch(\Exception $e){
            session()->flash('error','Hubo un error al agregar la comuna. Por favor, inténtelo nuevamente');
        }
    }

    /**
     * Display the specified resource.
     */
    /*public function show(string $id)
    {
        try{
            $comuna = Comuna::find($id);
            $region = $comuna->regionAsociada->REGION;
            return view('comuna.show', compact('comuna','region'));
        }catch(\Exception $e){
            session()->flash('error', 'Error al acceder a la comuna seleccionada, vuelva a intentarlo más tarde.');
            return view('comuna.index');
        }
    }*/

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $comuna = Comuna::find($id);
            $regiones = Region::all();
            return view('sia2.panel.comunas.edit',compact('comuna','regiones'));

        } catch (Exception $ex) {
            return redirect()->back()->with('error', 'Ha ocurrido un error al cargar la comuna');
        }    
    }


    /**
     * Update the specified resource in storage.
     */

    public function update(Request $request, string $id)
    {
        try {
            // Encontrar la comuna que se está actualizando
            $comuna = Comuna::findOrFail($id);

            // Validar los datos de entrada
            $validator = Validator::make($request->all(), [
                'COMUNA_NOMBRE' => ['required', 'string', 'max:128'],
                'REGION_ID' => ['required', 'exists:regiones,REGION_ID'],
            ], [
                'COMUNA_NOMBRE.required' => 'El campo "Nombre de la comuna" es requerido.',
                'COMUNA_NOMBRE.string' => 'El campo "Nombre de la comuna" debe ser un texto.',
                'COMUNA_NOMBRE.max' => 'El campo "Nombre de la comuna" no debe exceder los :max caracteres.',
                'REGION_ID.required' => 'El campo "Región asociada" es requerido',
                'REGION_ID.exists' => 'El campo "Región asociada" seleccionado no es válida.',
            ]);

            // Agregar regla de unicidad para el nombre de la comuna, excluyendo la comuna actual
            $validator->after(function ($validator) use ($request, $comuna) {
                $exists = Comuna::where([
                    'COMUNA_NOMBRE' => $request->input('COMUNA_NOMBRE'),
                    'REGION_ID' => $request->input('REGION_ID'),
                ])->where('COMUNA_ID', '!=', $comuna->COMUNA_ID)->exists();

                if ($exists) {
                    $validator->errors()->add('COMUNA_NOMBRE', 'Esta comuna ya existe en el registro.');
                }
            });

            // Si la validación falla, redirigir de vuelta con los errores
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            } else {
                $comuna->update([
                    'COMUNA_NOMBRE' => strtoupper($request->input('COMUNA_NOMBRE')),
                    'REGION_ID' => $request->REGION_ID,
                ]);
            }
            // Retornar a la vista index con el mensaje de éxito
            return redirect()->route('panel.comunas.index')->with('success', 'Comuna actualizada exitosamente');
        } catch (\Exception $e) {
            session()->flash('error', 'Error al crear la comuna');
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $comuna = Comuna::findOrFail($id);
        try{
            $comuna->delete();
            session()->flash('success','La comuna ha sido eliminada correctamente.');
        }catch(\Exception $e){
            session()->flash('error','Error al eliminar la comuna seleccionada, vuelva a intentarlo nuevamente.');
        }
        return redirect(route('panel.comunas.index'));
    }
}
