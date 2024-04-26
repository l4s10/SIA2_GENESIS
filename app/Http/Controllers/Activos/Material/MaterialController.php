<?php

namespace App\Http\Controllers\Activos\Material;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;
use Illuminate\Database\QueryException;
use Gloudemans\Shoppingcart\Facades\Cart;

use Carbon\Carbon;
use Dompdf\Dompdf;

use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MaterialesExport;

use App\Models\Material;
use App\Models\TipoMaterial;
use App\Models\Movimiento;
use App\Models\Oficina;
use App\Models\Ubicacion;
use App\Models\User;


class MaterialController extends Controller
{
    public function index()
    {
        // Código para el manejo de errores y retorno de vistas
        try
        {
           // Obtener OFICINA_ID del usuario actual
            $oficinaId = Auth::user()->OFICINA_ID;

            // Obtener materiales basados en la OFICINA_ID del usuario
            $materiales = Material::where('OFICINA_ID', $oficinaId)->get();

            // Obtener los tipos de materiales asociados a la oficina del usuario
            $tiposMateriales = TipoMaterial::where('OFICINA_ID', $oficinaId)->get();

            return view('sia2.activos.modmateriales.materiales.index', compact('materiales', 'tiposMateriales'));
        }
        catch (Exception $e)
        {
            // Retornar a la pagina previa con un session error
            return back()->with('error', 'Error cargando los materiales');
        }
    }

    public function getFilteredData(Request $request)
    {
        // Obtener ID de la oficina del usuario autenticado
        $oficinaId = Auth::user()->OFICINA_ID;

        // Iniciar la consulta filtrando por la oficina del usuario autenticado
        $query = Material::where('OFICINA_ID', $oficinaId);

        // Aplicar filtros adicionales si existen
        if ($request->filled('TIPO_MATERIAL_ID')) {
            $query->where('TIPO_MATERIAL_ID', $request->TIPO_MATERIAL_ID);
        }

        if ($request->filled('MATERIAL_NOMBRE')) {
            $query->where('MATERIAL_NOMBRE', 'like', '%' . $request->MATERIAL_NOMBRE . '%');
        }

        if ($request->filled('STOCK_MIN') && $request->filled('STOCK_MAX')) {
            $query->whereBetween('MATERIAL_STOCK', [$request->STOCK_MIN, $request->STOCK_MAX]);
        }

        // Realizar la consulta y obtener los resultados
        $materiales = $query->with('tipoMaterial')->get();

        // Obtener los tipos de materiales asociados a la oficina del usuario
        $tiposMateriales = TipoMaterial::where('OFICINA_ID', $oficinaId)->get();

        // Retornar a la vista con los materiales filtrados
        return view('sia2.activos.modmateriales.materiales.index', compact('materiales', 'tiposMateriales'));
    }

    public function create()
    {
        try {
            // Obtiene la OFICINA_ID del usuario actual
            $oficinaIdUsuario = Auth::user()->OFICINA_ID;
            // Obtiene los tipos de materiales asociados a la oficina del usuario
            $tiposMaterial = TipoMaterial::where('OFICINA_ID', $oficinaIdUsuario)->get();
            // Obtener el objeto oficina asociada al usuario actual
            $oficina = Oficina::where('OFICINA_ID', $oficinaIdUsuario)->firstOrFail();

            return view('sia2.activos.modmateriales.materiales.create', compact('tiposMaterial','oficina'));
        } catch (ModelNotFoundException $e) {
            // Manejar excepción de modelo no encontrado
            return redirect()->route('materiales.index')->with('error', 'Ocurrió un error inesperado.');
        } catch (Exception $e) {
            // Manejar otras excepciones
            return redirect()->route('materiales.index')->with('error', 'Ocurrió un error inesperado.');
        }
    }

