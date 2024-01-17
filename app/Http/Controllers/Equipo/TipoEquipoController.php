<?php

namespace App\Http\Controllers\Equipo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// Importar modelos
use App\Models\TipoEquipo;
use App\Models\Oficina;
// Importar excepciones
use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TipoEquipoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Código para el manejo de errores y retorno de vistas
        try
        {
            // Traer los tipos de equipo
            $tiposEquipo = TipoEquipo::all();
            // Retornar la vista con los datos
            return view('sia2.activos.modequipos.tipos.index', compact('tiposEquipo'));
        }
        catch (\Exception $e)
        {
            // Retornar a la pagina previa con un session error
            return back()->with('error', 'Error cargando los tipos de equipo');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Retornar la vista
        return view('sia2.activos.modequipos.tipos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try
        {
            // Validación de los datos
            $request->validate([
                'TIPO_EQUIPO_NOMBRE' => 'required|string|max:128',
            ]);

            // Transformar a mayúsculas antes de crear el nuevo tipo de equipo
            $tipoEquipoNombre = Str::upper($request->input('TIPO_EQUIPO_NOMBRE'));

            // Crear el nuevo tipo de equipo
            TipoEquipo::create([
                'TIPO_EQUIPO_NOMBRE' => $tipoEquipoNombre,
                'OFICINA_ID' => Auth::user()->OFICINA_ID, // Se la entregamos por Backend
            ]);

            // Retornar a la vista con un mensaje de éxito
            return redirect()->route('tiposequipos.index')->with('success', 'Tipo de equipo creado exitosamente');
        }
        catch(ValidationException $e)
        {
            // Retornar a la vista con un mensaje de error con inputs
            return redirect()->back()->withErrors($e->errors())->withInput();
        }
        catch(\Exception $e)
        {
            // Retornar a la vista con un mensaje de error
            return redirect()->route('tiposequipos.index')->with('error', 'Error al crear el tipo de equipo');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Manejo de errores
        try
        {
            $tipoEquipo = TipoEquipo::findOrFail($id);
            return view('sia2.activos.modequipos.tipos.show', compact('tipoEquipo'));
        }
        catch(Exception $e)
        {
            return redirect()->route('tiposequipos.index')->with('error', 'Error al cargar el tipo de equipo');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Manejo de errores
        try
        {
            // Obtenemos el tipo de equipo por ID con la relacion "oficina"
            $tipoEquipo = TipoEquipo::findOrFail($id);

            // Obtenemos la OFICINA_ID del usuario actual
            $oficinaIdUsuario = Auth::user()->OFICINA_ID;

            // Obtenemos la informacion de la oficina
            $oficina = Oficina::where('OFICINA_ID', $oficinaIdUsuario)->firstOrFail();

            // Retornamos la vista con los datos
            return view('sia2.activos.modequipos.tipos.edit', compact('tipoEquipo', 'oficina'));
        }
        catch( ModelNotFoundException $e)
        {
            // Manjejar excepcion de modelo no encontrado
            return redirect()->route('tiposequipos.index')->with('error', 'El tipo de equipo no se encontró');
        }
        catch(Exception $e)
        {
            // Manejar otras excepciones
            return redirect()->route('tiposequipos.index')->with('error', 'Error al cargar el tipo de equipo');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try
        {
            //Obtener el tipo de equipo por ID
            $tipoEquipo = TipoEquipo::findOrFail($id);

            // Validación de los datos
            $request->validate([
                'TIPO_EQUIPO_NOMBRE' => 'required|string|max:128',
            ]);

            // Transformar a mayúsculas antes de actualizar el tipo de equipo
            $tipoEquipoNombre = Str::upper($request->input('TIPO_EQUIPO_NOMBRE'));

            // Obtenemos la OFICINA_ID del usuario actual
            $oficinaIdUsuario = Auth::user()->OFICINA_ID;

            // Actualizar el tipo de equipo
            $tipoEquipo->update([
                'TIPO_EQUIPO_NOMBRE' => $tipoEquipoNombre,
                'OFICINA_ID' => $oficinaIdUsuario,
            ]);

            // Retornar a la vista con un mensaje de éxito
            return redirect()->route('tiposequipos.index')->with('success', 'Tipo de equipo actualizado exitosamente');
        }catch(ValidationException $e)
        {
            //Manejar la excepcion de validación
            return redirect()->back()->withErrors($e->errors())->withInput();
        }
        catch(ModelNotFoundException $e)
        {
            //Manejar la excepcion de modelo no encontrado
            return redirect()->route('tiposequipos.index')->with('error', 'El tipo de equipo no se encontró');
        }
        catch(Exception $e)
        {
            //Manejar otras excepciones
            return redirect()->route('tiposequipos.index')->with('error', 'Error al actualizar el tipo de equipo');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Manejo de errores
        try
        {
            //Obtener el tipo de equipo por ID
            $tipoEquipo = TipoEquipo::findOrFail($id);
            $tipoEquipo->delete();

            //Retornar a la vista con un mensaje de éxito
            return redirect()->route('tiposequipos.index')->with('success', 'Tipo de equipo eliminado exitosamente');
        }
        catch(ModelNotFoundException $e)
        {
            //Manejar la excepcion de modelo no encontrado
            return redirect()->route('tiposequipos.index')->with('error', 'El tipo de equipo no se encontró');
        }
        catch(Exception $e)
        {
            //Manejar otras excepciones
            return redirect()->route('tiposequipos.index')->with('error', 'Error al eliminar el tipo de equipo');
        }
    }
}
