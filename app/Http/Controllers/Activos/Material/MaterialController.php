<?php

namespace App\Http\Controllers\Activos\Material;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;
use Gloudemans\Shoppingcart\Facades\Cart;

use Carbon\Carbon;
use Dompdf\Dompdf;

use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MaterialesExport;

use App\Models\Material;
use App\Models\TipoMaterial;
use App\Models\Movimiento;
use App\Models\Oficina;




class MaterialController extends Controller
{
    public function index()
    {
        // Código para el manejo de errores y retorno de vistas
        try
        {
           // Obtener OFICINA_ID del usuario actual
            $oficinaIdUsuario = Auth::user()->OFICINA_ID;

            // Obtener materiales basados en la OFICINA_ID del usuario
            $materiales = Material::where('OFICINA_ID', $oficinaIdUsuario)->get();

            return view('sia2.activos.modmateriales.materiales.index', compact('materiales'));;
        }
        catch (Exception $e)
        {
            // Retornar a la pagina previa con un session error
            return back()->with('error', 'Error cargando los materiales');
        }
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
                'DETALLE_MOVIMIENTO' => 'required|string|max:1000'
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
            ]);

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
                    'MOVIMIENTO_DETALLE' => strtoupper($request->input('DETALLE_MOVIMIENTO'))
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

            return view('sia2.activos.modmateriales.materiales.edit', compact('material', 'tiposMateriales', 'oficina'));
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
                'DETALLE_MOVIMIENTO' => 'required|string|max:1000',
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

            // Actualizar los atributos del material
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
                'MOVIMIENTO_DETALLE' => strtoupper($request->input('DETALLE_MOVIMIENTO'))
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
        try{
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
            }

            return redirect()->route('materiales.index')->with('success', 'Material eliminado exitosamente.');
        } catch(ModelNotFoundException) {
            // Manejo de excepciones cuando no encuentre el material
            return redirect()->route('materiales.index')->with('error', 'Error al eliminar el material');
        } catch(Exception $e) {// "Exeption" estaba mal escrito
            return redirect()->route('materiales.index')->with('error', 'No se encontró el material.');
        }
    }

    public function addToCart(Material $material)
    {
        // Creamos la instancia del carrito de formularios
        $carritoMateriales = Cart::instance('carrito_materiales');
        // Agregar el material al carrito con una cantidad predeterminada (puedes ajustarlo según tus necesidades)
        $carritoMateriales->add($material, 1);

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
        $materiales = Material::where('OFICINA_ID', Auth::user()->OFICINA_ID)->get();
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
}