    public function store(Request $request)
    {
        try {
            // Reglas de validación y mensajes respectivos
            $validator = Validator::make($request->all(), [
                'TIPO_MATERIAL_ID' => 'required|exists:tipos_materiales,TIPO_MATERIAL_ID',
                'MATERIAL_NOMBRE' => 'required|string|max:40',
                'MATERIAL_STOCK' => 'required|integer|between:0,1000',
                'PROVEEDOR' => 'required|string|max:255',
                'NUMERO_FACTURA' => 'required|integer|between:0,1000000',
                'COD_LIBRO_ADQUISICIONES' => 'required|string|max:255',
                'NUM_RES_EXCENTO_COMPRA' => 'required|integer|between:0,1000000',
                'NUM_ORDEN_COMPRA' => 'required|string|max:255',

                // 'DETALLE_MOVIMIENTO' => 'required|string|max:1000'
            ],[
                'TIPO_MATERIAL_ID.required' => 'El campo Tipo Material es obligatorio.',
                'TIPO_MATERIAL_ID.exists' => 'El Tipo de Material seleccionado no es válido.',
                'MATERIAL_NOMBRE.required' => 'El campo Nombre es obligatorio.',
                'MATERIAL_NOMBRE.string' => 'El campo Nombre debe ser una cadena de texto.',
                'MATERIAL_NOMBRE.max' => 'El campo Nombre no debe exceder los :max caracteres.',
                'MATERIAL_STOCK.required' => 'El campo Stock es obligatorio.',
                'MATERIAL_STOCK.integer' => 'El campo Stock debe ser un número entero.',
                'MATERIAL_STOCK.between' => 'El campo Stock debe estar entre :min y :max.',
                'DETALLE_MOVIMIENTO.required' => 'El campo Detalle de Movimiento es obligatorio.',
                'DETALLE_MOVIMIENTO.string' => 'El campo Detalle de Movimiento debe ser una cadena de texto.',
                'DETALLE_MOVIMIENTO.max' => 'El campo Detalle de Movimiento no debe exceder los :max caracteres.',
                'PROVEEDOR.required' => 'El campo Proveedor es obligatorio.',
                'PROVEEDOR.string' => 'El campo Proveedor debe ser una cadena de texto.',
                'PROVEEDOR.max' => 'El campo Proveedor no debe exceder los :max caracteres.',
                'NUMERO_FACTURA.required' => 'El campo Número de Factura es obligatorio.',
                'NUMERO_FACTURA.integer' => 'El campo Número de Factura debe ser un número entero.',
                'NUMERO_FACTURA.between' => 'El campo Número de Factura debe estar entre :min y :max.',
                'COD_LIBRO_ADQUISICIONES.required' => 'El campo Código Libro de Adquisiciones es obligatorio.',
                'COD_LIBRO_ADQUISICIONES.string' => 'El campo Código Libro de Adquisiciones debe ser una cadena de texto.',
                'COD_LIBRO_ADQUISICIONES.max' => 'El campo Código Libro de Adquisiciones no debe exceder los :max caracteres.',
                'NUM_RES_EXCENTO_COMPRA.required' => 'El campo Número Resolución Exenta de Compra es obligatorio.',
                'NUM_RES_EXCENTO_COMPRA.integer' => 'El campo Número Resolución Exenta de Compra debe ser un número entero.',
                'NUM_RES_EXCENTO_COMPRA.between' => 'El campo Número Resolución Exenta de Compra debe estar entre :min y :max.',
                'NUM_ORDEN_COMPRA.required' => 'El campo Número de Orden de Compra es obligatorio.',
                'NUM_ORDEN_COMPRA.string' => 'El campo Número de Orden de Compra debe ser una cadena de texto.',
                'NUM_ORDEN_COMPRA.max' => 'El campo Número de Orden de Compra no debe exceder los :max caracteres.',

                // 'DETALLE_MOVIMIENTO.required' => 'El campo Detalle de Movimiento es obligatorio.',
                // 'DETALLE_MOVIMIENTO.string' => 'El campo Detalle de Movimiento debe ser una cadena de texto.',
                // 'DETALLE_MOVIMIENTO.max' => 'El campo Detalle de Movimiento no debe exceder los :max caracteres.',
            ]);


            // Validar clave única compuesta
            $validator->after(function ($validator) use ($request) {
                $exists = Material::where([
                    'OFICINA_ID' => Auth::user()->OFICINA_ID,
                    'TIPO_MATERIAL_ID' => $request->input('TIPO_MATERIAL_ID'),
                    'MATERIAL_NOMBRE' => strtoupper($request->input('MATERIAL_NOMBRE')),
                ])->exists();

                if ($exists) {
                    $validator->errors()->add('MATERIAL_NOMBRE', 'Este nombre de material con el tipo de material especificado ya existen en su dirección regional.');
                    $validator->errors()->add('TIPO_MATERIAL_ID', 'Este tipo de material con el nombre de material especificado ya existen en su dirección regional.');
                }
            });

            // Validar y redirigir mensaje al blade, si falla
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            // Crear un nuevo material e instanciar en $material para acceder a sus atributos al realizar el respectivo movimiento
            $material = Material::create([
                'OFICINA_ID' => Auth::user()->OFICINA_ID,
                'TIPO_MATERIAL_ID' => $request->TIPO_MATERIAL_ID,
                'MATERIAL_NOMBRE' => strtoupper($request->input('MATERIAL_NOMBRE')),
                'MATERIAL_STOCK' => $request->MATERIAL_STOCK,
            ]);

            if ($material) {
                // Formatear el detalle y dar formato
                $detalleMovimiento = strtoupper("Proveedor: {$request->input('PROVEEDOR')}, ".
                                    "Numero de Factura: {$request->input('NUMERO_FACTURA')}, ".
                                    "Codigo Libro Adquisiciones: {$request->input('COD_LIBRO_ADQUISICIONES')}, ".
                                    "Numero Res. Exenta de Compra: {$request->input('NUM_RES_EXCENTO_COMPRA')}, ".
                                    "Numero de Orden de Compra: {$request->input('NUM_ORDEN_COMPRA')}.");
                // Crear un nuevo movimiento asociado al material creado
                Movimiento::create([
                    'USUARIO_id' => Auth::user()->id,
                    'MATERIAL_ID' => $material->MATERIAL_ID,
                    'MOVIMIENTO_TITULAR' => (Auth::user()->USUARIO_NOMBRES.' '.Auth::user()->USUARIO_APELLIDOS),
                    'MOVIMIENTO_OBJETO' => 'MATERIAL: '.$material->MATERIAL_NOMBRE,
                    'MOVIMIENTO_TIPO_OBJETO' => $material->tipoMaterial->TIPO_MATERIAL_NOMBRE,
                    'MOVIMIENTO_TIPO' => 'INGRESO',
                    'MOVIMIENTO_STOCK_PREVIO' => 0,
                    'MOVIMIENTO_CANTIDAD_A_MODIFICAR' => $material->MATERIAL_STOCK,
                    'MOVIMIENTO_STOCK_RESULTANTE' => $material->MATERIAL_STOCK,
                    'MOVIMIENTO_DETALLE' => $detalleMovimiento
                ]);

                return redirect()->route('materiales.index')->with('success', 'Material creado exitosamente.');
            } else {
                session()->flash('error', 'Error al crear el material');
            }
        } catch (Exception $e) {
            return redirect()->route('materiales.index')->with('error', 'Ocurrió un error inesperado.');
        }
    }


