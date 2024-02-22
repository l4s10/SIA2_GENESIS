@extends('adminlte::page')

@section('title', 'Editar Solicitud Vehicular')

@section('content_header')
    <h1>Revisión Solicitud Vehicular</h1>
    <br>
    <br>
@stop

@section('content')
    <div class="container">
        <form action="{{ route('solicitudesvehiculos.update', $solicitud->SOLICITUD_VEHICULO_ID) }}" method="POST">
            @csrf
            @method('PUT')
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- ENCABEZADO DE LA SOLICITUD: FECHA DE CREACIÓN, TIPO Y ESTADO DE LA SOLICITUD --}}
            <h3>Encabezado</h3>
            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="fecha_solicitud"><i class="fa-regular fa-calendar-days"></i> Fecha de solicitud:</label>
                        <input type="text" class="form-control" id="fecha_solicitud" name="fecha_solicitud" value="{{ $fechaCreacionFormateada }}" disabled required style="text-align: center;">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="OFICINA_ID"><i class="fa-solid fa-street-view"></i> Dirección regional:</label>
                        <input type="text" class="form-control" id="OFICINA_ID" name="OFICINA_ID" value= "{{ Auth::user()->oficina->OFICINA_NOMBRE }}" disabled required style="text-align: center;">
                    </div>
                </div>
                

                <div class="col-md-2">
                    <div class="mb-3">
                        <label for="SOLICITUD_ESTADO"><i class="fa-solid fa-right-from-bracket"></i> Estado:</label>
                        <input type="text" class="form-control" id="SOLICITUD_ESTADO" name="SOLICITUD_ESTADO" value="{{ $solicitud->SOLICITUD_VEHICULO_ESTADO }}" readonly required style="text-align: center; color: #e6500a;">
                    </div>
                    <!-- Leyenda -->
                    <div class="mb-3">
                        <small>La solicitud <strong>no</strong> ha sido aprobada</small>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="mb-3">
                        <label for="SOLICITUD_VEHICULO_FOLIO"><i class="fa-solid fa-arrow-up-9-1"></i> Folio:</label>
                        <input type="text" class="form-control" id="SOLICITUD_VEHICULO_FOLIO" name="SOLICITUD_VEHICULO_FOLIO" value="{{ $solicitud->SOLICITUD_VEHICULO_ID }}" readonly required style="text-align: center;">
                    </div>
                </div>
            </div>



            <br>
            <h3>Solicitante</h3>
            <div class="form-group mb-4">
                <div class="row mb-4">
                    <div class="col-md-6">
                            <label for="USUARIO_NOMBRES" class="form-label"><i class="fa-solid fa-user"></i> Nombres y apellidos:</label>
                            <input type="text" id="USUARIO_NOMBRES" name="USUARIO_NOMBRES" class="form-control" value="{{ $solicitud->user->USUARIO_NOMBRES }} {{ $solicitud->user->USUARIO_APELLIDOS }}" readonly required>
                    </div>

                    <div class="col-md-4">
                        <label for="EMAIL" class="form-label"><i class="fa-solid fa-envelope"></i> Email:</label>
                        <input type="email" id="EMAIL" name="EMAIL" class="form-control" value="{{ $solicitud->user->email }}" readonly required>
                    </div>

                    <div class="col-md-2">
                        <label for="USUARIO_RUT" class="form-label"><i class="fa-solid fa-id-card"></i> RUT:</label>
                            <input type="text" id="USUARIO_RUT" name="USUARIO_RUT" class="form-control" value="{{ $solicitud->user->USUARIO_RUT }}" readonly required>
                    </div>
                </div>

                <div id="retorno" class="form-group mb-4" style="visibility: hidden;">
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="DEPARTAMENTO_O_UBICACION" class="form-label"><i class="fa-solid fa-building-user"></i> Ubicación o departamento:</label>
                        <input type="text" id="DEPARTAMENTO_O_UBICACION" name="DEPARTAMENTO_O_UBICACION" class="form-control" value="{{ isset($solicitud->user->departamento) ? $solicitud->user->departamento->DEPARTAMENTO_NOMBRE : $solicitud->user->ubicacion->UBICACION_NOMBRE }}" readonly required>

                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="CARGO" class="form-label"><i class="fa-solid fa-id-card"></i> Cargo:</label>
                            <input type="text" id="CARGO" class="form-control" name="CARGO" value="{{ $solicitud->user->cargo->CARGO_NOMBRE }}" required readonly>
                        </div>
                    </div>
                </div>         
                <br>
            </div>





            <h3>Vehículo, Conductor y Pasajeros</h3>         

            <div class="row mb-4">
                <div class="col-md-6">
                    <label for="VEHICULO_ID" class="form-label"><i class="fa-solid fa-car-side"></i> Vehículo asignado:</label>
                    <select name="VEHICULO_ID" id="VEHICULO_ID" class="form-control" required>
                        <option style="text-align: center;" value="">-- Seleccione un vehiculo --</option>
                        @foreach ($vehiculos as $vehiculo)
                            <option data-capacidad="{{ $vehiculo->tipoVehiculo->TIPO_VEHICULO_CAPACIDAD }}" value="{{ $vehiculo->VEHICULO_ID }}" {{ $solicitud->VEHICULO_ID == $vehiculo->VEHICULO_ID ? 'selected' : '' }}>
                                Marca: {{ $vehiculo->VEHICULO_MARCA }} - Patente: {{ $vehiculo->VEHICULO_PATENTE }} - Tipo: {{ $vehiculo->tipoVehiculo->TIPO_VEHICULO_NOMBRE }} - Capacidad: {{ $vehiculo->tipoVehiculo->TIPO_VEHICULO_CAPACIDAD }}
                            </option>
                        @endforeach
                    </select>
                    @error('VEHICULO_ID')
                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-3">
                    <label for="fechaHoraInicioSolicitada"><i class="fa-solid fa-compass"></i> Fecha y hora de salida solicitada:</label>
                    <input type="text" class="form-control" style="text-align: center;" value="{{ $solicitud->SOLICITUD_VEHICULO_FECHA_HORA_INICIO_SOLICITADA }}" readonly>
                </div>
                <div class="col-md-3">
                    <label for="fechaHoraTerminoSolicitada"><i class="fa-solid fa-compass"></i> Fecha y hora de regreso solicitada:</label>
                    <input type="text" class="form-control" style="text-align: center;" value="{{ $solicitud->SOLICITUD_VEHICULO_FECHA_HORA_TERMINO_SOLICITADA }}" readonly>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-6">
                </div>
                <div class="col-md-3">
                    <label for="fechaHoraInicioAsignada"><i class="fa-solid fa-compass"></i> Fecha y hora de salida asignada:</label>
                    <input type="text" class="form-control @error('SOLICITUD_VEHICULO_FECHA_HORA_INICIO_ASIGNADA') is-invalid @enderror" id="fechaHoraInicioAsignada" name="SOLICITUD_VEHICULO_FECHA_HORA_INICIO_ASIGNADA" required placeholder="-- Seleccione la fecha y hora --" style="background-color: #fff; color: #000; text-align: center; border: 2px solid #00B050;" value="{{ $solicitud->SOLICITUD_VEHICULO_FECHA_HORA_INICIO_ASIGNADA }}">
                    @error('SOLICITUD_VEHICULO_FECHA_HORA_INICIO_ASIGNADA')
                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-3">
                    <label for="fechaHoraTerminoAsignada"><i class="fa-solid fa-compass"></i> Fecha y hora de regreso asignada:</label>
                    <input type="text" class="form-control @error('SOLICITUD_VEHICULO_FECHA_HORA_TERMINO_ASIGNADA') is-invalid @enderror" id="fechaHoraTerminoAsignada" name="SOLICITUD_VEHICULO_FECHA_HORA_TERMINO_ASIGNADA" required placeholder="-- Seleccione la fecha y hora --" style="background-color: #fff; color: #000; text-align: center; border: 2px solid #00B050;" value="{{$solicitud->SOLICITUD_VEHICULO_FECHA_HORA_TERMINO_ASIGNADA ?? ''  }}">
                    @error('SOLICITUD_VEHICULO_FECHA_HORA_TERMINO_ASIGNADA')
                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>                
            </div>

            <div class="pasajero-box border p-3 mb-3">
                <h5>Conductor</h5>
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="oficina"><i class="fa-solid fa-street-view"></i> Dirección regional:</label>
                        <select id="oficina" class="form-control oficina" required>
                            <option style="text-align: center;" value="">-- Seleccione una opción --</option>
                            @foreach($oficinas as $oficina)
                                <option value="{{ $oficina->OFICINA_ID }}" 
                                    {{ (isset($solicitud->conductor) && $solicitud->conductor->OFICINA_ID == $oficina->OFICINA_ID) ? 'selected' : '' }}>
                                    {{ $oficina->OFICINA_NOMBRE }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="dependencia"><i class="fa-solid fa-building-user"></i> Ubicación o departamento:</label>
                        <select id="dependencia" class="form-control dependencia" required>
                            <option style="text-align: center;" value="">-- Seleccione una opción --</option>
                            <optgroup label="Ubicaciones">
                                @foreach($ubicaciones as $ubicacion)
                                    <option value="{{ $ubicacion->UBICACION_ID }}" 
                                        {{ (isset($solicitud->conductor) && $solicitud->conductor->UBICACION_ID == $ubicacion->UBICACION_ID) ? 'selected' : '' }}>
                                        {{ $ubicacion->UBICACION_NOMBRE }}
                                    </option>
                                @endforeach
                            </optgroup>
                            <optgroup label="Departamentos">
                                @foreach($departamentos as $departamento)
                                    <option value="{{ $departamento->DEPARTAMENTO_ID }}" 
                                        {{ (isset($solicitud->conductor) && $solicitud->conductor->DEPARTAMENTO_ID == $departamento->DEPARTAMENTO_ID) ? 'selected' : '' }}>
                                        {{ $departamento->DEPARTAMENTO_NOMBRE }}
                                    </option>
                                @endforeach
                            </optgroup>
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="conductor"><i class="fa-solid fa-person-circle-check"></i> Conductor:</label>
                        <select id="conductor" class="form-control conductor" name="CONDUCTOR_id"  required>
                            <option style="text-align: center;" value="">-- Seleccione al conductor --</option>
                            <optgroup label="Conductores Asociados">
                                @foreach($conductores as $conductor)
                                    <option value="{{ $conductor->id }}"  
                                        {{ (isset($solicitud) && $solicitud->CONDUCTOR_id == $conductor->id) ? 'selected' : '' }}>
                                        {{ $conductor->USUARIO_NOMBRES }} {{ $conductor->USUARIO_APELLIDOS }}
                                    </option>
                                @endforeach
                            </optgroup>
                            
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="SOLICITUD_VEHICULO_VIATICO"><i class="fa-solid fa-money-bill-wheat"></i> Viático:</label>
                        <select class="form-control" id="SOLICITUD_VEHICULO_VIATICO" name="SOLICITUD_VEHICULO_VIATICO" required>
                            <option style="text-align: center;" value="" selected>-- Seleccione una opción --</option>
                            <option value="SI" {{ (isset($solicitud) && $solicitud->SOLICITUD_VEHICULO_VIATICO == 'SI') ? 'selected' : '' }}>SI</option>
                            <option value="NO" {{ (isset($solicitud) && $solicitud->SOLICITUD_VEHICULO_VIATICO == 'NO') ? 'selected' : '' }}>NO</option>
                        </select>
                    </div>

                    <div class="form-group col-md-4">
                        <label for="SOLICITUD_VEHICULO_HORA_INICIO_CONDUCCION"><i class="fa-solid fa-clock"></i> Hora de inicio de conducción:</label>
                        <input type="time" class="form-control" id="SOLICITUD_VEHICULO_HORA_INICIO_CONDUCCION" name="SOLICITUD_VEHICULO_HORA_INICIO_CONDUCCION" required placeholder="-- Seleccione la hora de inicio --" style="background-color: #fff; color: #000; text-align: center;" value="{{ isset($solicitud) ? $solicitud->SOLICITUD_VEHICULO_HORA_INICIO_CONDUCCION : '' }}">
                        @error('SOLICITUD_VEHICULO_HORA_INICIO_CONDUCCION')
                            <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group col-md-4">
                        <label for="SOLICITUD_VEHICULO_HORA_TERMINO_CONDUCCION"><i class="fa-solid fa-clock"></i> Hora de término de conducción:</label>
                        <input type="time" class="form-control" id="SOLICITUD_VEHICULO_HORA_TERMINO_CONDUCCION" name="SOLICITUD_VEHICULO_HORA_TERMINO_CONDUCCION" required placeholder="-- Seleccione la hora de término --" style="background-color: #fff; color: #000; text-align: center;" value="{{ isset($solicitud) ? $solicitud->SOLICITUD_VEHICULO_HORA_TERMINO_CONDUCCION : '' }}" >
                        @error('SOLICITUD_VEHICULO_HORA_TERMINO_CONDUCCION')
                            <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>



            
            <div id="pasajeros" class="form-group mb-4" style="display: none;">
                @foreach($pasajeros as $index => $pasajero)
                    <div class="pasajeros-box border p-3 mb-3">
                        <h5>Pasajero N°{{ $index + 1 }}</h5>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="oficina_{{ $index }}"><i class="fa-solid fa-street-view"></i> Dirección regional:</label>
                                <select id="oficina_{{ $index }}" class="form-control" name="oficina_{{ $index }}" required>
                                    @foreach($oficinas as $oficina)
                                        <option value="{{ $oficina->OFICINA_ID }}" {{ $pasajero->usuario->oficina->OFICINA_ID == $oficina->OFICINA_ID ? 'selected' : '' }}>{{ $oficina->OFICINA_NOMBRE }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="dependencia_{{ $index }}"><i class="fa-solid fa-building-user"></i> Ubicación o departamento:</label>
                                <select id="dependencia_{{ $index }}" class="form-control" name="dependencia_{{ $index }}" required>
                                    @foreach($ubicaciones as $ubicacion)
                                        <option value="{{ $ubicacion->UBICACION_ID }}" {{ $pasajero->usuario->ubicacion && $pasajero->usuario->ubicacion->UBICACION_ID == $ubicacion->UBICACION_ID ? 'selected' : '' }}>{{ $ubicacion->UBICACION_NOMBRE }}</option>
                                    @endforeach
                                    @foreach($departamentos as $departamento)
                                        <option value="{{ $departamento->DEPARTAMENTO_ID }}" {{ $pasajero->usuario->departamento && $pasajero->usuario->departamento->DEPARTAMENTO_ID == $departamento->DEPARTAMENTO_ID ? 'selected' : '' }}>{{ $departamento->DEPARTAMENTO_NOMBRE }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="pasajero_{{ $index }}"><i class="fa-solid fa-person-circle-check"></i> Funcionario:</label>
                                <select id="pasajero_{{ $index }}" class="form-control" name="pasajero_{{ $index }}" required>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ $pasajero->usuario->id == $user->id ? 'selected' : '' }}>{{ $user->USUARIO_NOMBRES }} {{ $user->USUARIO_APELLIDOS }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            

            <div class="row mb-4">
                <div class="col-md-6">
                    <button id="botonPasajeros" type="button" class="btn" style="background-color: #9DA6B2; color: #fff;">
                        <i class="fa-solid fa-user-plus"></i> Mostrar Pasajeros
                    </button>
                </div>
                <div class="col-md-6 d-flex align-items-center justify-content-end">
                    <div class="row">
                        <div class="col-md-6">
                            <button id="botonAgregarPasajero" type="button" class="btn btn-block" style="background-color: #00B050; color: #fff; width: 200px;">
                                <i class="fa-solid fa-user-plus"></i> Agregar Pasajero
                            </button>
                        </div>
                        <div class="col-md-6">
                            <button id="botonEliminarPasajero" type="button" class="btn btn-warning" style="background-color: #E22C2C; color: #fff; width: 200px;">
                                <i class="fa-solid fa-user-minus"></i> Eliminar Pasajero
                            </button>
                        </div>
                    </div>
                </div>
            </div>
           


             {{-- DATOS DE SALIDA --}}
             <br>
             <br>         
             <br>
             <h3>Salida</h3>      
             <div class="row mb-4">
                 <div class="col-md-4">
                     <label for="SOLICITUD_VEHICULO_REGION"><i class="fa-solid fa-map-location-dot"></i> Región de destino:</label>
                     <input type="text" class="form-control" value="{{ $solicitud->comunaDestino->region->REGION_NOMBRE }}" readonly>
                 </div>
         
                 <div class="col-md-4">
                     <label for="SOLICITUD_VEHICULO_COMUNA"><i class="fa-solid fa-map-location-dot"></i> Comuna de destino:</label>
                     <input type="text" class="form-control" value="{{ $solicitud->comunaDestino->COMUNA_NOMBRE }}" readonly>
                 </div>
                 <div class="col-md-4">
                     <div id="jefeQueAutoriza">
                         <label for="SOLICITUD_VEHICULO_JEFE_QUE_AUTORIZA"><i class="fa-solid fa-user-check"></i> Jefe que autoriza:</label>
                         <input type="text" class="form-control" value="{{ $cargoJefeQueAutoriza->CARGO_NOMBRE }}" readonly>
                     </div>
                 </div>
             </div>

            <div class="form-group" id="motivoSolicitud">
                <label for="SOLICITUD_VEHICULO_MOTIVO"><i class="fa-solid fa-file-pen"></i> Labor a realizar:</label>
                <textarea id="SOLICITUD_VEHICULO_MOTIVO" name="SOLICITUD_VEHICULO_MOTIVO" rows="5" class="form-control" placeholder="Indique la labor a realizar (MÁX 255 CARACTERES)" maxlength="255" required readonly>{{ $solicitud->SOLICITUD_VEHICULO_MOTIVO }}</textarea>
            </div>


            <!-- DIV CONTENEDOR DE LOS CAMPOS DE ORDEN DE TRABAJO -->
            <div id="ordenTrabajoInputs" style="display: block;">
                <br>
                <h3>Orden de Trabajo</h3>
                <div class="row mb-4">
                    <div class="form-group col-md-4">
                        <label for="TRABAJA_NUMERO_ORDEN_TRABAJO"><i class="fa-solid fa-arrow-up-9-1"></i> Número de la Orden de Trabajo:</label>
                        <input type="number" class="form-control" id="TRABAJA_NUMERO_ORDEN_TRABAJO" name="TRABAJA_NUMERO_ORDEN_TRABAJO" placeholder="-- Ingrese el número de orden --" style=" text-align: center;" disabled required min="0" max="999999" value="{{ isset($solicitud->ordenTrabajo->ORDEN_TRABAJO_NUMERO) ? $solicitud->ordenTrabajo->ORDEN_TRABAJO_NUMERO : '' }}">
                        <div class="invalid-feedback" id="numeroOrdenTrabajoError"></div>
                    </div>
                    <div class="form-group col-md-2"></div>
                    <div class="form-group col-md-3">
                        <label for="TRABAJA_HORA_INICIO_ORDEN_TRABAJO"><i class="fa-solid fa-clock"></i> Inicio orden de trabajo:</label>
                        <input type="time" class="form-control" id="TRABAJA_HORA_INICIO_ORDEN_TRABAJO" name="TRABAJA_HORA_INICIO_ORDEN_TRABAJO" disabled required placeholder="-- Seleccione la hora de inicio --" style="background-color: #fff; color: #000; text-align: center;" value="{{ isset($solicitud->ordenTrabajo->ORDEN_TRABAJO_HORA_INICIO) ? $solicitud->ordenTrabajo->ORDEN_TRABAJO_HORA_INICIO : '' }}">
                        <div class="invalid-feedback" id="inicioOrdenTrabajoError"></div>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="TRABAJA_HORA_TERMINO_ORDEN_TRABAJO"><i class="fa-solid fa-clock"></i> Fin orden de trabajo:</label>
                        <input type="time" class="form-control" id="TRABAJA_HORA_TERMINO_ORDEN_TRABAJO" name="TRABAJA_HORA_TERMINO_ORDEN_TRABAJO" disabled required placeholder="-- Seleccione la hora de término--" style="background-color: #fff; color: #000; text-align: center;" value="{{ isset($solicitud->ordenTrabajo->ORDEN_TRABAJO_HORA_TERMINO) ? $solicitud->ordenTrabajo->ORDEN_TRABAJO_HORA_TERMINO : '' }}">
                        <div class="invalid-feedback" id="terminoOrdenTrabajoError"></div>
                    </div>
                </div>
            </div>

            <div class="form-group" id="motivoSolicitud">
                <label for="REVISION_SOLICITUD_OBSERVACION"><i class="fa-solid fa-file-pen"></i> Observaciones revisión:</label>
                <textarea id="REVISION_SOLICITUD_OBSERVACION" name="REVISION_SOLICITUD_OBSERVACION" rows="5" class="form-control" style="border: 2px solid #00B050;" placeholder="Inserte aquí sus comentarios o describa los ajustes realizados en la solicitud (Max 255 caracteres)." maxlength="255" required ></textarea>
            </div>


          
            
            <br><br><br><br>
            <button type="submit" class="btn btn-success" name="guardarRevision">Guardar esta revisión</button>
            <button type="submit" class="btn btn-success" name="finalizarRevisiones">Finalizar revisiones</button>
            @if (Auth::check() && isset($solicitud->user) && isset($solicitud->SOLICITUD_VEHICULO_JEFE_QUE_AUTORIZA))
                @if ((Auth::user()->cargo->CARGO_NOMBRE == "JEFE DE DEPARTAMENTO DE ADMINISTRACION" || Auth::user()->cargo->CARGO_NOMBRE == $solicitud->SOLICITUD_VEHICULO_JEFE_QUE_AUTORIZA) && Auth::user()->oficina->OFICINA_ID == $solicitud->user->OFICINA_ID)
                    <button type="submit" class="btn btn-success" name="autorizar">Autorizar</button>
                @endif
            @endif




        </form>
    </div>
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>

document.addEventListener('DOMContentLoaded', function () {
    let inputfechaHoraInicioAsignada = document.getElementById('fechaHoraInicioAsignada');
    let inputfechaHoraTerminoAsignada = document.getElementById('fechaHoraTerminoAsignada');
    let fechaHoraInicioPrecargada = new Date('{{ $solicitud->SOLICITUD_VEHICULO_FECHA_HORA_INICIO_ASIGNADA }}');
    let fechaHoraTerminoPrecargada = new Date('{{ $solicitud->SOLICITUD_VEHICULO_FECHA_HORA_TERMINO_ASIGNADA }}');

   
    let fechaActual = new Date(); // Fecha y hora actual
    let añoActual = fechaActual.getFullYear(); // Año actual
    let mesActual = fechaActual.getMonth(); // Mes actual
    let diaActual = fechaActual.getDate(); // Día actual
    let horaActual = fechaActual.getHours(); // Hora actual
    let minutoActual = fechaActual.getMinutes(); // Minuto actual

    // **Fecha mínima permitida (día actual)**
    let fechaMinimaPermitida = new Date(añoActual, mesActual, diaActual, horaActual, minutoActual);
    // **Fecha máxima permitida**
    let fechaMaximaPermitida;

    // Si estamos en diciembre, permitir hasta febrero del próximo año
    if (mesActual === 11) {
        fechaMaximaPermitida = new Date(añoActual + 1, 1, 28);
    } else {
        // Permitir hasta diciembre del año actual
        fechaMaximaPermitida = new Date(añoActual, 11, 31);
    }


    // Cargar los valores de fecha y hora asignada para $solicitud revisada 
    if (!isNaN(fechaHoraInicioPrecargada) && !isNaN(fechaHoraTerminoPrecargada)) {
        configurarFlatpickrs(fechaHoraInicioPrecargada, fechaHoraTerminoPrecargada);
        inputfechaHoraInicioAsignada.value = formatDate(fechaHoraInicioPrecargada);
        inputfechaHoraTerminoAsignada.value = formatDate(fechaHoraTerminoPrecargada);

    } else {
        inputfechaHoraInicioAsignada.placeholder = "-- Seleccione una fecha y hora --";
        inputfechaHoraTerminoAsignada.placeholder = "-- Seleccione una fecha y hora --";
        // Configurar Flatpickrs para caso de que todavía no se haya asignado fecha y hora para la $solicitud
        flatpickr(inputfechaHoraInicioAsignada, {
            enableTime: true,
            dateFormat: "d-m-Y H:i",
            minDate: fechaMinimaPermitida,
            maxDate: fechaMaximaPermitida,
            //defaultDate: fechaMinimaPermitida,
            locale: "es", // Establecer el idioma en español
            onChange: function(selectedDates, dateStr, instance) {
                if (selectedDates[0] < fechaMinimaPermitida) {
                    alert("La fecha y hora seleccionada es menor a la hora mínima permitida");
                    inputfechaHoraInicioAsignada.value = "";
                } else {
                    // Habilitar el input de término una vez que se ha seleccionado la hora de inicio
                    inputfechaHoraTerminoAsignada.disabled = false;
                    // Actualizar minDate para el input de término
                    let fechaHoraInicioSeleccionada = selectedDates[0];
                    let horaInicioSeleccionada = fechaHoraInicioSeleccionada.getHours();
                    let minutoInicioSeleccionado = fechaHoraInicioSeleccionada.getMinutes();

                    let fechaMinimaTermino = new Date(fechaHoraInicioSeleccionada);
                    fechaMinimaTermino.setHours(horaInicioSeleccionada);
                    fechaMinimaTermino.setMinutes(minutoInicioSeleccionado);

                    inputfechaHoraTerminoAsignada._flatpickr.set("minDate", fechaMinimaTermino);
                }
            }
        });

        flatpickr(inputfechaHoraTerminoAsignada, {
            enableTime: true,
            dateFormat: "d-m-Y H:i",
            minDate: fechaMinimaPermitida, // Se establece inicialmente, luego se actualizará
            maxDate: fechaMaximaPermitida,
            locale: "es", // Establecer el idioma en español
            onClose: function(selectedDates, dateStr, instance) {
                let fechaHoraTerminoSeleccionada = selectedDates[0];
                if (fechaHoraTerminoSeleccionada < inputfechaHoraInicioAsignada._flatpickr.latestSelectedDateObj) {
                    alert("La fecha y hora seleccionada es anterior a la hora de inicio.");
                    inputfechaHoraTerminoAsignada._flatpickr.setDate(null); // Limpiar la selección
                }
            }
        });

    }

    function configurarFlatpickrs(fechaInicio, fechaTermino) {
        flatpickr(inputfechaHoraInicioAsignada, {
            enableTime: true,
            dateFormat: "d-m-Y H:i",
            minDate: fechaMinimaPermitida,
            maxDate: fechaMaximaPermitida,
            defaultDate: fechaMinimaPermitida,
            locale: "es", // Establecer el idioma en español
            onClose: function(selectedDates, dateStr, instance) {
                if (selectedDates[0] < fechaMinimaPermitida) {
                    alert("La fecha y hora seleccionada es menor a la hora mínima permitida");
                    inputfechaHoraInicioAsignada.value = "";
                } else {
                    // Habilitar el input de término una vez que se ha seleccionado la hora de inicio
                    inputfechaHoraTerminoAsignada.disabled = false;
                    // Actualizar minDate para el input de término
                    let fechaHoraInicioSeleccionada = selectedDates[0];
                    let horaInicioSeleccionada = fechaHoraInicioSeleccionada.getHours();
                    let minutoInicioSeleccionado = fechaHoraInicioSeleccionada.getMinutes();

                    let fechaMinimaTermino = new Date(fechaHoraInicioSeleccionada);
                    fechaMinimaTermino.setHours(horaInicioSeleccionada);
                    fechaMinimaTermino.setMinutes(minutoInicioSeleccionado);

                    inputfechaHoraTerminoAsignada._flatpickr.set("minDate", fechaMinimaTermino);
                }
            }
        });

        flatpickr(inputfechaHoraTerminoAsignada, {
            enableTime: true,
            dateFormat: "d-m-Y H:i",
            minDate: fechaMinimaPermitida, // Se establece inicialmente, luego se actualizará
            maxDate: fechaMaximaPermitida,
            defaultDate: fechaTermino,
            locale: "es", // Establecer el idioma en español
            onClose: function(selectedDates, dateStr, instance) {
                let fechaHoraTerminoSeleccionada = selectedDates[0];
                if (fechaHoraTerminoSeleccionada < inputfechaHoraInicioAsignada._flatpickr.latestSelectedDateObj) {
                    alert("La fecha y hora seleccionada es anterior a la hora de inicio.");
                    inputfechaHoraTerminoAsignada._flatpickr.setDate(null); // Limpiar la selección
                }
            }
        });

        // Deshabilitar el input de término al cargar la página y establecer su valor como vacío
        inputfechaHoraTerminoAsignada.disabled = false;
        inputfechaHoraInicioAsignada.value = null;
        inputfechaHoraTerminoAsignada.value = fechaTermino;
        //inputfechaHoraTerminoAsignada.dispatchEvent(new Event('change'));

        
    }

    function formatDate(date) {
            let year = date.getFullYear().toString().padStart(2, '0');
    let month = (date.getMonth() + 1).toString().padStart(2, '0');
    let day = date.getDate().toString().padStart(2, '0');
    let hours = date.getHours().toString().padStart(2, '0');
    let minutes = date.getMinutes().toString().padStart(2, '0');
    let formattedDate = `${day}-${month}-${year} ${hours}:${minutes}`;
    return formattedDate;
    }
});
</script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Obtener los elementos de los selectores de hora
        var horaInicioSelector = document.getElementById("SOLICITUD_VEHICULO_HORA_INICIO_CONDUCCION");
        var horaTerminoSelector = document.getElementById("SOLICITUD_VEHICULO_HORA_TERMINO_CONDUCCION");

        // Obtener los valores de hora asignada para $solicitud revisada 
        var horaInicioPreCargada = new Date('{{ $solicitud->SOLICITUD_VEHICULO_HORA_INICIO_CONDUCCION }}');
        var horaTerminoPreCargada = new Date('{{ $solicitud->SOLICITUD_VEHICULO_HORA_TERMINO_CONDUCCION }}');

        // Configurar flatpickr para el selector de hora de inicio
        var pickerHoraInicio = flatpickr(horaInicioSelector, {
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i",
            onClose: function(selectedDates, dateStr, instance) {
                // Habilitar el selector de hora de término cuando se selecciona una hora de inicio
                pickerHoraTermino.set("minTime", dateStr);
                horaTerminoSelector.disabled = false;
            }
        });

        // Configurar flatpickr para el selector de hora de término
        var pickerHoraTermino = flatpickr(horaTerminoSelector, {
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i",
            onClose: function(selectedDates, dateStr, instance) {
                // Validar que la hora de término sea igual o posterior a la hora de inicio
                if (dateStr < pickerHoraInicio.selectedDates[0]) {
                    alert("La hora de término debe ser igual o posterior a la hora de inicio.");
                    horaTerminoSelector.value = "";
                }
            }
        });

        // Verificar si los datos existen en $solicitud y son válidos
        if (horaInicioPreCargada instanceof Date && !isNaN(horaInicioPreCargada) &&
            horaTerminoPreCargada instanceof Date && !isNaN(horaTerminoPreCargada)) {

            // Preconfigurar los selectores de hora con los valores de $solicitud
            horaInicioSelector.value = formatoHoraMinuto(horaInicioPreCargada);
            horaTerminoSelector.value = formatoHoraMinuto(horaTerminoPreCargada);

            // Configurar minTime para el selector de hora de término
            pickerHoraTermino.set("minTime", formatoHoraMinuto(horaInicioPreCargada));
            // Habilitar el selector de hora de término
            horaTerminoSelector.disabled = false;
        }

        // Inhabilitar el selector de hora de término inicialmente si no hay valores pre-cargados
        if (!horaInicioPreCargada || !horaTerminoPreCargada) {
            horaTerminoSelector.disabled = true;
        }
    });

    // Función para formatear la hora en HH:MM
    function formatoHoraMinuto(date) {
        var horas = date.getHours();
        var minutos = date.getMinutes();
        return (horas < 10 ? '0' : '') + horas + ':' + (minutos < 10 ? '0' : '') + minutos;
    }
