@extends('adminlte::page')

@section('title', 'Detalles de Solicitud')

@section('content_header')
    <h1>Detalles de Solicitud</h1>
@stop

@section('content')
    {{-- Contenedor general para los acordeones --}}
    <div class="accordion" id="generalAccordion">

        {{-- Acordeon para datos de la solicitud --}}
        <div class="card">
            <div class="card-header" id="datosSolicitudHeading">
                <h2 class="mb-0">
                    <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#datosSolicitudCollapse" aria-expanded="true" aria-controls="datosSolicitudCollapse">
                        Datos de la solicitud de bodega
                    </button>
                </h2>
            </div>
            <div id="datosSolicitudCollapse" class="collapse show" aria-labelledby="datosSolicitudHeading" data-parent="#generalAccordion">
                <div class="card-body">
                    {{-- Contenido de datos de la solicitud --}}
                    <div class="row">
                        <div class="col-6">
                            <h4>Datos solicitante</h4>
                            <p><strong><i class="fa-solid fa-user"></i> Nombre del solicitante:</strong> {{$solicitud->solicitante->USUARIO_NOMBRES}} {{$solicitud->solicitante->USUARIO_APELLIDOS}}</p>
                            <p><strong><i class="fa-solid fa-envelope"></i> Correo electrónico:</strong> {{$solicitud->solicitante->email}}</p>
                            <p><strong><i class="fa-solid fa-phone"></i> Teléfono:</strong> {{$solicitud->solicitante->USUARIO_FONO}}</p>
                            <p><strong><i class="fa-solid fa-building-user"></i> Ubicación / Departamento: </strong> {{$solicitud->solicitante->ubicacion->UBICACION_NOMBRE ?? $solicitud->solicitante->departamento->DEPARTAMENTO_NOMBRE}}</p>
                        </div>
                        <div class="col-6">
                            <h4>Datos solicitud</h4>
                            <p><strong><i class="fa-solid fa-file-circle-check"></i> Estado de la solicitud:</strong>
                                @switch($solicitud->SOLICITUD_ESTADO)
                                    @case('INGRESADO')
                                    <span class="badge estado-ingresado rounded-pill">INGRESADO</span>
                                    @break
                                    @case('EN REVISION')
                                    <span class="badge estado-en-revision rounded-pill">EN REVISION</span>
                                    @break
                                    @case('APROBADO')
                                    <span class="badge estado-aprobado rounded-pill">APROBADO</span>
                                    @break
                                    @case('RECHAZADO')
                                    <span class="badge estado-rechazado rounded-pill">RECHAZADO</span>
                                    @break
                                    @case('TERMINADO')
                                    <span class="badge estado-terminado rounded-pill">TERMINADO</span>
                                    @break
                                @endswitch
                            </p>
                            <p><strong><i class="fa-solid fa-calendar-week"></i> Fecha y hora de solicitud:</strong> {{ $solicitud->mostrarFecha($solicitud->created_at) }}</p>
                            <p><strong><i class="fa-solid fa-calendar-plus"></i> Fecha y hora de inicio solicitada:</strong> {{ $solicitud->mostrarFecha($solicitud->SOLICITUD_FECHA_HORA_INICIO_SOLICITADA) }}</p>
                            <p><strong><i class="fa-regular fa-calendar-plus"></i> Fecha y hora de término solicitada:</strong> {{ $solicitud->mostrarFecha($solicitud->SOLICITUD_FECHA_HORA_TERMINO_SOLICITADA) }}</p>
                            <p><strong><i class="fa-solid fa-calendar-check"></i> Fecha y hora de inicio autorizada:</strong> {{ $solicitud->mostrarFecha($solicitud->SOLICITUD_FECHA_HORA_INICIO_ASIGNADA) ?? 'SIN ASIGNACION POR AHORA' }}</p>
                            <p><strong><i class="fa-regular fa-calendar-check"></i> Fecha y hora de término autorizada:</strong> {{ $solicitud->mostrarFecha($solicitud->SOLICITUD_FECHA_HORA_TERMINO_ASIGNADA) ?? 'SIN ASIGNACION POR AHORA' }}</p>
                        </div>
                    </div>
                    <h4>Descripción</h4>
                    <p><strong><i class="fa-solid fa-file-pen"></i> Descripción:</strong> {{ $solicitud->SOLICITUD_MOTIVO }}</p>

                    <h5>Bodega solicitada</h5>
                    <table class="table table-bordered">
                        <thead class="tablacolor">
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

    <a href="{{ route('solicitudes.bodegas.index') }}" class="btn btn-secondary mt-4"><i class="fa-solid fa-hand-point-left"></i> Volver</a>
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