    public function edit(string $id)
    {
        try {
            // Obtener el material a editar
            $material = Material::findOrFail($id);
            // Obtiener la OFICINA_ID del usuario actual
            $oficinaIdUsuario = Auth::user()->OFICINA_ID;
            // Obtener tipos de materiales según oficina_id del usuario en sesión
            $tiposMateriales = TipoMaterial::where('OFICINA_ID', $oficinaIdUsuario)->get();
            // Obtener la información de la oficina del usuario
            $oficina = Oficina::where('OFICINA_ID', $oficinaIdUsuario)->firstOrFail();
            // Obtener ubicaciones del sistema
            $ubicaciones = Ubicacion::where('OFICINA_ID', $oficinaIdUsuario)->get();
            // Obtener usuarios del sistema...
            $usuarios = User::all();

            return view('sia2.activos.modmateriales.materiales.edit', compact('material', 'tiposMateriales', 'oficina', 'ubicaciones', 'usuarios'));
        } catch (ModelNotFoundException $e) {
            // Manejar excepción de modelo no encontrado
            return redirect()->route('materiales.index')->with('error', 'Ocurrió un error inesperado.');
        } catch (Exception $e) {
            // Manejar otras excepciones
            return redirect()->route('materiales.index')->with('error', 'Ocurrió un error inesperado.');
        }
    }


