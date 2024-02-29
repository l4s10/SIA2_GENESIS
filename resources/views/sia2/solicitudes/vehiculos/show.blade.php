@extends('adminlte::page')

@section('title', 'Editar Solicitud Vehicular')

@section('content_header')
    <h1>Revisión Solicitud Vehicular</h1>
@stop

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            Detalles de la Solicitud
        </div>
        <div class="card-body">
            <p><strong>ID de Solicitud:</strong> {{ $solicitud->id }}</p>
            {{-- Detalles de la solicitud aquí--}}
        </div>
    </div>
    <div class="card border">
        <div class="card-header">
            <h2>Detalles de la Solicitud</h2>
        </div>
        <div class="card-body">
                <div class="container-timeline">
                    <div class="card timeline-item">
                        <div class="card-header">
                            <div class="timeline-icon">
                                <i class="fas fa-car"></i>
                            </div>
                            <div class="timeline-content">
                                <h3>Solicitud creada</h3>
                                <p>{{ $solicitud->created_at }}</p>
                            </div>
                        </div>
                    </div>
                    @foreach($revisiones as $revision)
                        <div class="card timeline-item">
                            <div class="card-header">
                                <div class="timeline-icon">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div class="timeline-content">
                                    <h3>Revisión por {{ $revision->gestionador ? $revision->gestionador->USUARIO_NOMBRES.' '.$revision->gestionador->USUARIO_APELLIDOS : 'Usuario Desconocido' }}</h3>
                                    <p>{{ $revision->created_at }}</p>
                                    <p>Observaciones: {{ $revision->REVISION_SOLICITUD_OBSERVACION }}</p>
                                    <p>Estado:
                                        @if(in_array($revision->SOLICITUD_VEHICULO_ESTADO, ['INGRESADO', 'EN REVISIÓN', 'POR APROBAR', 'POR AUTORIZAR', 'POR RENDIR', 'TERMINADO', 'RECHAZADO']))
                                            {{ $revision->SOLICITUD_VEHICULO_ESTADO }}
                                        @else
                                            Estado Desconocido
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <div class="card timeline-item">
                        <div class="card-header">
                            <div class="timeline-icon">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="timeline-content">
                                <h3>Estado actual: {{ $solicitud->SOLICITUD_VEHICULO_ESTADO }}</h3>
                            </div>
                    </div>
                 </div>
            </div>
        </div>
    </div>
</div>
<script src="scripts.js"></script>
@endsection
@section('css')
<style>
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

        .estado-autorizado {
        color: #ffffff;
        background-color: #0CB009;
        }
</style>
@stop