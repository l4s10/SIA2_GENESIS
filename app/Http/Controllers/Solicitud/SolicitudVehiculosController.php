<?php

namespace App\Http\Controllers\Solicitud;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;
use Illuminate\Support\Facades\Auth;


// Importar modelos
use App\Models\SolicitudVehicular;
use App\Models\Oficina;
use App\Models\Ubicacion;
use App\Models\Departamento;
use App\Models\User;


use App\Models\TipoVehiculo;



class SolicitudVehiculosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Try-catch para el manejo de excepciones
        try {
            // Obtener la oficina del usuario actual
            $oficinaIdUsuario = Auth::user()->OFICINA_ID;

            // Obtener las solicitudes vehiculares realizadas por usuarios de la oficina correspondiente
            $solicitudes = SolicitudVehicular::whereHas('user', function ($query) use ($oficinaIdUsuario) {
                $query->where('OFICINA_ID', $oficinaIdUsuario);
            })->get();



            // Retornar la vista con las solicitudes
            return view('sia2.solicitudes.vehiculos.index', compact('solicitudes'));
        } catch (Exception $e) {
            // Manejar excepciones si es necesario
            return redirect()->back()->with('error', 'Error al cargar las solicitudes.');
        }
    }
    

    
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Try-catch para el manejo de excepciones
        try {
            $oficinas = Oficina::all();
            $ubicaciones = Ubicacion::all();
            $departamentos = Departamento::all();
            $users = User::all();
            // Obtener tipos de vehículos basados en la OFICINA_ID del usuario
            $tiposVehiculos = TipoVehiculo::where('OFICINA_ID', Auth::user()->OFICINA_ID)->get();

            return view('sia2.solicitudes.vehiculos.create', compact('tiposVehiculos','oficinas','ubicaciones','departamentos','users'));
        } catch (Exception $e) {
            // Manejar excepciones si es necesario
            return redirect()->route('solicitudes.index')->with('error', 'Error al cargar los materiales.');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    /*public function store(Request $request)
    {
        try{
            // Valida los datos del formulario de solicitud de materiales.
            $validator = Validator::make($request->all(),[
                'SOLICITUD_MOTIVO' => 'required|string|max:255',
                'SOLICITUD_FECHA_HORA_INICIO_SOLICITADA' => 'required|date',
                'SOLICITUD_FECHA_HORA_TERMINO_SOLICITADA' => 'required|date|after:SOLICITUD_FECHA_HORA_INICIO_SOLICITADA',
            ], [
                //Mensajes de error
                'required' => 'El campo :attribute es requerido.',
                'date' => 'El campo :attribute debe ser una fecha.',
                'after' => 'El campo :attribute debe ser una fecha posterior a la fecha de inicio solicitada.',
                'string' => 'El campo :attribute debe ser una cadena de caracteres.'
            ]);

            // Si la validación falla, redirige al formulario con los errores y el input antiguo
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            // Si la validacion es exitosa, crea y almacena la solicitud
            $solicitud = Solicitud::create([
                'USUARIO_id' => Auth::user()->id, // Asigna el ID del usuario autenticado
                'SOLICITUD_MOTIVO' => $request->input('SOLICITUD_MOTIVO'),
                'SOLICITUD_ESTADO' => 'INGRESADO', // Valor predeterminado
                'SOLICITUD_FECHA_HORA_INICIO_SOLICITADA' => $request->input('SOLICITUD_FECHA_HORA_INICIO_SOLICITADA'),
                'SOLICITUD_FECHA_HORA_TERMINO_SOLICITADA' => $request->input('SOLICITUD_FECHA_HORA_TERMINO_SOLICITADA'),
            ]);

            //Si se crea la solicitud correctamente, se asocia los materiales del carrito a la solicitud a traves de la relacion creada en el modelo.
            if($solicitud){
                // Llamamos a la instancia del carrito de materiales
                foreach (Cart::instance('carrito_materiales')->content() as $cartItem) {
                    $material = Material::find($cartItem->id);

                    // Agrega el material a la solicitud con la cantidad del carrito
                    $solicitud->materiales()->attach($material, [
                        'SOLICITUD_MATERIAL_CANTIDAD' => $cartItem->qty
                    ]);
                }
                // Limpia el carrito después de agregar los materiales a la solicitud
                Cart::instance('carrito_materiales')->destroy();
            }
            // Redireccion a la vista index de solicitud de materiales, con el mensaje de exito.
            return redirect()->route('solicitudesmateriales.index')->with('success', 'Solicitud creada exitosamente');
        }catch(Exception $e){
            // Manejo de excepciones
            return redirect()->route('solicitudesmateriales.index')->with('error', 'Error al crear la solicitud.');
        }
    }

    /**
     * Display the specified resource.
     */
   /* public function show(string $id)
    {
        try {
            // Recuperar la solicitud con sus materiales asociados
            $solicitud = Solicitud::has('materiales')->findOrFail($id);
            // Retornar la vista con la solicitud
            return view('sia2.solicitudes.materiales.show', compact('solicitud'));
        } catch (Exception $e) {
            // Manejar excepciones si la solicitud no se encuentra o hay algún error manejable
            return redirect()->route('solicitudesmateriales.index')->with('error', 'Error al mostrar la solicitud.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
 /*   public function edit(string $id)
    {
        // Try-catch para el manejo de excepciones
        try {
            // Obtener la oficina del usuario actual
            $oficinaIdUsuario = Auth::user()->OFICINA_ID;

            // Consulta SQL para obtener vehículos asociados a la oficina del usuario
            $vehiculos = Vehiculo::select('VEHICULOS.*')
                ->leftJoin('UBICACIONES', 'VEHICULOS.UBICACION_ID', '=', 'UBICACIONES.UBICACION_ID')
                ->leftJoin('DEPARTAMENTOS', 'VEHICULOS.DEPARTAMENTO_ID', '=', 'DEPARTAMENTOS.DEPARTAMENTO_ID')
                ->where(function($query) use ($oficinaIdUsuario) {
                    $query->where('UBICACIONES.OFICINA_ID', $oficinaIdUsuario)
                        ->whereNull('VEHICULOS.DEPARTAMENTO_ID');
                })
                ->orWhere(function($query) use ($oficinaIdUsuario) {
                    $query->where('DEPARTAMENTOS.OFICINA_ID', $oficinaIdUsuario)
                        ->whereNull('VEHICULOS.UBICACION_ID');
                })
                ->get();
            // Retornar la vista del formulario con los materiales y el carrito
            return view('sia2.solicitudes.vehiculos.edit', compact('vehiculos'));
        } catch (Exception $e) {
            // Manejar excepciones si es necesario
            return redirect()->route('solicitudes.index')->with('error', 'Error al cargar los materiales.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
  /*  public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
 /*   public function destroy($id)
    {
        //Try catch
        try {
            // Busca la solicitud con sus materiales asociados
            $solicitud = Solicitud::has('materiales')->findOrFail($id);

            //Eliminar registros asociados a esta solicitud en la tabla solicitud_material (para no tener problemas de parent row not found)
            $solicitud->materiales()->detach();

            // Elimina la solicitud
            $solicitud->delete();

            // Puedes agregar un mensaje de éxito si lo deseas
            return redirect()->route('solicitudesmateriales.index')->with('success', 'Solicitud eliminada exitosamente');
        } catch (Exception $e) {
            // Manejar excepciones si es necesario
            return redirect()->route('solicitudesmateriales.index')->with('error', 'Error al eliminar la solicitud.');
        }
    }*/

}