    public function update(Request $request, $id)
    {
        try {
            // Obtener el material a actualizar
            $material = Material::findOrFail($id);

            // Reglas de validación y mensajes respectivos
            $validator = Validator::make($request->all(), [
                'TIPO_MATERIAL_ID' => 'required|exists:tipos_materiales,TIPO_MATERIAL_ID',
                'MATERIAL_NOMBRE' => 'required|string|max:40',
                'MATERIAL_STOCK' => 'required|integer',
                'STOCK_NUEVO' =>    ['required','integer','between:0,1000',
                                    // Validación dinámica para "STOCK_NUEVO" en función de la selección del input "TIPO_MOVMIENTO"
                                        function ($attribute, $value, $fail) use ($request) {
                                            $stockNuevo = $request->input('STOCK_NUEVO');
                                            $materialStock = $request->input('MATERIAL_STOCK');
                                            $tipoMovimiento = $request->input('TIPO_MOVIMIENTO');

                                            if ($tipoMovimiento === 'INGRESO' && ($stockNuevo <= 0 || $stockNuevo > 1000)) {
                                                $fail('Para "Tipo de movimiento" INGRESO, la "Cantidad a modificar" debe estar entre 1 y 1000');
                                            } elseif (($tipoMovimiento === 'TRASLADO' || $tipoMovimiento === 'MERMA') && ($stockNuevo <= 0 || $stockNuevo > $materialStock)) {
                                                $fail('Para "Tipo de movimiento" TRASLADO o MERMA, la "Cantidad a modificar" debe estar entre 1 y '.$materialStock);
                                            } elseif ($tipoMovimiento === 'OTRO' && ($stockNuevo > 0 || $stockNuevo < 0)) {
                                                $fail('Para "Tipo de movimiento" OTRO, la "Cantidad a modificar" debe ser 0');
                                            }
                                        },
                                    ],
                // 'DETALLE_MOVIMIENTO' => 'required|string|max:1000',
                'TIPO_MOVIMIENTO' => 'required|string|max:10',
            ], [
                'TIPO_MATERIAL_ID.required' => 'El campo "Tipo de Material" es obligatorio.',
                'TIPO_MATERIAL_ID.exists' => 'El "Tipo de Material" seleccionado no es válido.',
                'MATERIAL_NOMBRE.required' => 'El campo "Nombre de Material" es obligatorio.',
                'MATERIAL_NOMBRE.string' => 'El campo "Nombre de Material" debe ser una cadena de texto.',
                'MATERIAL_NOMBRE.max' => 'El campo "Nombre de Material" no debe exceder los :max caracteres.',
                'MATERIAL_STOCK.required' => 'El campo "Stock Actual" es obligatorio.',
                'MATERIAL_STOCK.integer' => 'El campo "Stock Actual" debe ser un número entero.',
                'STOCK_NUEVO.required' => 'El campo "Cantidad a Modificar" es obligatorio.',
                'STOCK_NUEVO.integer' => 'El campo "Cantidad a Modificar" debe ser un número entero.',
                'STOCK_NUEVO.between' => 'La "Cantidad a Modificar" debe estar entre :min y :max.',
                'DETALLE_MOVIMIENTO.required' => 'El campo "Detalle de Movimiento" es obligatorio.',
                'DETALLE_MOVIMIENTO.string' => 'El campo "Detalle de Movimiento" debe ser una cadena de texto.',
                'DETALLE_MOVIMIENTO.max' => 'El campo "Detalle de Movimiento" no debe exceder los :max caracteres.',
                'TIPO_MOVIMIENTO.required' => 'El campo "Tipo de Movimiento" es obligatorio.',
                'TIPO_MOVIMIENTO.string' => 'El campo "Tipo de Movimiento" debe ser una cadena de texto.',
                'TIPO_MOVIMIENTO.max' => 'El campo "Tipo de Movimiento" no debe exceder los :max caracteres.'
            ]);

            /*$validator->after(function ($validator) use ($request) {
                $exists = Material::where([
                    'OFICINA_ID' => Auth::user()->OFICINA_ID,
                    'MATERIAL_NOMBRE' => $request->input('MATERIAL_NOMBRE'),
                    'TIPO_MATERIAL_ID' => $request->input('TIPO_MATERIAL_ID'),
                ])->exists();

                if ($exists) {
                    $validator->errors()->add('MATERIAL_NOMBRE', 'Este nombre de material con el tipo de material especificado, ya existen en su dirección regional.');
                    $validator->errors()->add('TIPO_MATERIAL_ID', 'Este tipo de material con el nombre de material especificado, ya existen en su dirección regional.');
                }
            });*/

            // Validar y redirigir mensaje al blade, si falla
            if ($validator->fails()) {
                return redirect()->route('materiales.edit', $material->MATERIAL_ID)->withErrors($validator)->withInput();
            }

            // Calcular el stock resultante según el tipo de movimiento
            if (($request->TIPO_MOVIMIENTO == 'INGRESO') || ($request->TIPO_MOVIMIENTO == 'OTRO')) {
                $stockResultante = $request->MATERIAL_STOCK + $request->STOCK_NUEVO;
            } else {
                $stockResultante = $request->MATERIAL_STOCK - $request->STOCK_NUEVO;
            }

            // Variables para almacenar los valores de los campos dinámicos
            $detalleMovimiento = '';

            switch ($request->TIPO_MOVIMIENTO) {
                case 'INGRESO':
                    // Validar campos requeridos para ingreso
                    $validatorIngreso = Validator::make($request->all(), [
                        'PROVEEDOR' => 'nullable|string|max:255',
                        'NUMERO_FACTURA' => 'nullable|integer|between:0,1000000',
                        'COD_LIBRO_ADQUISICIONES' => 'nullable|string|max:255',
                        'NUM_RES_EXCENTO_COMPRA' => 'nullable|integer|between:0,1000000',
                        'NUM_ORDEN_COMPRA' => 'required|string|max:255',
                    ], [
                        // 'PROVEEDOR.required' => 'El campo Proveedor es obligatorio.',
                        'PROVEEDOR.string' => 'El campo Proveedor debe ser una cadena de texto.',
                        'PROVEEDOR.max' => 'El campo Proveedor no debe exceder los :max caracteres.',
                        // 'NUMERO_FACTURA.required' => 'El campo Número de Factura es obligatorio.',
                        'NUMERO_FACTURA.integer' => 'El campo Número de Factura debe ser un número entero.',
                        'NUMERO_FACTURA.between' => 'El campo Número de Factura debe estar entre :min y :max.',
                        // 'COD_LIBRO_ADQUISICIONES.required' => 'El campo Código Libro de Adquisiciones es obligatorio.',
                        'COD_LIBRO_ADQUISICIONES.string' => 'El campo Código Libro de Adquisiciones debe ser una cadena de texto.',
                        'COD_LIBRO_ADQUISICIONES.max' => 'El campo Código Libro de Adquisiciones no debe exceder los :max caracteres.',
                        // 'NUM_RES_EXCENTO_COMPRA.required' => 'El campo Número Resolución Exenta de Compra es obligatorio.',
                        'NUM_RES_EXCENTO_COMPRA.integer' => 'El campo Número Resolución Exenta de Compra debe ser un número entero.',
                        'NUM_RES_EXCENTO_COMPRA.between' => 'El campo Número Resolución Exenta de Compra debe estar entre :min y :max.',
                        'NUM_ORDEN_COMPRA.required' => 'El campo Número de Orden de Compra es obligatorio.',
                        'NUM_ORDEN_COMPRA.string' => 'El campo Número de Orden de Compra debe ser una cadena de texto.',
                        'NUM_ORDEN_COMPRA.max' => 'El campo Número de Orden de Compra no debe exceder los :max caracteres.',
                    ]);

                    // si el validador falla, redirigir con errores
                    if ($validatorIngreso->fails()) {
                        return redirect()->route('materiales.edit', $material->MATERIAL_ID)->withErrors($validatorIngreso)->withInput();
                    }

                    // Aquí concatenas la información para el detalle de movimiento para un ingreso
                    // Formatear el detalle y dar formato
                    $proveedor = $request->input('PROVEEDOR') ? $request->input('PROVEEDOR') : 'No especifica';
                    $numeroFactura = $request->input('NUMERO_FACTURA') ? $request->input('NUMERO_FACTURA') : 'No especifica';
                    $codigoLibroAdquisiciones = $request->input('COD_LIBRO_ADQUISICIONES') ? $request->input('COD_LIBRO_ADQUISICIONES') : 'No especifica';
                    $numResExcentoCompra = $request->input('NUM_RES_EXCENTO_COMPRA') ? $request->input('NUM_RES_EXCENTO_COMPRA') : 'No especifica';
                    $numOrdenCompra = $request->input('NUM_ORDEN_COMPRA');

                    // Concatenar la información
                    $detalleMovimiento = strtoupper("Proveedor: {$proveedor}, ".
                    "Numero de Factura: {$numeroFactura}, ".
                    "Codigo Libro Adquisiciones: {$codigoLibroAdquisiciones}, ".
                    "Numero Res. Exenta de Compra: {$numResExcentoCompra}, ".
                    "Numero de Orden de Compra: {$numOrdenCompra}.");
                    break;
                // ...

                case 'TRASLADO':
                    // Validar campos requeridos para traslado
                    $validatorTraslado = Validator::make($request->all(), [
                        'UBICACION_ID' => 'required|exists:ubicaciones,UBICACION_ID',
                        'FECHA_MEMO_CONDUCTOR' => 'required|date',
                        'CORREO_ELECTRONICO_SOLICITANTE' => 'required',
                    ], [
                        'UBICACION_ID.required' => 'El campo Ubicación es obligatorio.',
                        'UBICACION_ID.exists' => 'La Ubicación seleccionada no es válida.',
                        'FECHA_MEMO_CONDUCTOR.required' => 'El campo Fecha Memo Conductor es obligatorio.',
                        'FECHA_MEMO_CONDUCTOR.date' => 'El campo Fecha Memo Conductor debe ser una fecha válida.',
                        'CORREO_ELECTRONICO_SOLICITANTE.required' => 'El campo Correo Electrónico Solicitante es obligatorio.',
                    ]);

                    // si el validador falla, redirigir con errores
                    if ($validatorTraslado->fails()) {
                        return redirect()->route('materiales.edit', $material->MATERIAL_ID)->withErrors($validatorTraslado)->withInput();
                    }

                    // Aquí concatenas la información para el detalle de movimiento para un traslado
                    $ubicacion = Ubicacion::find($request->UBICACION_ID);
                    $fechaMemoConductor = Carbon::parse($request->FECHA_MEMO_CONDUCTOR)->format('d-m-Y');
                    $detalleMovimiento = "Traslado a ubicación: {$ubicacion->UBICACION_NOMBRE}, Fecha memo conductor: {$fechaMemoConductor}, Correo electrónico solicitante: {$request->CORREO_ELECTRONICO_SOLICITANTE}.";
                    break;
                case 'MERMA':
                    // Validar campos requeridos para merma
                    $validatorMerma = Validator::make($request->all(), [
                        'FECHA_AUTORIZACION' => 'required|date',
                        'NOMBRE_JEFE_AUTORIZA' => 'required|string|max:255',
                    ], [
                        'FECHA_AUTORIZACION.required' => 'El campo Fecha de Autorización es obligatorio.',
                        'FECHA_AUTORIZACION.date' => 'El campo Fecha de Autorización debe ser una fecha válida.',
                        'NOMBRE_JEFE_AUTORIZA.required' => 'El campo Nombre del Jefe que autoriza es obligatorio.',
                        'NOMBRE_JEFE_AUTORIZA.string' => 'El campo Nombre del Jefe que autoriza debe ser una cadena de texto.',
                        'NOMBRE_JEFE_AUTORIZA.max' => 'El campo Nombre del Jefe que autoriza no debe exceder los :max caracteres.',
                    ]);

                    // si el validador falla, redirigir con errores
                    if ($validatorMerma->fails()) {
                        return redirect()->route('materiales.edit', $material->MATERIAL_ID)->withErrors($validatorMerma)->withInput();
                    }

                    // Aquí concatenas la información para el detalle de movimiento para una merma
                    $fechaAutorizacion = Carbon::parse($request->FECHA_AUTORIZACION)->format('d-m-Y');
                    $detalleMovimiento = "Merma autorizada por: {$request->NOMBRE_JEFE_AUTORIZA}, Fecha de autorización: {$fechaAutorizacion}.";
                    break;
                case 'OTRO':
                    // Manejar caso de ingreso, si es necesario
                    $detalleMovimiento = strtoupper($request->input('DETALLE_MOVIMIENTO'));
                    break;
                // Agrega casos adicionales según sea necesario
            }

            // Actualizar los atributos del material, despues de validar los campos en los casos anteriores
            $material->update([
                'TIPO_MATERIAL_ID' => $request->TIPO_MATERIAL_ID,
                'MATERIAL_NOMBRE' => strtoupper($request->input('MATERIAL_NOMBRE')),
                'MATERIAL_STOCK' => $stockResultante,
            ]);

            // Crear un nuevo movimiento asociado al material modificado
            Movimiento::create([
                'USUARIO_id' => Auth::user()->id,
                'MATERIAL_ID' => $material->MATERIAL_ID,
                'MOVIMIENTO_TITULAR' => (Auth::user()->USUARIO_NOMBRES.' '.Auth::user()->USUARIO_APELLIDOS),
                'MOVIMIENTO_OBJETO' => 'MATERIAL: ' . $material->MATERIAL_NOMBRE,
                'MOVIMIENTO_TIPO_OBJETO' => $material->tipoMaterial->TIPO_MATERIAL_NOMBRE,
                'MOVIMIENTO_TIPO' => $request->TIPO_MOVIMIENTO,
                'MOVIMIENTO_STOCK_PREVIO' => $request->MATERIAL_STOCK,
                'MOVIMIENTO_CANTIDAD_A_MODIFICAR' => $request->STOCK_NUEVO,
                'MOVIMIENTO_STOCK_RESULTANTE' => $stockResultante,
                'MOVIMIENTO_DETALLE' => $detalleMovimiento
            ]);

            return redirect()->route('materiales.index')->with('success', 'Material actualizado exitosamente.');
        } catch (ModelNotFoundException $e) {
            return redirect()->route('materiales.index')->with('error', 'No se encontró el material con el ID proporcionado.');
        } catch (Exception $e) {
            return redirect()->route('materiales.index')->with('error', 'Error al actualizar el material: ');
        }
    }



