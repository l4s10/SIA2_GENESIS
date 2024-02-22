<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

// Importar el modelo de Solicitudes de Reparacion
use App\Models\SolicitudReparacion;
use App\Models\CategoriaReparacion;

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
            $grafico1 = $this->reparacionesPorCategoria(new Request());
            $grafico2 = $this->Grafico2(new Request());

            // Devolver los datos de los graficos
            return response()->json([
                'status' => 'success',
                'data' => [
                    'grafico1' => $grafico1,
                    'grafico2' => $grafico2,
                    // 'grafico3' => $grafico3,
                    // 'grafico4' => $grafico4,
                    // 'grafico5' => $grafico5,
                    // 'grafico6' => $grafico6,
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
            // Filtrar los datos de los graficos
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
    public function reparacionesPorCategoria(Request $request)
    {
        try{
            // Obtener los datos opcionales de la request (fechas de inicio y fin) y la OFICINA_ID del usuario logueado
            $fechaInicio = $request->input('fecha_inicio');
            $fechaFin = $request->input('fecha_fin');
            $oficinaId = Auth::user()->OFICINA_ID; // Obtener el ID de la oficina del usuario autenticado

            // Categorías específicas a considerar
            $categorias = ['AIRE ACONDICIONADO', 'INFRAESTRUCTURA', 'MOVILIARIO', 'OTRO'];

            // Filtrar solicitudes de reparación por categoría y unir con categorías para obtener el nombre
            $reparacionesPorCategoria = SolicitudReparacion::join('categorias_reparaciones', 'solicitudes_reparaciones.CATEGORIA_REPARACION_ID', '=', 'categorias_reparaciones.CATEGORIA_REPARACION_ID')
                ->whereIn('categorias_reparaciones.CATEGORIA_REPARACION_NOMBRE', $categorias)
                ->groupBy('categorias_reparaciones.CATEGORIA_REPARACION_NOMBRE')
                ->select('categorias_reparaciones.CATEGORIA_REPARACION_NOMBRE', DB::raw('COUNT(solicitudes_reparaciones.SOLICITUD_REPARACION_ID) as cantidad'))
                ->get();

            // Devolver la cantidad de reparaciones por categoria junto con el nombre de la categoría
            return [
                'reparacionesPorCategoria' => $reparacionesPorCategoria,
            ];
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
            // Obtener los datos opcionales de la request (fechas de inicio y fin) y la OFICINA_ID del usuario logueado
            $fechaInicio = $request->input('fecha_inicio');
            $fechaFin = $request->input('fecha_fin');
            $oficinaId = Auth::user()->OFICINA_ID; // Obtener el ID de la oficina del usuario autenticado

            // Categorías específicas a considerar
            $categorias = ['MANTENCION CORRECTIVA', 'MANTENCION PREVENTIVA'];

            // Filtrar solicitudes de reparación por categoría y unir con categorías para obtener el nombre
            $mantencionesPorCategoria = SolicitudReparacion::join('categorias_reparaciones', 'solicitudes_reparaciones.CATEGORIA_REPARACION_ID', '=', 'categorias_reparaciones.CATEGORIA_REPARACION_ID')
                ->whereIn('categorias_reparaciones.CATEGORIA_REPARACION_NOMBRE', $categorias)
                ->groupBy('categorias_reparaciones.CATEGORIA_REPARACION_NOMBRE')
                ->select('categorias_reparaciones.CATEGORIA_REPARACION_NOMBRE', DB::raw('COUNT(solicitudes_reparaciones.SOLICITUD_REPARACION_ID) as cantidad'))
                ->get();

            // Devolver la cantidad de reparaciones por categoria junto con el nombre de la categoría
            return [
                'mantencionesPorCategoria' => $mantencionesPorCategoria,
            ];
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al obtener la cantidad de reparaciones por categoria: ' . $e->getMessage(),
            ], 500);
        }
    }
}
