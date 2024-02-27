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
                        Datos de la solicitud de salas
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
                                    <span class="badge estado-aceptado rounded-pill">APROBADO</span>
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
                    <h4>Descripción</h4>
                    <p><strong><i class="fa-solid fa-file-pen"></i> Descripción:</strong> {{ $solicitud->SOLICITUD_MOTIVO }}</p>
                </div>
            </div>
        </div>

        {{-- Acordeon para sala solicitada y asignada --}}
        <div class="card">
            <div class="card-header" id="salasSolicitadasHeading">
                <h2 class="mb-0">
                    <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#salasSolicitadasCollapse" aria-expanded="true" aria-controls="salasSolicitadasCollapse">
                        Sala solicitada y asignada
                    </button>
                </h2>
            </div>
            <div id="salasSolicitadasCollapse" class="collapse" aria-labelledby="salasSolicitadasHeading" data-parent="#generalAccordion">
                <div class="card-body">
                    {{-- Aquí va el contenido de las salas solicitadas --}}
                    <h5>Sala Solicitadas</h5>
                    <table class="table table-bordered">
                        <thead class="tablacolor">
                            <tr>
                                <th>Nombre sala</th>
                                <th>Capacidad sala</th>
                                <th>Estado sala</th>
                                <th>SALA AUTORIZADA</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ $solicitud->salas->sala->SALA_NOMBRE }}</td>
                                <td>{{ $solicitud->salas->sala->SALA_CAPACIDAD }}</td>
                                <td>
                                    @switch($solicitud->salas->sala->SALA_ESTADO)
                                        @case('DISPONIBLE')
                                            <span class="badge estado-aceptado rounded-pill">DISPONIBLE</span>
                                            @break
                                        @case('OCUPADA')
                                            <span class="badge estado-rechazado rounded-pill">OCUPADA</span>
                                            @break
                                        @default
                                            <span class="badge badge-secondary">{{ $solicitud->salas->sala->SALA_ESTADO }}</span>
                                    @endswitch
                                </td>
                                <td>{{$solicitud->salas->salaAsignada->SALA_NOMBRE ?? 'NO SE HA ASIGNADO UNA SALA POR AHORA.'}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Acordeon para equipos solicitados --}}
        <div class="card">
            <div class="card-header" id="equiposSolicitadosHeading">
                <h2 class="mb-0">
                    <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#equiposSolicitadosCollapse" aria-expanded="false" aria-controls="equiposSolicitadosCollapse">
                        Equipos solicitados
                    </button>
                </h2>
            </div>
            <div id="equiposSolicitadosCollapse" class="collapse" aria-labelledby="equiposSolicitadosHeading" data-parent="#generalAccordion">
                <div class="card-body">
                    {{-- Contenido de equipos solicitados --}}
                    <table class="table table-bordered">
                        <thead class="tablacarrito">
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
                                    <td>{{ $tipoEquipo->pivot->SOLICITUD_EQUIPOS_CANTIDAD_AUTORIZADA }}</td>
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

    {{-- Formulario para revisar la solicitud --}}
    @if(in_array($solicitud->SOLICITUD_ESTADO, ['INGRESADO', 'EN REVISION']))
        <h2>Formulario de revision</h2>
        <form action="{{ route('solicitudes.salas.update', $solicitud->SOLICITUD_ID) }}" method="POST">
            @csrf
            @method('PUT')
            {{-- Estado de la solicitud --}}
            <div class="form-group">
                <label for="SOLICITUD_ESTADO"><i class="fa-solid fa-file-circle-check"></i> Estado de la solicitud</label>
                <select class="form-control" id="SOLICITUD_ESTADO" name="SOLICITUD_ESTADO">
                    <option value="INGRESADO" {{ old('SOLICITUD_ESTADO', $solicitud->SOLICITUD_ESTADO) == 'INGRESADO' ? 'selected' : '' }}>🟠 INGRESADO</option>
                    <option value="EN REVISION" {{ old('SOLICITUD_ESTADO', $solicitud->SOLICITUD_ESTADO) == 'EN REVISION' ? 'selected' : '' }}>🟡 EN REVISION</option>
                    <option value="APROBADO" {{ old('SOLICITUD_ESTADO', $solicitud->SOLICITUD_ESTADO) == 'APROBADO' ? 'selected' : '' }}>🟢 APROBADO</option>
                    <option value="RECHAZADO" {{ old('SOLICITUD_ESTADO', $solicitud->SOLICITUD_ESTADO) == 'RECHAZADO' ? 'selected' : '' }}>🔴 RECHAZADO</option>
                    <option value="TERMINADO" {{ old('SOLICITUD_ESTADO', $solicitud->SOLICITUD_ESTADO) == 'TERMINADO' ? 'selected' : '' }}>⚫ TERMINADO</option>
                </select>
                @error('SOLICITUD_ESTADO')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="row">
                <div class="col-md-6">
                    {{-- Fecha y hora de inicio autorizada --}}
                    <div class="form-group">
                        <label for="SOLICITUD_FECHA_HORA_INICIO_ASIGNADA"><i class="fa-solid fa-calendar-days"></i> Fecha y hora de inicio autorizada</label>
                        <input type="datetime-local" class="form-control" id="SOLICITUD_FECHA_HORA_INICIO_ASIGNADA" name="SOLICITUD_FECHA_HORA_INICIO_ASIGNADA" value="{{ old('SOLICITUD_FECHA_HORA_INICIO_ASIGNADA', $solicitud->SOLICITUD_FECHA_HORA_INICIO_ASIGNADA) }}">
                        @error('SOLICITUD_FECHA_HORA_INICIO_ASIGNADA')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    {{-- Fecha y hora de término autorizada --}}
                    <div class="form-group">
                        <label for="SOLICITUD_FECHA_HORA_TERMINO_ASIGNADA"><i class="fa-solid fa-calendar-xmark"></i> Fecha y hora de término autorizada</label>
                        <input type="datetime-local" class="form-control" id="SOLICITUD_FECHA_HORA_TERMINO_ASIGNADA" name="SOLICITUD_FECHA_HORA_TERMINO_ASIGNADA" value="{{ old('SOLICITUD_FECHA_HORA_TERMINO_ASIGNADA', $solicitud->SOLICITUD_FECHA_HORA_TERMINO_ASIGNADA) }}">
                        @error('SOLICITUD_FECHA_HORA_TERMINO_ASIGNADA')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Sala asignada (por defecto marcar la solicitada) --}}
            <div class="form-group">
                <label for="SOLICITUD_SALA_ID_ASIGNADA"><i class="fa-solid fa-door-open"></i> Sala asignada</label>
                <select class="form-control" id="SOLICITUD_SALA_ID_ASIGNADA" name="SOLICITUD_SALA_ID_ASIGNADA">
                    <option value="">Asigne una sala</option>
                    @foreach ($salas as $sala)
                        <option value="{{$sala->SALA_ID}}" {{ (old('SOLICITUD_SALA_ID_ASIGNADA') ?? $solicitud->SOLICITUD_SALA_ID_ASIGNADA) == $sala->SALA_ID ? 'selected' : '' }}>{{$sala->SALA_NOMBRE}}</option>
                    @endforeach
                </select>
                @error('SOLICITUD_SALA_ID_ASIGNADA')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            {{-- Autorizar cantidades de equipos solicitados --}}
            <div class="form-group">
                <label for="autorizarEquipos">Autorizar cantidades de equipos solicitados</label>
                <table class="table table-bordered">
                    <thead class="tablacolor">
                        <tr>
                            <th>Tipo de Equipo</th>
                            <th>Cantidad solicitada</th>
                            <th>Cantidad a autorizar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($solicitud->equipos as $tipoEquipo)
                        <tr>
                            <td>{{ $tipoEquipo->TIPO_EQUIPO_NOMBRE }}</td>
                            <td>{{ $tipoEquipo->pivot->SOLICITUD_EQUIPOS_CANTIDAD }}</td>
                            <td>
                                <input class="form-control" type="number" name="autorizar[{{ $tipoEquipo->TIPO_EQUIPO_ID }}]" value="{{ $tipoEquipo->pivot->SOLICITUD_EQUIPOS_CANTIDAD_AUTORIZADA }}" min="0">
                                @if($errors->has('autorizar.' . $tipoEquipo->TIPO_EQUIPO_ID))
                                    <span class="text-danger">{{ $errors->first('autorizar.' . $tipoEquipo->TIPO_EQUIPO_ID) }}</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Observaciones de la solicitud --}}
            <div class="form-group">
                <label for="REVISION_SOLICITUD_OBSERVACION"><i class="fa-solid fa-eye"></i> Observaciones</label>
                <textarea class="form-control" id="REVISION_SOLICITUD_OBSERVACION" name="REVISION_SOLICITUD_OBSERVACION" rows="3">{{ old('REVISION_SOLICITUD_OBSERVACION', $solicitud->REVISION_SOLICITUD_OBSERVACION) }}</textarea>
                @error('REVISION_SOLICITUD_OBSERVACION')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            {{-- Botones de retorno y envo --}}
            <a href="{{ route('solicitudes.salas.index') }}" class="btn btn-secondary"><i class="fa-solid fa-hand-point-left"></i> Volver</a>
            <button type="submit" name="action" value="guardar" class="btn agregar"><i class="fa-solid fa-plus"></i> Guardar revisión</button>
            <button type="submit" name="action" value="finalizar_revision" class="btn btn-success"><i class="fa-solid fa-check"></i> Finalizar revisiones y autorizar</button>
            <button type="submit" name="action" value="rechazar" class="btn btn-danger"><i class="fa-solid fa-ban"></i> Rechazar</button>
        </form>
    @else
        <a href="{{ route('solicitudes.salas.index') }}" class="btn btn-secondary"><i class="fa-solid fa-hand-point-left"></i> Volver</a>
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

        .estado-aceptado {
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