    public function destroy(string $id)
    {
        try {
            // Encontrar el material por su ID
            $material = Material::find($id);

            // Verificar si el material existe antes de intentar eliminarlo
            if ($material) {

                // Crear un nuevo movimiento asociado al material eliminado
                Movimiento::create([
                    'USUARIO_id' => Auth::user()->id,
                    'MATERIAL_ID' => $material->MATERIAL_ID,
                    'MOVIMIENTO_TITULAR' => (Auth::user()->USUARIO_NOMBRES.' '.Auth::user()->USUARIO_APELLIDOS),
                    'MOVIMIENTO_OBJETO' => 'MATERIAL: ' . $material->MATERIAL_NOMBRE,
                    'MOVIMIENTO_TIPO_OBJETO' => $material->tipoMaterial->TIPO_MATERIAL_NOMBRE,
                    'MOVIMIENTO_TIPO' => 'ELIMINACIÓN',
                    'MOVIMIENTO_STOCK_PREVIO' => $material->MATERIAL_STOCK,
                    'MOVIMIENTO_CANTIDAD_A_MODIFICAR' => $material->MATERIAL_STOCK,
                    'MOVIMIENTO_STOCK_RESULTANTE' => ($material->MATERIAL_STOCK-$material->MATERIAL_STOCK),
                    'MOVIMIENTO_DETALLE' => 'ELIMINACIÓN DEL MATERIAL EN EL INVENTARIO',
                ]);

                // Eliminar el material
                $material->delete();

                return redirect()->route('materiales.index')->with('success', 'Material eliminado exitosamente.');
            } else {
                // Material no encontrado
                return redirect()->route('materiales.index')->with('error', 'No se encontró el material.');
            }
        } catch (QueryException $e) {
            // Manejo de excepciones cuando hay violaciones de restricción de clave externa
            return redirect()->route('materiales.index')->with('error', 'No se puede eliminar el material porque tiene registros relacionados.');
        } catch (Exception $e) {
            // Manejo de otras excepciones
            return redirect()->route('materiales.index')->with('error', 'Error al eliminar el material');
        }
    }


