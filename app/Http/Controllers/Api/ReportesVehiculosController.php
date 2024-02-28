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
            $grafico1 = $this->rankingRevisoresSolicitudesVehiculos(new Request());
            $grafico2 = $this->rankingDepartamentosUbicaciones(new Request());
            $grafico3 = $this->rankingSolicitudes(new Request());
            $grafico4 = $this->rankingVehiculosAsignados(new Request());

            //devolver
            return response()->json([
                'status' => 'success',
                'data' => [
                    'grafico1' => $grafico1,
                    'grafico2' => $grafico2,
                    'grafico3' => $grafico3,
                    'grafico4' => $grafico4,
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
            $grafico1 = $this->rankingRevisoresSolicitudesVehiculos($request);
            $grafico2 = $this->rankingDepartamentosUbicaciones($request);
            $grafico3 = $this->rankingSolicitudes($request);
            $grafico4 = $this->rankingVehiculosAsignados($request);


            //devolver
            return response()->json([
                'status' => 'success',
                'data' => [
                    'grafico1' => $grafico1,
                    'grafico2' => $grafico2,
                    'grafico3' => $grafico3,
                    'grafico4' => $grafico4,
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
    public function rankingRevisoresSolicitudesVehiculos(Request $request)
    {
        try {
            $fechaInicio = $request->input('fecha_inicio');
            $fechaFin = $request->input('fecha_fin');
            $oficinaId = Auth::user()->OFICINA_ID;

            $rankingRevisores = RevisionSolicitud::whereNotNull('revisiones_solicitudes.SOLICITUD_VEHICULO_ID')
                ->join('users', 'revisiones_solicitudes.USUARIO_id', '=', 'users.id')
                ->where('users.OFICINA_ID', $oficinaId) // Aplicar filtro de OFICINA_ID
                ->when($fechaInicio && $fechaFin, function ($query) use ($fechaInicio, $fechaFin) {
                    // Asegúrate de ajustar 'revisiones_solicitudes.created_at' si la columna de fecha se llama diferente
                    return $query->whereBetween('revisiones_solicitudes.created_at', [$fechaInicio, $fechaFin]);
                })
                ->groupBy('users.id', 'users.USUARIO_NOMBRES', 'users.USUARIO_APELLIDOS')
                ->select('users.id', DB::raw('CONCAT(users.USUARIO_NOMBRES, " ", users.USUARIO_APELLIDOS) as nombre_completo'), DB::raw('COUNT(DISTINCT revisiones_solicitudes.SOLICITUD_VEHICULO_ID) as cantidad'))
                ->orderBy('cantidad', 'desc')
                ->get();

            return [
                'ranking' => $rankingRevisores,
            ];
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
    public function rankingDepartamentosUbicaciones(Request $request)
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

            return [
                'solicitudesPorEntidad' => $rankingDepartamentosUbicaciones,
            ];
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
    public function rankingSolicitudes(Request $request)
    {
        try {
            $fechaInicio = $request->input('fecha_inicio');
            $fechaFin = $request->input('fecha_fin');
            $oficinaId = Auth::user()->OFICINA_ID;

            $query = SolicitudVehicular::query();

            $query->join('users', 'solicitudes_vehiculos.USUARIO_id', '=', 'users.id')
                ->where('users.OFICINA_ID', $oficinaId);

            if ($fechaInicio && $fechaFin) {
                $query->whereBetween('created_at', [$fechaInicio, $fechaFin]);
            }

            $rankingSolicitudes = $query->groupBy('SOLICITUD_VEHICULO_ESTADO')
                ->select('SOLICITUD_VEHICULO_ESTADO as SOLICITUD_ESTADO', DB::raw('COUNT(*) as total_solicitudes'))
                ->orderBy('total_solicitudes', 'desc')
                ->get();

            return [
                'rankingEstados' => $rankingSolicitudes,
            ];
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al obtener el ranking de solicitudes vehiculares: ',
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
    public function rankingVehiculosAsignados(Request $request)
    {
        try {
            $fechaInicio = $request->input('fecha_inicio');
            $fechaFin = $request->input('fecha_fin');
            $oficinaId = Auth::user()->OFICINA_ID;

            $query = SolicitudVehicular::query();

            $query->join('users', 'solicitudes_vehiculos.USUARIO_id', '=', 'users.id')
                ->where('users.OFICINA_ID', $oficinaId);

            if ($fechaInicio && $fechaFin) {
                $query->whereBetween('created_at', [$fechaInicio, $fechaFin]);
            }

            $rankingVehiculos = $query->join('vehiculos', 'solicitudes_vehiculos.VEHICULO_ID', '=', 'vehiculos.VEHICULO_ID')
                ->groupBy('vehiculos.VEHICULO_PATENTE')
                ->select('vehiculos.VEHICULO_PATENTE', DB::raw('COUNT(*) as total_solicitudes'))
                ->orderBy('total_solicitudes', 'desc')
                ->get();

            return [
                'rankingVehiculos' => $rankingVehiculos,
            ];
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al obtener el ranking de vehículos asignados: ' . $e->getMessage(),
            ], 500);
        }
    }

}
