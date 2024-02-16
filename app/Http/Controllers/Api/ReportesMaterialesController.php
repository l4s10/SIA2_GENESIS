<?php

namespace App\Http\Controllers\Api;
// Importacion de clases y demas
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
// Importamos modelos
use App\Models\Solicitud;
use App\Models\SolicitudMaterial;
use App\Models\RevisionSolicitud;

class ReportesMaterialesController extends Controller
{
    public function home()
    {
        return response()->json([
            'status' => 'success',
            'message' => 'Bienvenido a la API de reportes de materiales. MEOW MEOW NIGGA'
        ]);
    }
    //Obtener general GET para la primera carga
    public function getGraficos()
    {
        try {
            // Asumiendo que las funciones de gráfico pueden manejar una llamada sin parámetros de fecha
            // y devolver todos los datos relevantes en ese caso
            $rankingGestionadores = $this->Grafico1(new Request());
            $solicitudesPorUbicacionDepto = $this->Grafico2(new Request());
            $rankingEstados = $this->Grafico3(new Request());

            // Construye la respuesta con todos los datos de los gráficos para la carga inicial
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
     *   Grafico materiales 1: RANKING DE GESTIONADORES DE SOLICITUDES DE MATERIALES
     * Genera un ranking de gestores de solicitudes de materiales basado en la cantidad de solicitudes gestionadas.
     * Este método filtra las solicitudes por fechas y oficina del usuario autenticado para asegurar relevancia.
     *
     * La consulta se construye de la siguiente manera:
     * 1. Se unen las tablas 'solicitudes_materiales', 'solicitudes', y 'users' (como 'solicitantes') para obtener las solicitudes de materiales.
     * 2. Se filtra por 'OFICINA_ID' para incluir solo las solicitudes de la oficina del usuario autenticado.
     * 3. Se aplica un filtro de fecha, si se proporcionan, para limitar las solicitudes al rango de fechas especificado.
     * 4. Se realiza una selección distinta de 'SOLICITUD_ID' para evitar duplicados y contar cada solicitud una sola vez.
     * 5. Se unen las tablas 'revisiones_solicitudes' y 'users' (como 'revisores') para contar las revisiones hechas a las solicitudes filtradas.
     * 6. Se asegura que las revisiones también pertenezcan a la misma oficina del usuario autenticado.
     * 7. Se agrupa el resultado por 'revisores.id' para contar las gestiones únicas realizadas por cada gestor.
     * 8. Se ordena el resultado por el total de gestiones en orden descendente para obtener el ranking.
     *
     * @param Request $request Contiene 'fecha_inicio', 'fecha_fin' y el ID de oficina del usuario autenticado.
     * @return array|\Illuminate\Http\JsonResponse
    */
    public function Grafico1(Request $request)
    {
        try {
            $fechaInicio = $request->input('fecha_inicio');
            $fechaFin = $request->input('fecha_fin');
            $oficinaId = Auth::user()->OFICINA_ID; // Obtener el ID de la oficina del usuario autenticado

            // Obtenemos los SOLICITUD_ID de las solicitudes de materiales únicas que pertenecen a la oficina del usuario autenticado en base a fechas si se proporcionan.
            $solicitudesUnicas = SolicitudMaterial::query()
                ->join('solicitudes', 'solicitudes_materiales.SOLICITUD_ID', '=', 'solicitudes.SOLICITUD_ID')
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
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al obtener el ranking de gestionadores: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
    *   Grafico materiales 2: SOLICITUDES DE MATERIALES DE MATERIALES REQUERIDOS POR DEPARTAMENTO / UNIDAD
    * Obtiene el número de solicitudes de materiales por departamento o unidad, contando las solicitudes únicas.
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

            // Obtener los IDs de solicitudes únicos que cumplen con los criterios de fecha y oficina
            $solicitudesUnicas = SolicitudMaterial::query()
            ->join('solicitudes', 'solicitudes_materiales.SOLICITUD_ID', '=', 'solicitudes.SOLICITUD_ID')
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
     * Grafico 3: RANKING DE ESTADOS DE SOLICITUDES DE MATERIALES
     * Genera un ranking de los estados de las solicitudes de materiales, mostrando la cantidad de solicitudes en cada estado.
     * Filtra por la oficina del usuario autenticado y aplica un filtro de fechas si se proporcionan.
     *
     * La consulta:
     * 1. Filtra las solicitudes de materiales por oficina y, opcionalmente, por fechas.
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

            $rankingEstados = SolicitudMaterial::query()
                ->join('solicitudes', 'solicitudes_materiales.SOLICITUD_ID', '=', 'solicitudes.SOLICITUD_ID')
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

}
