@extends('adminlte::page')

@section('title', 'Revisar Solicitud')

@section('content_header')
    <h1>Revisar Solicitud</h1>
@stop

@section('content')

    {{-- Contenedor general para los acordeones --}}
    <div class="accordion" id="generalAccordion">

        {{-- Accordeon para los datos de la solicitud --}}
        <div class="card">
            <div class="card-header" id="headingOne">
                <h2 class="mb-0">
                    <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        Mostrar / ocultar datos de la solicitud de formularios
                    </button>
                </h2>
            </div>

            <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#generalAccordion">
                <div class="card-body">
                    {{-- Contenido de los datos de la solicitud --}}
                    <div class="row">
                        <div class="col-6">
                            <h4>Datos Solicitante</h4>
                            <p><strong><i class="fa-solid fa-user"></i> Nombre del solicitante:</strong> {{ $solicitud->solicitante->USUARIO_NOMBRES }} {{$solicitud->solicitante->USUARIO_APELLIDOS}}</p>
                            <p><strong><i class="fa-solid fa-envelope"></i> Correo del solicitante:</strong> {{ $solicitud->solicitante->email }}</p>
                            <p><strong><i class="fa-solid fa-phone"></i> Teléfono del solicitante:</strong> {{ $solicitud->solicitante->USUARIO_FONO }}</p>
                            <p><strong><i class="fa-solid fa-building-user"></i> Ubicación / Departamento:</strong> {{ $solicitud->solicitante->ubicacion->UBICACION_NOMBRE ?? $solicitud->solicitante->departamento->DEPARTAMENTO_NOMBRE }}</p>
                        </div>
                        <div class="col-6">
                            <h4>Datos Solicitud</h4>
                            <p><strong><i class="fa-solid fa-file-circle-check"></i> Estado de la solicitud:</strong>
                                @switch($solicitud->SOLICITUD_ESTADO)
                                    @case('INGRESADO')
                                    <span class="badge estado-ingresado rounded-pill">INGRESADO</span>
                                    @break
                                    @case('EN REVISION')
                                    <span class="badge estado-en-revision rounded-pill">EN REVISION</span>
                                    @break
                                    @case('AUTORIZADO')
                                    <span class="badge estado-autorizado rounded-pill">AUTORIZADO</span>
                                    @break
                                    @case('RECHAZADO')
                                    <span class="badge estado-rechazado rounded-pill">RECHAZADO</span>
                                    @break
                                    @case('TERMINADO')
                                    <span class="badge estado-terminado rounded-pill">TERMINADO</span>
                                    @break
                                @endswitch
                            </p>
                            <p><strong><i class="fa-solid fa-calendar-week"></i> Fecha y hora de solicitud:</strong> {{ $solicitud->created_at }}</p>
                            <p><strong><i class="fa-solid fa-calendar-plus"></i> Fecha y hora de inicio solicitada:</strong> {{ $solicitud->SOLICITUD_FECHA_HORA_INICIO_SOLICITADA }}</p>
                            <p><strong><i class="fa-regular fa-calendar-plus"></i> Fecha y hora de término solicitada:</strong> {{ $solicitud->SOLICITUD_FECHA_HORA_TERMINO_SOLICITADA }}</p>
                            <p><strong><i class="fa-solid fa-calendar-check"></i> Fecha y hora de inicio autorizada:</strong> {{ $solicitud->SOLICITUD_FECHA_HORA_INICIO_ASIGNADA ?? 'SIN ASIGNACION POR AHORA' }}</p>
                            <p><strong><i class="fa-regular fa-calendar-check"></i> Fecha y hora de término autorizada:</strong> {{ $solicitud->SOLICITUD_FECHA_HORA_TERMINO_ASIGNADA ?? 'SIN ASIGNACION POR AHORA' }}</p>
                        </div>
                    </div>

                    {{-- Formularios solicitados --}}
                    <h4>Descripción</h4>
                    <p><strong><i class="fa-solid fa-file-pen"></i> Descripción:</strong> {{ $solicitud->SOLICITUD_MOTIVO }}</p>

                    {{-- Contenido de los formularios solicitados --}}
                    <h4 class="mt-4">Formularios Solicitados</h4>
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

    {{-- Formulario de revision de formularios (OCULTAR DEPENDIENDO DE ESTADO) --}}
    @if(in_array($solicitud->SOLICITUD_ESTADO, ['INGRESADO', 'EN REVISION']))
        {{-- Formulario de revision --}}
        <h2>Formulario de revision</h2>
        <form action="{{ route('solicitudes.formularios.update', $solicitud->SOLICITUD_ID) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- ESTADO SOLICITUD --}}
            {{-- <div class="form-group">
                <label for="SOLICITUD_ESTADO"><i class="fa-solid fa-file-circle-check"></i> Estado de la solicitud</label>
                <select class="form-control" id="SOLICITUD_ESTADO" name="SOLICITUD_ESTADO">
                    <option value="INGRESADO" {{ $solicitud->SOLICITUD_ESTADO == 'INGRESADO' ? 'selected' : '' }}>🟠 INGRESADO</option>
                    <option value="EN REVISION" {{ $solicitud->SOLICITUD_ESTADO == 'EN REVISION' ? 'selected' : '' }}>🟡 EN REVISION</option>
                    <option value="APROBADO" {{ $solicitud->SOLICITUD_ESTADO == 'APROBADO' ? 'selected' : '' }}>🟢 APROBADO</option>
                    <option value="RECHAZADO" {{ $solicitud->SOLICITUD_ESTADO == 'RECHAZADO' ? 'selected' : '' }}>🔴 RECHAZADO</option>
                    <option value="TERMINADO" {{ $solicitud->SOLICITUD_ESTADO == 'TERMINADO' ? 'selected' : '' }}>⚫ TERMINADO</option>
                </select>
            </div> --}}

            <div class="row">
                <div class="col-md-6">
                    {{-- FECHA Y HORA DE INICIO ASIGNADA --}}
                    <div class="form-group">
                        <label for="SOLICITUD_FECHA_HORA_INICIO_ASIGNADA"><i class="fa-solid fa-calendar-days"></i> Fecha y hora de inicio asignada</label>
                        <input type="datetime-local" class="form-control" id="SOLICITUD_FECHA_HORA_INICIO_ASIGNADA" name="SOLICITUD_FECHA_HORA_INICIO_ASIGNADA" value="{{ $solicitud->SOLICITUD_FECHA_HORA_INICIO_ASIGNADA }}">
                    </div>
                </div>

                <div class="col-md-6">
                    {{-- FECHA Y HORA DE TÉRMINO ASIGNADA --}}
                    <div class="form-group">
                        <label for="SOLICITUD_FECHA_HORA_TERMINO_ASIGNADA"><i class="fa-solid fa-calendar-xmark"></i> Fecha y hora de término asignada</label>
                        <input type="datetime-local" class="form-control" id="SOLICITUD_FECHA_HORA_TERMINO_ASIGNADA" name="SOLICITUD_FECHA_HORA_TERMINO_ASIGNADA" value="{{ $solicitud->SOLICITUD_FECHA_HORA_TERMINO_ASIGNADA }}">
                    </div>
                </div>
            </div>

            {{-- OBSERVACION --}}
            <div class="form-group">
                <label for="REVISION_SOLICITUD_OBSERVACION"><i class="fa-solid fa-eye"></i> Observación</label>
                <textarea class="form-control" id="REVISION_SOLICITUD_OBSERVACION" name="REVISION_SOLICITUD_OBSERVACION" rows="3">{{ $solicitud->REVISION_SOLICITUD_OBSERVACION }}</textarea>
            </div>

            {{-- BOTONES DE ENVIO Y REGRESAR A INDEX DE SOLICITUDES FORMULARIOS --}}
            <a href="{{ route('solicitudes.formularios.index') }}" class="btn btn-secondary"><i class="fa-solid fa-hand-point-left"></i> Volver</a>
            <button type="submit" name="action" value="guardar" class="btn agregar"><i class="fa-solid fa-floppy-disk"></i> Guardar revisión</button>
            <button type="submit" name="action" value="finalizar_revision" class="btn btn-success"><i class="fa-solid fa-clipboard-check"></i> Finalizar revisiones y autorizar</button>
            <button type="submit" name="action" value="rechazar" class="btn btn-danger"><i class="fa-solid fa-ban"></i> Rechazar</button>
        </form>
    @else
        <a href="{{ route('solicitudes.formularios.index') }}" class="btn btn-secondary"><i class="fa-solid fa-hand-point-left"></i> Volver</a>
    @endif
@stop

@section('css')
    <style>
        #carouselObservaciones .carousel-indicators li {
            background-color: orange; /* Cambia el color de fondo a naranja */
        }
        #carouselObservaciones .carousel-indicators .active {
            background-color: darkorange; /* Un tono más oscuro para el indicador activo */
        }

        .tablacolor {
            background-color: #723E72; /* Color de fondo personalizado */
            color: #fff; /* Color de texto personalizado */
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

        .estado-autorizado {
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
    {{-- Llamar a fechasAutorizadas.js --}}
    <script src="{{ asset('js/Components/fechasAutorizadas.js') }}"></script>
@stop