</script>

        
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Obtener el contenedor de los campos de orden de trabajo
        var ordenTrabajoInputs = document.getElementById('ordenTrabajoInputs');
        
        // Obtener los elementos de hora y nro de orden de trabajo
        var horaInicioSelector = document.getElementById("TRABAJA_HORA_INICIO_ORDEN_TRABAJO");
        var horaTerminoSelector = document.getElementById("TRABAJA_HORA_TERMINO_ORDEN_TRABAJO");
        var numeroOrdenTrabajo = document.getElementById('TRABAJA_NUMERO_ORDEN_TRABAJO');



        // Verificar si la solicitud tiene una orden de trabajo asociada
        @if(isset($solicitud->ordenTrabajo))
            // Mostrar los campos de orden de trabajo si la solicitud tiene una orden de trabajo
            ordenTrabajoInputs.style.display = 'block';
            numeroOrdenTrabajo.disabled = false;
            horaInicioSelector.disabled = false;
            horaTerminoSelector.disabled = false;

        @else
            // Ocultar los campos de orden de trabajo si la solicitud no tiene una orden de trabajo
            ordenTrabajoInputs.style.display = 'none';
            numeroOrdenTrabajo.disabled = true;
            horaInicioSelector.disabled = true;
            horaTerminoSelector.disabled = true;
        @endif

        // Función para configurar un flatpickr con la misma configuración
        function configurarFlatpickr(selector, minTimeConfig, onCloseCallback) {
            return flatpickr(selector, {
                enableTime: true,
                noCalendar: true,
                dateFormat: "H:i",
                minTime: minTimeConfig,
                onClose: onCloseCallback
            });
        }


        // Configurar flatpickr para el selector de hora de inicio
        var pickerHoraInicio = configurarFlatpickr(horaInicioSelector, undefined, function(selectedDates, dateStr, instance) {
            // Habilitar el selector de hora de término cuando se selecciona una hora de inicio
            pickerHoraTermino.set("minTime", dateStr);
            horaTerminoSelector.disabled = false;
        });

        // Configurar flatpickr para el selector de hora de término
        var pickerHoraTermino = configurarFlatpickr(horaTerminoSelector, horaInicioSelector.value, function(selectedDates, dateStr, instance) {
            // Validar que la hora de término sea igual o posterior a la hora de inicio
            if (dateStr < pickerHoraInicio.selectedDates[0]) {
                alert("La hora de término debe ser igual o posterior a la hora de inicio.");
                horaTerminoSelector.value = "";
            }
        });
    });
