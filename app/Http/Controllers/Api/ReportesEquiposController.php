<?php

namespace App\Http\Controllers\Api;
// Importaciones de librerias necesarias para las QUERYS
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Exception;
// Importamos modelos
use App\Models\Solicitud;
use App\Models\SolicitudEquipos;
use App\Models\RevisionSolicitud;

class ReportesEquiposController extends Controller
{
    //Obtener general GET para la primera carga
    public function getGraficos()
    {
        try {
            // Asumiendo que las funciones de gráfico pueden manejar una llamada sin parámetros de fecha
            // y devolver todos los datos relevantes en ese caso
            $rankingGestionadores = $this->Grafico1(new Request());
            $solicitudesPorUbicacionDepto = $this->Grafico2(new Request());
            $rankingEstados = $this->Grafico3(new Request());
            $rankingTiposEquipos = $this->Grafico4(new Request());
            $promedioAtencion = $this->Grafico5(new Request());
            // Construye la respuesta con todos los datos de los gráficos para la carga inicial
            return response()->json([
                'status' => 'success',
                'data' => [
                    'grafico1' => $rankingGestionadores,
                    'grafico2' => $solicitudesPorUbicacionDepto,
                    'grafico3' => $rankingEstados,
                    'grafico4' => $rankingTiposEquipos,
                    'grafico5' => $promedioAtencion
                ]
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al obtener los datos de los gráficos: ' . $e->getMessage()
            ], 500);
        }
    }

