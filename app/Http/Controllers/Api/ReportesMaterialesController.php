<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SolicitudMaterial;
use Illuminate\Support\Facades\DB;

class ReportesMaterialesController extends Controller
{
    public function home()
    {
        return response()->json([
            'status' => 'success',
            'message' => 'Bienvenido a la API de reportes de materiales. MEOW MEOW NIGGA'
        ]);
    }

    /*
    *   Grafico materiales 1: RANKING DE GESTIONADORES DE SOLICITUDES DE MATERIALES
    */
    public function Grafico1()
    {
        try {
            // Obtener el conteo de revisiones por usuario (gestionador) incluyendo nombres y apellidos
            $rankingGestionadores = SolicitudMaterial::query()
                ->join('solicitudes', 'solicitudes_materiales.SOLICITUD_ID', '=', 'solicitudes.SOLICITUD_ID')
                ->join('revisiones_solicitudes', 'solicitudes.SOLICITUD_ID', '=', 'revisiones_solicitudes.SOLICITUD_ID')
                ->join('users', 'revisiones_solicitudes.USUARIO_ID', '=', 'users.id')
                ->select('users.id', DB::raw('CONCAT(users.USUARIO_NOMBRES, " ", users.USUARIO_APELLIDOS) as nombre_completo'), DB::raw('count(*) as total_gestiones'))
                ->groupBy('users.id', 'users.USUARIO_NOMBRES', 'users.USUARIO_APELLIDOS')
                ->orderBy('total_gestiones', 'DESC')
                ->get();

            return response()->json([
                'status' => 'success',
                'rankingGestionadores' => $rankingGestionadores
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al obtener el ranking de gestionadores: ' . $e->getMessage()
            ], 500);
        }
    }

}