</script>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        var oficinas = {!! json_encode($oficinas) !!};
        var ubicaciones = {!! json_encode($ubicaciones) !!};
        var departamentos = {!! json_encode($departamentos) !!};
        var users = {!! json_encode($users) !!};

        // Obtener la capacidad máxima del vehículo para validar agregado de pasajeros
        var capacidadMaxima = {!! $solicitud->vehiculo->tipoVehiculo->TIPO_VEHICULO_CAPACIDAD !!}-1;
        var contadorPasajeros = 0;

        var pasajerosExistente = document.getElementsByClassName('pasajeros-box').length;
        contadorPasajeros = pasajerosExistente;




        // Propuesta para eliminación de pasajeros hasta la cantidad solicitada
        // Obtener la capacidad máxima del vehículo para validar agregado de pasajeros
        /*var capacidadMaxima = {!! $solicitud->vehiculo->tipoVehiculo->TIPO_VEHICULO_CAPACIDAD !!};
        var contadorPasajeros = 0;

        // Contar la cantidad de pasajeros existentes al cargar la página
        var pasajerosExistente = document.getElementsByClassName('pasajeros-box').length;
        contadorPasajeros = pasajerosExistente;*/


        configurarSelectores();
        configurarSelectoresPasajero();


        // Función para configurar los selectores de oficina
        function configurarSelectores() {
            var pasajerosBoxes = document.querySelectorAll('.pasajeros-box');

            pasajerosBoxes.forEach(function(box, index) {
                var oficinaSelect = box.querySelector('select[id="oficina_' + index + '"]');
                var dependenciaSelect = box.querySelector('select[id="dependencia_' + index + '"]');
                var pasajeroSelect = box.querySelector('select[id="pasajero_' + index + '"]');

                if (oficinaSelect && dependenciaSelect && pasajeroSelect) {
                    var selectedOficinaId = oficinaSelect.value;
                    var selectedDependenciaId = dependenciaSelect.value;
                    var pasajeroId = pasajeroSelect.value;

                    // Limpiar selectores
                    dependenciaSelect.innerHTML = '<option style="text-align: center;" value="">-- Seleccione una dependencia --</option>';
                    pasajeroSelect.innerHTML = '<option style="text-align: center;" value="">-- Seleccione un pasajero --</option>';

                    // Configurar selectores de oficina
                    oficinas.forEach(function(oficina) {
                        var option = document.createElement('option');
                        option.value = oficina.OFICINA_ID;
                        option.textContent = oficina.OFICINA_NOMBRE;
                        if (oficina.OFICINA_ID == selectedOficinaId) {
                            option.selected = true;
                        }
                        oficinaSelect.appendChild(option);
                    });

                    // Configurar selectores de dependencia
                    var optgroupUbicaciones = document.createElement('optgroup');
                    optgroupUbicaciones.label = 'Ubicaciones';

                    var optgroupDepartamentos = document.createElement('optgroup');
                    optgroupDepartamentos.label = 'Departamentos';

                    ubicaciones.forEach(function(ubicacion) {
                        if (ubicacion.OFICINA_ID == selectedOficinaId) {
                            var option = document.createElement('option');
                            option.value = ubicacion.UBICACION_ID;
                            option.textContent = ubicacion.UBICACION_NOMBRE;
                            if (ubicacion.UBICACION_ID == selectedDependenciaId) {
                                option.selected = true;
                            }
                            optgroupUbicaciones.appendChild(option);
                        }
                    });

                    departamentos.forEach(function(departamento) {
                        if (departamento.OFICINA_ID == selectedOficinaId) {
                            var option = document.createElement('option');
                            option.value = departamento.DEPARTAMENTO_ID;
                            option.textContent = departamento.DEPARTAMENTO_NOMBRE;
                            if (departamento.DEPARTAMENTO_ID == selectedDependenciaId) {
                                option.selected = true;
                            }
                            optgroupDepartamentos.appendChild(option);
                        }
                    });

                    dependenciaSelect.appendChild(optgroupUbicaciones);
                    dependenciaSelect.appendChild(optgroupDepartamentos);

                    // Configurar selectores de pasajero
                    var optgroupUsuarios = document.createElement('optgroup');
                    optgroupUsuarios.label = 'Funcionarios Asociados';

                    users.forEach(function(user) {
                        if (user.UBICACION_ID == selectedDependenciaId || user.DEPARTAMENTO_ID == selectedDependenciaId) {
                            var option = document.createElement('option');
                            option.value = user.id;
                            option.textContent = user.USUARIO_NOMBRES + ' ' + user.USUARIO_APELLIDOS;
                            if (user.id == pasajeroId) {
                                option.selected = true;
                            }
                            optgroupUsuarios.appendChild(option);
                        }
                    });

                    pasajeroSelect.appendChild(optgroupUsuarios);

                    // Habilitar/deshabilitar selectores según selecciones
                    dependenciaSelect.disabled = selectedOficinaId === '';
                    pasajeroSelect.disabled = selectedDependenciaId === '';
                }
            });
        }

       

        function configurarSelectoresPasajero() {
            // Aquí colocamos toda la lógica de configuración de selectores para el ingreso de datos
            // Puedes copiar y pegar la lógica que has definido para la configuración estándar
            // Eventos change a los selectores de oficina
            var oficinaSelects = document.querySelectorAll('.pasajeros-box select[id^="oficina_"]');
            var dependenciaSelects = document.querySelectorAll('.pasajeros-box select[id^="dependencia_"]');
            var pasajeroSelects = document.querySelectorAll('.pasajeros-box select[id^="pasajero_"]');

            
            oficinaSelects.forEach(function(oficinaSelect) {
                oficinaSelect.addEventListener('change', function() {
                    var parentBox = this.closest('.pasajeros-box');
                    var dependenciaSelect = parentBox.querySelector('select[id^="dependencia_"]');
                    var pasajeroSelect = parentBox.querySelector('select[id^="pasajero_"]');
                    var selectedOficinaId = this.value;

                    // Limpiar selectores dependientes
                    dependenciaSelect.innerHTML = '<option style="text-align: center;" value="">-- Seleccione una dependencia --</option>';
                    pasajeroSelect.innerHTML = '<option style="text-align: center;" value="">-- Seleccione un pasajero --</option>';

                    
                    // OptGroups
                    var optgroupUbicaciones = document.createElement('optgroup');
                    optgroupUbicaciones.label = 'Ubicaciones';

                    var optgroupDepartamentos = document.createElement('optgroup');
                    optgroupDepartamentos.label = 'Departamentos';

                    // Configurar selectores de dependencia
                    ubicaciones.forEach(function(ubicacion) {
                        if (ubicacion.OFICINA_ID == selectedOficinaId) {
                            var option = document.createElement('option');
                            option.value = ubicacion.UBICACION_ID;
                            option.textContent = ubicacion.UBICACION_NOMBRE;
                            optgroupUbicaciones.appendChild(option);
                        }
                    });

                    departamentos.forEach(function(departamento) {
                        if (departamento.OFICINA_ID == selectedOficinaId) {
                            var option = document.createElement('option');
                            option.value = departamento.DEPARTAMENTO_ID;
                            option.textContent = departamento.DEPARTAMENTO_NOMBRE;
                            optgroupDepartamentos.appendChild(option);
                        }
                    });

                    // Agregar los grupos de opciones al selector de dependencia
                    dependenciaSelect.appendChild(optgroupUbicaciones);
                    dependenciaSelect.appendChild(optgroupDepartamentos);

                    // Habilitar/deshabilitar selectores dependientes
                    dependenciaSelect.disabled = selectedOficinaId === '';
                    pasajeroSelect.disabled = true;

                });
            });

            // Eventos change a los selectores de dependencia
            dependenciaSelects.forEach(function(dependenciaSelect) {
                dependenciaSelect.addEventListener('change', function() {
                    var parentBox = this.closest('.pasajeros-box');
                    var pasajeroSelect = parentBox.querySelector('select[id^="pasajero_"]');
                    var selectedDependenciaId = this.value;

                    // Limpiar selector de pasajero
                    pasajeroSelect.innerHTML = '<option style="text-align: center;" value="">-- Seleccione un pasajero --</option>';

                    // Configurar selectores de pasajero
                    var optgroupUsuarios = document.createElement('optgroup');
                    optgroupUsuarios.label = 'Funcionarios Asociados';

                    users.forEach(function(user) {
                        if (user.UBICACION_ID == selectedDependenciaId || user.DEPARTAMENTO_ID == selectedDependenciaId) {
                            var option = document.createElement('option');
                            option.value = user.id;
                            option.textContent = user.USUARIO_NOMBRES + ' ' + user.USUARIO_APELLIDOS;
                            optgroupUsuarios.appendChild(option);
                        }
                    });

                    pasajeroSelect.appendChild(optgroupUsuarios);
                    // Habilitar/deshabilitar selector de pasajero
                    pasajeroSelect.disabled = selectedDependenciaId === '';

                });
            });

            // Eventos change a los selectores de pasajero
            pasajeroSelects.forEach(function(pasajeroSelect, index) {
                pasajeroSelect.addEventListener('change', function() {
                    var selectedUserId = this.value;
                    var indice = index;

                    // Verificar duplicados en otros selectores de pasajero
                    var esDuplicado = false;
                    var contador = 0;
                    pasajeroSelects.forEach(function(otraSeleccion, otroIndice) {
                        contador ++;
                        if (otroIndice !== indice && otraSeleccion.value === selectedUserId) {
                            esDuplicado = true;
                            alert('El funcionario seleccionado ya figura como "Pasajero N° '+(contador)+'". Por favor, seleccione otra persona.');
                            return;
                        }
                    });

                    // Verificar si el pasajero seleccionado es igual al conductor
                    var conductorId = document.getElementById('conductor').value; // Obtener el valor del conductor
                    if (selectedUserId === conductorId) {
                        esDuplicado = true;
                        alert('El funcionario seleccionado ya figura como conductor. Por favor, seleccione otra persona.');
                    }

                    // Si hay un duplicado, establecer el valor del selector actual en blanco
                    if (esDuplicado) {
                        this.value = '';
                    }
                });
            });
        }


        // Restringir el límite de pasajeros al agregar un nuevo pasajero
        var botonAgregarPasajero = document.getElementById('botonAgregarPasajero');
        botonAgregarPasajero.addEventListener('click', function() {

            var pasajerosContainer = document.getElementById('pasajeros');

            // Verificar si todos los campos de las filas anteriores están llenos
            var pasajeroBoxes = pasajerosContainer.querySelectorAll('.pasajeros-box');
            var camposIncompletos = false;
            var contador = 1;
            
            pasajeroBoxes.forEach(function(pasajeroBox) {
                var camposPasajero = pasajeroBox.querySelectorAll('.form-control');
                var camposLlenos = true; // Suponemos que los campos están llenos en esta fila

                camposPasajero.forEach(function(campo) {
                    if (campo.value === '') {
                        camposLlenos = false;
                        return; // Salir del bucle si se encuentra un campo incompleto
                    }
                });

                if (!camposLlenos) {
                    camposIncompletos = true;
                    alert('Por favor, complete todos los campos del "Pasajero N°'+contador+'" antes de agregar uno nuevo.');
                    return; // Detener la ejecución si hay campos incompletos
                } else {
                    contador++;
                }
            });

            if (camposIncompletos) {
                return; // Detener la ejecución si hay campos incompletos
            }

            

            if (contadorPasajeros < capacidadMaxima) {
                var newIndex = pasajerosContainer.getElementsByClassName('pasajeros-box').length + 1;
                var newPasajeroBox = document.createElement('div');
                newPasajeroBox.className = 'pasajeros-box border p-3 mb-3';
                newPasajeroBox.innerHTML = `
                <h5>Pasajero N°${newIndex}</h5>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="oficina_${newIndex}"><i class="fa-solid fa-street-view"></i> Dirección regional:</label>
                            <select id="oficina_${newIndex}" class="form-control" name="oficina_${newIndex}" required>
                                <option style="text-align: center;" selected value="">-- Seleccione una dirección regional --</option>
                                @foreach($oficinas as $oficina)
                                    <option value="{{ $oficina->OFICINA_ID }}">{{ $oficina->OFICINA_NOMBRE }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="dependencia_${newIndex}"><i class="fa-solid fa-building-user"></i> Ubicación o departamento:</label>
                            <select id="dependencia_${newIndex}" class="form-control" name="dependencia_${newIndex}" required disabled>
                                <option style="text-align: center;" selected value="">-- Seleccione una dependencia --</option>
                                <optgroup label="Ubicaciones">
                                    @foreach($ubicaciones as $ubicacion)
                                        <option value="{{ $ubicacion->UBICACION_ID }}">{{ $ubicacion->UBICACION_NOMBRE }}</option>
                                    @endforeach
                                </optgroup>
                                <optgroup label="Departamentos">
                                    @foreach($departamentos as $departamento)
                                        <option value="{{ $departamento->DEPARTAMENTO_ID }}">{{ $departamento->DEPARTAMENTO_NOMBRE }}</option>
                                    @endforeach
                                </optgroup>
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="pasajero_${newIndex}"><i class="fa-solid fa-person-circle-check"></i> Funcionario:</label>
                            <select id="pasajero_${newIndex}" class="form-control" name="pasajero_${newIndex}" required disabled>
                                <option style="text-align: center;" selected value="">-- Seleccione un pasajero --</option>
                                <optgroup label="Funcionario Asociado">
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->USUARIO_NOMBRES }} {{ $user->USUARIO_APELLIDOS }}</option>
                                    @endforeach
                                </optgroup>
                            </select>
                        </div>
                    </div>`;
                pasajerosContainer.appendChild(newPasajeroBox);
                configurarSelectoresPasajero();
                contadorPasajeros++;

                
                actualizarBotonAgregar();
                actualizarBotonEliminar();
            } else {
                alert('Se ha alcanzado la capacidad máxima de pasajeros para este tipo de vehículo.');
            }

            // Desplazar la página hacia abajo para mostrar el último pasajero agregado
            var ultimoPasajeroAgregado = document.querySelector('.pasajeros-box:last-child');
            ultimoPasajeroAgregado.scrollIntoView({ behavior: 'smooth', block: 'start' });
        });

        // Propuesta para eliminar todos los pasajeros 
        // Evento click al botón "Eliminar Pasajero"
        var botonEliminarPasajero = document.getElementById('botonEliminarPasajero');
        botonEliminarPasajero.addEventListener('click', function() {
            var pasajerosContainer = document.getElementById('pasajeros');
            var pasajerosBoxes = pasajerosContainer.getElementsByClassName('pasajeros-box');
            if (pasajerosBoxes.length > 0) {
                var lastPasajeroBox = pasajerosBoxes[pasajerosBoxes.length - 1];
                lastPasajeroBox.remove();
                contadorPasajeros--;
                actualizarBotonAgregar();
                actualizarBotonEliminar();
            }
            // Desplazar la página hacia arriba para mostrar el botón "Agregar Pasajero"
            var botonAgregarPasajero = document.getElementById('botonAgregarPasajero');
            botonAgregarPasajero.scrollIntoView({ behavior: 'smooth', block: 'end' });
        });
        
        console.log("CONTADOR PASJERROS: ",contadorPasajeros);
        /* Propuesta para eliminar pasajeros evitando los solicitados
        
        var botonEliminarPasajero = document.getElementById('botonEliminarPasajero');
        botonEliminarPasajero.addEventListener('click', function() {
            var pasajerosContainer = document.getElementById('pasajeros');
            var pasajerosBoxes = pasajerosContainer.getElementsByClassName('pasajeros-box');
            if (pasajerosBoxes.length > pasajerosExistente) {
                var lastPasajeroBox = pasajerosBoxes[pasajerosBoxes.length - 1];
                lastPasajeroBox.remove();
                contadorPasajeros--;
            }
        });*/
        // Función para actualizar el estado del botón de agregar
        function actualizarBotonAgregar() {
            var botonAgregarPasajero = document.getElementById('botonAgregarPasajero');
            var pasajerosContainer = document.getElementById('pasajeros');
            var totalPasajeros = pasajerosContainer.getElementsByClassName('pasajeros-box').length;
            if (totalPasajeros <= capacidadMaxima) {
                botonAgregarPasajero.style.display = 'block'; // Ocultar el botón si se alcanza el límite
            } 
        }

        // Función para actualizar el estado del botón de eliminar
        function actualizarBotonEliminar() {
            var botonEliminarPasajero = document.getElementById('botonEliminarPasajero');
            var pasajerosContainer = document.getElementById('pasajeros');
            if (pasajerosContainer.getElementsByClassName('pasajeros-box').length > 0) {
                botonEliminarPasajero.style.display = 'block'; // Mostrar el botón si hay pasajeros para eliminar
            } else {
                botonEliminarPasajero.style.display = 'none'; // Ocultar el botón si no hay pasajeros
            }
        }
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var oficinas = {!! json_encode($oficinas) !!};
        var ubicaciones = {!! json_encode($ubicaciones) !!};
        var departamentos = {!! json_encode($departamentos) !!};
        var conductores = {!! json_encode($conductores) !!};

        var oficinaSelects = document.querySelectorAll('.oficina');
        var dependenciaSelects = document.querySelectorAll('.dependencia');
        var conductorSelects = document.querySelectorAll('.conductor');

        
        // Lógica para conductor recuperado
        function fillSelectors(selectedOficinaId, selectedDependenciaId, selectedConductorId) {
            // Llenar select de oficinas
            oficinaSelects.forEach(function(oficinaSelect) {
                oficinas.forEach(function(oficina) {
                    var option = document.createElement('option');
                    option.value = oficina.OFICINA_ID;
                    option.textContent = oficina.OFICINA_NOMBRE;
                    if (oficina.OFICINA_ID === selectedOficinaId) {
                        option.selected = true;
                    }
                    oficinaSelect.appendChild(option);
                });
            });

            // Llenar select de dependencias (ubicaciones y departamentos)
            dependenciaSelects.forEach(function(dependenciaSelect) {
                dependenciaSelect.innerHTML = '<option style="text-align: center;"  value="">-- Seleccione una dependencia --</option>';

                var optgroupUbicaciones = document.createElement('optgroup');
                optgroupUbicaciones.label = 'Ubicaciones';

                var optgroupDepartamentos = document.createElement('optgroup');
                optgroupDepartamentos.label = 'Departamentos';

                ubicaciones.forEach(function(ubicacion) {
                    if (ubicacion.OFICINA_ID == selectedOficinaId) {
                        var option = document.createElement('option');
                        option.value = ubicacion.UBICACION_ID;
                        option.textContent = ubicacion.UBICACION_NOMBRE;
                        if (ubicacion.UBICACION_ID === selectedDependenciaId) {
                            option.selected = true;
                        }
                        optgroupUbicaciones.appendChild(option);
                    }
                });

                departamentos.forEach(function(departamento) {
                    if (departamento.OFICINA_ID == selectedOficinaId) {
                        var option = document.createElement('option');
                        option.value = departamento.DEPARTAMENTO_ID;
                        option.textContent = departamento.DEPARTAMENTO_NOMBRE;
                        if (departamento.DEPARTAMENTO_ID === selectedDependenciaId) {
                            option.selected = true;
                        }
                        optgroupDepartamentos.appendChild(option);
                    }
                });

                dependenciaSelect.appendChild(optgroupUbicaciones);
                dependenciaSelect.appendChild(optgroupDepartamentos);
            });

            // Llenar select de conductores
            conductorSelects.forEach(function(conductorSelect) {
                conductorSelect.innerHTML = '<option style="text-align: center;" value="">-- Seleccione un conductor --</option>';

                conductores.forEach(function(conductor) {
                    if ((conductor.UBICACION_ID == selectedDependenciaId) || (conductor.DEPARTAMENTO_ID == selectedDependenciaId)) {
                        var option = document.createElement('option');
                        option.value = conductor.id;
                        option.textContent = conductor.USUARIO_NOMBRES + ' ' + conductor.USUARIO_APELLIDOS;
                        if (conductor.id === selectedConductorId) {
                            option.selected = true;
                        }
                        conductorSelect.appendChild(option);
                    }
                });
            });
        }

        // Llenar los selectores con los datos de la solicitud si existen
        var solicitudConductor = {!! isset($solicitud->conductor) ? json_encode($solicitud->conductor) : 'null' !!};
        if (solicitudConductor) {
            fillSelectors(solicitudConductor.OFICINA_ID, solicitudConductor.UBICACION_ID || solicitudConductor.DEPARTAMENTO_ID, solicitudConductor.id);
        } else {
            // Llenar los selectores con valores por defecto
            fillSelectors('', '', '');
        }

        // Eventos change a los selectores de oficina
        oficinaSelects.forEach(function(oficinaSelect) {
            oficinaSelect.addEventListener('change', function() {
                var selectedOficinaId = this.value;
                var parentBox = this.closest('.pasajero-box');
                var dependenciaSelect = parentBox.querySelector('.dependencia');
                var conductorSelect = parentBox.querySelector('.conductor');

                dependenciaSelect.innerHTML = '<option style="text-align: center;" value="">-- Seleccione una dependencia --</option>';
                conductorSelect.innerHTML= '<option style="text-align: center;" value="">-- Seleccione un conductor --</option>';

                var optgroupUbicaciones = document.createElement('optgroup');
                optgroupUbicaciones.label = 'Ubicaciones';

                var optgroupDepartamentos = document.createElement('optgroup');
                optgroupDepartamentos.label = 'Departamentos';

                ubicaciones.forEach(function(ubicacion) {
                    if (ubicacion.OFICINA_ID == selectedOficinaId) {
                        var option = document.createElement('option');
                        option.value = ubicacion.UBICACION_ID;
                        option.textContent = ubicacion.UBICACION_NOMBRE;
                        optgroupUbicaciones.appendChild(option);
                    }
                });

                departamentos.forEach(function(departamento) {
                    if (departamento.OFICINA_ID == selectedOficinaId) {
                        var option = document.createElement('option');
                        option.value = departamento.DEPARTAMENTO_ID;
                        option.textContent = departamento.DEPARTAMENTO_NOMBRE;
                        optgroupDepartamentos.appendChild(option);
                    }
                });

                dependenciaSelect.appendChild(optgroupUbicaciones);
                dependenciaSelect.appendChild(optgroupDepartamentos);

                if (selectedOficinaId === '') {
                    dependenciaSelect.disabled = true;
                    conductorSelect.disabled = true;
                } else {
                    dependenciaSelect.disabled = false;
                    conductorSelect.disabled = true;
                }
            });
        });

        // Eventos change a los selectores de dependencia
        dependenciaSelects.forEach(function(dependenciaSelect) {
            dependenciaSelect.addEventListener('change', function() {
                var selectedDependenciaId = this.value;
                var parentBox = this.closest('.pasajero-box');
                var conductorSelect = parentBox.querySelector('.conductor');

                conductorSelect.innerHTML = '<option style="text-align: center;" value="">-- Seleccione al conductor --</option>';

                var optgroupConductoresAsociados = document.createElement('optgroup');
                optgroupConductoresAsociados.label = 'Conductores Asociados';

                conductores.forEach(function(conductor) {
                    if ((conductor.UBICACION_ID == selectedDependenciaId) || (conductor.DEPARTAMENTO_ID == selectedDependenciaId)) {
                        var option = document.createElement('option');
                        option.value = conductor.id;
                        option.textContent = conductor.USUARIO_NOMBRES + ' ' + conductor.USUARIO_APELLIDOS;
                        optgroupConductoresAsociados.appendChild(option);
                    }
                });

                conductorSelect.appendChild(optgroupConductoresAsociados);

                if (selectedDependenciaId === '') {
                    conductorSelect.disabled = true;
                } else {
                    conductorSelect.disabled = false;
                }
            });
        });

        // Eventos change a los selectores de conductores
        conductorSelects.forEach(function(conductorSelect) {
            conductorSelect.addEventListener('change', function() {
                var selectedConductorId = this.value;

                // Obtener todos los contenedores de filas de pasajeros
                var pasajeroRows = document.querySelectorAll('.pasajeros-box');
                
                // Inicializar el contador de pasajeros
                var contador = 1;

                pasajeroRows.forEach(function(row) {
                    // Obtener el último selector de pasajeros dentro de esta fila
                    var ultimoPasajeroSelect = row.querySelector('.form-control:last-child');

                    // Verificar si el pasajero actual coincide con el conductor seleccionado
                    if (ultimoPasajeroSelect && ultimoPasajeroSelect.value === selectedConductorId) {
                        // Mostrar una alerta si el conductor seleccionado ya está asignado como pasajero en otro lugar
                        alert('El conductor seleccionado ya figura como "Pasajero N° ' + contador + '". Por favor, seleccione a otra persona.');
                        
                        // Limpiar la selección del conductor
                        conductorSelect.value = '';
                        
                        // Detener la iteración del forEach
                        return;
                    }

                    // Incrementar el contador de pasajeros
                    contador++;
                });
            });
        });
    });
