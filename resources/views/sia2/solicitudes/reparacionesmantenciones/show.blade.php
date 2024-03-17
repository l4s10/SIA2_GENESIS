@extends('adminlte::page')

@section('title', 'Detalles de Solicitud')

@section('content_header')
    <h1>Detalles de Solicitud</h1>
@stop

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                Detalles de la Solicitud
            </div>
            <div class="card-body">
                <p class="card-text"><i class="fa-solid fa-user"></i> Solicitante: {{ $solicitud->solicitante->USUARIO_NOMBRES }} {{$solicitud->solicitante->USUARIO_APELLIDOS}}</p>
                <p class="card-text"><i class="fa-solid fa-file-pen"></i> Motivo: {{ $solicitud->SOLICITUD_REPARACION_MOTIVO }}</p>
                <p class="card-text"><i class="fa-solid fa-file-pen"></i> Tipo: {{ $solicitud->SOLICITUD_REPARACION_TIPO }}</p>
                <p class="card-text"><i class="fa-solid fa-warehouse"></i> / </i><i class="fa-solid fa-car-on"></i> Categoría: {{ $solicitud->categoria->CATEGORIA_REPARACION_NOMBRE }}</p>
                <p><i class="fa-solid fa-file-circle-check"></i> Estado: <span class="badge rounded-pill estado-{{ strtolower(str_replace(' ', '-', $solicitud->SOLICITUD_REPARACION_ESTADO)) }}">{{ $solicitud->SOLICITUD_REPARACION_ESTADO }}</span></p>
                <p class="card-text"><i class="fa-solid fa-calendar-check"></i>  Fecha y hora de inicio: {{ $solicitud->SOLICITUD_REPARACION_FECHA_HORA_INICIO }}</p>
                <p class="card-text"><i class="fa-regular fa-calendar-check"></i> Fecha y hora de término: {{ $solicitud->SOLICITUD_REPARACION_FECHA_HORA_TERMINO }}</p>
                <p class="card-text"><i class="fa-solid fa-calendar-week"></i>  Fecha de creacion: {{$solicitud->created_at}}</p>
            </div>
        </div>
    </div>
    <a href="{{ route('solicitudes.reparaciones.index') }}" class="btn btn-secondary mt-4"><i class="fa-solid fa-hand-point-left"></i> Volver</a>
@stop

@section('css')
<style>
        
</style>
@stop

@section('js')

@stop
