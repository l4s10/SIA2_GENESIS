@extends('adminlte::page')

@section('title', 'Detalles de Solicitud')

@section('content_header')
    <h1>Detalles de Solicitud</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            Detalles de la Solicitud
        </div>
        <div class="card-body">
            <h5 class="card-title">Motivo: {{ $solicitud->SOLICITUD_MOTIVO }}</h5>
            <p class="card-text">Estado: {{ $solicitud->SOLICITUD_ESTADO }}</p>
            <p class="card-text">Fecha y Hora de Inicio Solicitada: {{ $solicitud->SOLICITUD_FECHA_HORA_INICIO_SOLICITADA }}</p>
            <p class="card-text">Fecha y Hora de Término Solicitada: {{ $solicitud->SOLICITUD_FECHA_HORA_TERMINO_SOLICITADA }}</p>
            <h5 class="mt-4">Sala Solicitada y autorizada</h5>

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Nombre sala</th>
                        <th>Sala asignada</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($solicitud->salas as $sala)
                        <tr>
                            <td>{{ $sala->SALA_NOMBRE }}</td>
                            <td>{{$salaAsignada->SALA_NOMBRE ?? 'No se ha asignado una sala por ahora.'}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <h5 class="mt-4">Equipos Solicitados</h5>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Tipo de Equipo</th>
                        <th>Cantidad</th>
                        <th>Cantidad autorizada</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($solicitud->equipos as $tipoEquipo)
                        <tr>
                            <td>{{ $tipoEquipo->TIPO_EQUIPO_NOMBRE }}</td>
                            <td>{{ $tipoEquipo->pivot->SOLICITUD_EQUIPOS_CANTIDAD }}</td>
                            <td>{{ $tipoEquipo->pivot->SOLICITUD_EQUIPOS_CANTIDAD_AUTORIZADA ?? 'No se han autorizado cantidades por ahora.' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Boton para volver a index --}}
    <a href="{{ route('solicitudes.salas.index') }}" class="btn btn-primary mt-4">Volver a la lista de solicitudes</a>
@stop

@section('css')
@stop

@section('js')
@stop
