<?php

namespace App\Http\Controllers\Api;
// Importaciones de librearias necesarias
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
// Importacion de modelos necesarios
use App\Models\Solicitud;
use App\Models\SolicitudBodega;
use App\Models\RevisionSolicitud;

class ReportesBodegasController extends Controller
{
    /**
     * Obtiene todos los datos de gráficos para la carga inicial.
     * Este método agrupa y retorna los resultados de múltiples funciones de gráfico,
     * permitiendo una carga inicial eficiente de todos los datos necesarios para los gráficos de reportes de bodegas.
     *
     * @return \Illuminate\Http\JsonResponse
    */
    public function getGraficos()
    {
        try {
            // Obtener los datos de cada gráfico individualmente
            $grafico1 = $this->Grafico1(new Request());
            $grafico2 = $this->Grafico2(new Request());
            $grafico3 = $this->Grafico3(new Request());
            $grafico4 = $this->Grafico4(new Request());
            $grafico5 = $this->Grafico5(new Request());

            // Devolver los resultados en un solo JSON
            return response()->json([
                'status' => 'success',
                'data' => [
                    'grafico1' => $grafico1,
                    'grafico2' => $grafico2,
                    'grafico3' => $grafico3,
                    'grafico4' => $grafico4,
                    'grafico5' => $grafico5
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al obtener los datos de los gráficos: ' . $e->getMessage()
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

            // Obtener los datos de cada gráfico individualmente, aplicando los filtros de fechas
            $grafico1 = $this->Grafico1($request);
            $grafico2 = $this->Grafico2($request);
            $grafico3 = $this->Grafico3($request);
            $grafico4 = $this->Grafico4($request);
            $grafico5 = $this->Grafico5($request);

            // Devolver los resultados en un solo JSON
            return response()->json([
                'status' => 'success',
                'data' => [
                    'grafico1' => $grafico1,
                    'grafico2' => $grafico2,
                    'grafico3' => $grafico3,
                    'grafico4' => $grafico4,
                    'grafico5' => $grafico5
                ]
            ]);
        }catch(\Exception $e){
            return response()->json([
                'status' => 'error',
                'message' => 'Error al filtrar los datos de los gráficos: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Gráfico 1: Gestionadores de solicitudes de bodegas
     * Obtiene el ranking de gestionadores de solicitudes de bodegas basado en la cantidad de solicitudes gestionadas.
     * Este método cuenta las solicitudes únicas gestionadas por cada usuario y las ordena en forma descendente.
     * Se pueden aplicar filtros opcionales de fechas para acotar el rango de solicitudes consideradas.
     *
     * @param Request $request Opcionalmente incluye 'fecha_inicio' y 'fecha_fin' para filtrar las solicitudes.
     * @return array|\Illuminate\Http\JsonResponse
    */
    public function Grafico1(Request $request)
    {
        try {
            // Obtener los parámetros de fecha del request y el ID de la oficina del usuario autenticado
            $fechaInicio = $request->input('fecha_inicio');
            $fechaFin = $request->input('fecha_fin');
            $oficinaId = Auth::user()->OFICINA_ID;

            // Ajustar la fecha de fin para que sea hasta el final del día
            if ($fechaFin) {
                $fechaFin = date('Y-m-d', strtotime($fechaFin)) . ' 23:59:59';
            }

            // Obtenemos los SOLICITUD_ID de las solicitudes de bodegas únicas que pertenecen a la oficina del usuario autenticado en base a fechas si se proporcionan.
            $solicitudesUnicas = SolicitudBodega::query()
                ->join('solicitudes', 'solicitudes_bodegas.SOLICITUD_ID', '=', 'solicitudes.SOLICITUD_ID')
                ->join('users as solicitantes', 'solicitudes.USUARIO_id', '=', 'solicitantes.id')
                ->where('solicitantes.OFICINA_ID', $oficinaId)
                ->when($fechaInicio && $fechaFin, function ($query) use ($fechaInicio, $fechaFin) {
                    return $query->whereBetween('solicitudes.created_at', [$fechaInicio, $fechaFin]);
                })
                ->select('solicitudes.SOLICITUD_ID')
                ->distinct()
                ->pluck('SOLICITUD_ID');

            // Luego, contamos las revisiones hechas a esas solicitudes únicas por cada revisor, asegurándonos de que las revisiones también caigan dentro del rango de fechas y pertenecen a la misma oficina.
            $rankingGestionadores = RevisionSolicitud::query()
                ->join('users as revisores', 'revisiones_solicitudes.USUARIO_ID', '=', 'revisores.id')
                ->whereIn('revisiones_solicitudes.SOLICITUD_ID', $solicitudesUnicas)
                ->where('revisores.OFICINA_ID', $oficinaId)
                ->when($fechaInicio && $fechaFin, function ($query) use ($fechaInicio, $fechaFin) {
                    // Aplicar el filtro de fechas a las revisiones
                    return $query->whereBetween('revisiones_solicitudes.created_at', [$fechaInicio, $fechaFin]);
                })
                ->select('revisores.id', DB::raw('CONCAT(revisores.USUARIO_NOMBRES, " ", revisores.USUARIO_APELLIDOS) as nombre_completo'), DB::raw('COUNT(revisiones_solicitudes.SOLICITUD_ID) as total_gestiones'))
                ->groupBy('revisores.id', 'revisores.USUARIO_NOMBRES', 'revisores.USUARIO_APELLIDOS')
                ->orderBy('total_gestiones', 'DESC')
                ->get();

            // Devolver la response en JSON con el status y la data
            return response()->json([
                'status' => 'success',
                'data' => $rankingGestionadores,
                'message' => 'Reporte 1 obtenido con exito.'
            ], 200);
        }catch(\Exception $e){
            return response()->json([
                'status' => 'error',
                'message' => 'Error al obtener el ranking de gestionadores: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Gráfico 2: Solicitudes de bodegas requeridos por ubicación y departamento
     * Contabiliza las solicitudes de bodegas por ubicación y departamento.
     * Este gráfico combina los conteos de solicitudes asociadas tanto a ubicaciones como a departamentos,
     * permitiendo una visión general de las áreas con mayor número de solicitudes.
     *
     * @param Request $request Contiene opcionalmente 'fecha_inicio' y 'fecha_fin' para filtrar las solicitudes.
     * @return array|\Illuminate\Http\JsonResponse
    */
    public function Grafico2(Request $request)
    {
        try {
            // Obtener los parámetros de fecha del request y el ID de la oficina del usuario autenticado
            $fechaInicio = $request->input('fecha_inicio');
            $fechaFin = $request->input('fecha_fin');
            $oficinaId = Auth::user()->OFICINA_ID;

            // Ajustar la fecha de fin para que sea hasta el final del día
            if ($fechaFin) {
                $fechaFin = date('Y-m-d', strtotime($fechaFin)) . ' 23:59:59';
            }

            // Obtenemos los SOLICITUD_ID de las solicitudes de bodegas únicas que pertenecen a la oficina del usuario autenticado en base a fechas si se proporcionan.
            $solicitudesUnicas = SolicitudBodega::query()
                ->join('solicitudes', 'solicitudes_bodegas.SOLICITUD_ID', '=', 'solicitudes.SOLICITUD_ID')
                ->join('users as solicitantes', 'solicitudes.USUARIO_id', '=', 'solicitantes.id')
                ->where('solicitantes.OFICINA_ID', $oficinaId)
                ->when($fechaInicio && $fechaFin, function ($query) use ($fechaInicio, $fechaFin) {
                    return $query->whereBetween('solicitudes.created_at', [$fechaInicio, $fechaFin]);
                })
                ->select('solicitudes.SOLICITUD_ID')
                ->distinct()
                ->pluck('SOLICITUD_ID');

            // Luego, contamos las solicitudes de bodegas hechas a esas solicitudes únicas por cada ubicación y departamento, asegurándonos de que las solicitudes también caigan dentro del rango de fechas y pertenecen a la misma oficina.

            // Consulta para obtener las solicitudes por departamento
            $departamentosQuery = Solicitud::query()
            ->join('users', 'solicitudes.USUARIO_id', '=', 'users.id')
            ->join('departamentos', 'users.DEPARTAMENTO_ID', '=', 'departamentos.DEPARTAMENTO_ID')
            ->whereIn('solicitudes.SOLICITUD_ID', $solicitudesUnicas)
            ->select('departamentos.DEPARTAMENTO_NOMBRE as entidad', DB::raw('COUNT(*) as total_solicitudes'))
            ->groupBy('departamentos.DEPARTAMENTO_NOMBRE');

            // Consulta para obtener las solicitudes por ubicación
            $ubicacionesQuery = Solicitud::query()
            ->join('users', 'solicitudes.USUARIO_id', '=', 'users.id')
            ->join('ubicaciones', 'users.UBICACION_ID', '=', 'ubicaciones.UBICACION_ID')
            ->whereIn('solicitudes.SOLICITUD_ID', $solicitudesUnicas)
            ->select('ubicaciones.UBICACION_NOMBRE as entidad', DB::raw('COUNT(*) as total_solicitudes'))
            ->groupBy('ubicaciones.UBICACION_NOMBRE');

            // Unimos los resultados de ambas consultas
            $solicitudesPorEntidad = $departamentosQuery->unionAll($ubicacionesQuery)->get();

            // Devolver la response en JSON con el status y la data
            return response()->json([
                'status' => 'success',
                'data' => $solicitudesPorEntidad,
                'message' => 'Reporte 2 obtenido con exito.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al obtener las solicitudes por entidad: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Gráfico 3: Ranking de estados de solicitudes de bodegas
     * Esta función obtiene el ranking de estados de solicitudes de bodegas.
     * Recibe opcionalmente las fechas de inicio y fin, y el ID de la oficina del usuario autenticado.
     * Realiza una consulta a la base de datos para obtener el número de solicitudes en cada estado,
     * contando las solicitudes únicas en cada estado.
     * Luego, ordena el resultado por el total de solicitudes en orden descendente.
     * Devuelve un arreglo con el ranking de estados.
     *
     * @param Request $request
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function Grafico3(Request $request)
    {
        try {
            // Obtener los parámetros de fecha del request y el ID de la oficina del usuario autenticado
            $fechaInicio = $request->input('fecha_inicio');
            $fechaFin = $request->input('fecha_fin');
            $oficinaId = Auth::user()->OFICINA_ID;

            // Ajustar la fecha de fin para que sea hasta el final del día
            if ($fechaFin) {
                $fechaFin = date('Y-m-d', strtotime($fechaFin)) . ' 23:59:59';
            }

            // Obtenemos el ranking de estados de solicitudes de bodegas basado en la cantidad de solicitudes en cada estado.
            $rankingEstados = SolicitudBodega::query()
                ->join('solicitudes', 'solicitudes_bodegas.SOLICITUD_ID', '=', 'solicitudes.SOLICITUD_ID')
                ->join('users', 'solicitudes.USUARIO_id', '=', 'users.id') // Obtenemos el ID del usuario que hizo la solicitud para filtrar por oficina
                ->where('users.OFICINA_ID', '=', $oficinaId) // Filtrar por OFICINA_ID
                ->select('solicitudes.SOLICITUD_ESTADO', DB::raw('COUNT(DISTINCT solicitudes.SOLICITUD_ID) as total_solicitudes'))
                ->when($fechaInicio && $fechaFin, function ($query) use ($fechaInicio, $fechaFin) {
                    return $query->whereBetween('solicitudes.created_at', [$fechaInicio, $fechaFin]);
                })
                ->groupBy('solicitudes.SOLICITUD_ESTADO')
                ->orderBy('total_solicitudes', 'DESC')
                ->get();

            // Devolver la response en JSON con el status y la data
            return response()->json([
                'status' => 'success',
                'data' => $rankingEstados,
                'message' => 'Reporte 3 obtenido con exito.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al obtener el ranking de estados: ' . $e->getMessage()
            ], 500);
        }
    }

        /**
     * Gráfico 4: Ranking de salas más solicitadas
     * Ranking de las salas más solicitadas.
     * Identifica las salas que han recibido la mayor cantidad de solicitudes, contando solo solicitudes únicas,
     * y las ordena por la cantidad de veces que han sido solicitadas.
     *
     * @param Request $request Contiene opcionalmente 'fecha_inicio' y 'fecha_fin' para filtrar las solicitudes.
     * @return array|\Illuminate\Http\JsonResponse
    */
    public function Grafico4(Request $request)
    {
        try {
            // Opcionalmente recibidos desde el request
            $fechaInicio = $request->input('fecha_inicio');
            $fechaFin = $request->input('fecha_fin');
            $oficinaId = Auth::user()->OFICINA_ID; // Obtener el ID de la oficina del usuario autenticado

            // Ajustar la fecha de fin para que sea hasta el final del día
            if ($fechaFin) {
                $fechaFin = date('Y-m-d', strtotime($fechaFin)) . ' 23:59:59';
            }

            $rankingBodegas = SolicitudBodega::query()
                ->join('solicitudes', 'solicitudes_bodegas.SOLICITUD_ID', '=', 'solicitudes.SOLICITUD_ID')
                ->join('bodegas', 'solicitudes_bodegas.BODEGA_ID', '=', 'bodegas.BODEGA_ID')
                ->join('users', 'solicitudes.USUARIO_id', '=', 'users.id') // Obtenemos el ID del usuario que hizo la solicitud para filtrar por oficina
                ->where('users.OFICINA_ID', '=', $oficinaId) // Filtrar por OFICINA_ID
                ->select('bodegas.BODEGA_NOMBRE', DB::raw('COUNT(DISTINCT solicitudes.SOLICITUD_ID) as total_solicitudes'))
                ->when($fechaInicio && $fechaFin, function ($query) use ($fechaInicio, $fechaFin) {
                    return $query->whereBetween('solicitudes.created_at', [$fechaInicio, $fechaFin]);
                })
                ->groupBy('bodegas.BODEGA_NOMBRE')
                ->orderBy('total_solicitudes', 'DESC')
                ->get();

            // Devolver los datos en JSON
            return response()->json([
                'status' => 'success',
                'data' => $rankingBodegas,
                'message' => 'Reporte 4 obtenido con exito.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al obtener el ranking de bodegas: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
    *   Grafico 5: PROMEDIO DE ATENCION (CREADO-EN REVISION, EN REVISION- APROBADO/RECHAZADO, APROBADO/RECHAZADO-ENTREGADO)
    *   Este método calcula el tiempo promedio que toma cada estado de la solicitud de materiales.
    * Para "creado-en revision": filtrar solicitudes de materiales por oficina y fechas, si se especifican. Y comparar la fecha de creación con la fecha de la primera revisión.
    * Para "en revision-aprobado/rechazado": filtrar solicitudes de materiales por oficina y fechas, si se especifican. Y comparar la fecha de la primera revisión con la fecha de aprobación/rechazo.
    * Para "aprobado/rechazado-entregado": filtrar solicitudes de materiales por oficina y fechas, si se especifican en estado "FINALIZADO". Y comparar la fecha de modificacion de solicitud con la fecha de aprobacion/rechazo.
    * Modelos: SolicitudMaterial, RevisionSolicitud, Solicitud
    * @param Request $request La solicitud HTTP, que puede incluir 'fecha_inicio', 'fecha_fin'.
    */
    public function Grafico5(Request $request)
    {
        try{
            // Obtener datos de la request y el ID de la oficina del usuario autenticado
            $fechaInicio = $request->input('fecha_inicio');
            $fechaFin = $request->input('fecha_fin');
            $oficinaId = Auth::user()->OFICINA_ID;

            // Ajustar la fecha de fin para que sea hasta el final del día
            if ($fechaFin) {
                $fechaFin = date('Y-m-d', strtotime($fechaFin)) . ' 23:59:59';
            }

            $promedioAtencion = SolicitudBodega::query()
                ->join('solicitudes', 'solicitudes_bodegas.SOLICITUD_ID', '=', 'solicitudes.SOLICITUD_ID')
                ->join('revisiones_solicitudes', 'solicitudes.SOLICITUD_ID', '=', 'revisiones_solicitudes.SOLICITUD_ID')
                ->join('users', 'solicitudes.USUARIO_id', '=', 'users.id')
                ->where('users.OFICINA_ID', $oficinaId)
                ->when($fechaInicio && $fechaFin, function ($query) use ($fechaInicio, $fechaFin) {
                    return $query->whereBetween('solicitudes.created_at', [$fechaInicio, $fechaFin]);
                })
                ->select(DB::raw('AVG(DATEDIFF(revisiones_solicitudes.created_at, solicitudes.created_at)) as promedio_creacion_atencion'))
                ->first();

            // Promedio atencion desde revision a aprobado/rechazado
            // Filtrar solicitudes materiales en estado "APROBADO" o "RECHAZADO"
            // Comparar fechas: fecha de creacion de la ULTIMA revisiones_solicitudes con la fecha de aprobacion/rechazo de la solicitud persé
            $promedioRevisionAprobacion = SolicitudBodega::query()
                ->join('solicitudes', 'solicitudes_bodegas.SOLICITUD_ID', '=', 'solicitudes.SOLICITUD_ID')
                ->join('revisiones_solicitudes', function ($join) {
                    $join->on('solicitudes.SOLICITUD_ID', '=', 'revisiones_solicitudes.SOLICITUD_ID')
                        ->where('revisiones_solicitudes.created_at', function ($subquery) {
                            $subquery->select(DB::raw('MAX(created_at)'))
                                ->from('revisiones_solicitudes')
                                ->whereColumn('revisiones_solicitudes.SOLICITUD_ID', '=', 'solicitudes.SOLICITUD_ID');
                        });
                })
                ->join('users', 'solicitudes.USUARIO_id', '=', 'users.id')
                ->where('users.OFICINA_ID', $oficinaId)
                ->where('solicitudes.SOLICITUD_ESTADO', 'APROBADO')
                ->orWhere('solicitudes.SOLICITUD_ESTADO', 'RECHAZADO')
                ->when($fechaInicio && $fechaFin, function ($query) use ($fechaInicio, $fechaFin) {
                    return $query->whereBetween('solicitudes.created_at', [$fechaInicio, $fechaFin]);
                })
                ->select(DB::raw('AVG(DATEDIFF(revisiones_solicitudes.created_at, solicitudes.created_at)) as promedio_revision_aprobacion'))
                ->first();

             // Promedio atencion desde creacion hasta TERMINADO, aprobado o rechazado
            // Comparar fechas: La fecha de created_at de la solicitud con la fecha de updated_at de la solicitud
            $promedioAprobacionEntrega = SolicitudBodega::query()
            ->join('solicitudes', 'solicitudes_bodegas.SOLICITUD_ID', '=', 'solicitudes.SOLICITUD_ID')
            ->join('users', 'solicitudes.USUARIO_id', '=', 'users.id')
            ->where('users.OFICINA_ID', $oficinaId)
            ->where(function ($query) {
                $query->where('solicitudes.SOLICITUD_ESTADO', 'TERMINADO')
                      ->orWhere('solicitudes.SOLICITUD_ESTADO', 'APROBADO')
                      ->orWhere('solicitudes.SOLICITUD_ESTADO', 'RECHAZADO');
            })
            ->when($fechaInicio && $fechaFin, function ($query) use ($fechaInicio, $fechaFin) {
                return $query->whereBetween('solicitudes.created_at', [$fechaInicio, $fechaFin]);
            })
            ->select(DB::raw('AVG(DATEDIFF(solicitudes.updated_at, solicitudes.created_at)) as promedio_aprobacion_entrega'))
            ->first();

            // Devolver datos en JSON con el status y la data
            return response()->json([
                'status' => 'success',
                'data' => [
                    'promedioAtencion' => $promedioAtencion,
                    'promedioRevisionAprobacion' => $promedioRevisionAprobacion,
                    'promedioAprobacionEntrega' => $promedioAprobacionEntrega
                ],
                'message' => 'Reporte 5 obtenido con exito.'
            ], 200);
        }catch(\Exception $e){
            return response()->json([
                'status' => 'error',
                'message' => 'Error al obtener el promedio de atención: ' . $e->getMessage()
            ], 500);
        }
    }
}
