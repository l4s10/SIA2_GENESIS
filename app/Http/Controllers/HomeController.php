<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
// Importar modelos nuevos
use App\Models\Solicitud;
use App\Models\SolicitudSala;
use App\Models\SolicitudBodega;
use App\Models\SolicitudReparacion;
use App\Models\SolicitudVehicular;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Obtener el id de la oficina del usuario autenticado
        $oficinaId = auth()->user()->OFICINA_ID;


        // Obtenemos los SOLICITUD_ID de las solicitudes de salas únicas que pertenecen a la oficina del usuario autenticado en base a fechas si se proporcionan.
        $solicitudesUnicasSalas = SolicitudSala::query()
            ->join('solicitudes', 'solicitudes_salas.SOLICITUD_ID', '=', 'solicitudes.SOLICITUD_ID')
            ->join('users as solicitantes', 'solicitudes.USUARIO_id', '=', 'solicitantes.id')
            ->where('solicitantes.OFICINA_ID', $oficinaId)
            ->where('solicitudes.SOLICITUD_ESTADO', 'AUTORIZADO')
            ->select('solicitudes.SOLICITUD_ID')
            ->distinct()
            ->pluck('SOLICITUD_ID');


        $salas = [];

        // Parseamos a evento de FullCalendar
        foreach ($solicitudesUnicasSalas as $solicitudId) {
            $solicitud = Solicitud::with('salasAutorizadas.salaAsignada')->find($solicitudId);
            $nombresSalas = $solicitud->salasAutorizadas->map(function($solicitudSala) {
                return $solicitudSala->salaAsignada->SALA_NOMBRE;
            })->join(', '); // Une los nombres de las salas con coma

            $salas[] = [
                'title' => $nombresSalas, // Aquí pasas todos los nombres de las salas
                'start' => $solicitud->SOLICITUD_FECHA_HORA_INICIO_ASIGNADA,
                'end' => $solicitud->SOLICITUD_FECHA_HORA_TERMINO_ASIGNADA,
                'color' => '#0064A0',
                'departamento' => $solicitud->solicitante->departamento->DEPARTAMENTO_NOMBRE ?? $solicitud->solicitante->ubicacion->UBICACION_NOMBRE,
                'nombreSolicitante' => $solicitud->solicitante->USUARIO_NOMBRES . ' ' . $solicitud->solicitante->USUARIO_APELLIDOS,
                'tipoEvento' => 'Reserva de sala'
            ];
        }


        // Obtenemos los SOLICITUD_ID de las solicitudes de bodegas únicas que pertenecen a la oficina del usuario autenticado en base a fechas si se proporcionan.
        $solicitudesUnicasBodegas = SolicitudBodega::query()
            ->join('solicitudes', 'solicitudes_bodegas.SOLICITUD_ID', '=', 'solicitudes.SOLICITUD_ID')
            ->join('users as solicitantes', 'solicitudes.USUARIO_id', '=', 'solicitantes.id')
            ->where('solicitantes.OFICINA_ID', $oficinaId)
            ->where('solicitudes.SOLICITUD_ESTADO', 'AUTORIZADO')
            ->select('solicitudes.SOLICITUD_ID')
            ->distinct()
            ->pluck('SOLICITUD_ID');

        $bodegas = [];

        // Parseamos a evento de FullCalendar
        foreach ($solicitudesUnicasBodegas as $solicitudId) {
            $solicitud = Solicitud::with('bodegasAutorizadas.bodega')->find($solicitudId);
            $nombresBodegas = $solicitud->bodegasAutorizadas->map(function($solicitudBodega) {
                return $solicitudBodega->bodega->BODEGA_NOMBRE;
            })->join(', '); // Une los nombres de las bodegas con coma

            $bodegas[] = [
                'title' => $nombresBodegas, // Aquí pasas todos los nombres de las bodegas
                'start' => $solicitud->SOLICITUD_FECHA_HORA_INICIO_ASIGNADA,
                'end' => $solicitud->SOLICITUD_FECHA_HORA_TERMINO_ASIGNADA,
                'color' => '#E6500A',
                'departamento' => $solicitud->solicitante->departamento->DEPARTAMENTO_NOMBRE ?? $solicitud->solicitante->ubicacion->UBICACION_NOMBRE,
                'nombreSolicitante' => $solicitud->solicitante->USUARIO_NOMBRES . ' ' . $solicitud->solicitante->USUARIO_APELLIDOS,
                'tipoEvento' => 'Reserva de bodega'
            ];
        }



        // Tipos de categorías para mantenimientos
        $categorias = ['MANTENCION CORRECTIVA', 'MANTENCION PREVENTIVA'];

        // Query para obtener las solicitudes de mantenimiento por Departamento
        $mantenimientosPorDepartamento = SolicitudReparacion::join('users', 'solicitudes_reparaciones.USUARIO_id', '=', 'users.id')
            ->join('departamentos', 'users.DEPARTAMENTO_ID', '=', 'departamentos.DEPARTAMENTO_ID')
            ->join('categorias_reparaciones', 'solicitudes_reparaciones.CATEGORIA_REPARACION_ID', '=', 'categorias_reparaciones.CATEGORIA_REPARACION_ID')
            ->whereIn('categorias_reparaciones.CATEGORIA_REPARACION_NOMBRE', $categorias)
            ->whereNotNull('users.DEPARTAMENTO_ID')
            ->where('users.OFICINA_ID', '=', $oficinaId)
            ->where('solicitudes_reparaciones.SOLICITUD_REPARACION_ESTADO', '=', 'AUTORIZADO')
            ->get();

        // Query para obtener las solicitudes de mantenimiento por Ubicación
        $mantenimientosPorUbicacion = SolicitudReparacion::join('users', 'solicitudes_reparaciones.USUARIO_id', '=', 'users.id')
            ->join('ubicaciones', 'users.UBICACION_ID', '=', 'ubicaciones.UBICACION_ID')
            ->join('categorias_reparaciones', 'solicitudes_reparaciones.CATEGORIA_REPARACION_ID', '=', 'categorias_reparaciones.CATEGORIA_REPARACION_ID')
            ->whereIn('categorias_reparaciones.CATEGORIA_REPARACION_NOMBRE', $categorias)
            ->whereNotNull('users.UBICACION_ID')
            ->where('users.OFICINA_ID', '=', $oficinaId)
            ->get();

        // Unir los resultados de las dos consultas anteriores (merge)
        $mantenimientosPorEntidad = $mantenimientosPorDepartamento->merge($mantenimientosPorUbicacion);

        // parsear a evento de FullCalendar
        $mantenimientos = [];
        foreach ($mantenimientosPorEntidad as $mantenimiento) {
            $mantenimientos[] = [
                'title' => $mantenimiento->vehiculo->VEHICULO_PATENTE ?? 'Sin patente',
                'start' => $mantenimiento->SOLICITUD_REPARACION_FECHA_HORA_INICIO,
                'end' => $mantenimiento->SOLICITUD_REPARACION_FECHA_HORA_TERMINO,
                'color' => '#d9d9d9',
                'departamento' => $mantenimiento->solicitante->departamento->DEPARTAMENTO_NOMBRE ?? $mantenimiento->solicitante->ubicacion->UBICACION_NOMBRE ?? 'Sin departamento / ubicación',
                'nombreSolicitante' => $mantenimiento->solicitante->USUARIO_NOMBRES . ' ' . $mantenimiento->solicitante->USUARIO_APELLIDOS,
                'tipoEvento' => 'Mantenimiento de vehículo'
            ];
        }

        // Obtener solicitudes vehiculares por rendir dentro de la misma OFICINA_ID que el usuario autenticado
        $solicitudesVehicularesPorRendir = SolicitudVehicular::join('users', 'solicitudes_vehiculos.USUARIO_id', '=', 'users.id')
            ->where('users.OFICINA_ID', '=', $oficinaId)
            ->where('solicitudes_vehiculos.SOLICITUD_VEHICULO_ESTADO', '=', 'POR RENDIR')
            ->get();

        // Parsear a evento de FullCalendar
        $solicitudesVehiculares = [];
        foreach ($solicitudesVehicularesPorRendir as $solicitudVehicular) {
            $solicitudesVehiculares[] = [
                'title' => $solicitudVehicular->vehiculo->VEHICULO_PATENTE,
                'start' => $solicitudVehicular->SOLICITUD_VEHICULO_FECHA_HORA_INICIO_ASIGNADA,
                'end' => $solicitudVehicular->SOLICITUD_VEHICULO_FECHA_HORA_TERMINO_ASIGNADA,
                'color' => '#696969',
                'departamento' => $solicitudVehicular->user->departamento->DEPARTAMENTO_NOMBRE ?? $solicitudVehicular->user->ubicacion->UBICACION_NOMBRE ?? 'Sin departamento / ubicación',
                'nombreSolicitante' => $solicitudVehicular->user->USUARIO_NOMBRES . ' ' . $solicitudVehicular->user->USUARIO_APELLIDOS,
                'tipoEvento' => 'Reserva de vehículo'
            ];
        }
        //*CONCATENAMOS TODOS LOS EVENTOS EN UN ARRAY DE EVENTOS.
        $events = array_merge($salas, $bodegas, $mantenimientos, $solicitudesVehiculares);
        return view('home', compact('events'));
    }
}
