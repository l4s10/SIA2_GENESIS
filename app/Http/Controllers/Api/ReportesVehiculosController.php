<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
// Modelos
use App\Models\SolicitudVehicular;
use App\Models\RevisionSolicitud;

class ReportesVehiculosController extends Controller
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
            $grafico1 = $this->Grafico1(new Request());
            $grafico2 = $this->Grafico2(new Request());
            $grafico3 = $this->Grafico3(new Request());
            $grafico4 = $this->Grafico4(new Request());
            $grafico5 = $this->Grafico5(new Request());

            //devolver
            return response()->json([
                'status' => 'success',
                'data' => [
                    'grafico1' => $grafico1,
                    'grafico2' => $grafico2,
                    'grafico3' => $grafico3,
                    'grafico4' => $grafico4,
                    'grafico5' => $grafico5,
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
     * Filtra y devuelve datos generales para los gráficos.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function filtrarGeneral(Request $request)
    {
        try{
            //Obtener datos de graficos
            $grafico1 = $this->Grafico1(new Request());
            $grafico2 = $this->Grafico2(new Request());
            $grafico3 = $this->Grafico3(new Request());
            $grafico4 = $this->Grafico4(new Request());
            $grafico5 = $this->Grafico5(new Request());


            //devolver
            return response()->json([
                'status' => 'success',
                'data' => [
                    'grafico1' => $grafico1,
                    'grafico2' => $grafico2,
                    'grafico3' => $grafico3,
                    'grafico4' => $grafico4,
                    'grafico5' => $grafico5,
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
     * Obtiene el ranking de revisores de solicitudes de vehículos.
     *
     * @param Request $request La solicitud HTTP.
     * @return array El ranking de revisores de solicitudes de vehículos.
     */
    public function Grafico1(Request $request)
    {
        try {
            $fechaInicio = $request->input('fecha_inicio');
            $fechaFin = $request->input('fecha_fin');
            $oficinaId = Auth::user()->OFICINA_ID;

            // Ajustar la fecha de fin para que sea hasta el final del día
            if ($fechaFin) {
                $fechaFin = date('Y-m-d', strtotime($fechaFin)) . ' 23:59:59';
            }

            $rankingRevisores = RevisionSolicitud::whereNotNull('revisiones_solicitudes.SOLICITUD_VEHICULO_ID')
                ->join('users', 'revisiones_solicitudes.USUARIO_id', '=', 'users.id')
                ->where('users.OFICINA_ID', $oficinaId) // Aplicar filtro de OFICINA_ID
                ->when($fechaInicio && $fechaFin, function ($query) use ($fechaInicio, $fechaFin) {
                    // Asegúrate de ajustar 'revisiones_solicitudes.created_at' si la columna de fecha se llama diferente
                    return $query->whereBetween('revisiones_solicitudes.created_at', [$fechaInicio, $fechaFin]);
                })
                ->groupBy('users.id', 'users.USUARIO_NOMBRES', 'users.USUARIO_APELLIDOS')
                ->select('users.id', DB::raw('CONCAT(users.USUARIO_NOMBRES, " ", users.USUARIO_APELLIDOS) as nombre_completo'), DB::raw('COUNT(DISTINCT revisiones_solicitudes.SOLICITUD_VEHICULO_ID) as total_gestiones'))
                ->orderBy('total_gestiones', 'desc')
                ->get();

            // Devolver la response en JSON con el status y la data
            return response()->json([
                'status' => 'success',
                'data' => $rankingRevisores,
                'message' => 'Reporte 1 obtenido con exito.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al obtener el ranking de revisores de solicitudes de vehículos: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Obtiene el ranking de departamentos y ubicaciones.
     *
     * @param Request $request La solicitud HTTP.
     * @return array El ranking de departamentos y ubicaciones.
     */
    public function Grafico2(Request $request)
    {
        try {
            $fechaInicio = $request->input('fecha_inicio');
            $fechaFin = $request->input('fecha_fin');
            $oficinaId = Auth::user()->OFICINA_ID;

            $queryDepartamentos = SolicitudVehicular::query();
            $queryUbicaciones = SolicitudVehicular::query();

            $queryDepartamentos->join('users', 'solicitudes_vehiculos.USUARIO_id', '=', 'users.id')
                ->join('departamentos', 'users.DEPARTAMENTO_ID', '=', 'departamentos.DEPARTAMENTO_ID')
                ->where('users.OFICINA_ID', $oficinaId)
                ->whereNotNull('users.DEPARTAMENTO_ID');

            $queryUbicaciones->join('users', 'solicitudes_vehiculos.USUARIO_id', '=', 'users.id')
                ->join('ubicaciones', 'users.UBICACION_ID', '=', 'ubicaciones.UBICACION_ID')
                ->where('users.OFICINA_ID', $oficinaId)
                ->whereNotNull('users.UBICACION_ID');

            if ($fechaInicio && $fechaFin) {
                $queryDepartamentos->whereBetween('solicitudes_vehiculos.created_at', [$fechaInicio, $fechaFin]);
                $queryUbicaciones->whereBetween('solicitudes_vehiculos.created_at', [$fechaInicio, $fechaFin]);
            }

            $rankingDepartamentos = $queryDepartamentos->groupBy('departamentos.DEPARTAMENTO_ID', 'departamentos.DEPARTAMENTO_NOMBRE')
                ->select('departamentos.DEPARTAMENTO_NOMBRE as entidad', DB::raw('COUNT(*) as total_solicitudes'))
                ->orderBy('total_solicitudes', 'desc')
                ->get();

            $rankingUbicaciones = $queryUbicaciones->groupBy('ubicaciones.UBICACION_ID', 'ubicaciones.UBICACION_NOMBRE')
                ->select('ubicaciones.UBICACION_NOMBRE as entidad', DB::raw('COUNT(*) as total_solicitudes'))
                ->orderBy('total_solicitudes', 'desc')
                ->get();

            // Union ALL de los dos rankings
            $rankingDepartamentosUbicaciones = $rankingDepartamentos->merge($rankingUbicaciones);

            // Devolver la response en JSON con el status y la data
            return response()->json([
                'status' => 'success',
                'data' => $rankingDepartamentosUbicaciones,
                'message' => 'Reporte 2 obtenido con exito.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al obtener el ranking de departamentos y ubicaciones: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Método para obtener el ranking de estados de solicitudes de vehículos.
     *
     * @param Request $request La solicitud HTTP entrante.
     * @return array El ranking de solicitudes de vehículos.
     */
    // Grafico 1: Estados de solicitudes de vehículos
    public function Grafico3(Request $request)
    {
        try {
            $fechaInicio = $request->input('fecha_inicio');
            $fechaFin = $request->input('fecha_fin');
            $oficinaId = Auth::user()->OFICINA_ID;

            $query = SolicitudVehicular::query();

            $query->join('users', 'solicitudes_vehiculos.USUARIO_id', '=', 'users.id')
                ->where('users.OFICINA_ID', $oficinaId);

            if ($fechaInicio && $fechaFin) {
                $query->whereBetween('solicitudes_vehiculos.created_at', [$fechaInicio, $fechaFin]);
            }


            $rankingSolicitudes = $query->groupBy('SOLICITUD_VEHICULO_ESTADO')
                ->select('SOLICITUD_VEHICULO_ESTADO as SOLICITUD_ESTADO', DB::raw('COUNT(*) as total_solicitudes'))
                ->orderBy('total_solicitudes', 'desc')
                ->get();

            // Devolver la response en JSON con el status y la data
            return response()->json([
                'status' => 'success',
                'data' => $rankingSolicitudes,
                'message' => 'Reporte 3 obtenido con exito.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al obtener el ranking de solicitudes vehiculares: '. $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Obtiene el ranking de vehículos asignados dentro de un rango de fechas y para una oficina específica.
     *
     * @param Request $request La solicitud HTTP con los parámetros de fecha_inicio y fecha_fin.
     * @return array El ranking de vehículos asignados.
     * @throws \Exception Si ocurre un error al obtener el ranking de vehículos asignados.
     */
    public function Grafico4(Request $request)
    {
        try {
            $fechaInicio = $request->input('fecha_inicio');
            $fechaFin = $request->input('fecha_fin');
            $oficinaId = Auth::user()->OFICINA_ID;

            $query = SolicitudVehicular::query();

            $query->join('users', 'solicitudes_vehiculos.USUARIO_id', '=', 'users.id')
                ->where('users.OFICINA_ID', $oficinaId);

            if ($fechaInicio && $fechaFin) {
                $query->whereBetween('solicitudes_vehiculos.created_at', [$fechaInicio, $fechaFin]);
            }


            $rankingVehiculos = $query->join('vehiculos', 'solicitudes_vehiculos.VEHICULO_ID', '=', 'vehiculos.VEHICULO_ID')
                ->groupBy('vehiculos.VEHICULO_PATENTE')
                ->select('vehiculos.VEHICULO_PATENTE', DB::raw('COUNT(*) as total_solicitudes'))
                ->orderBy('total_solicitudes', 'desc')
                ->get();

            // Devolver los datos en JSON
            return response()->json([
                'status' => 'success',
                'data' => $rankingVehiculos,
                'message' => 'Reporte 4 obtenido con exito.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al obtener el ranking de vehículos asignados: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function Grafico5(Request $request)
    {
        try {
            $fechaInicio = $request->input('fecha_inicio');
            $fechaFin = $request->input('fecha_fin');
            $oficinaId = Auth::user()->OFICINA_ID;

            // Promedio de días desde 'INGRESADO' a 'EN REVISIÓN'
            $promedioIngresadoARevision = DB::table('solicitudes_vehiculos as sv')
                ->join(DB::raw('(SELECT SOLICITUD_VEHICULO_ID, MIN(created_at) as created_at FROM revisiones_solicitudes WHERE REVISION_SOLICITUD_OBSERVACION IS NOT NULL GROUP BY SOLICITUD_VEHICULO_ID) rs'), function ($join) {
                    $join->on('sv.SOLICITUD_VEHICULO_ID', '=', 'rs.SOLICITUD_VEHICULO_ID');
                })
                ->join('users', 'sv.USUARIO_id', '=', 'users.id')
                ->where('users.OFICINA_ID', $oficinaId)
                ->whereBetween('sv.created_at', [$fechaInicio, $fechaFin])
                ->select(DB::raw('COALESCE(AVG(DATEDIFF(rs.created_at, sv.created_at)), 0) as promedio_dias_ingresado_a_revision'))
                ->first();

            // Promedio de días desde 'INGRESADO' a 'POR RENDIR'
            $promedioIngresadoARendir = DB::table('solicitudes_vehiculos as sv')
                ->join(DB::raw('(SELECT SOLICITUD_VEHICULO_ID, MAX(created_at) as created_at FROM autorizaciones GROUP BY SOLICITUD_VEHICULO_ID) au'), function ($join) {
                    $join->on('sv.SOLICITUD_VEHICULO_ID', '=', 'au.SOLICITUD_VEHICULO_ID');
                })
                ->join('users', 'sv.USUARIO_id', '=', 'users.id')
                ->where('users.OFICINA_ID', $oficinaId)
                ->whereBetween('sv.created_at', [$fechaInicio, $fechaFin])
                ->select(DB::raw('COALESCE(AVG(DATEDIFF(au.created_at, sv.created_at)), 0) as promedio_dias_ingresado_a_rendir'))
                ->first();

            // Promedio de días desde 'INGRESADO' a 'TERMINADO'
            $promedioIngresadoATerminado = DB::table('solicitudes_vehiculos as sv')
                ->join(DB::raw('(SELECT SOLICITUD_VEHICULO_ID, MAX(created_at) as created_at FROM rendiciones GROUP BY SOLICITUD_VEHICULO_ID) rn'), function ($join) {
                    $join->on('sv.SOLICITUD_VEHICULO_ID', '=', 'rn.SOLICITUD_VEHICULO_ID');
                })
                ->join('users', 'sv.USUARIO_id', '=', 'users.id')
                ->where('users.OFICINA_ID', $oficinaId)
                ->whereBetween('sv.created_at', [$fechaInicio, $fechaFin])
                ->select(DB::raw('COALESCE(AVG(DATEDIFF(rn.created_at, sv.created_at)), 0) as promedio_dias_ingresado_a_terminado'))
                ->first();

            // Devolver como response
            return response()->json([
                'status' => 'success',
                'data' => [
                    'promedioAtencion' => $promedioIngresadoARevision ? $promedioIngresadoARevision->promedio_dias_ingresado_a_revision : 0,
                    'promedioRevisionAprobacion' => $promedioIngresadoARendir ? $promedioIngresadoARendir->promedio_dias_ingresado_a_rendir : 0,
                    'promedioAprobacionEntrega' => $promedioIngresadoATerminado ? $promedioIngresadoATerminado->promedio_dias_ingresado_a_terminado : 0
                ],
                'message' => 'Reporte 5 obtenido con exito.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al obtener el promedio de días de atención: ' . $e->getMessage(),
            ], 500);
        }
    }
}