    // Filtrar general
    public function filtrarGeneral(Request $request)
    {
        try {
            // Extraer fechas del request
            $fechaInicio = $request->input('fecha_inicio');
            $fechaFin = $request->input('fecha_fin');

            // Ajustar la fecha de fin para que sea hasta el final del día
            if ($fechaFin) {
                $fechaFin = date('Y-m-d', strtotime($fechaFin)) . ' 23:59:59';
            }

            // Cada gráfico devuelve su propia estructura de datos
            $rankingGestionadores = $this->Grafico1($request);
            // Cargamos el grafico2
            $solicitudesPorUbicacionDepto = $this->Grafico2($request);
            // Cargamos el grafico3
            $rankingEstados = $this->Grafico3($request);
            // Cargamos el grafico4
            $rankingTiposEquipos = $this->Grafico4($request);
            // Cargamos el grafico5
            $promedioAtencion = $this->Grafico5($request);

            // Construye la respuesta con todos los datos de los gráficos para la carga inicial
            return response()->json([
                'status' => 'success',
                'data' => [
                    'grafico1' => $rankingGestionadores,
                    'grafico2' => $solicitudesPorUbicacionDepto,
                    'grafico3' => $rankingEstados,
                    'grafico4' => $rankingTiposEquipos,
                    'grafico5' => $promedioAtencion
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
     * Grafico Equipos 1: Ranking de gestionadores de solicitudes de equipos
     * Obtiene el ranking de gestores de solicitudes de equipos, basado en el número total de gestiones realizadas.
     * Este método considera únicamente las solicitudes que pertenecen a la misma oficina del usuario autenticado
     * y aplica filtros de fecha si se proporcionan.
     *
     * La consulta se realiza en varios pasos:
     * 1. Selecciona las solicitudes de equipos filtrando por oficina y fechas, si se especifican.
     * 2. Cuenta el número de gestiones (revisiones) realizadas por cada gestor (usuario que revisa la solicitud).
     * 3. Agrupa los resultados por gestor y ordena de forma descendente por el total de gestiones para obtener el ranking.
     *
     * @param Request $request La solicitud HTTP, que puede incluir 'fecha_inicio', 'fecha_fin'.
     * @return \Illuminate\Http\JsonResponse Un objeto JSON que contiene el ranking de gestores.
    */
    public function Grafico1(Request $request)
    {
        try {
            $fechaInicio = $request->input('fecha_inicio');
            $fechaFin = $request->input('fecha_fin');
            $oficinaId = Auth::user()->OFICINA_ID; // Obtener el ID de la oficina del usuario autenticado

            // Ajustar la fecha de fin para que sea hasta el final del día
            if ($fechaFin) {
                $fechaFin = date('Y-m-d', strtotime($fechaFin)) . ' 23:59:59';
            }

            // Obtenemos los SOLICITUD_ID de las solicitudes de materiales únicas que pertenecen a la oficina del usuario autenticado en base a fechas si se proporcionan.
            $solicitudesUnicas = SolicitudEquipos::query()
                ->join('solicitudes', 'solicitudes_equipos.SOLICITUD_ID', '=', 'solicitudes.SOLICITUD_ID')
                ->join('users as solicitantes', 'solicitudes.USUARIO_id', '=', 'solicitantes.id')
                ->where('solicitantes.OFICINA_ID', $oficinaId)
                ->when($fechaInicio && $fechaFin, function ($query) use ($fechaInicio, $fechaFin) {
                    return $query->whereBetween(DB::raw('DATE(solicitudes.created_at)'), [$fechaInicio, $fechaFin]);
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
                    return $query->whereBetween(DB::raw('DATE(revisiones_solicitudes.created_at)'), [$fechaInicio, $fechaFin]);
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
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al obtener el ranking de gestionadores: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Grafico Equipos 2: Solicitudes de equipos por ubicación y departamento
    * Obtiene el número de solicitudes de equipos por ubicación o departamento, contando las solicitudes únicas.
    * Filtra las solicitudes por oficina del usuario autenticado y, opcionalmente, por un rango de fechas.
    *
    * Realiza dos subconsultas:
    * 1. Una para contar solicitudes por departamento.
    * 2. Otra para contar solicitudes por unidad.
    * Luego, combina los resultados de ambas subconsultas.
    *
    * @param Request $request La solicitud HTTP, que puede incluir 'fecha_inicio', 'fecha_fin'.
    * @return \Illuminate\Http\JsonResponse Un objeto JSON que contiene las solicitudes por departamento y unidad.
    */
    public function Grafico2(Request $request)
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

            // Obtener los IDs de solicitudes únicos que cumplen con los criterios de fecha y oficina
            $solicitudesUnicas = SolicitudEquipos::query()
            ->join('solicitudes', 'solicitudes_equipos.SOLICITUD_ID', '=', 'solicitudes.SOLICITUD_ID')
            ->join('users as solicitantes', 'solicitudes.USUARIO_id', '=', 'solicitantes.id')
            ->where('solicitantes.OFICINA_ID', $oficinaId)
            ->when($fechaInicio && $fechaFin, function ($query) use ($fechaInicio, $fechaFin) {
                return $query->whereBetween(DB::raw('solicitudes.created_at'), [$fechaInicio, $fechaFin]);
            })
            ->select('solicitudes.SOLICITUD_ID')
            ->distinct()
            ->pluck('SOLICITUD_ID');


            // Ahora, contamos las solicitudes únicas por departamento o ubicación
            // Subconsulta para departamentos, aplicando el filtro de solicitudes únicas
            $departamentosQuery = Solicitud::query()
            ->join('users', 'solicitudes.USUARIO_id', '=', 'users.id')
            ->join('departamentos', 'users.DEPARTAMENTO_ID', '=', 'departamentos.DEPARTAMENTO_ID')
            ->whereIn('solicitudes.SOLICITUD_ID', $solicitudesUnicas)
            ->select('departamentos.DEPARTAMENTO_NOMBRE as entidad', DB::raw('COUNT(*) as total_solicitudes'))
            ->groupBy('departamentos.DEPARTAMENTO_NOMBRE');


            // Subconsulta para ubicaciones, aplicando el filtro de solicitudes únicas
            $ubicacionesQuery = Solicitud::query()
                ->join('users', 'solicitudes.USUARIO_id', '=', 'users.id')
                ->join('ubicaciones', 'users.UBICACION_ID', '=', 'ubicaciones.UBICACION_ID')
                ->whereIn('solicitudes.SOLICITUD_ID', $solicitudesUnicas)
                ->select('ubicaciones.UBICACION_NOMBRE as entidad', DB::raw('COUNT(*) as total_solicitudes'))
                ->groupBy('ubicaciones.UBICACION_NOMBRE');

            // Combinar los resultados de departamentos y ubicaciones
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
     * Grafico Equipos 3: Ranking de estados de solicitudes de equipos
     * Genera un ranking de los estados de las solicitudes de equipos, mostrando la cantidad de solicitudes en cada estado.
     * Filtra por la oficina del usuario autenticado y aplica un filtro de fechas si se proporcionan.
     *
     * La consulta:
     * 1. Filtra las solicitudes de equipos por oficina y, opcionalmente, por fechas.
     * 2. Agrupa las solicitudes por estado y cuenta el número de solicitudes en cada estado.
     * 3. Ordena los resultados por la cantidad de solicitudes de forma descendente.
     *
     * @param Request $request La solicitud HTTP, que puede incluir 'fecha_inicio', 'fecha_fin'.
     * @return \Illuminate\Http\JsonResponse Un objeto JSON que contiene el ranking de estados de solicitudes.
    */
    public function Grafico3(Request $request)
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

            $rankingEstados = SolicitudEquipos::query()
                ->join('solicitudes', 'solicitudes_equipos.SOLICITUD_ID', '=', 'solicitudes.SOLICITUD_ID')
                ->join('users', 'solicitudes.USUARIO_id', '=', 'users.id') // Obtenemos a los usuarios que hicieron las solicitudes para filtrar por OFICINA_ID
                ->where('users.OFICINA_ID', '=', $oficinaId) // Filtrar por OFICINA_ID
                ->select('solicitudes.SOLICITUD_ESTADO', DB::raw('COUNT(DISTINCT solicitudes.SOLICITUD_ID) as total_solicitudes'))
                ->when($fechaInicio && $fechaFin, function ($query) use ($fechaInicio, $fechaFin) {
                    return $query->whereBetween(DB::raw('solicitudes.created_at'), [$fechaInicio, $fechaFin]);
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
     * Grafico Equipos 4: Ranking de tipos de equipos más solicitados.
     * Este método calcula el número de solicitudes por tipo de equipo,
     * filtrando por la oficina del usuario autenticado y aplicando un rango de fechas si se proporciona.
     *
     * La consulta se realiza en pasos:
     * 1. Selecciona las solicitudes de equipos por tipo, filtrando por oficina y fechas, si se especifican.
     * 2. Cuenta el número de solicitudes realizadas por cada tipo de equipo.
     * 3. Agrupa los resultados por tipo de equipo y ordena de forma descendente por el total de solicitudes para obtener el ranking.
     *
     * @param Request $request La solicitud HTTP, que puede incluir 'fecha_inicio', 'fecha_fin'.
     * @return \Illuminate\Http\JsonResponse Un objeto JSON que contiene el ranking de tipos de equipos más solicitados.
     */
    public function Grafico4(Request $request)
    {
        try {
            $fechaInicio = $request->input('fecha_inicio');
            $fechaFin = $request->input('fecha_fin');
            $oficinaId = Auth::user()->OFICINA_ID; // Obtener el ID de la oficina del usuario autenticado

            // Ajustar la fecha de fin para que sea hasta el final del día
            if ($fechaFin) {
                $fechaFin = date('Y-m-d', strtotime($fechaFin)) . ' 23:59:59';
            }

            // Realiza la consulta a la base de datos
            $rankingTiposEquipos = SolicitudEquipos::query()
                ->join('solicitudes', 'solicitudes_equipos.SOLICITUD_ID', '=', 'solicitudes.SOLICITUD_ID')
                ->join('tipos_equipos', 'solicitudes_equipos.TIPO_EQUIPO_ID', '=', 'tipos_equipos.TIPO_EQUIPO_ID')
                ->join('users', 'solicitudes.USUARIO_id', '=', 'users.id')
                ->where('users.OFICINA_ID', $oficinaId)
                ->when($fechaInicio && $fechaFin, function ($query) use ($fechaInicio, $fechaFin) {
                    return $query->whereBetween(DB::raw('solicitudes.created_at'), [$fechaInicio, $fechaFin]);
                })
                ->select('tipos_equipos.TIPO_EQUIPO_NOMBRE', DB::raw('COUNT(solicitudes_equipos.TIPO_EQUIPO_ID) as total_solicitudes'))
                ->groupBy('tipos_equipos.TIPO_EQUIPO_NOMBRE')
                ->orderBy('total_solicitudes', 'DESC')
                ->get();

            return response()->json([
                'status' => 'success',
                'data' => $rankingTiposEquipos,
                'message' => 'Reporte 4 obtenido con exito.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al obtener el ranking de tipos de equipos más solicitados: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Gráfico 5: Promedio de atención de solicitudes de equipos.
     *
     * Obtiene el promedio de diferencias en días entre la creación de solicitudes y su primera revisión,
     * filtrando por la oficina del usuario autenticado, fechas opcionales y tratando las solicitudes como únicas.
     *
     * @param Request $request Puede incluir 'fecha_inicio' y 'fecha_fin' para filtrar las solicitudes.
     * @return JsonResponse Retorna un JSON con el promedio de días de atención de solicitudes, filtrado por oficina y fechas opcionales.
    */
    public function Grafico5(Request $request)
    {
        try {
            $fechaInicio = $request->input('fecha_inicio');
            $fechaFin = $request->input('fecha_fin');
            $oficinaId = Auth::user()->OFICINA_ID; // Obtener el ID de la oficina del usuario autenticado

            // Ajustar la fecha de fin para que sea hasta el final del día
            if ($fechaFin) {
                $fechaFin = date('Y-m-d', strtotime($fechaFin)) . ' 23:59:59';
            }

            // Realiza la consulta a la base de datos
            $promedioAtencion = SolicitudEquipos::query()
                ->join('solicitudes', 'solicitudes_equipos.SOLICITUD_ID', '=', 'solicitudes.SOLICITUD_ID')
                ->join('revisiones_solicitudes', 'solicitudes.SOLICITUD_ID', '=', 'revisiones_solicitudes.SOLICITUD_ID')
                ->join('users', 'solicitudes.USUARIO_id', '=', 'users.id')
                ->where('users.OFICINA_ID', $oficinaId)
                ->when($fechaInicio && $fechaFin, function ($query) use ($fechaInicio, $fechaFin) {
                    return $query->whereBetween(DB::raw('solicitudes.created_at'), [$fechaInicio, $fechaFin]);
                })
                ->select(DB::raw('AVG(DATEDIFF(revisiones_solicitudes.created_at, solicitudes.created_at)) as promedio_creacion_atencion'))
                ->first();

            // Promedio atencion desde revision a aprobado/rechazado
            // Filtrar solicitudes de equipos en estado "APROBADO" o "RECHAZADO"
            // Comparar fechas: fecha de creacion de la ULTIMA revisiones_solicitudes con la fecha de aprobacion/rechazo de la solicitud persé
            $promedioRevisionAprobacion = SolicitudEquipos::query()
                ->join('solicitudes', 'solicitudes_equipos.SOLICITUD_ID', '=', 'solicitudes.SOLICITUD_ID')
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
                    return $query->whereBetween(DB::raw('solicitudes.created_at'), [$fechaInicio, $fechaFin]);
                })
                ->select(DB::raw('AVG(DATEDIFF(solicitudes.SOLICITUD_FECHA_HORA_INICIO_ASIGNADA, revisiones_solicitudes.created_at)) as promedio_revision_aprobacion'))
                ->first();

            // Promedio atencion desde a"APROBADO"/"RECHAZADO" a "TERMINADO"
            // Filtrar solicitudes materiales en estado "TERMINADO"
            // Comparar fechas: La fecha de SOLICITUD_FECHA_HORA_INICIO_ASIGNADA de la solicitud. Con la fecha de modificacion de la solicitud "updated_at".
            $promedioAprobacionEntrega = SolicitudEquipos::query()
                ->join('solicitudes', 'solicitudes_equipos.SOLICITUD_ID', '=', 'solicitudes.SOLICITUD_ID')
                ->join('users', 'solicitudes.USUARIO_id', '=', 'users.id')
                ->where('users.OFICINA_ID', $oficinaId)
                ->where('solicitudes.SOLICITUD_ESTADO', 'TERMINADO')
                ->when($fechaInicio && $fechaFin, function ($query) use ($fechaInicio, $fechaFin) {
                    return $query->whereBetween(DB::raw('solicitudes.created_at'), [$fechaInicio, $fechaFin]);
                })
                ->select(DB::raw('AVG(DATEDIFF(solicitudes.updated_at, solicitudes.SOLICITUD_FECHA_HORA_INICIO_ASIGNADA)) as promedio_aprobacion_entrega'))
                ->first();

            return response()->json([
                'status' => 'success',
                'data' => [
                    'promedioAtencion' => $promedioAtencion,
                    'promedioRevisionAprobacion' => $promedioRevisionAprobacion,
                    'promedioAprobacionEntrega' => $promedioAprobacionEntrega
                ],
                'message' => 'Reporte 5 obtenido con exito.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al obtener el promedio de atención de solicitudes: ' . $e->getMessage()
            ], 500);
        }
    }
}