    public function addToCart(Request $request, Material $material)
    {
        $cantidadSolicitada = $request->input('cantidad', 1);
        //$stockMaterial = $material->MATERIAL_STOCK;

        // Obtén la cantidad ya en el carrito para este material
        $cantidadEnCarrito = Cart::instance('carrito_materiales')->search(function ($cartItem) use ($material) {
            return $cartItem->id === $material->MATERIAL_ID;
        })->sum('qty');

        // Verifica si la cantidad solicitada supera el stock disponible considerando lo que ya está en el carrito
        if (($cantidadSolicitada + $cantidadEnCarrito) > 10000) { // Se define el límite para pedir (10.000)
            return redirect()->back()->with('error', 'La cantidad solicitada es muy alta.');
        }

        // Agrega el material al carrito con la cantidad solicitada
        $carritoMateriales = Cart::instance('carrito_materiales');
        $carritoMateriales->add($material, $cantidadSolicitada);

        return redirect()->back()->with('success', 'Material agregado al carrito exitosamente');
    }

    // Si queremos mostrar el carrito en una vista apartada (Probablemente no se use pero se codifica por si acaso)
    public function showCart()
    {
        $cartItems = Cart::instance('carrito_materiales')->content();
        return view('sia2.activos.modmateriales.materiales.show_cart', compact('cartItems'));
    }

