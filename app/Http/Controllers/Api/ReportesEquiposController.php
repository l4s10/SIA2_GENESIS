<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SolicitudEquipos;
use Illuminate\Support\Facades\DB;
use Exception;

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

            // Construye la respuesta con todos los datos de los gráficos para la carga inicial
            return response()->json([
                'status' => 'success',
                'data' => [
                    'grafico1' => $rankingGestionadores,
                    'grafico2' => $solicitudesPorUbicacionDepto,
                    'grafico3' => $rankingEstados
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

            // Cada gráfico devuelve su propia estructura de datos
            $rankingGestionadores = $this->Grafico1($request);
            // Cargamos el grafico2
            $solicitudesPorUbicacionDepto = $this->Grafico2($request);
            // Cargamos el grafico3
            $rankingEstados = $this->Grafico3($request);

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

    /**
     * Grafico Equipos 1: Ranking de gestionadores de solicitudes de equipos
     */
    public function Grafico1(Request $request)
    {
        try {
            // Opcionalmente recibidos desde el request
            $fechaInicio = $request->input('fecha_inicio');
            $fechaFin = $request->input('fecha_fin');

            $rankingGestionadores = SolicitudEquipos::query()
                ->join('solicitudes', 'solicitudes_equipos.SOLICITUD_ID', '=', 'solicitudes.SOLICITUD_ID')
                ->join('revisiones_solicitudes', 'solicitudes.SOLICITUD_ID', '=', 'revisiones_solicitudes.SOLICITUD_ID')
                ->join('users', 'revisiones_solicitudes.USUARIO_ID', '=', 'users.id')
                ->select('users.id', DB::raw('CONCAT(users.USUARIO_NOMBRES, " ", users.USUARIO_APELLIDOS) as nombre_completo'), DB::raw('count(*) as total_gestiones'))
                ->when($fechaInicio && $fechaFin, function ($query) use ($fechaInicio, $fechaFin) {
                    // Se tiene una columna de tipo timestamp en 'revisiones_solicitudes' para la fecha de la revisión
                    // la cual es 'created_at', si mas adelante se llegase a cambiar. Favor actualizar el nombre de la columna aqui debajo.
                    return $query->whereBetween('revisiones_solicitudes.created_at', [$fechaInicio, $fechaFin]);
                })
                ->groupBy('users.id', 'users.USUARIO_NOMBRES', 'users.USUARIO_APELLIDOS')
                ->orderBy('total_gestiones', 'DESC')
                ->get();

            return [
                'ranking' => $rankingGestionadores
            ];
        } catch (Exception $e) {
            throw new Exception('Error al obtener el ranking de gestionadores: ' . $e->getMessage());
        }
    }

    /**
     * Grafico Equipos 2: Solicitudes de equipos por ubicación y departamento
    */
    public function Grafico2(Request $request)
    {
        try {
            // Opcionalmente recibidos desde el request
            $fechaInicio = $request->input('fecha_inicio');
            $fechaFin = $request->input('fecha_fin');

            // Subconsulta para departamentos
            $departamentosQuery = SolicitudEquipos::query()
                ->join('solicitudes', 'solicitudes_equipos.SOLICITUD_ID', '=', 'solicitudes.SOLICITUD_ID')
                ->join('users', 'solicitudes.USUARIO_id', '=', 'users.id')
                ->join('departamentos', 'users.DEPARTAMENTO_ID', '=', 'departamentos.DEPARTAMENTO_ID')
                ->select('departamentos.DEPARTAMENTO_NOMBRE as entidad', DB::raw('COUNT(*) as total_solicitudes'))
                ->whereNotNull('users.DEPARTAMENTO_ID')
                ->groupBy('departamentos.DEPARTAMENTO_NOMBRE');

            // Subconsulta para ubicaciones
            $ubicacionesQuery = SolicitudEquipos::query()
                ->join('solicitudes', 'solicitudes_equipos.SOLICITUD_ID', '=', 'solicitudes.SOLICITUD_ID')
                ->join('users', 'solicitudes.USUARIO_id', '=', 'users.id')
                ->join('ubicaciones', 'users.UBICACION_ID', '=', 'ubicaciones.UBICACION_ID')
                ->select('ubicaciones.UBICACION_NOMBRE as entidad', DB::raw('COUNT(*) as total_solicitudes'))
                ->whereNotNull('users.UBICACION_ID')
                ->groupBy('ubicaciones.UBICACION_NOMBRE');

            // Combinar los resultados
            $solicitudesPorEntidad = $departamentosQuery->union($ubicacionesQuery);

            // Aplicar filtro de fechas si están presentes
            if ($fechaInicio && $fechaFin) {
                $solicitudesPorEntidad = $solicitudesPorEntidad->whereBetween('solicitudes.created_at', [$fechaInicio, $fechaFin]);
            }

            // Obtener los resultados
            $solicitudesPorEntidad = $solicitudesPorEntidad->get();

            return [
                'solicitudesPorEntidad' => $solicitudesPorEntidad
            ];
        } catch (Exception $e) {
            throw new Exception('Error al obtener las solicitudes por ubicación y departamento: ' . $e->getMessage());
        }
    }

    /**
     * Grafico Equipos 3: Ranking de estados de solicitudes de equipos
    */
    public function Grafico3(Request $request)
    {
        try {
            // Opcionalmente recibidos desde el request
            $fechaInicio = $request->input('fecha_inicio');
            $fechaFin = $request->input('fecha_fin');

            $rankingEstados = SolicitudEquipos::query()
                ->join('solicitudes', 'solicitudes_equipos.SOLICITUD_ID', '=', 'solicitudes.SOLICITUD_ID')
                ->select('solicitudes.SOLICITUD_ESTADO', DB::raw('count(*) as total_solicitudes'))
                ->when($fechaInicio && $fechaFin, function ($query) use ($fechaInicio, $fechaFin) {
                    return $query->whereBetween('solicitudes.created_at', [$fechaInicio, $fechaFin]);
                })
                ->groupBy('solicitudes.SOLICITUD_ESTADO')
                ->orderBy('total_solicitudes', 'DESC')
                ->get();

            return [
                'rankingEstados' => $rankingEstados
            ];
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al obtener el ranking de estados: ' . $e->getMessage()
            ], 500);
        }
    }
}
