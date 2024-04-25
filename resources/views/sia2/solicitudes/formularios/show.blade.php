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
            <p class="card-text"><i class="fa-solid fa-calendar-plus"></i> Fecha y hora de inicio solicitada: {{ $solicitud->mostrarFecha($solicitud->SOLICITUD_FECHA_HORA_INICIO_SOLICITADA) }}</p>
            <p class="card-text"><i class="fa-solid fa-calendar-plus"></i> Fecha y hora de despacho: {{ $solicitud->mostrarFecha($solicitud->SOLICITUD_FECHA_HORA_INICIO_ASIGNADA) ?: 'Aún no se ha asignado' }}</p>
            {{-- <p class="card-text"><i class="fa-regular fa-calendar-plus"></i> Fecha y Hora de Término Solicitada: {{ $solicitud->SOLICITUD_FECHA_HORA_TERMINO_SOLICITADA }}</p> --}}

            <h5 class="mt-4">Formularios Solicitados</h5>
            <table class="table table-bordered">
                <thead class="tablacolor">
                    <tr>
                        <th>Tipo de formulario</th>
                        <th>Nombre del formulario</th>
                        <th>Cantidad solicitada</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($solicitud->formularios as $formulario)
                        <tr>
                            <td>{{ $formulario->FORMULARIO_TIPO }}</td>
                            <td>{{ $formulario->FORMULARIO_NOMBRE }}</td>
                            <td>{{ $formulario->pivot->SOLICITUD_FORMULARIOS_CANTIDAD }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Boton para volver a index --}}
    <a href="{{ route('solicitudes.formularios.index') }}" class="btn btn-secondary mt-4"><i class="fa-solid fa-hand-point-left"></i> Volver</a>
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