</script>

<script>
    // Agrega el script para mostrar/ocultar pasajeros
    document.addEventListener('DOMContentLoaded', function() {
        // Obtener referencia al botón y al div de los pasajeros
        const botonPasajeros = document.getElementById('botonPasajeros');
        const pasajerosDiv = document.getElementById('pasajeros');
        const divOculto = document.getElementById('retorno');
        const botonAgregarPasajero = document.getElementById('botonAgregarPasajero');
        const botonEliminarPasajero = document.getElementById('botonEliminarPasajero');
        let capacidadMaxima = obtenerCapacidadMaxima(); // Obtener la capacidad máxima permitida

        // Ocultar los botones de agregar y eliminar pasajeros inicialmente
        botonAgregarPasajero.style.display = 'none';
        botonEliminarPasajero.style.display = 'none';

        botonPasajeros.addEventListener('click', function() {
            console.log("Botón clickeado");
            if (pasajerosDiv.style.display === 'none') {
                console.log("Mostrando pasajeros");
                pasajerosDiv.style.display = 'block'; // Mostrar el div de los pasajeros    
                botonPasajeros.innerHTML = '<i class="fa-solid fa-user-minus"></i> Ocultar Pasajeros'; // Cambiar texto
                
                verificarVisibilidadBotones(); // Verificar visibilidad de botones
                // Desplazar la página hasta la sección de pasajeros
                window.scrollTo({
                    top: pasajerosDiv.offsetTop,
                    behavior: "smooth" // Desplazamiento suave
                });
            } else {
                console.log("Ocultando pasajeros");
                pasajerosDiv.style.display = 'none'; // Ocultar el div de los pasajeros
                botonPasajeros.innerHTML = '<i class="fa-solid fa-user-plus"></i> Mostrar Pasajeros'; // Cambiar texto
                botonAgregarPasajero.style.display = 'none';
                botonEliminarPasajero.style.display = 'none';
                // Desplazar la página hasta la sección de Solicitante
                window.scrollTo({
                    top: divOculto.offsetTop,
                    behavior: "smooth" // Desplazamiento suave
                });
            }
        });

        // Función para obtener la capacidad máxima del vehículo seleccionado
        function obtenerCapacidadMaxima() {
            // Obtener el elemento select del vehículo
            const selectVehiculo = document.getElementById('VEHICULO_ID');

            // Obtener la opción seleccionada
            const opcionSeleccionada = selectVehiculo.options[selectVehiculo.selectedIndex];

            // Obtener la capacidad máxima del atributo de datos personalizado (data-capacidad)
            const capacidadMaxima = opcionSeleccionada.getAttribute('data-capacidad');

            // Retornar la capacidad máxima como un número entero
            return parseInt(capacidadMaxima);
        }

        // Función para verificar la visibilidad de los botones de agregar y eliminar pasajeros
        function verificarVisibilidadBotones() {
            const pasajeroBoxes = pasajerosDiv.querySelectorAll('.pasajeros-box');
            const cantidadPasajeros = pasajeroBoxes.length;
            console.log(cantidadPasajeros);
            console.log(capacidadMaxima);

            // Mostrar u ocultar los botones de acuerdo a la cantidad de pasajeros y la capacidad máxima
            if (( cantidadPasajeros >= 0 ) && ( cantidadPasajeros < (capacidadMaxima-1) )) {
                console.log("HOLA");
                botonAgregarPasajero.style.display = 'block';
            } 
            
            if((cantidadPasajeros <= (capacidadMaxima-1)) && (cantidadPasajeros > 0)) {
                botonEliminarPasajero.style.display ='block';
            } else {
                botonEliminarPasajero.style.display ='none';
            }
        
        }
    });
</script>
@stop

