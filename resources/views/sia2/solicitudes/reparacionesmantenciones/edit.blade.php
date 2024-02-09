@extends('adminlte::page')

@section('title', 'Revisar solicitud')

@section('content_header')
    <h1>Revisar solicitud</h1>
@stop

@section('content')
    {{-- sweetalerts de session --}}
    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                Swal.fire({
                    icon: 'success',
                    title: '{{ session('success') }}',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#0064A0'
                });
            });
        </script>
    @elseif(session('error'))
        <script>
            document.addEventListener('DOMContentLoader', () => {
                Swal.fire([
                    icon: 'error',
                    title: '{{ session('error') }}',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#0064A0'
                ]);
            });
        </script>
    @endif

    {{-- Contenedor general para los acordeones --}}
    <div class="accordion" id="generalAccordion">

        {{-- Acordeon para datos de la solicitud --}}
        <div class="card">
            <div class="card-header" id="datosSolicitudHeading">
                <h2 class="mb-0">
                    <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#datosSolicitudCollapse" aria-expanded="true" aria-controls="datosSolicitudCollapse">
                        Datos de la solicitud de reparación / mantención
                    </button>
                </h2>
            </div>
            <div id="datosSolicitudCollapse" class="collapse show" aria-labelledby="datosSolicitudHeading" data-parent="#generalAccordion">
                <div class="card-body">
                    {{-- Contenido de datos de la solicitud --}}
                    <div class="row">
                        <div class="col-6">
                            <h4>Datos solicitante</h4>
                            <p><strong>Nombre del solicitante:</strong> {{$solicitud->solicitante->USUARIO_NOMBRES}} {{$solicitud->solicitante->USUARIO_APELLIDOS}}</p>
                            <p><strong>Correo electrónico:</strong> {{$solicitud->solicitante->email}}</p>
                            <p><strong>Teléfono:</strong> {{$solicitud->solicitante->USUARIO_FONO}}</p>
                            <p><strong>Ubicación / Departamento: </strong> {{$solicitud->solicitante->ubicacion->UBICACION_NOMBRE ?? $solicitud->solicitante->departamento->DEPARTAMENTO_NOMBRE}}</p>
                        </div>
                        <div class="col-6">
                            <h4>Datos solicitud</h4>
                            <p><strong>Estado de la solicitud:</strong>
                                @switch($solicitud->SOLICITUD_REPARACION_ESTADO)
                                    @case('INGRESADO')
                                    <span class="badge badge-secondary">INGRESADO</span>
                                    @break
                                    @case('EN REVISION')
                                    <span class="badge badge-primary">EN REVISION</span>
                                    @break
                                    @case('APROBADO')
                                    <span class="badge badge-success">APROBADO</span>
                                    @break
                                    @case('RECHAZADO')
                                    <span class="badge badge-danger">RECHAZADO</span>
                                    @break
                                    @case('TERMINADO')
                                    <span class="badge badge-warning">TERMINADO</span>
                                    @break
                                @endswitch
                            </p>
                            @if ($solicitud->SOLICITUD_REPARACION_TIPO == 'MANTENCION')
                                <p><strong>Tipo de solicitud:</strong> Mantención</p>
                            @else
                                <p><strong>Tipo de solicitud:</strong> Reparación</p>
                            @endif
                            <p><strong>Categoría de solicitud:</strong> {{ $solicitud->categoria->CATEGORIA_REPARACION_NOMBRE }}</p>
                            <p><strong>Fecha y hora de solicitud:</strong> {{ $solicitud->created_at }}</p>
                            <p><strong>Fecha y hora de inicio autorizada:</strong> {{ $solicitud->SOLICITUD_REPARACION_FECHA_HORA_INICIO ?? 'SIN ASIGNACION POR AHORA' }}</p>
                            <p><strong>Fecha y hora de término autorizada:</strong> {{ $solicitud->SOLICITUD_REPARACION_FECHA_HORA_TERMINO ?? 'SIN ASIGNACION POR AHORA' }}</p>
                        </div>
                    </div>
                    <h4>Descripción</h4>
                    <p><strong>Descripción:</strong> {{ $solicitud->SOLICITUD_REPARACION_MOTIVO }}</p>
                </div>
            </div>
        </div>

        {{-- Acordeon para las observaciones --}}
        @if ($solicitud->revisiones->isNotEmpty())
            <div class="card">
                <div class="card-header" id="headingObservaciones">
                    <h2 class="mb-0">
                        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseObservaciones" aria-expanded="false" aria-controls="collapseObservaciones">
                            Observaciones
                        </button>
                    </h2>
                </div>

                <div id="collapseObservaciones" class="collapse" aria-labelledby="headingObservaciones" data-parent="#generalAccordion">
                    <div class="card-body">
                        {{-- Contenido de las observaciones --}}
                        <div id="carouselObservaciones" class="carousel slide" data-ride="carousel">
                            <ol class="carousel-indicators">
                                @foreach ($solicitud->revisiones as $key => $observacion)
                                    <li data-target="#carouselObservaciones" data-slide-to="{{ $key }}" class="{{ $key == 0 ? 'active' : '' }}"></li>
                                @endforeach
                            </ol>

                            <div class="carousel-inner text-center">
                                @foreach ($solicitud->revisiones as $key => $observacion)
                                    <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                                        <h5>Observación {{ $key + 1 }}</h5>
                                        <p>"{{ $observacion->REVISION_SOLICITUD_OBSERVACION }}" -- {{$observacion->gestionador->USUARIO_NOMBRES}} {{$observacion->gestionador->USUARIO_APELLIDOS}}</p>
                                    </div>
                                @endforeach
                            </div>

                            <div class="carousel-footer d-flex justify-content-between" style="margin-top: 2%;">
                                <a class="carousel-control-prev btn btn-primary btn-sm" href="#carouselObservaciones" role="button" data-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="sr-only">Anterior</span>
                                </a>
                                <a class="carousel-control-next btn btn-primary btn-sm" href="#carouselObservaciones" role="button" data-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="sr-only">Siguiente</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    {{-- Formulario para revisar la solicitud --}}

    <form action="{{ route('solicitudes.reparaciones.update', $solicitud->SOLICITUD_REPARACION_ID) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- Estado solicitud --}}
        <div class="form-group">
            <label for="SOLICITUD_REPARACION_ESTADO">Estado de la solicitud</label>
            <select class="form-control" id="SOLICITUD_REPARACION_ESTADO" name="SOLICITUD_REPARACION_ESTADO">
                <option value="INGRESADO" {{ $solicitud->SOLICITUD_REPARACION_ESTADO == 'INGRESADO' ? 'selected' : '' }}>INGRESADO</option>
                <option value="EN REVISION" {{ $solicitud->SOLICITUD_REPARACION_ESTADO == 'EN REVISION' ? 'selected' : '' }}>EN REVISION</option>
                <option value="APROBADO" {{ $solicitud->SOLICITUD_REPARACION_ESTADO == 'APROBADO' ? 'selected' : '' }}>APROBADO</option>
                <option value="RECHAZADO" {{ $solicitud->SOLICITUD_REPARACION_ESTADO == 'RECHAZADO' ? 'selected' : '' }}>RECHAZADO</option>
                <option value="TERMINADO" {{ $solicitud->SOLICITUD_REPARACION_ESTADO == 'TERMINADO' ? 'selected' : '' }}>TERMINADO</option>
            </select>
        </div>

        <div class="row">
            <div class="col-md-6">
                {{-- Fecha y hora de inicio --}}
                <div class="form-group">
                    <label for="SOLICITUD_REPARACION_FECHA_HORA_INICIO">Fecha y hora de inicio autorizada</label>
                    <input type="datetime-local" class="form-control" id="SOLICITUD_REPARACION_FECHA_HORA_INICIO" name="SOLICITUD_REPARACION_FECHA_HORA_INICIO" value="{{ $solicitud->SOLICITUD_REPARACION_FECHA_HORA_INICIO }}">
                </div>
            </div>
            <div class="col-md-6">
                {{-- Fecha y hora de término --}}
                <div class="form-group">
                    <label for="SOLICITUD_REPARACION_FECHA_HORA_TERMINO">Fecha y hora de término autorizada</label>
                    <input type="datetime-local" class="form-control" id="SOLICITUD_REPARACION_FECHA_HORA_TERMINO" name="SOLICITUD_REPARACION_FECHA_HORA_TERMINO" value="{{ $solicitud->SOLICITUD_REPARACION_FECHA_HORA_TERMINO }}">
                </div>
            </div>
        </div>

        {{-- Observaciones --}}
        <div class="form-group">
            <label for="REVISION_SOLICITUD_OBSERVACION">Observaciones</label>
            <textarea class="form-control" id="REVISION_SOLICITUD_OBSERVACION" name="REVISION_SOLICITUD_OBSERVACION" rows="3"></textarea>
        </div>

        {{-- Botones --}}
        <div class="form-group">
            <a href="{{ route('solicitudes.reparaciones.index') }}" class="btn btn-danger">Volver</a>
            <button type="submit" class="btn btn-primary">Guardar cambios</button>
        </div>
    </form>

@stop
