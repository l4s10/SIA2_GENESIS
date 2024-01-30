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
                <p class="card-text">Solicitante: {{ $solicitud->solicitante->USUARIO_NOMBRES }} {{$solicitud->solicitante->USUARIO_APELLIDOS}}</p>
                <p class="card-text">Motivo: {{ $solicitud->SOLICITUD_REPARACION_MOTIVO }}</p>
                <p class="card-text">Tipo: {{ $solicitud->SOLICITUD_REPARACION_TIPO }}</p>
                <p class="card-text">Categoría: {{ $solicitud->categoria->CATEGORIA_REPARACION_NOMBRE }}</p>
                <p class="card-text">Estado: {{ $solicitud->SOLICITUD_REPARACION_ESTADO }}</p>
                <p class="card-text">Fecha y hora de inicio: {{ $solicitud->SOLICITUD_REPARACION_FECHA_HORA_INICIO }}</p>
                <p class="card-text">Fecha y hora de término: {{ $solicitud->SOLICITUD_REPARACION_FECHA_HORA_TERMINO }}</p>
                <p class="card-text">Fecha de creacion: {{$solicitud->created_at}}</p>
            </div>
        </div>
    </div>
@stop

@section('css')

@stop

@section('js')

@stop