    // Funcion para eliminar un formulario del carrito
    public function deleteFromCart($rowId){
        // Cargamos la instancia del carrito de formularios
        $carritoMateriales = Cart::instance('carrito_materiales');
        // Eliminamos el formulario del carrito
        $carritoMateriales->remove($rowId);
        // Redireccionamos a la vista del carrito
        return redirect()->back()->with('success', 'Material eliminado exitosamente');
    }

    // Exportable para Excel
    public function exportExcel()
    {
        return Excel::download(new MaterialesExport, 'Maestro_Materiales.xlsx');
    }

    // Exportable para PDF
    public function exportPdf()
    {
        $responsable = Auth::user()->USUARIO_NOMBRES.' '.Auth::user()->USUARIO_APELLIDOS . ' - ' . Auth::user()->USUARIO_RUT;
        $direccion = Auth::user()->oficina->OFICINA_NOMBRE;
        // Obtener materiales de la dirección regional del usuario.
        $materiales = Material::where('OFICINA_ID', Auth::user()->OFICINA_ID)
            ->with('tipoMaterial') // Cargar la relación tipoMaterial
            ->get();
        $fecha = Carbon::now()->setTimezone('America/Santiago')->format('d/m/Y H:i');
        // Reemplazar los caracteres '/' por '-' y ':' por '-' para evitar problemas en el nombre del archivo
        $fechaParaNombreArchivo = str_replace(['/', ':', ' '], '-', $fecha);
        $imagePath = public_path('img/logosii.jpg');
        $imagePath2 = public_path('img/fondo_sii_intranet.jpg');
        $html = view('sia2.activos.modmateriales.materiales.materialespdf', compact('materiales', 'fecha', 'imagePath', 'imagePath2', 'responsable', 'direccion'))->render();

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);

        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Concatenar la fecha de creación al nombre del archivo, asegurando que sea compatible con los sistemas de archivos
        $nombreArchivo = "Reporte_Materiales_" . $fechaParaNombreArchivo . ".pdf";

