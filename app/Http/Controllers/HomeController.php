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
            ->where('solicitudes.SOLICITUD_ESTADO', 'APROBADO')
            ->select('solicitudes.SOLICITUD_ID')
            ->distinct()
            ->pluck('SOLICITUD_ID');

        $salas = [];

        // Parseamos a evento de FullCalendar
        foreach ($solicitudesUnicasSalas as $solicitudId) {
            $solicitud = Solicitud::with('salasAutorizadas.salaAsignada')->find($solicitudId);
            $nombresSalas = $solicitud->salasAutorizadas->map(function($solicitudSala) {
                return $solicitudSala->salaAsignada->SALA_NOMBRE ?? $solicitudSala->salaSolicitada->SALA_NOMBRE ?? 'sin nombre';
            })->join(', '); // Une los nombres de las salas con coma

            $salas[] = [
                'title' => $nombresSalas, // Aquí pasas todos los nombres de las salas
                'start' => $solicitud->SOLICITUD_FECHA_HORA_INICIO_ASIGNADA ?? 'Fecha no asignada',
                'end' => $solicitud->SOLICITUD_FECHA_HORA_TERMINO_ASIGNADA ?? 'Fecha no asignada',
                'color' => '#0064A0',
                'departamento' => $solicitud->solicitante->departamento->DEPARTAMENTO_NOMBRE ?? ($solicitud->solicitante->ubicacion->UBICACION_NOMBRE ?? 'Sin departamento / ubicación'),
                'nombreSolicitante' => ($solicitud->solicitante->USUARIO_NOMBRES ?? 'Sin nombre') . ' ' . ($solicitud->solicitante->USUARIO_APELLIDOS ?? 'Sin apellido'),
                'tipoEvento' => 'Reserva de sala'
            ];
        }

        // Obtenemos los SOLICITUD_ID de las solicitudes de bodegas únicas que pertenecen a la oficina del usuario autenticado en base a fechas si se proporcionan.
        $solicitudesUnicasBodegas = SolicitudBodega::query()
            ->join('solicitudes', 'solicitudes_bodegas.SOLICITUD_ID', '=', 'solicitudes.SOLICITUD_ID')
            ->join('users as solicitantes', 'solicitudes.USUARIO_id', '=', 'solicitantes.id')
            ->where('solicitantes.OFICINA_ID', $oficinaId)
            ->where('solicitudes.SOLICITUD_ESTADO', 'APROBADO')
            ->select('solicitudes.SOLICITUD_ID')
            ->distinct()
            ->pluck('SOLICITUD_ID');

        $bodegas = [];

        // Parseamos a evento de FullCalendar
        foreach ($solicitudesUnicasBodegas as $solicitudId) {
            $solicitud = Solicitud::with('bodegasAutorizadas.bodega')->find($solicitudId);
            $nombresBodegas = $solicitud->bodegasAutorizadas->map(function($solicitudBodega) {
                return $solicitudBodega->bodega->BODEGA_NOMBRE ?? 'Sin nombre';
            })->join(', '); // Une los nombres de las bodegas con coma

            $bodegas[] = [
                'title' => $nombresBodegas, // Aquí pasas todos los nombres de las bodegas
                'start' => $solicitud->SOLICITUD_FECHA_HORA_INICIO_ASIGNADA ?? 'Fecha no asignada',
                'end' => $solicitud->SOLICITUD_FECHA_HORA_TERMINO_ASIGNADA ?? 'Fecha no asignada',
                'color' => '#E6500A',
                'departamento' => $solicitud->solicitante->departamento->DEPARTAMENTO_NOMBRE ?? ($solicitud->solicitante->ubicacion->UBICACION_NOMBRE ?? 'Sin departamento / ubicación'),
                'nombreSolicitante' => ($solicitud->solicitante->USUARIO_NOMBRES ?? 'Sin nombre') . ' ' . ($solicitud->solicitante->USUARIO_APELLIDOS ?? 'Sin apellido'),
                'tipoEvento' => 'Reserva de bodega'
            ];
        }

        // Query para obtener las solicitudes de mantenimiento por Departamento
        $mantenimientosSolicitados = SolicitudReparacion::join('users', 'solicitudes_reparaciones.USUARIO_id', '=', 'users.id')
            ->select('solicitudes_reparaciones.*', 'users.*', 'solicitudes_reparaciones.created_at')
            ->where('solicitudes_reparaciones.SOLICITUD_REPARACION_TIPO', '=', 'MANTENCION')
            ->where('users.OFICINA_ID', '=', $oficinaId)
            ->where('solicitudes_reparaciones.SOLICITUD_REPARACION_ESTADO', '=', 'APROBADO')
            ->get();

        $mantenimientos = [];
        foreach ($mantenimientosSolicitados as $mantenimiento) {
            $mantenimientos[] = [
                'title' => $mantenimiento->vehiculo->VEHICULO_PATENTE ?? 'Sin patente',
                'start' => $mantenimiento->created_at ?? 'Fecha no asignada', // Asignar created_at a start
                'end' => $mantenimiento->SOLICITUD_REPARACION_FECHA_HORA_INICIO ?? 'Fecha no asignada',
                'color' => '#d9d9d9',
                'departamento' => $mantenimiento->solicitante->departamento->DEPARTAMENTO_NOMBRE ?? ($mantenimiento->solicitante->ubicacion->UBICACION_NOMBRE ?? 'Sin departamento / ubicación'),
                'nombreSolicitante' => ($mantenimiento->solicitante->USUARIO_NOMBRES ?? 'Sin nombre') . ' ' . ($mantenimiento->solicitante->USUARIO_APELLIDOS ?? 'Sin apellido'),
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
                'title' => $solicitudVehicular->vehiculo->VEHICULO_PATENTE ?? 'Sin patente',
                'start' => $solicitudVehicular->SOLICITUD_VEHICULO_FECHA_HORA_INICIO_ASIGNADA ?? 'Fecha no asignada',
                'end' => $solicitudVehicular->SOLICITUD_VEHICULO_FECHA_HORA_TERMINO_ASIGNADA ?? 'Fecha no asignada',
                'color' => '#696969',
                'departamento' => $solicitudVehicular->user->departamento->DEPARTAMENTO_NOMBRE ?? ($solicitudVehicular->user->ubicacion->UBICACION_NOMBRE ?? 'Sin departamento / ubicación'),
                'nombreSolicitante' => ($solicitudVehicular->user->USUARIO_NOMBRES ?? 'Sin nombre') . ' ' . ($solicitudVehicular->user->USUARIO_APELLIDOS ?? 'Sin apellido'),
                'tipoEvento' => 'Reserva de vehículo'
            ];
        }

        // Concatenamos todos los eventos en un array de eventos.
        $events = array_merge($salas, $bodegas, $mantenimientos, $solicitudesVehiculares);

        return view('home', compact('events'));
    }
}
