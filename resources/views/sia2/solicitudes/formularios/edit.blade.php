@extends('adminlte::page')

@section('title', 'Revisar solicitud')

@section('content_header')
    <h1>Revisar solicitud</h1>
@stop

@section('content')
    <div class="accordion" id="accordionExample">
        <div class="card">
            <div class="card-header" id="headingOne">
                <h2 class="mb-0">
                    <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        Mostrar / ocultar datos de la solicitud de formularios
                    </button>
                </h2>
            </div>

            <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
                {{-- Mostrar datos de la solicitud en una tarjeta como en la vista show --}}
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <h4>Datos Solicitante</h4>
                            <p><strong>Nombre del solicitante:</strong> {{ $solicitud->solicitante->USUARIO_NOMBRES }} {{$solicitud->solicitante->USUARIO_APELLIDOS}}</p>
                            <p><strong>Correo del solicitante:</strong> {{ $solicitud->solicitante->email }}</p>
                            <p><strong>Teléfono del solicitante:</strong> {{ $solicitud->solicitante->USUARIO_FONO }}</p>
                            <p><strong>Ubicación / Departamento:</strong> {{ $solicitud->solicitante->ubicacion->UBICACION_NOMBRE ?? $solicitud->solicitante->departamento->DEPARTAMENTO_NOMBRE }}</p>
                        </div>
                        <div class="col-6">
                            <h4>Datos Solicitud</h4>
                            <p><strong>Estado de la solicitud:</strong>
                                @switch($solicitud->SOLICITUD_ESTADO)
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
                            <p><strong>Fecha y hora de solicitud:</strong> {{ $solicitud->created_at }}</p>
                            <p><strong>Fecha y hora de inicio solicitada:</strong> {{ $solicitud->SOLICITUD_FECHA_HORA_INICIO_SOLICITADA }}</p>
                            <p><strong>Fecha y hora de término solicitada:</strong> {{ $solicitud->SOLICITUD_FECHA_HORA_TERMINO_SOLICITADA }}</p>
                            <p><strong>Fecha y hora de inicio autorizada:</strong> {{ $solicitud->SOLICITUD_FECHA_HORA_INICIO_ASIGNADA ?? 'SIN ASIGNACION POR AHORA' }}</p>
                            <p><strong>Fecha y hora de término autorizada:</strong> {{ $solicitud->SOLICITUD_FECHA_HORA_TERMINO_ASIGNADA ?? 'SIN ASIGNACION POR AHORA' }}</p>
                        </div>
                    </div>
                    <h4>Descripción</h4>
                    <p><strong>Descripción:</strong> {{ $solicitud->SOLICITUD_MOTIVO }}</p>
                    {{-- Llamar a relacion 'formularios' para traer el pedido en una tabla --}}
                    <h4 class="mt-4">Formularios Solicitados</h4>
                    <table class="table table-bordered">
                        <thead>
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
        </div>
    </div>

    <div class="accordion" id="accordionObservaciones">
        <div class="card">
            <div class="card-header" id="headingObservaciones">
                <h2 class="mb-0">
                    <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseObservaciones" aria-expanded="false" aria-controls="collapseObservaciones">
                        Observaciones
                    </button>
                </h2>
            </div>

            <div id="collapseObservaciones" class="collapse" aria-labelledby="headingObservaciones" data-parent="#accordionObservaciones">
                <div class="card-body">
                    @if ($solicitud->revisiones->isEmpty())
                        <p>SOLICITUD SIN OBSERVACIONES</p>
                    @else
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
                                        <p>{{ $observacion->REVISION_SOLICITUD_OBSERVACION }}</p>
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
                    @endif
                </div>
            </div>
        </div>
    </div>




    {{-- Formulario con los datos que recibira la request de update
            'SOLICITUD_ESTADO' => 'required|string|max:255|in:INGRESADO,EN REVISION,APROBADO,RECHAZADO,TERMINADO',
            'SOLICITUD_FECHA_HORA_INICIO_ASIGNADA' => 'required|date',
            'SOLICITUD_FECHA_HORA_TERMINO_ASIGNADA' => 'required|date|after:SOLICITUD_FECHA_HORA_INICIO_ASIGNADA',
            'REVISION_SOLICITUD_OBSERVACION' => 'required|string|max:255',
            'REVISION_SOLICITUD_FECHA_HORA_TRAMITACION' => 'required|date', --}}
    <h2>Formulario de revision</h2>
    <form action="{{ route('solicitudes.formularios.update', $solicitud->SOLICITUD_ID) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- ESTADO SOLICITUD --}}
        <div class="form-group">
            <label for="SOLICITUD_ESTADO">Estado de la solicitud</label>
            <select class="form-control" id="SOLICITUD_ESTADO" name="SOLICITUD_ESTADO">
                <option value="INGRESADO" {{ $solicitud->SOLICITUD_ESTADO == 'INGRESADO' ? 'selected' : '' }}>INGRESADO</option>
                <option value="EN REVISION" {{ $solicitud->SOLICITUD_ESTADO == 'EN REVISION' ? 'selected' : '' }}>EN REVISION</option>
                <option value="APROBADO" {{ $solicitud->SOLICITUD_ESTADO == 'APROBADO' ? 'selected' : '' }}>APROBADO</option>
                <option value="RECHAZADO" {{ $solicitud->SOLICITUD_ESTADO == 'RECHAZADO' ? 'selected' : '' }}>RECHAZADO</option>
                <option value="TERMINADO" {{ $solicitud->SOLICITUD_ESTADO == 'TERMINADO' ? 'selected' : '' }}>TERMINADO</option>
            </select>
        </div>

        {{-- FECHA Y HORA DE INICIO ASIGNADA --}}
        <div class="form-group">
            <label for="SOLICITUD_FECHA_HORA_INICIO_ASIGNADA">Fecha y hora de inicio asignada</label>
            <input type="datetime-local" class="form-control" id="SOLICITUD_FECHA_HORA_INICIO_ASIGNADA" name="SOLICITUD_FECHA_HORA_INICIO_ASIGNADA" value="{{ $solicitud->SOLICITUD_FECHA_HORA_INICIO_ASIGNADA }}">
        </div>

        {{-- FECHA Y HORA DE TÉRMINO ASIGNADA --}}
        <div class="form-group">
            <label for="SOLICITUD_FECHA_HORA_TERMINO_ASIGNADA">Fecha y hora de término asignada</label>
            <input type="datetime-local" class="form-control" id="SOLICITUD_FECHA_HORA_TERMINO_ASIGNADA" name="SOLICITUD_FECHA_HORA_TERMINO_ASIGNADA" value="{{ $solicitud->SOLICITUD_FECHA_HORA_TERMINO_ASIGNADA }}">
        </div>

        {{-- OBSERVACION --}}
        <div class="form-group">
            <label for="REVISION_SOLICITUD_OBSERVACION">Observación</label>
            <textarea class="form-control" id="REVISION_SOLICITUD_OBSERVACION" name="REVISION_SOLICITUD_OBSERVACION" rows="3">{{ $solicitud->REVISION_SOLICITUD_OBSERVACION }}</textarea>
        </div>

        {{-- FECHA Y HORA DE TRAMITACIÓN --}}
        <div class="form-group">
            <label for="REVISION_SOLICITUD_FECHA_HORA_TRAMITACION">Fecha y hora de trámite</label>
            <input type="datetime-local" class="form-control" id="REVISION_SOLICITUD_FECHA_HORA_TRAMITACION" name="REVISION_SOLICITUD_FECHA_HORA_TRAMITACION" value="{{ $solicitud->REVISION_SOLICITUD_FECHA_HORA_TRAMITACION }}">
        </div>

        {{-- BOTONES DE ENVIO Y REGRESAR A INDEX DE SOLICITUDES FORMULARIOS --}}
        <a href="{{ route('solicitudes.formularios.index') }}" class="btn btn-secondary">Cancelar</a>
        <button type="submit" class="btn btn-primary">Actualizar</button>
    </form>
@stop

@section('css')
    <style>
        #carouselObservaciones .carousel-indicators li {
            background-color: orange; /* Cambia el color de fondo a naranja */
        }
        #carouselObservaciones .carousel-indicators .active {
            background-color: darkorange; /* Un tono más oscuro para el indicador activo */
        }
    </style>
@stop

