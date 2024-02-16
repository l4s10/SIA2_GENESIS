<?php

namespace App\Http\Controllers\Api;
// Importacion de librerias necesarias
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
// Importacion de modelos necesarios
use App\Models\Solicitud;
use App\Models\SolicitudSala;
use App\Models\RevisionSolicitud;

class ReportesSalasController extends Controller
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
        try {
            // Asumiendo que las funciones de gráfico pueden manejar una llamada sin parámetros de fecha
            // y devolver todos los datos relevantes en ese caso
            $rankingGestionadores = $this->Grafico1(new Request());
            $solicitudesPorUbicacionDepto = $this->Grafico2(new Request());
            $rankingEstados = $this->Grafico3(new Request());
            $rankingSalasSolicitadas = $this->Grafico4(new Request());
            $promedioAtencion = $this->Grafico5(new Request());

            // Construye la respuesta con todos los datos de los gráficos para la carga inicial
            return response()->json([
                'status' => 'success',
                'data' => [
                    'grafico1' => $rankingGestionadores,
                    'grafico2' => $solicitudesPorUbicacionDepto,
                    'grafico3' => $rankingEstados,
                    'grafico4' => $rankingSalasSolicitadas,
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
     * Filtra los datos de los gráficos por un rango de fechas especificado.
     * Esta función extrae las fechas de inicio y fin del request y aplica estos filtros a cada gráfico individualmente.
     * Los resultados filtrados de cada gráfico son luego agrupados y devueltos en una sola respuesta JSON.
     *
     * @param Request $request Contiene las fechas de inicio y fin para filtrar los datos.
     * @return \Illuminate\Http\JsonResponse
    */
    public function filtrarGeneral(Request $request)
    {
        try {
            // Extraer fechas del request
            $fechaInicio = $request->input('fecha_inicio');
            $fechaFin = $request->input('fecha_fin');

            // Cada gráfico devuelve su propia estructura de datos
            $rankingGestionadores = $this->Grafico1($request);
            // Cargamos el grafico2
            $solicitudesPorUbicacionDepto = $this->Grafico2($request);
            // Cargamos el grafico3
            $rankingEstados = $this->Grafico3($request);
            // Retornamos en JSON la data filtrada de los gráficos
            // Ahora 'data' contiene un array con todos los datos de los gráficos
            return response()->json([
                'status' => 'success',
                'data' => [
                    'grafico1' => $rankingGestionadores,
                    'grafico2' => $solicitudesPorUbicacionDepto,
                    'grafico3' => $rankingEstados
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al filtrar los reportes: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Gráfico 1: Gestionadores de solicitudes de salas
     * Obtiene el ranking de gestionadores de solicitudes de salas basado en la cantidad de solicitudes gestionadas.
     * Este método cuenta las solicitudes únicas gestionadas por cada usuario y las ordena en forma descendente.
     * Se pueden aplicar filtros opcionales de fechas para acotar el rango de solicitudes consideradas.
     *
     * @param Request $request Opcionalmente incluye 'fecha_inicio' y 'fecha_fin' para filtrar las solicitudes.
     * @return array|\Illuminate\Http\JsonResponse
    */
    public function Grafico1(Request $request)
    {
        try{
            $fechaInicio = $request->input('fecha_inicio');
            $fechaFin = $request->input('fecha_fin');
            $oficinaId = Auth::user()->OFICINA_ID; // Obtener el ID de la oficina del usuario autenticado

            // Obtenemos los SOLICITUD_ID de las solicitudes de materiales únicas que pertenecen a la oficina del usuario autenticado en base a fechas si se proporcionan.
            $solicitudesUnicas = SolicitudSala::query()
                ->join('solicitudes', 'solicitudes_salas.SOLICITUD_ID', '=', 'solicitudes.SOLICITUD_ID')
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

            return [
                'ranking' => $rankingGestionadores
            ];
        }catch(\Exception $e){
            return response()->json([
                'status' => 'error',
                'message' => 'Error al obtener el ranking de gestionadores: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Gráfico 2: Solicitudes de salas requeridos por ubicación y departamento
     * Contabiliza las solicitudes de salas por ubicación y departamento.
     * Este gráfico combina los conteos de solicitudes asociadas tanto a ubicaciones como a departamentos,
     * permitiendo una visión general de las áreas con mayor número de solicitudes.
     *
     * @param Request $request Contiene opcionalmente 'fecha_inicio' y 'fecha_fin' para filtrar las solicitudes.
     * @return array|\Illuminate\Http\JsonResponse
    */
    public function Grafico2(Request $request)
    {
        try {
            // Opcionalmente recibidos desde el request
            $fechaInicio = $request->input('fecha_inicio');
            $fechaFin = $request->input('fecha_fin');
            $oficinaId = Auth::user()->OFICINA_ID; // Obtener el ID de la oficina del usuario autenticado

            // Obtener los IDs de solicitudes únicos que cumplen con los criterios de fecha y oficina
            $solicitudesUnicas = SolicitudSala::query()
            ->join('solicitudes', 'solicitudes_salas.SOLICITUD_ID', '=', 'solicitudes.SOLICITUD_ID')
            ->join('users as solicitantes', 'solicitudes.USUARIO_id', '=', 'solicitantes.id')
            ->where('solicitantes.OFICINA_ID', $oficinaId)
            ->when($fechaInicio && $fechaFin, function ($query) use ($fechaInicio, $fechaFin) {
                return $query->whereBetween('solicitudes.created_at', [$fechaInicio, $fechaFin]);
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

            // Devolver la data
            return [
                'solicitudesPorEntidad' => $solicitudesPorEntidad
            ];
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al obtener las solicitudes por entidad: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Gráfico 3: Ranking de estados de solicitudes de salas
     * Esta función obtiene el ranking de estados de solicitudes de salas.
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
            // Opcionalmente recibidos desde el request
            $fechaInicio = $request->input('fecha_inicio');
            $fechaFin = $request->input('fecha_fin');
            $oficinaId = Auth::user()->OFICINA_ID; // Obtener el ID de la oficina del usuario autenticado

            $rankingEstados = SolicitudSala::query()
                ->join('solicitudes', 'solicitudes_salas.SOLICITUD_ID', '=', 'solicitudes.SOLICITUD_ID')
                ->join('users', 'solicitudes.USUARIO_id', '=', 'users.id') // Obtenemos el ID del usuario que hizo la solicitud para filtrar por oficina
                ->where('users.OFICINA_ID', '=', $oficinaId) // Filtrar por OFICINA_ID
                ->select('solicitudes.SOLICITUD_ESTADO', DB::raw('COUNT(DISTINCT solicitudes.SOLICITUD_ID) as total_solicitudes'))
                ->when($fechaInicio && $fechaFin, function ($query) use ($fechaInicio, $fechaFin) {
                    return $query->whereBetween('solicitudes.created_at', [$fechaInicio, $fechaFin]);
                })
                ->groupBy('solicitudes.SOLICITUD_ESTADO')
                ->orderBy('total_solicitudes', 'DESC')
                ->get();

            return [
                'rankingEstados' => $rankingEstados
            ];
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

            $rankingSalas = SolicitudSala::query()
                ->join('solicitudes', 'solicitudes_salas.SOLICITUD_ID', '=', 'solicitudes.SOLICITUD_ID')
                ->join('salas', 'solicitudes_salas.SALA_ID', '=', 'salas.SALA_ID')
                ->join('users', 'solicitudes.USUARIO_id', '=', 'users.id') // Obtenemos el ID del usuario que hizo la solicitud para filtrar por oficina
                ->where('users.OFICINA_ID', '=', $oficinaId) // Filtrar por OFICINA_ID
                ->select('salas.SALA_NOMBRE', DB::raw('COUNT(DISTINCT solicitudes.SOLICITUD_ID) as total_solicitudes'))
                ->when($fechaInicio && $fechaFin, function ($query) use ($fechaInicio, $fechaFin) {
                    return $query->whereBetween('solicitudes.created_at', [$fechaInicio, $fechaFin]);
                })
                ->groupBy('salas.SALA_NOMBRE')
                ->orderBy('total_solicitudes', 'DESC')
                ->get();

            return [
                'rankingSalasSolicitadas' => $rankingSalas
            ];
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al obtener el ranking de salas: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Gráfico 5: Promedio de atención de solicitudes de salas.
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

            // Realiza la consulta a la base de datos
            $promedioAtencion = SolicitudSala::query()
                ->join('solicitudes', 'solicitudes_salas.SOLICITUD_ID', '=', 'solicitudes.SOLICITUD_ID')
                ->join('revisiones_solicitudes', 'solicitudes.SOLICITUD_ID', '=', 'revisiones_solicitudes.SOLICITUD_ID')
                ->join('users', 'solicitudes.USUARIO_id', '=', 'users.id')
                ->where('users.OFICINA_ID', $oficinaId)
                ->when($fechaInicio && $fechaFin, function ($query) use ($fechaInicio, $fechaFin) {
                    return $query->whereBetween('solicitudes.created_at', [$fechaInicio, $fechaFin]);
                })
                ->select(DB::raw('AVG(DATEDIFF(revisiones_solicitudes.created_at, solicitudes.created_at)) as promedio_atencion'))
                ->first();

            return [
                'promedioAtencion' => $promedioAtencion
            ];
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al obtener el promedio de atención de solicitudes: ' . $e->getMessage()
            ], 500);
        }
    }
}
