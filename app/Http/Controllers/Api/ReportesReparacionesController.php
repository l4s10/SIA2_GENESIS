<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

// Importar el modelo de Solicitudes de Reparacion
use App\Models\SolicitudReparacion;
use App\Models\RevisionSolicitud;

class ReportesReparacionesController extends Controller
{
    /**
     * Obtiene todos los datos de gráficos para la carga inicial.
     * Este método agrupa y retorna los resultados de múltiples funciones de gráfico,
     * permitiendo una carga inicial eficiente de todos los datos necesarios para los gráficos de reportes de salas.
     *
     * @return \Illuminate\Http\JsonResponse
    */
    public function getGraficos()
    {
        try{
            // Cargar los datos de los graficos
            $grafico1 = $this->Grafico1(new Request());
            $grafico2 = $this->Grafico2(new Request());
            $grafico3 = $this->Grafico3(new Request());
            $grafico4 = $this->Grafico4(new Request());
            $grafico5 = $this->Grafico5(new Request());
            $grafico6 = $this->Grafico6(new Request());
            $grafico7 = $this->Grafico7(new Request());
            $grafico8 = $this->Grafico8(new Request());
            $grafico9 = $this->Grafico9(new Request());

            // Devolver los datos de los graficos
            return response()->json([
                'status' => 'success',
                'data' => [
                    'grafico1' => $grafico1,
                    'grafico2' => $grafico2,
                    'grafico3' => $grafico3,
                    'grafico4' => $grafico4,
                    'grafico5' => $grafico5,
                    'grafico6' => $grafico6,
                    'grafico7' => $grafico7,
                    'grafico8' => $grafico8,
                    'grafico9' => $grafico9,
                ]
            ]);
        }catch (\Exception $e){
            return response()->json([
                'status' => 'error',
                'message' => 'Error al obtener los datos de los gráficos',
                // 'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Filtra los datos de los gráficos por un rango de fechas especificado.
     * Esta función extrae las fechas de inicio y fin del request y aplica estos filtros a cada gráfico individualmente.
     * Los resultados filtrados de cada gráfico son luego agrupados y devueltos en una sola respuesta JSON.
     *
     * @param Request $request Contiene las fechas de inicio y fin para filtrar los datos.
     * @return \Illuminate\Http\JsonResponse
    */
    public function filtrarGeneral(Request $request)
    {
        try{
            // Filtrar los datos de los gráficos por el rango de fechas especificado
            $grafico1 = $this->Grafico1($request);
            $grafico2 = $this->Grafico2($request);
            $grafico3 = $this->Grafico3($request);
            $grafico4 = $this->Grafico4($request);
            $grafico5 = $this->Grafico5($request);
            $grafico6 = $this->Grafico6($request);
            $grafico7 = $this->Grafico7($request);
            $grafico8 = $this->Grafico8($request);
            $grafico9 = $this->Grafico9($request);


            // Devolver los datos de los gráficos filtrados
            return response()->json([
                'status' => 'success',
                'data' => [
                    'grafico1' => $grafico1,
                    'grafico2' => $grafico2,
                    'grafico3' => $grafico3,
                    'grafico4' => $grafico4,
                    'grafico5' => $grafico5,
                    'grafico6' => $grafico6,
                    'grafico7' => $grafico7,
                    'grafico8' => $grafico8,
                    'grafico9' => $grafico9,
                ]
            ]);

        }catch (\Exception $e){
            return response()->json([
                'status' => 'error',
                'message' => 'Error al filtrar los datos de los gráficos',
                // 'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Grafico 1 REPARACIONES POR CATEGORIA
     * Este método obtiene la cantidad de reparaciones realizadas por cada categoría de reparación. (AIRE ACONDICIONADO, INFRAESTRUCTURA, MOVILIARIO, OTRO, MANTENCION CORRECTIVA, MANTENCION PREVENTIVA)
     * De las anteriores solo necesitamos las primeras 4.
    */
    public function Grafico1(Request $request)
    {
        try{
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

            // Categorías de reparaciones sobre las que se evaluará la cantidad de reparaciones
            $categorias = ['AIRE ACONDICIONADO', 'INFRAESTRUCTURA', 'MOVILIARIO', 'OTRO'];

            $reparacionesPorCategoria = SolicitudReparacion::join('categorias_reparaciones', 'solicitudes_reparaciones.CATEGORIA_REPARACION_ID', '=', 'categorias_reparaciones.CATEGORIA_REPARACION_ID')
                ->join('users', 'solicitudes_reparaciones.USUARIO_id', '=', 'users.id')
                ->where('users.OFICINA_ID', '=', $oficinaId)
                ->whereIn('categorias_reparaciones.CATEGORIA_REPARACION_NOMBRE', $categorias)
                ->when($fechaInicio && $fechaFin, function ($query) use ($fechaInicio, $fechaFin) {
                    return $query->whereBetween('solicitudes_reparaciones.created_at', [$fechaInicio, $fechaFin]);
                })
                ->groupBy('categorias_reparaciones.CATEGORIA_REPARACION_NOMBRE')
                ->select('categorias_reparaciones.CATEGORIA_REPARACION_NOMBRE', DB::raw('COUNT(solicitudes_reparaciones.SOLICITUD_REPARACION_ID) as cantidad'))
                ->orderBy('cantidad', 'DESC')
                ->get();

            // Devolver como response JSON
            return response()->json([
                'status' => 'success',
                'data' => $reparacionesPorCategoria,
                'message' => 'Cantidad de reparaciones por categoria obtenida correctamente desde la fecha ' . $fechaInicio->format('d-m-Y H:i:s') . ' hasta la fecha ' . $fechaFin->format('d-m-Y H:i:s')
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al obtener la cantidad de reparaciones por categoria: ' . $e->getMessage(),
            ], 500);
        }
    }


    /**
     * Grafico 2 MANTENCIONES POR CATEGORIA
     * Este método obtiene la cantidad de mantenciones realizadas por cada categoría de reparación. (MANTENCION CORRECTIVA, MANTENCION PREVENTIVA)
     *
     */
    public function Grafico2(Request $request)
    {
        try{
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

            $categorias = ['MANTENCION CORRECTIVA', 'MANTENCION PREVENTIVA'];

            $mantencionesPorCategoria = SolicitudReparacion::join('categorias_reparaciones', 'solicitudes_reparaciones.CATEGORIA_REPARACION_ID', '=', 'categorias_reparaciones.CATEGORIA_REPARACION_ID')
                ->join('users', 'solicitudes_reparaciones.USUARIO_id', '=', 'users.id')
                ->where('users.OFICINA_ID', '=', $oficinaId)
                ->whereIn('categorias_reparaciones.CATEGORIA_REPARACION_NOMBRE', $categorias)
                ->when($fechaInicio && $fechaFin, function ($query) use ($fechaInicio, $fechaFin) {
                    return $query->whereBetween('solicitudes_reparaciones.created_at', [$fechaInicio, $fechaFin]);
                })
                ->groupBy('categorias_reparaciones.CATEGORIA_REPARACION_NOMBRE')
                ->select('categorias_reparaciones.CATEGORIA_REPARACION_NOMBRE', DB::raw('COUNT(solicitudes_reparaciones.SOLICITUD_REPARACION_ID) as cantidad'))
                ->orderBy('cantidad', 'DESC')
                ->get();

            // Devolver como response JSON
            return response()->json([
                'status' => 'success',
                'data' => $mantencionesPorCategoria,
                'message' => 'Cantidad de mantenciones por categoria obtenida correctamente desde la fecha ' . $fechaInicio->format('d-m-Y H:i:s') . ' hasta la fecha ' . $fechaFin->format('d-m-Y H:i:s')
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al obtener la cantidad de reparaciones por categoria: ' . $e->getMessage(),
            ], 500);
        }
    }


    public function Grafico3(Request $request)
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

            // Tipos de categorías para reparaciones físicas
            $categorias = ['AIRE ACONDICIONADO', 'INFRAESTRUCTURA', 'MOVILIARIO', 'OTRO'];

            // Obtener el ranking de estados por categorías físicas
            $ranking = SolicitudReparacion::join('categorias_reparaciones', 'solicitudes_reparaciones.CATEGORIA_REPARACION_ID', '=', 'categorias_reparaciones.CATEGORIA_REPARACION_ID')
                ->join('users', 'solicitudes_reparaciones.USUARIO_id', '=', 'users.id')
                ->where('users.OFICINA_ID', '=', $oficinaId)
                ->whereIn('categorias_reparaciones.CATEGORIA_REPARACION_NOMBRE', $categorias)
                ->when($fechaInicio && $fechaFin, function ($query) use ($fechaInicio, $fechaFin) {
                    return $query->whereBetween('solicitudes_reparaciones.created_at', [$fechaInicio, $fechaFin]);
                })
                ->groupBy('solicitudes_reparaciones.SOLICITUD_REPARACION_ESTADO')
                ->select('solicitudes_reparaciones.SOLICITUD_REPARACION_ESTADO as estado', DB::raw('COUNT(*) as cantidad'))
                ->orderBy('cantidad', 'DESC')
                ->get();

            // Devolver como response JSON
            return response()->json([
                'status' => 'success',
                'data' => $ranking,
                'message' => 'Ranking de estados para reparaciones físicas obtenido correctamente desde la fecha ' . $fechaInicio->format('d-m-Y H:i:s') . ' hasta la fecha ' . $fechaFin->format('d-m-Y H:i:s')
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al obtener el ranking de estados para reparaciones físicas: ' . $e->getMessage(),
            ], 500);
        }
    }



    public function Grafico4(Request $request)
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

            // Tipos de categorías para mantenimientos
            $categorias = ['MANTENCION CORRECTIVA', 'MANTENCION PREVENTIVA'];

            // Obtener el ranking de estados por categorías de mantenimiento
            $ranking = SolicitudReparacion::join('categorias_reparaciones', 'solicitudes_reparaciones.CATEGORIA_REPARACION_ID', '=', 'categorias_reparaciones.CATEGORIA_REPARACION_ID')
                ->join('users', 'solicitudes_reparaciones.USUARIO_id', '=', 'users.id')
                ->where('users.OFICINA_ID', '=', $oficinaId)
                ->whereIn('categorias_reparaciones.CATEGORIA_REPARACION_NOMBRE', $categorias)
                ->when($fechaInicio && $fechaFin, function ($query) use ($fechaInicio, $fechaFin) {
                    return $query->whereBetween('solicitudes_reparaciones.created_at', [$fechaInicio, $fechaFin]);
                })
                ->groupBy('solicitudes_reparaciones.SOLICITUD_REPARACION_ESTADO')
                ->select('solicitudes_reparaciones.SOLICITUD_REPARACION_ESTADO as estado', DB::raw('COUNT(*) as cantidad'))
                ->orderBy('cantidad', 'DESC')
                ->get();

            // Devolver como response JSON
            return response()->json([
                'status' => 'success',
                'data' => $ranking,
                'message' => 'Ranking de estados para mantenimientos obtenido correctamente desde la fecha ' . $fechaInicio->format('d-m-Y H:i:s') . ' hasta la fecha ' . $fechaFin->format('d-m-Y H:i:s')
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al obtener el ranking de estados para mantenimientos: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function Grafico5(Request $request)
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

            // Tipos de categorías para reparaciones físicas
            $categorias = ['AIRE ACONDICIONADO', 'INFRAESTRUCTURA', 'MOVILIARIO', 'OTRO'];

            // Asumiendo que 'categorias_reparaciones' es la tabla y 'CATEGORIA_REPARACION_NOMBRE' es la columna que contiene el nombre de la categoría
            // Ajusta estas referencias según tu esquema de base de datos real

            // Query para obtener las solicitudes por Departamento, filtradas por las categorías específicas
            $solicitudesPorDepartamento = SolicitudReparacion::join('users', 'solicitudes_reparaciones.USUARIO_id', '=', 'users.id')
                ->join('departamentos', 'users.DEPARTAMENTO_ID', '=', 'departamentos.DEPARTAMENTO_ID')
                ->join('categorias_reparaciones', 'solicitudes_reparaciones.CATEGORIA_REPARACION_ID', '=', 'categorias_reparaciones.CATEGORIA_REPARACION_ID')
                ->whereIn('categorias_reparaciones.CATEGORIA_REPARACION_NOMBRE', $categorias)
                ->whereNotNull('users.DEPARTAMENTO_ID')
                ->where('users.OFICINA_ID', '=', $oficinaId)
                ->when($fechaInicio && $fechaFin, function ($query) use ($fechaInicio, $fechaFin) {
                    return $query->whereBetween('solicitudes_reparaciones.created_at', [$fechaInicio, $fechaFin]);
                })
                ->select('departamentos.DEPARTAMENTO_NOMBRE as entidad', DB::raw('COUNT(*) as cantidad'))
                ->groupBy('departamentos.DEPARTAMENTO_NOMBRE')
                ->orderBy('cantidad', 'DESC')
                ->get();

            // Query para obtener las solicitudes por Ubicación, filtradas por las categorías específicas
            $solicitudesPorUbicacion = SolicitudReparacion::join('users', 'solicitudes_reparaciones.USUARIO_id', '=', 'users.id')
                ->join('ubicaciones', 'users.UBICACION_ID', '=', 'ubicaciones.UBICACION_ID')
                ->join('categorias_reparaciones', 'solicitudes_reparaciones.CATEGORIA_REPARACION_ID', '=', 'categorias_reparaciones.CATEGORIA_REPARACION_ID')
                ->whereIn('categorias_reparaciones.CATEGORIA_REPARACION_NOMBRE', $categorias)
                ->whereNotNull('users.UBICACION_ID')
                ->where('users.OFICINA_ID', '=', $oficinaId)
                ->when($fechaInicio && $fechaFin, function ($query) use ($fechaInicio, $fechaFin) {
                    return $query->whereBetween('solicitudes_reparaciones.created_at', [$fechaInicio, $fechaFin]);
                })
                ->select('ubicaciones.UBICACION_NOMBRE as entidad', DB::raw('COUNT(*) as cantidad'))
                ->groupBy('ubicaciones.UBICACION_NOMBRE')
                ->orderBy('cantidad', 'DESC')
                ->get();

            // Unir los resultados de las dos consultas anteriores (merge)
            $solicitudesPorEntidad = $solicitudesPorDepartamento->merge($solicitudesPorUbicacion);

            // Retornar las solicitudes por departamento y ubicación
            return response()->json([
                'status' => 'success',
                'data' => $solicitudesPorEntidad,
                'message' => 'Solicitudes de reparaciones por departamento y ubicación obtenidas correctamente desde la fecha ' . $fechaInicio->format('d-m-Y H:i:s') . ' hasta la fecha ' . $fechaFin->format('d-m-Y H:i:s')
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al obtener las solicitudes de reparaciones por departamento/ubicación: ' . $e->getMessage(),
            ], 500);
        }
    }


    public function Grafico6(Request $request)
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

            // Tipos de categorías para mantenimientos
            $categorias = ['MANTENCION CORRECTIVA', 'MANTENCION PREVENTIVA'];

            // Query para obtener las solicitudes de mantenimiento por Departamento
            $mantenimientosPorDepartamento = SolicitudReparacion::join('users', 'solicitudes_reparaciones.USUARIO_id', '=', 'users.id')
                ->join('departamentos', 'users.DEPARTAMENTO_ID', '=', 'departamentos.DEPARTAMENTO_ID')
                ->join('categorias_reparaciones', 'solicitudes_reparaciones.CATEGORIA_REPARACION_ID', '=', 'categorias_reparaciones.CATEGORIA_REPARACION_ID')
                ->whereIn('categorias_reparaciones.CATEGORIA_REPARACION_NOMBRE', $categorias)
                ->whereNotNull('users.DEPARTAMENTO_ID')
                ->where('users.OFICINA_ID', '=', $oficinaId)
                ->when($fechaInicio && $fechaFin, function ($query) use ($fechaInicio, $fechaFin) {
                    return $query->whereBetween('solicitudes_reparaciones.created_at', [$fechaInicio, $fechaFin]);
                })
                ->select('departamentos.DEPARTAMENTO_NOMBRE as entidad', DB::raw('COUNT(*) as cantidad'))
                ->groupBy('departamentos.DEPARTAMENTO_NOMBRE')
                ->orderBy('cantidad', 'DESC')
                ->get();

            // Query para obtener las solicitudes de mantenimiento por Ubicación
            $mantenimientosPorUbicacion = SolicitudReparacion::join('users', 'solicitudes_reparaciones.USUARIO_id', '=', 'users.id')
                ->join('ubicaciones', 'users.UBICACION_ID', '=', 'ubicaciones.UBICACION_ID')
                ->join('categorias_reparaciones', 'solicitudes_reparaciones.CATEGORIA_REPARACION_ID', '=', 'categorias_reparaciones.CATEGORIA_REPARACION_ID')
                ->whereIn('categorias_reparaciones.CATEGORIA_REPARACION_NOMBRE', $categorias)
                ->whereNotNull('users.UBICACION_ID')
                ->where('users.OFICINA_ID', '=', $oficinaId)
                ->when($fechaInicio && $fechaFin, function ($query) use ($fechaInicio, $fechaFin) {
                    return $query->whereBetween('solicitudes_reparaciones.created_at', [$fechaInicio, $fechaFin]);
                })
                ->select('ubicaciones.UBICACION_NOMBRE as entidad', DB::raw('COUNT(*) as cantidad'))
                ->groupBy('ubicaciones.UBICACION_NOMBRE')
                ->orderBy('cantidad', 'DESC')
                ->get();

            // Unir los resultados de las dos consultas anteriores (merge)
            $mantenimientosPorEntidad = $mantenimientosPorDepartamento->merge($mantenimientosPorUbicacion);

            // Retornar como response JSON
            return response()->json([
                'status' => 'success',
                'data' => $mantenimientosPorEntidad,
                'message' => 'Solicitudes de mantenimiento por departamento y ubicación obtenidas correctamente desde la fecha ' . $fechaInicio->format('d-m-Y H:i:s') . ' hasta la fecha ' . $fechaFin->format('d-m-Y H:i:s')
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al obtener las solicitudes de mantenimiento por departamento/ubicación: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function Grafico7(Request $request)
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

            $vehiculosConMasReparaciones = SolicitudReparacion::join('vehiculos', 'solicitudes_reparaciones.VEHICULO_ID', '=', 'vehiculos.VEHICULO_ID')
                ->join('users', 'solicitudes_reparaciones.USUARIO_id', '=', 'users.id')
                ->where('users.OFICINA_ID', '=', $oficinaId)
                ->whereNotNull('solicitudes_reparaciones.VEHICULO_ID')
                ->when($fechaInicio && $fechaFin, function ($query) use ($fechaInicio, $fechaFin) {
                    return $query->whereBetween('solicitudes_reparaciones.created_at', [$fechaInicio, $fechaFin]);
                })
                ->groupBy('vehiculos.VEHICULO_ID', 'vehiculos.VEHICULO_PATENTE') // Agrupar también por VEHICULO_PATENTE
                ->select('vehiculos.VEHICULO_PATENTE as patente', DB::raw('COUNT(solicitudes_reparaciones.SOLICITUD_REPARACION_ID) as cantidad'))
                ->orderBy('cantidad', 'DESC')
                ->get();

            // Devolver como response JSON
            return response()->json([
                'status' => 'success',
                'data' => $vehiculosConMasReparaciones,
                'message' => 'Vehículos con más reparaciones obtenidos correctamente desde la fecha ' . $fechaInicio->format('d-m-Y H:i:s') . ' hasta la fecha ' . $fechaFin->format('d-m-Y H:i:s')
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al obtener los vehículos con más reparaciones: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function Grafico8(Request $request)
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

            $gestionadores = RevisionSolicitud::join('users', 'revisiones_solicitudes.USUARIO_id', '=', 'users.id')
                ->join('solicitudes_reparaciones', 'revisiones_solicitudes.SOLICITUD_REPARACION_ID', '=', 'solicitudes_reparaciones.SOLICITUD_REPARACION_ID')
                ->whereNotNull('solicitudes_reparaciones.VEHICULO_ID')
                ->where('users.OFICINA_ID', '=', $oficinaId)
                ->when($fechaInicio && $fechaFin, function ($query) use ($fechaInicio, $fechaFin) {
                    return $query->whereBetween('revisiones_solicitudes.created_at', [$fechaInicio, $fechaFin]);
                })
                ->select(
                    'revisiones_solicitudes.USUARIO_id',
                    DB::raw('CONCAT(users.USUARIO_NOMBRES, " ", users.USUARIO_APELLIDOS) as nombre_completo'),
                    DB::raw('COUNT(DISTINCT revisiones_solicitudes.SOLICITUD_REPARACION_ID) as total_gestiones')
                )
                ->groupBy('revisiones_solicitudes.USUARIO_id', 'nombre_completo')
                ->orderBy('total_gestiones', 'DESC')
                ->get();

            // Devolver como response JSON
            return response()->json([
                'status' => 'success',
                'data' => $gestionadores,
                'message' => 'Gestionadores de solicitudes de vehículos obtenidos correctamente desde la fecha ' . $fechaInicio->format('d-m-Y H:i:s') . ' hasta la fecha ' . $fechaFin->format('d-m-Y H:i:s')
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al obtener los gestionadores de solicitudes de vehículos: ' . $e->getMessage(),
            ], 500);
        }
    }


    public function Grafico9(Request $request)
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

            $gestionadores = RevisionSolicitud::join('users', 'revisiones_solicitudes.USUARIO_id', '=', 'users.id')
                ->join('solicitudes_reparaciones', 'revisiones_solicitudes.SOLICITUD_REPARACION_ID', '=', 'solicitudes_reparaciones.SOLICITUD_REPARACION_ID')
                ->whereNull('solicitudes_reparaciones.VEHICULO_ID')
                ->where('users.OFICINA_ID', '=', $oficinaId)
                ->when($fechaInicio && $fechaFin, function ($query) use ($fechaInicio, $fechaFin) {
                    return $query->whereBetween('revisiones_solicitudes.created_at', [$fechaInicio, $fechaFin]);
                })
                ->select(
                    'revisiones_solicitudes.USUARIO_id',
                    DB::raw('CONCAT(users.USUARIO_NOMBRES, " ", users.USUARIO_APELLIDOS) as nombre_completo'),
                    DB::raw('COUNT(DISTINCT revisiones_solicitudes.SOLICITUD_REPARACION_ID) as total_gestiones')
                )
                ->groupBy('revisiones_solicitudes.USUARIO_id', 'nombre_completo')
                ->orderBy('total_gestiones', 'DESC')
                ->get();

            // Devolver como response JSON
            return response()->json([
                'status' => 'success',
                'data' => $gestionadores,
                'message' => 'Gestionadores de solicitudes de inmuebles obtenidos correctamente desde la fecha ' . $fechaInicio->format('d-m-Y H:i:s') . ' hasta la fecha ' . $fechaFin->format('d-m-Y H:i:s')
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al obtener los gestionadores de solicitudes de inmuebles: ' . $e->getMessage(),
            ], 500);
        }
    }
}