        $dompdf->stream($nombreArchivo, ["Attachment" => false]);
    }

    // Exportable Auditoria Materiales para PDF
    public function exportAuditoriaPdf()
    {
        $responsable = Auth::user()->USUARIO_NOMBRES.' '.Auth::user()->USUARIO_APELLIDOS . ' - ' . Auth::user()->USUARIO_RUT;
        $direccion = Auth::user()->oficina->OFICINA_NOMBRE;

        // Obtener los movimientos que representan las auditorías, ordenados de la más reciente a la más antigua
        $auditorias = Movimiento::where('MOVIMIENTO_OBJETO', 'LIKE', 'MATERIAL: %')
                        ->orderBy('created_at', 'desc') // Asumiendo que 'created_at' es el campo de fecha
                        ->get();

        $fecha = now()->setTimezone('America/Santiago')->format('d/m/Y H:i');
        $fechaParaNombreArchivo = str_replace(['/', ':', ' '], '-', $fecha);
        $imagePath = public_path('img/logosii.jpg');
        $imagePath2 = public_path('img/fondo_sii_intranet.jpg');

        // Renderizar la vista del PDF con los datos de las auditorías
        $html = view('sia2.auditorias.materialesauditoriapdf', compact('auditorias', 'fecha', 'imagePath', 'imagePath2', 'responsable', 'direccion'))->render();

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);

        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Nombre del archivo para el PDF
        $nombreArchivo = "Reporte_Movimiento_Material_" . $fechaParaNombreArchivo . ".pdf";

        // Descargar el PDF
        $dompdf->stream($nombreArchivo, ["Attachment" => false]);
    }

}
