<?php

// namespace: Define el espacio de nombres en el que se encuentra el controlador
namespace App\Http\Controllers\Solicitud;

// Importar FACADES y elementos necesarios
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;
use Illuminate\Support\Facades\Auth;
use Gloudemans\Shoppingcart\Facades\Cart;

// Importar modelos
use App\Models\Solicitud;
use App\Models\Material;
use App\Models\Movimiento;
use App\Models\RevisionSolicitud;

class SolicitudMaterialesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Try-catch para el manejo de excepciones
        try {
            // Recuperar las solicitudes con sus materiales asociados y que el solicitante tenga la misma OFICINA_ID que el usuario logueado
            $solicitudes = Solicitud::whereHas('solicitante', function ($query) {
                $query->where('OFICINA_ID', Auth::user()->OFICINA_ID);
            })->whereHas('materiales')->get();

            // Retornar la vista con las solicitudes
            return view('sia2.solicitudes.materiales.index', compact('solicitudes'));
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
            // Función que lista materiales basados en la OFICINA_ID del usuario logueado
            $materiales = Material::where('OFICINA_ID', Auth::user()->OFICINA_ID)->get();

            // Obtener los elementos del carrito
            $cartItems = Cart::instance('carrito_materiales')->content();

            // Retornar la vista del formulario con los materiales y el carrito
            return view('sia2.solicitudes.materiales.create', compact('materiales', 'cartItems'));
        } catch (Exception $e) {
            // Manejar excepciones si es necesario
            return redirect()->route('solicitudes.index')->with('error', 'Error al cargar los materiales.');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
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

            // Validar que la instancia del carrito no esté vacía
            if (Cart::instance('carrito_materiales')->count() === 0) {
                return redirect()->back()->with('error', 'El carrito de materiales está vacío.');
            }
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
            return redirect()->route('solicitudes.materiales.index')->with('success', 'Solicitud creada exitosamente');
        }catch(Exception $e){
            // Manejo de excepciones
            return redirect()->route('solicitudes.materiales.index')->with('error', 'Error al crear la solicitud.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            // Recuperar la solicitud con sus materiales asociados
            $solicitud = Solicitud::has('materiales')->findOrFail($id);
            // Retornar la vista con la solicitud
            return view('sia2.solicitudes.materiales.show', compact('solicitud'));
        } catch (Exception $e) {
            // Manejar excepciones si la solicitud no se encuentra o hay algún error manejable
            return redirect()->route('solicitudes.materiales.index')->with('error', 'Error al mostrar la solicitud.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // try-catch para el manejo de excepciones
        try {
            // Recuperar la solicitud con sus materiales asociados
            $solicitud = Solicitud::has('materiales')->findOrFail($id);
            // Retornar la vista con la solicitud
            return view('sia2.solicitudes.materiales.edit', compact('solicitud'));
        } catch (Exception $e) {
            // Manejar excepciones si la solicitud no se encuentra o hay algún error manejable
            return redirect()->route('solicitudes.materiales.index')->with('error', 'Error al mostrar la solicitud.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // try-catch
        try{
            // Valida los datos del formulario de solicitud de equipos.
            $validator = Validator::make($request->all(),[
                // 'SOLICITUD_ESTADO' => 'required|string|max:255|in:INGRESADO,EN REVISION,APROBADO,RECHAZADO,TERMINADO',
                'SOLICITUD_FECHA_HORA_INICIO_ASIGNADA' => 'required|date',
                'SOLICITUD_FECHA_HORA_TERMINO_ASIGNADA' => 'required|date|after:SOLICITUD_FECHA_HORA_INICIO_ASIGNADA',

                'REVISION_SOLICITUD_OBSERVACION' => 'required|string|max:255',
                'autorizar.*' => 'required|numeric|min:0', // Asegura que todos los valores en el array sean numéricos y no negativos
            ], [
                //Mensajes de error
                'required' => 'El campo :attribute es requerido.',
                'date' => 'El campo :attribute debe ser una fecha.',
                'after' => 'El campo :attribute debe ser una fecha posterior a la fecha de inicio solicitada.',
                'string' => 'El campo :attribute debe ser una cadena de caracteres.',
                'in' => 'El campo :attribute debe ser uno de los valores: INGRESADO, EN REVISION, APROBADO, RECHAZADO, TERMINADO',
                'numeric' => 'El campo :attribute debe ser un número.',
                'min' => 'El campo :attribute debe ser un número no negativo.'
            ]);

            // Si la validación falla, redirige al formulario con los errores y el input antiguo
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            // Obtener la solicitud
            $solicitud = Solicitud::has('materiales')->findOrFail($id);

             // Determinar la acción basada en el botón presionado
            switch ($request->input('action')) {
                case 'guardar':
                    // Lógica para guardar cambios
                    $solicitud->update(['SOLICITUD_ESTADO' => 'EN REVISION']);
                break;

                case 'finalizar_revision':
                    // Lógica para finalizar la revisión
                    $solicitud->update(['SOLICITUD_ESTADO' => 'AUTORIZADO']);
                break;

                case 'rechazar':
                    // Lógica para rechazar la solicitud
                    $solicitud->update(['SOLICITUD_ESTADO' => 'RECHAZADO']);
                break;

                // default:
                    // Lógica por defecto o para casos no contemplados
                    // break;
            }

            // Actualizar la solicitud
            $solicitud->update([
                // 'SOLICITUD_ESTADO' => $request->input('SOLICITUD_ESTADO'),
                'SOLICITUD_FECHA_HORA_INICIO_ASIGNADA' => $request->input('SOLICITUD_FECHA_HORA_INICIO_ASIGNADA'),
                'SOLICITUD_FECHA_HORA_TERMINO_ASIGNADA' => $request->input('SOLICITUD_FECHA_HORA_TERMINO_ASIGNADA'),
            ]);

            // Autorizar los materiales si corresponde
            $this->autorizarMateriales($request, $solicitud);

            // crear la reviison de la solicitud
            $this->createRevisionSolicitud($request, $solicitud);

            // Redireccion a la vista index de solicitud de materiales, con el mensaje de exito.
            return redirect()->route('solicitudes.materiales.index')->with('success', 'Solicitud actualizada exitosamente');
        }catch(Exception $e){
            // Manejo de excepciones
            return redirect()->route('solicitudes.materiales.index')->with('error', 'Error al validar los datos.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
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
            return redirect()->route('solicitudes.materiales.index')->with('success', 'Solicitud eliminada exitosamente');
        } catch (Exception $e) {
            // Manejar excepciones si es necesario
            return redirect()->route('solicitudes.materiales.index')->with('error', 'Error al eliminar la solicitud.');
        }
    }

    /**
    * Crear una nueva revision para la solicitud.
    */
    private function createRevisionSolicitud(Request $request, Solicitud $solicitud)
    {
        // try-catch
        try
        {
            // Crear la revisión de la solicitud
            RevisionSolicitud::create([
                'USUARIO_id' => Auth::user()->id,
                'SOLICITUD_ID' => $solicitud->SOLICITUD_ID,
                'REVISION_SOLICITUD_OBSERVACION' => $request->input('REVISION_SOLICITUD_OBSERVACION'),
            ]);
        }
        catch(Exception $e)
        {
            // Manejo de excepciones
            return redirect()->route('solicitudes.formularios.index')->with('error', 'Error al crear la revisión de la solicitud.');
        }
    }

    /**
     * Autorizar materiales
     */
    private function autorizarMateriales(Request $request, Solicitud $solicitud){
        // try-catch
        try {
            $autorizaciones = $request->input('autorizar', []);

            foreach ($autorizaciones as $materialId => $cantidadAutorizada) {
                $solicitud->materiales()->updateExistingPivot($materialId, ['SOLICITUD_MATERIAL_CANTIDAD_AUTORIZADA' => $cantidadAutorizada]);

                // Actualiza el stock del material
                $material = Material::findOrFail($materialId);
                $stockPrevio = $material->MATERIAL_STOCK;
                $stockResultante = $stockPrevio - $cantidadAutorizada;
                $material->update(['MATERIAL_STOCK' => $stockResultante]);

                // Registra el movimiento
                Movimiento::create([
                    'USUARIO_id' => Auth::user()->id,
                    'MATERIAL_ID' => $material->MATERIAL_ID,
                    'MOVIMIENTO_TITULAR' => (Auth::user()->USUARIO_NOMBRES.' '.Auth::user()->USUARIO_APELLIDOS),
                    'MOVIMIENTO_OBJETO' => 'MATERIAL: ' . $material->MATERIAL_NOMBRE,
                    'MOVIMIENTO_TIPO_OBJETO' => $material->tipoMaterial->TIPO_MATERIAL_NOMBRE,
                    'MOVIMIENTO_TIPO' => 'RESTA',
                    'MOVIMIENTO_STOCK_PREVIO' => $stockPrevio,
                    'MOVIMIENTO_CANTIDAD_A_MODIFICAR' => $cantidadAutorizada,
                    'MOVIMIENTO_STOCK_RESULTANTE' => $stockResultante,
                    'MOVIMIENTO_DETALLE' => 'Solicitud de materiales: ' . $solicitud->SOLICITUD_MOTIVO,
                ]);

            }
        } catch (Exception $e) {
            // Considera loguear el error para depuración
            return redirect()->back()->with('error', 'Error al autorizar los equipos, vuelva a intentarlo más tarde.');
        }
    }

    public function confirmar($id)
    {
        try {
            $solicitud = Solicitud::findOrFail($id);
            // Verificar si el usuario autenticado es el solicitante y si la solicitud no está ya terminada
            if (Auth::user()->USUARIO_ID == $solicitud->SOLICITUD_USUARIO_ID && $solicitud->SOLICITUD_ESTADO == 'AUTORIZADO') {
                $solicitud->SOLICITUD_ESTADO = 'TERMINADO';
                $solicitud->save();

                return redirect()->route('solicitudes.materiales.index')->with('success', 'Solicitud confirmada con éxito.');
            } else {
                return redirect()->route('solicitudes.materiales.index')->with('error', 'No se puede confirmar la solicitud.');
            }
        } catch (\Exception $e) {
            return redirect()->route('solicitudes.materiales.index')->with('error', 'Error al confirmar la solicitud: ' . $e->getMessage());
        }
    }
}
