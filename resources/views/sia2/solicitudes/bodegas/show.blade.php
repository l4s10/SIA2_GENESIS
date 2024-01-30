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
                <h5 class="card-title">Motivo: {{ $solicitud->SOLICITUD_MOTIVO }}</h5>
                <p class="card-text">Estado: {{ $solicitud->SOLICITUD_ESTADO }}</p>
                <p class="card-text">Fecha y Hora de Inicio Solicitada: {{ $solicitud->SOLICITUD_FECHA_HORA_INICIO_SOLICITADA }}</p>
                <p class="card-text">Fecha y Hora de Término Solicitada: {{ $solicitud->SOLICITUD_FECHA_HORA_TERMINO_SOLICITADA }}</p>
                <h5 class="mt-4">Bodega Solicita</h5>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Nombre bodega</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($solicitud->bodegas as $bodega)
                            <tr>
                                <td>{{ $bodega->BODEGA_NOMBRE }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop

@section('css')

@stop

@section('js')

@stop
