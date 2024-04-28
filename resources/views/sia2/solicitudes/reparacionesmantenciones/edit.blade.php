@extends('adminlte::page')

@section('title', 'Revisar Solicitud')

@section('content_header')
    <h1>Revisar Solicitud</h1>
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
                            <p><strong><i class="fa-solid fa-user"></i> Nombre del solicitante:</strong> {{$solicitud->solicitante->USUARIO_NOMBRES}} {{$solicitud->solicitante->USUARIO_APELLIDOS}}</p>
                            <p><strong><i class="fa-solid fa-envelope"></i> Correo electrónico:</strong> {{$solicitud->solicitante->email}}</p>
                            <p><strong><i class="fa-solid fa-phone"></i> Teléfono:</strong> {{$solicitud->solicitante->USUARIO_FONO}}</p>
                            <p><strong><i class="fa-solid fa-building-user"></i> Ubicación / Departamento: </strong> {{$solicitud->solicitante->ubicacion->UBICACION_NOMBRE ?? $solicitud->solicitante->departamento->DEPARTAMENTO_NOMBRE}}</p>
                        </div>
                        <div class="col-6">
                            <h4>Datos solicitud</h4>
                            <p><strong><i class="fa-solid fa-file-circle-check"></i> Estado de la solicitud:</strong>
                                @switch($solicitud->SOLICITUD_REPARACION_ESTADO)
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
                            @if ($solicitud->SOLICITUD_REPARACION_TIPO == 'MANTENCION')
                                <p><strong><i class="fa-solid fa-file-pen"></i> Tipo de solicitud:</strong> Mantención</p>
                            @else
                                <p><strong><i class="fa-solid fa-file-pen"></i> Tipo de solicitud:</strong> Reparación</p>
                            @endif
                            <p><strong><i class="fa-solid fa-warehouse"></i> / </i><i class="fa-solid fa-car-on"></i> Categoría de solicitud:</strong> {{ $solicitud->categoria->CATEGORIA_REPARACION_NOMBRE }}</p>
                            <p><strong><i class="fa-solid fa-calendar-week"></i> Fecha y hora de solicitud:</strong> {{ $solicitud->created_at }}</p>
                            <p><strong><i class="fa-solid fa-calendar-check"></i> Fecha y hora cumplimiento solicitud:</strong> {{ $solicitud->SOLICITUD_REPARACION_FECHA_HORA_INICIO ?? 'SIN FECHA POR AHORA' }}</p>
                        </div>
                    </div>
                    <h4>Descripción</h4>
                    <p><strong><i class="fa-solid fa-file-pen"></i> Descripción:</strong> {{ $solicitud->SOLICITUD_REPARACION_MOTIVO }}</p>
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
    @if(in_array($solicitud->SOLICITUD_REPARACION_ESTADO, ['INGRESADO', 'EN REVISION']))
        <h2>Revisar solicitud</h2>
        <form action="{{ route('solicitudes.reparaciones.update', $solicitud->SOLICITUD_REPARACION_ID) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- Estado solicitud --}}
            {{-- <div class="form-group">
                <label for="SOLICITUD_REPARACION_ESTADO"><i class="fa-solid fa-file-circle-check"></i> Estado de la solicitud</label>
                <select class="form-control" id="SOLICITUD_REPARACION_ESTADO" name="SOLICITUD_REPARACION_ESTADO">
                    <option value="INGRESADO" {{ $solicitud->SOLICITUD_REPARACION_ESTADO == 'INGRESADO' ? 'selected' : '' }}>🟠 INGRESADO</option>
                    <option value="EN REVISION" {{ $solicitud->SOLICITUD_REPARACION_ESTADO == 'EN REVISION' ? 'selected' : '' }}>🟡 EN REVISION</option>
                    <option value="APROBADO" {{ $solicitud->SOLICITUD_REPARACION_ESTADO == 'APROBADO' ? 'selected' : '' }}>🟢 APROBADO</option>
                    <option value="RECHAZADO" {{ $solicitud->SOLICITUD_REPARACION_ESTADO == 'RECHAZADO' ? 'selected' : '' }}>🔴 RECHAZADO</option>
                    <option value="TERMINADO" {{ $solicitud->SOLICITUD_REPARACION_ESTADO == 'TERMINADO' ? 'selected' : '' }}>⚫ TERMINADO</option>
                </select>
            </div> --}}

            <div class="row">
                <div class="col-md-12">
                    {{-- Fecha y hora de inicio --}}
                    <div class="form-group">
                        <label for="SOLICITUD_REPARACION_FECHA_HORA_INICIO"><i class="fa-solid fa-calendar-days"></i> Fecha y hora cumplimiento solicitud</label>
                        <input type="datetime-local" class="form-control" id="SOLICITUD_REPARACION_FECHA_HORA_INICIO" name="SOLICITUD_REPARACION_FECHA_HORA_INICIO" value="{{ $solicitud->SOLICITUD_REPARACION_FECHA_HORA_INICIO }}" required>
                    </div>
                    {{-- Contenedor de errores --}}
                    @error('SOLICITUD_REPARACION_FECHA_HORA_INICIO')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                {{-- Fecha y hora de término --}}
                {{-- <div class="col-md-6">
                    <div class="form-group">
                        <label for="SOLICITUD_REPARACION_FECHA_HORA_TERMINO"><i class="fa-solid fa-calendar-xmark"></i> Fecha y hora de término autorizada</label>
                        <input type="datetime-local" class="form-control" id="SOLICITUD_REPARACION_FECHA_HORA_TERMINO" name="SOLICITUD_REPARACION_FECHA_HORA_TERMINO" value="{{ $solicitud->SOLICITUD_REPARACION_FECHA_HORA_TERMINO }}">
                    </div>
                </div> --}}
            </div>

            {{-- Observaciones --}}
            <div class="form-group">
                <label for="REVISION_SOLICITUD_OBSERVACION"><i class="fa-solid fa-eye"></i> Observaciones</label>
                <textarea class="form-control" id="REVISION_SOLICITUD_OBSERVACION" name="REVISION_SOLICITUD_OBSERVACION" rows="3" ></textarea>
                @error('REVISION_SOLICITUD_OBSERVACION')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            {{-- Botones --}}
            <div class="form-group">
                <a href="{{ route('solicitudes.reparaciones.index') }}" class="btn btn-secondary"><i class="fa-solid fa-hand-point-left"></i> Volver</a>
                <button type="submit" name="action" value="guardar" class="btn agregar"><i class="fa-solid fa-floppy-disk"></i> Guardar revisión</button>
                <button type="submit" name="action" value="finalizar_revision" class="btn btn-success"><i class="fa-solid fa-clipboard-check"></i> Finalizar revisiones y aprobar</button>
                <button type="submit" name="action" value="rechazar" class="btn btn-danger"><i class="fa-solid fa-ban"></i> Rechazar</button>
            </div>
        </form>
    @else
        <a href="{{ route('solicitudes.reparaciones.index') }}" class="btn btn-secondary"><i class="fa-solid fa-hand-point-left"></i> Volver</a>
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
    {{-- Llamar a fechasAutorizadas.js --}}
    {{-- <script src="{{ asset('js/Components/fechasReparaciones.js') }}"></script> --}}
        {{-- Inicializa flatpickers --}}
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Obtener la fecha y hora actual
                let today = new Date();

                // Obtener el valor capturado por la base de datos para la fecha de inicio
                let fechaInicioValue = "{{$solicitud->SOLICITUD_REPARACION_FECHA_HORA_INICIO }}";

                // Inicializar Flatpickr para el campo de fecha y hora de inicio autorizada
                flatpickr("#SOLICITUD_REPARACION_FECHA_HORA_INICIO", {
                    enableTime: true,
                    dateFormat: "Y-m-d H:i:s",
                    altFormat: "d-m-Y H:i",
                    altInput: true,
                    locale: "es",
                    // Establecer el valor mínimo solo si hay un valor capturado por la base de datos
                    minDate: fechaInicioValue ? fechaInicioValue : today,
                    maxDate: new Date(today.getFullYear(), 11, 31), // Permitir fechas hasta fin de año
                    minTime: "08:00", // Hora mínima permitida
                    maxTime: "19:00", // Hora máxima permitida
                    placeholder: 'Seleccione la fecha y hora de inicio' // Añadido placeholder
                });

                // Inicializar Flatpickr para el campo de fecha y hora de término autorizada
                flatpickr("#SOLICITUD_REPARACION_FECHA_HORA_TERMINO", {
                    enableTime: true,
                    dateFormat: "Y-m-d H:i:s",
                    altFormat: "d-m-Y H:i",
                    altInput: true,
                    locale: "es",
                    minDate: today, // Establecer la fecha mínima como la fecha actual
                    maxDate: new Date(today.getFullYear() + 1, 1, 28), // Permitir fechas hasta febrero del siguiente año
                    minTime: "08:00", // Hora mínima permitida
                    maxTime: "19:00", // Hora máxima permitida
                    placeholder: 'Seleccione la fecha y hora de término' // Añadido placeholder
                });
            });
        </script>
@stop
