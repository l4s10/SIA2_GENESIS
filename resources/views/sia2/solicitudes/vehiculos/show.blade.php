@extends('adminlte::page')

@section('title', 'Editar Solicitud Vehicular')

@section('content_header')
    <h1>Revisión Solicitud Vehicular</h1>
    <br>
    <br>
@stop

@section('content')
    <div class="container">
        <h1>Detalle de la Solicitud</h1>
        <div class="solicitud-details">
            <p><strong>ID de Solicitud:</strong> {{ $solicitud->id }}</p>
            {{-- Detalles de la solicitud aquí--}}
        </div>
        <h2>Timeline de la Solicitud</h2>
        <div class="timeline">
            <div class="container-timeline">
                <div class="timeline-item">
                    <div class="timeline-icon">
                        <i class="fas fa-car"></i>
                    </div>
                    <div class="timeline-content">
                        <h3>Solicitud creada</h3>
                        <p>{{ $solicitud->created_at }}</p>
                    </div>
                </div>
                @foreach($revisiones as $revision)
                    <div class="timeline-item">
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
                @endforeach
                <div class="timeline-item">
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

    <script src="scripts.js"></script>
@endsection
