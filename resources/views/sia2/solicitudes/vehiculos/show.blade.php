@extends('adminlte::page')

@section('title', 'Editar Solicitud Vehicular')

@section('content_header')
    <div class="row">
        <div class="col-md-6">
            <h1>Revisión Solicitud Vehicular</h1>
        </div>
    </div>
@stop

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h2>Detalle de la Solicitud</h2>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="solicitante"><strong>Solicitante:</strong></label>
                                    <input type="text" id="solicitante" class="form-control" value="{{ $solicitud->user->USUARIO_NOMBRES . ' ' . $solicitud->user->USUARIO_APELLIDOS }}" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="estado_actual"><strong>Estado Actual:</strong></label>
                                    <input type="text" id="estado_actual" class="form-control" value="{{ $solicitud->SOLICITUD_VEHICULO_ESTADO }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="direccion_regional"><strong>Dirección Regional:</strong></label>
                                    <input type="text" id="direccion_regional" class="form-control" value="{{ $solicitud->user->oficina->OFICINA_NOMBRE }}" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="dependencia"><strong>Dependencia:</strong></label>
                                    <input type="text" id="dependencia" class="form-control" value="
                                        @if($solicitud->user->ubicacion)
                                            {{ $solicitud->user->ubicacion->UBICACION_NOMBRE }}
                                        @elseif($solicitud->user->departamento)
                                            {{ $solicitud->user->departamento->DEPARTAMENTO_NOMBRE }}
                                        @else
                                            Ninguna ubicación o departamento especificado
                                        @endif
                                    " readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
                @foreach($historialEstados as $estado)
                    <div class="timeline-item">
                        <div class="timeline-icon">
                            <i class="fas fa-info-circle"></i>
                        </div>
                        <div class="timeline-content">
                            <h3>{{ $estado['estado'] }}</h3>
                            <p>{{ $estado['fecha'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <script src="scripts.js"></script>
@endsection