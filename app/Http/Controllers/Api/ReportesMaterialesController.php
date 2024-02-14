<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SolicitudMaterial;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Auth;

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
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al filtrar los reportes: ' . $e->getMessage()
            ], 500);
        }
    }

    /*
    *   Grafico materiales 1: RANKING DE GESTIONADORES DE SOLICITUDES DE MATERIALES
    */
    public function Grafico1(Request $request)
    {
        try {
            // Opcionalmente recibidos desde el request
            $fechaInicio = $request->input('fecha_inicio');
            $fechaFin = $request->input('fecha_fin');
            $oficinaId = Auth::user()->OFICINA_ID; // Obtener el ID de la oficina del usuario autenticado

            $rankingGestionadores = SolicitudMaterial::query()
                ->join('solicitudes', 'solicitudes_materiales.SOLICITUD_ID', '=', 'solicitudes.SOLICITUD_ID')
                ->join('revisiones_solicitudes', 'solicitudes.SOLICITUD_ID', '=', 'revisiones_solicitudes.SOLICITUD_ID')
                ->join('users as solicitantes', 'solicitudes.USUARIO_id', '=', 'solicitantes.id') // Usuario que hizo la solicitud
                ->join('users as revisores', 'revisiones_solicitudes.USUARIO_id', '=', 'revisores.id') // Usuario que revisó la solicitud
                ->where('solicitantes.OFICINA_ID', $oficinaId) // Filtrar por la oficina del usuario autenticado
                ->where('revisores.OFICINA_ID', $oficinaId) // Asegurar que el revisor también pertenezca a la misma oficina
                ->select('revisores.id', DB::raw('CONCAT(revisores.USUARIO_NOMBRES, " ", revisores.USUARIO_APELLIDOS) as nombre_completo'), DB::raw('COUNT(*) as total_gestiones'))
                ->when($fechaInicio && $fechaFin, function ($query) use ($fechaInicio, $fechaFin) {
                    return $query->whereBetween('revisiones_solicitudes.created_at', [$fechaInicio, $fechaFin]);
                })
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

    /*
    *   Grafico materiales 2: SOLICITUDES DE MATERIALES DE MATERIALES REQUERIDOS POR DEPARTAMENTO / UNIDAD
    *   Viajamos a traves de la tabla "solicitudes_materiales" y obtenemos las "SOLCIITUD_ID"
    *   Luego viajamos en la tabla "solicitudes" y obtenemos el "USUARIO_id" de cada solicitud de materiales a traves de la relacion "solicitante"
    *   Luego viajamos en la tabla "users" y obtenemos el DEPARTAMENTO_ID o UNIDAD_ID de cada usuario a traves de la relacion "departamento" o "ubicacion"
    */
    public function Grafico2(Request $request)
    {
        try {
            // Opcionalmente recibidos desde el request
            $fechaInicio = $request->input('fecha_inicio');
            $fechaFin = $request->input('fecha_fin');
            $oficinaId = Auth::user()->OFICINA_ID; // Obtener el ID de la oficina del usuario autenticado

            // Subconsulta para departamentos
            $departamentosQuery = SolicitudMaterial::query()
                ->join('solicitudes', 'solicitudes_materiales.SOLICITUD_ID', '=', 'solicitudes.SOLICITUD_ID')
                ->join('users', 'solicitudes.USUARIO_id', '=', 'users.id')
                ->join('departamentos', 'users.DEPARTAMENTO_ID', '=', 'departamentos.DEPARTAMENTO_ID')
                ->where('users.OFICINA_ID', '=', $oficinaId)
                ->select('departamentos.DEPARTAMENTO_NOMBRE as entidad', DB::raw('COUNT(*) as total_solicitudes'))
                ->whereNotNull('users.DEPARTAMENTO_ID')
                ->groupBy('departamentos.DEPARTAMENTO_NOMBRE');

            // Subconsulta para ubicaciones
            $ubicacionesQuery = SolicitudMaterial::query()
                ->join('solicitudes', 'solicitudes_materiales.SOLICITUD_ID', '=', 'solicitudes.SOLICITUD_ID')
                ->join('users', 'solicitudes.USUARIO_id', '=', 'users.id')
                ->join('ubicaciones', 'users.UBICACION_ID', '=', 'ubicaciones.UBICACION_ID')
                ->where('users.OFICINA_ID', '=', $oficinaId)
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
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al obtener las solicitudes por entidad: ' . $e->getMessage()
            ], 500);
        }
    }

    /*
    *   Grafico materiales 3: RANKING DE ESTADOS DE SOLICITUDES DE MATERIALES
    *   Viajamos a traves de la tabla "solicitudes_materiales" y obtenemos las "SOLCIITUD_ID" para luego obtener las solicitudes filtradas.
    *   Despues accedemos al campo "SOLICITUD_ESTADO" de la tabla "solicitudes" para obtener el estado de cada solicitud.
    * luego agrupamos los estados y devolvemos la cantidad que hay en cada uno
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
                ->join('users', 'solicitudes.USUARIO_id', '=', 'users.id') // Asegúrate de unirte a la tabla de usuarios para acceder a OFICINA_ID
                ->where('users.OFICINA_ID', '=', $oficinaId) // Filtrar por OFICINA_ID
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
