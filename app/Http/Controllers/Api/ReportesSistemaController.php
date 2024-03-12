<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

// Modelos gays
use App\Models\User;
use App\Models\Oficina;
use App\Models\Ubicacion;
use App\Models\Departamento;

class ReportesSistemaController extends Controller
{
    /**
     * Obtiene los datos de los gráficos y los devuelve en formato JSON.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getGraficos()
    {
        try{
            //Obtener datos de graficos
            $grafico1 = $this->rankingSolicitudes(new Request());

            //devolver
            return response()->json([
                'status' => 'success',
                'data' => [
                    'grafico1' => $grafico1,
                ],
            ], 200);
        }catch(\Exception $e){
            return response()->json([
                'status' => 'error',
                'message' => 'Error al obtener los graficos: ' . $e->getMessage(),
            ], 500);
        }
    }


    /**
     * Filtra los datos generales para generar un reporte del sistema.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function filtrarGeneral(Request $request)
    {
        try{
            //Obtener datos de graficos
            $grafico1 = $this->rankingSolicitudes($request);

            //devolver
            return response()->json([
                'status' => 'success',
                'data' => [
                    'grafico1' => $grafico1,
                ],
                'message' => 'Datos generales obtenidos correctamente.',
            ], 200);
        }catch(\Exception $e){
            return response()->json([
                'status' => 'error',
                'message' => 'Error al obtener los graficos: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Genera un ranking de solicitudes por categoría dentro del sistema SIA V2.
     *
     * Este método calcula y muestra el ranking de todos los tipos de solicitudes en el sistema,
     * incluyendo solicitudes de materiales, equipos, salas, bodegas, vehículos, formularios y reparaciones.
     * Las tablas de solicitudes intermedias (materiales, equipos, formularios, salas y bodegas) utilizan un "SOLICITUD_ID"
     * que apunta a la tabla "solicitudes", y cada registro único por "SOLICITUD_ID" se cuenta una sola vez para evitar duplicados.
     * Las solicitudes de vehículos y reparaciones se cuentan directamente desde sus respectivas tablas base,
     * diferenciando las reparaciones en vehiculares e inmuebles mediante la presencia o ausencia del "VEHICULO_ID".
     *
     * @param Request $request La solicitud HTTP que puede incluir 'fecha_inicio', 'fecha_fin' y el ID de oficina del usuario autenticado.
     *                         - 'fecha_inicio' y 'fecha_fin' definen el rango de fechas para filtrar las solicitudes.
     *                         - El ID de oficina se utiliza para filtrar las solicitudes por la oficina del usuario autenticado.
     *
     * @return \Illuminate\Http\JsonResponse Devuelve una respuesta JSON que contiene el ranking de solicitudes por categoría.
     *                                       Cada categoría incluye el nombre formateado y el conteo de solicitudes únicas asociadas.
     *
    */
    public function rankingSolicitudes(Request $request)
    {
        try {
            // Obtener y formatear fechas de inicio y fin
            $fechaInicioInput = $request->input('fecha_inicio');
            $fechaFinInput = $request->input('fecha_fin');

            //?? VALIDACIONES DE INPUT
            // Si no se proporciona fecha de inicio, usar el primer día del mes actual
            $fechaInicio = $fechaInicioInput ? Carbon::createFromFormat('Y-m-d', $fechaInicioInput)->startOfDay() : Carbon::now()->startOfMonth()->startOfDay();
            // Si no se proporciona fecha de fin, usar el día actual
            $fechaFin = $fechaFinInput ? Carbon::createFromFormat('Y-m-d', $fechaFinInput)->endOfDay() : Carbon::now()->endOfDay();
            //?? VALIDACION REGIONAL
            // Obtener el ID de la oficina del usuario autenticado para filtrar por regional
            $oficinaId = Auth::user()->OFICINA_ID;

            // Inicializar el ranking
            $ranking = [];

            // Lista de tablas intermedias que utilizan SOLICITUD_ID para enlazar a la tabla solicitudes
            $tablasIntermedias = [
                'solicitudes_materiales',
                'solicitudes_equipos',
                'solicitudes_salas',
                'solicitudes_bodegas',
                'solicitudes_formularios'
            ];

            // Contar solicitudes únicas de tablas intermedias
            foreach ($tablasIntermedias as $tabla) {
                $cantidad = DB::table($tabla)
                    ->join('solicitudes', $tabla . '.SOLICITUD_ID', '=', 'solicitudes.SOLICITUD_ID')
                    ->join('users', 'solicitudes.USUARIO_id', '=', 'users.id')
                    ->where('users.OFICINA_ID', $oficinaId)
                    ->when($fechaInicio && $fechaFin, function ($query) use ($fechaInicio, $fechaFin) {
                        return $query->whereBetween('solicitudes.created_at', [$fechaInicio, $fechaFin]);
                    })
                    ->distinct('solicitudes.SOLICITUD_ID')
                    ->count();

                // Formatear el nombre para que sea más legible
                $nombreAmigable = $this->formatearNombreCategoria($tabla);
                $ranking[$nombreAmigable] = $cantidad;
            }

            // Vehículos y reparaciones (divididas en vehiculares e inmuebles)
            $cantidadVehiculos = DB::table('solicitudes_vehiculos')
                ->join('users', 'solicitudes_vehiculos.USUARIO_id', '=', 'users.id')
                ->where('users.OFICINA_ID', $oficinaId)
                ->when($fechaInicio && $fechaFin, function ($query) use ($fechaInicio, $fechaFin) {
                    return $query->whereBetween('solicitudes_vehiculos.created_at', [$fechaInicio, $fechaFin]);
                })
                ->count();

            $ranking['Vehículos'] = $cantidadVehiculos;

            // Reparaciones Vehiculares
            $cantidadReparacionesVehiculares = DB::table('solicitudes_reparaciones')
                ->join('users', 'solicitudes_reparaciones.USUARIO_id', '=', 'users.id')
                ->whereNotNull('solicitudes_reparaciones.VEHICULO_ID')
                ->where('users.OFICINA_ID', $oficinaId)
                ->when($fechaInicio && $fechaFin, function ($query) use ($fechaInicio, $fechaFin) {
                    return $query->whereBetween('solicitudes_reparaciones.created_at', [$fechaInicio, $fechaFin]);
                })
                ->count();

            $ranking['Reparaciones Vehiculares'] = $cantidadReparacionesVehiculares;

            // Reparaciones Inmuebles
            $cantidadReparacionesInmuebles = DB::table('solicitudes_reparaciones')
                ->join('users', 'solicitudes_reparaciones.USUARIO_id', '=', 'users.id')
                ->whereNull('solicitudes_reparaciones.VEHICULO_ID')
                ->where('users.OFICINA_ID', $oficinaId)
                ->when($fechaInicio && $fechaFin, function ($query) use ($fechaInicio, $fechaFin) {
                    return $query->whereBetween('solicitudes_reparaciones.created_at', [$fechaInicio, $fechaFin]);
                })
                ->count();

            $ranking['Reparaciones Inmuebles'] = $cantidadReparacionesInmuebles;

            // Ordenar el ranking por cantidad (opcional)
            arsort($ranking);

            //Devolver como response json
            return response()->json([
                'status' => 'success',
                'data' => $ranking,
                'message' => 'Ranking de solicitudes obtenido correctamente desde la fecha ' . $fechaInicio->format('d-m-Y H:i:s') . ' hasta la fecha ' . $fechaFin->format('d-m-Y H:i:s') . '.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al obtener el ranking de solicitudes: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Formatea el nombre de la tabla para que sea más legible.
     *
     * @param string $tablaNombre Nombre de la tabla.
     * @return string Nombre formateado.
     */
    private function formatearNombreCategoria($tablaNombre)
    {
        // Eliminar el prefijo "solicitudes_" y eliminar guion bajo
        $nombreSinPrefijo = substr($tablaNombre, 11); // Elimina 'solicitudes_'
        $nombreSinEspacios = str_replace('_', '', $nombreSinPrefijo);

        // Convertir a mayúsculas el primer carácter de cada palabra
        $nombreFormateado = ucwords($nombreSinEspacios);

        return $nombreFormateado;
    }

    /**
     * Get the distribution of users by gender within a specified date range.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDistribucionPorGenero(Request $request)
    {
        try {
            $fechaInicioInput = $request->input('fecha_inicio');
            $fechaFinInput = $request->input('fecha_fin');

            $fechaInicio = $fechaInicioInput ? Carbon::createFromFormat('Y-m-d', $fechaInicioInput)->startOfDay() : Carbon::now()->startOfMonth()->startOfDay();
            $fechaFin = $fechaFinInput ? Carbon::createFromFormat('Y-m-d', $fechaFinInput)->endOfDay() : Carbon::now()->endOfDay();

            $oficinaId = Auth::user()->OFICINA_ID;

            $distribucion = User::where('OFICINA_ID', $oficinaId)
                                ->whereBetween('USUARIO_FECHA_INGRESO', [$fechaInicio, $fechaFin])
                                ->groupBy('USUARIO_SEXO')
                                ->selectRaw('USUARIO_SEXO as sexo, COUNT(*) as total')
                                ->get();

            $totalUsuarios = $distribucion->sum('total');

            return response()->json([
                'status' => 'success',
                'data' => $distribucion,
                'total' => $totalUsuarios,
                'message' => "Distribución de género de los funcionarios obtenida con éxito desde las fechas " . $fechaInicio->format('d-m-Y H:i:s') . " hasta " . $fechaFin->format('d-m-Y H:i:s') . "."
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al obtener la distribución de género: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function filtrarUsuarios(Request $request)
    {
        try {
            $query = User::query();

            // Ejemplo simple de conteo
            $conteoMasculinos = $query->clone()->where('USUARIO_SEXO', 'MASCULINO')->count();
            $conteoFemeninos = $query->where('USUARIO_SEXO', 'FEMENINO')->count();

            return response()->json([
                'masculinos' => $conteoMasculinos,
                'femeninos' => $conteoFemeninos,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al filtrar usuarios: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function getTotalsPorUbicacion($ubicacionId)
    {
        $ubicacion = Ubicacion::find($ubicacionId);

        if (!$ubicacion) {
            return response()->json(['error' => 'Ubicación no encontrada'], 404);
        }

        if($ubicacion instanceof \Illuminate\Database\Eloquent\Collection){
            return response()->json(['error' => 'Esperaba un modelo de ubicación, pero se obtuvo una colección.'], 500);
        }

        $hombres = User::where('UBICACION_ID', $ubicacion->UBICACION_ID)->where('USUARIO_SEXO', 'MASCULINO')->count();
        $mujeres = User::where('UBICACION_ID', $ubicacion->UBICACION_ID)->where('USUARIO_SEXO', 'FEMENINO')->count();
        $total = $hombres + $mujeres;

        return response()->json([
            'ubicacion' => $ubicacion->UBICACION_NOMBRE,
            'hombres' => $hombres,
            'mujeres' => $mujeres,
            'total' => $total
        ]);
    }

    public function getTotalsPorDepartamento($departamentoId)
    {
        $departamento = Departamento::find($departamentoId);

        if (!$departamento) {
            return response()->json(['error' => 'Departamento no encontrado'], 404);
        }

        if($departamento instanceof \Illuminate\Database\Eloquent\Collection){
            return response()->json(['error' => 'Esperaba un modelo de departamento, pero se obtuvo una colección.'], 500);
        }

        $hombres = User::where('DEPARTAMENTO_ID', $departamento->DEPARTAMENTO_ID)->where('USUARIO_SEXO', 'MASCULINO')->count();
        $mujeres = User::where('DEPARTAMENTO_ID', $departamento->DEPARTAMENTO_ID)->where('USUARIO_SEXO', 'FEMENINO')->count();
        $total = $hombres + $mujeres;

        return response()->json([
            'departamento' => $departamento->DEPARTAMENTO_NOMBRE,
            'hombres' => $hombres,
            'mujeres' => $mujeres,
            'total' => $total
        ]);
    }
}
