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
            <p class="card-text"><i class="fa-solid fa-file-pen"></i> Motivo: {{ $solicitud->SOLICITUD_MOTIVO }}</p>
            <p><i class="fa-solid fa-file-circle-check"></i> Estado: <span class="badge rounded-pill estado-{{ strtolower(str_replace(' ', '-', $solicitud->SOLICITUD_ESTADO)) }}">{{ $solicitud->SOLICITUD_ESTADO }}</span></p>
            <p class="card-text"><i class="fa-solid fa-calendar-plus"></i> Fecha y Hora de Inicio Solicitada: {{ $solicitud->SOLICITUD_FECHA_HORA_INICIO_SOLICITADA }}</p>
            <p class="card-text"><i class="fa-regular fa-calendar-plus"></i> Fecha y Hora de Término Solicitada: {{ $solicitud->SOLICITUD_FECHA_HORA_TERMINO_SOLICITADA }}</p>
            <p class="card-text"><i class="fa-solid fa-calendar-check"></i> Fecha y Hora de Inicio Autorizada: {{ $solicitud->SOLICITUD_FECHA_HORA_INICIO_ASIGNADA ?? 'NO SE HA AUTORIZADO FECHA POR AHORA.'}}</p>
            <p class="card-text"><i class="fa-regular fa-calendar-check"></i> Fecha y Hora de Término Autorizada: {{ $solicitud->SOLICITUD_FECHA_HORA_INICIO_ASIGNADA ?? 'NO SE HA AUTORIZADO FECHA POR AHORA.' }}</p>

            <h5 class="mt-4">Equipos Solicitados</h5>
            <table class="table table-bordered">
                <thead class="tablacolor">
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
                            <td>{{ $tipoEquipo->pivot->SOLICITUD_EQUIPOS_CANTIDAD_AUTORIZADA ?? 'NO SE HAN AUTORIZADO CANTIDADES POR AHORA.'}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <a href="{{ route('solicitudes.equipos.index') }}" class="btn btn-secondary mt-4"><i class="fa-solid fa-hand-point-left"></i> Volver</a>
@stop

@section('css')
<style>
        .tablacolor {
            background-color: #723E72; /* Color de fondo personalizado */
            color: #fff; /* Color de texto personalizado */
        }
        .tablacarrito {
            background-color: #956E95;
            color: #fff;
        }
        .agregar{
            background-color: #e6500a;
            color: #fff;
        }

        /*Colores de los estados*/
        .estado-ingresado {
        color: #000000;
        background-color: #FFA600;
        }

        .estado-en-revision {
        color: #000000;
        background-color: #F7F70B;
        }

        .estado-aprobado {
        color: #ffffff;
        background-color: #0CB009;
        }

        .estado-rechazado {
        color: #FFFFFF;
        background-color: #F70B0B;
        }

        .estado-terminado {
        color: #000000;
        background-color: #d9d9d9;
        }
</style>
@stop

@section('js')

@stop
