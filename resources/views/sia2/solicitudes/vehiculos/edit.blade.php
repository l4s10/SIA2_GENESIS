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


            {{-- ENCABEZADO DE LA SOLICITUD: FECHA DE CREACIÓN, TIPO Y ESTADO DE LA SOLICITUD --}}
            <h3>Encabezado</h3>
            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="fecha_solicitud"><i class="fa-regular fa-calendar-days"></i> Fecha de Solicitud:</label>
                        <input type="text" class="form-control" id="fecha_solicitud" name="fecha_solicitud" value="{{ $fechaCreacionFormateada }}" disabled required style="text-align: center;">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="tipo_solicitud"><i class="fa-solid fa-clipboard-question"></i> Tipo de Solicitud:</label>
                        <input type="text" class="form-control" id="tipo_solicitud" name="tipo_solicitud" value="GENERAL" disabled required style="text-align: center;">
                    </div>
                </div>

                

                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="SOLICITUD_ESTADO"><i class="fa-solid fa-right-from-bracket"></i> Estado de la Solicitud:</label>
                        <input type="text" class="form-control" id="SOLICITUD_ESTADO" name="SOLICITUD_ESTADO" value="{{ $solicitud->SOLICITUD_VEHICULO_ESTADO }}" readonly required style="text-align: center; color: #e6500a;">
                    </div>
                    <!-- Leyenda -->
                    <div class="mb-3">
                        <small>La solicitud <strong>ha</strong> sido ingresada</small>
                    </div>
                </div>
            </div>



            <br>
            <h3>Solicitante</h3>
            <div class="form-group mb-4">
                <div class="row mb-4">
                    <div class="col-md-6">
                            <label for="USUARIO_NOMBRES" class="form-label"><i class="fa-solid fa-user"></i> Nombres y Apellidos:</label>
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


                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="DEPARTAMENTO_O_UBICACION" class="form-label"><i class="fa-solid fa-building-user"></i> Ubicación o Departamento:</label>
                        <input type="text" id="DEPARTAMENTO_O_UBICACION" name="DEPARTAMENTO_O_UBICACION" class="form-control" value="{{ isset($solicitud->user->departamento) ? $solicitud->user->departamento->DEPARTAMENTO_NOMBRE : $solicitud->user->ubicacion->UBICACION_NOMBRE }}" readonly required>

                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="CARGO" class="form-label"><i class="fa-solid fa-id-card"></i> Cargo:</label>
                            <input type="text" id="CARGO" class="form-control" name="CARGO" value="{{ $solicitud->user->cargo->CARGO_NOMBRE }}" required readonly>
                        </div>
                    </div>
                </div>


                <div class="form-group" id="motivoSolicitud">
                    <label for="SOLICITUD_VEHICULO_MOTIVO"><i class="fa-solid fa-file-pen"></i> Labor a realizar:</label>
                    <textarea id="SOLICITUD_VEHICULO_MOTIVO" name="SOLICITUD_VEHICULO_MOTIVO" rows="5" class="form-control" placeholder="Indique la labor a realizar (MÁX 255 CARACTERES)" maxlength="255" required readonly>{{ $solicitud->SOLICITUD_VEHICULO_MOTIVO }}</textarea>
                </div>
                <br>
                <div class="row mb-4">
                    <div class="col-md-4">
                        <label for="SOLICITUD_VEHICULO_REGION"><i class="fa-solid fa-map-location-dot"></i> Región de Destino:</label>
                        <input type="text" class="form-control" value="{{ $solicitud->comunaDestino->region->REGION_NOMBRE }}" readonly>
                    </div>
            
                    <div class="col-md-4">
                        <label for="SOLICITUD_VEHICULO_COMUNA"><i class="fa-solid fa-map-location-dot"></i> Comuna de Destino:</label>
                        <input type="text" class="form-control" value="{{ $solicitud->comunaDestino->COMUNA_NOMBRE }}" readonly>
                    </div>
                    <div class="col-md-4">
                        <div id="jefeQueAutoriza">
                            <label for="SOLICITUD_VEHICULO_JEFE_QUE_AUTORIZA"><i class="fa-solid fa-user-check"></i> Jefe que autoriza:</label>
                            <input type="text" class="form-control" value="{{ $cargoJefeQueAutoriza->CARGO_NOMBRE }}" readonly>
                        </div>
                    </div>
                </div>



                {{-- VEHICULO, FECHAS Y HORAS DE EGRESO E INGRESO AL ESTACIONAMIENTO --}}
            <br>
                <div class="row mb-4">
                    <div class="col-md-4">
                        <label for="TIPO_VEHICULO_ID" class="form-label"><i class="fa-solid fa-car-side"></i> Tipo de Vehículo:</label>
                        <input type="text" class="form-control" style="text-align: center;" value="Tipo: {{ $solicitud->tipoVehiculo->TIPO_VEHICULO_NOMBRE }} - Capacidad: {{ $solicitud->tipoVehiculo->TIPO_VEHICULO_CAPACIDAD-1 }}" readonly>
                    </div>
                    <div class="col-md-2">
                    </div>
                    <div class="col-md-3">
                        <label for="fechaHoraInicioSolicitada"><i class="fa-solid fa-compass"></i> Salida del estacionamiento:</label>
                        <input type="text" class="form-control" style="text-align: center;" value="{{ $solicitud->SOLICITUD_VEHICULO_FECHA_HORA_INICIO_SOLICITADA }}" readonly>
                    </div>
                    <div class="col-md-3">
                        <label for="fechaHoraTerminoSolicitada"><i class="fa-solid fa-compass"></i> Reingreso al estacionamiento:</label>
                        <input type="text" class="form-control" style="text-align: center;" value="{{ $solicitud->SOLICITUD_VEHICULO_FECHA_HORA_TERMINO_SOLICITADA }}" readonly>
                    </div>
                </div>

                <div id="pasajeros" class="form-group mb-4" style="display: none;">
                    @foreach($pasajeros as $index => $pasajero)
                        <div class="pasajero-box border p-3 mb-3">
                            <h5>Pasajero N°{{ $index + 1 }}</h5>
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="oficina_{{ $index }}"><i class="fa-solid fa-building"></i> Oficina:</label>
                                    <input type="text" id="oficina_{{ $index }}" class="form-control" value="{{ $pasajero->usuario->oficina->OFICINA_NOMBRE }}" readonly>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="dependencia_{{ $index }}"><i class="fa-solid fa-building-user"></i> Ubicación o Departamento:</label>
                                    <input type="text" id="dependencia_{{ $index }}" class="form-control" value="{{ $pasajero->usuario->ubicacion ? $pasajero->usuario->ubicacion->UBICACION_NOMBRE : $pasajero->usuario->departamento->DEPARTAMENTO_NOMBRE }}" readonly>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="pasajero_{{ $index }}"><i class="fa-solid fa-user-plus"></i> Funcionario:</label>
                                    <input type="text" id="pasajero_{{ $index }}" class="form-control" value="{{ $pasajero->usuario->USUARIO_NOMBRES }} {{ $pasajero->usuario->USUARIO_APELLIDOS }}" readonly>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            
            <button id="botonPasajeros" type="button" class="btn" style="background-color: #00B050; color: #fff;">
                <i class="fa-solid fa-user-plus"></i> Mostrar Pasajeros
                </button>

            <br>
            <br>
            <br>
            <br>
            <br>

      
            <h3>Asignación de Vehículo</h3>         

            <div class="row mb-4">
                <div class="col-md-4">
                    <label for="VEHICULO_ID" class="form-label"><i class="fa-solid fa-car-side"></i> Vehículo:</label>
                    <select name="VEHICULO_ID" id="VEHICULO_ID" class="form-control" required>
                        <option style="text-align: center;" value="">-- Seleccione un vehiculo --</option>
                        @foreach ($vehiculos as $vehiculo)
                            <option value="{{ $vehiculo->VEHICULO_ID }}" {{ $solicitud->VEHICULO_ID == $vehiculo->VEHICULO_ID ? 'selected' : '' }}>
                                Marca: {{ $vehiculo->VEHICULO_MARCA }} - Patente: {{ $vehiculo->VEHICULO_PATENTE }} - Tipo: {{ $vehiculo->tipoVehiculo->TIPO_VEHICULO_NOMBRE }} - Capacidad: {{ $vehiculo->tipoVehiculo->TIPO_VEHICULO_CAPACIDAD }}
                            </option>
                        @endforeach
                    </select>
                    @error('VEHICULO_ID')
                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-2">
                </div>
                <div class="col-md-3">
                    <label for="fechaHoraInicioAsignada"><i class="fa-solid fa-compass"></i> Salida del estacionamiento:</label>
                    <input type="text" class="form-control @error('SOLICITUD_VEHICULO_FECHA_HORA_INICIO_ASIGNADA') is-invalid @enderror" id="fechaHoraInicioAsignada" name="SOLICITUD_VEHICULO_FECHA_HORA_INICIO_ASIGNADA" required placeholder="-- Seleccione la fecha y hora --" style="background-color: #fff; color: #000; text-align: center;" value="{{ $solicitud->SOLICITUD_VEHICULO_FECHA_HORA_INICIO_ASIGNADA }}">
                    @error('SOLICITUD_VEHICULO_FECHA_HORA_INICIO_ASIGNADA')
                        <div class="error" style="color: #E22C2C">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-3">
                    <label for="fechaHoraTerminoAsignada"><i class="fa-solid fa-compass"></i> Reingreso al estacionamiento:</label>
                    <input type="text" class="form-control @error('SOLICITUD_VEHICULO_FECHA_HORA_TERMINO_ASIGNADA') is-invalid @enderror" id="fechaHoraTerminoAsignada" name="SOLICITUD_VEHICULO_FECHA_HORA_TERMINO_ASIGNADA" required placeholder="-- Seleccione la fecha y hora --" style="background-color: #fff; color: #000; text-align: center;" value="{{$solicitud->SOLICITUD_VEHICULO_FECHA_HORA_TERMINO_ASIGNADA ?? ''  }}">
                    @error('SOLICITUD_VEHICULO_FECHA_HORA_TERMINO_ASIGNADA')
                        <div class="error" style="color: #E22C2C">{{ $message }}</div>
                    @enderror
                </div>
                

                
            </div>



            





            <div class="pasajero-box border p-3 mb-3">
                <h3>Asignación de Conductor</h3>
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="oficina">Oficina</label>
                        <select id="oficina" class="form-control oficina" required>
                            <option style="text-align: center;" value="">-- Seleccione una opción --</option>
                            @foreach($oficinas as $oficina)
                                <option value="{{ $oficina->OFICINA_ID }}">{{ $oficina->OFICINA_NOMBRE }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="dependencia">Ubicación o Departamento</label>
                        <select id="dependencia" class="form-control dependencia" disabled required>
                            <option style="text-align: center;" value="">-- Seleccione una opción --</option>
                            <optgroup label="Ubicaciones">
                                @foreach($ubicaciones as $ubicacion)
                                    <option value="{{ $ubicacion->UBICACION_ID }}" data-office-id="{{ $ubicacion->OFICINA_ID }}">{{ $ubicacion->UBICACION_NOMBRE }}</option>
                                @endforeach
                            </optgroup>
                            <optgroup label="Departamentos">
                                @foreach($departamentos as $departamento)
                                <option value="{{ $departamento->DEPARTAMENTO_ID }}" data-office-id="{{ $departamento->DEPARTAMENTO_ID }}">{{ $departamento->DEPARTAMENTO_NOMBRE }}</option>
                                @endforeach
                            </optgroup>
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="conductor"><i class="fa-solid fa-user-plus"></i> Conductor:</label>
                        <select id="conductor" class="form-control conductor" name="CONDUCTOR_id"  disabled required>
                            <option style="text-align: center;" value="">-- Seleccione al conductor --</option>
                            <optgroup label="Conductores">
                                @foreach($conductores as $conductor)
                                    <option value="{{ $conductor->id }}"  data-ubicacion-id="{{ $conductor->UBICACION_ID }}" data-departamento-id="{{ $conductor->DEPARTAMENTO_ID }}" >{{ $conductor->USUARIO_NOMBRES }} {{ $conductor->USUARIO_APELLIDOS }}</option>
                                @endforeach
                            </optgroup>
                            <optgroup label="Funcionarios Asociados">
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" data-ubicacion-id="{{ $user->UBICACION_ID }}" data-departamento-id="{{ $user->DEPARTAMENTO_ID }}" >{{ $user->USUARIO_NOMBRES }} {{ $user->USUARIO_APELLIDOS }}</option>
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
                        <label for="SOLICITUD_VEHICULO_HORA_INICIO_CONDUCCION"><i class="fa-solid fa-compass"></i> Hora de Inicio de Conducción:</label>
                        <input type="time" class="form-control" id="SOLICITUD_VEHICULO_HORA_INICIO_CONDUCCION" name="SOLICITUD_VEHICULO_HORA_INICIO_CONDUCCION" required placeholder="-- Seleccione la hora de inicio --" style="background-color: #fff; color: #000; text-align: center;" value="{{ isset($solicitud) ? $solicitud->SOLICITUD_VEHICULO_HORA_INICIO_CONDUCCION : '' }}">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="SOLICITUD_VEHICULO_HORA_TERMINO_CONDUCCION"><i class="fa-solid fa-compass"></i> Hora de Término de Conducción:</label>
                        <input type="time" class="form-control" id="SOLICITUD_VEHICULO_HORA_TERMINO_CONDUCCION" name="SOLICITUD_VEHICULO_HORA_TERMINO_CONDUCCION" required placeholder="-- Seleccione la hora de término --" style="background-color: #fff; color: #000; text-align: center;" value="{{ isset($solicitud) ? $solicitud->SOLICITUD_VEHICULO_HORA_TERMINO_CONDUCCION : '' }}" >
                        @error('SOLICITUD_VEHICULO_HORA_TERMINO_CONDUCCION')
                            <div class="error" style="color: #E22C2C">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            





           {{-- DATOS DE SALIDA --}}
            <br>           
            <br>
            <h3>Salida</h3>      
            
            <br><br><br><br>
            <button type="submit" class="btn btn-success" name="guardar">Guardar Cambios</button>
            <button type="submit" class="btn btn-success" name="autorizar">Autorizar</button>
            <a href="{{ route('solicitudesvehiculos.exportar') }}" class="btn btn-primary">Descargar Excel</a>
            <a href="{{ route('descargar.plantilla', ['id' => $solicitud->SOLICITUD_VEHICULO_ID]) }}">Descargar Plantilla Excel</a>



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
            dateFormat: "Y-m-d H:i",
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
            dateFormat: "Y-m-d H:i",
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
            dateFormat: "Y-m-d H:i",
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
            dateFormat: "Y-m-d H:i",
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
        let year = date.getFullYear();
        let month = (date.getMonth() + 1).toString().padStart(2, '0');
        let day = date.getDate().toString().padStart(2, '0');
        let hours = date.getHours().toString().padStart(2, '0');
        let minutes = date.getMinutes().toString().padStart(2, '0');
        return `${year}-${month}-${day} ${hours}:${minutes}`;
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
        var oficinas = {!! json_encode($oficinas) !!};
        var ubicaciones = {!! json_encode($ubicaciones) !!};
        var departamentos = {!! json_encode($departamentos) !!};
        var conductores = {!! json_encode($users) !!};
        var conductoresConPoliza = {!! json_encode($conductores) !!};

        var oficinaSelects = document.querySelectorAll('.oficina');
        var dependenciaSelects = document.querySelectorAll('.dependencia');
        var conductorSelects = document.querySelectorAll('.conductor');

        // Lógica para llenar los selectores con la información del conductor si está disponible
        conductorSelects.forEach(function(conductorSelect) {
            var conductorId = {!! json_encode($solicitud->conductor->id ?? '') !!};
            var ubicacionId = {!! json_encode($solicitud->conductor->UBICACION_ID ?? '') !!};
            var departamentoId = {!! json_encode($solicitud->conductor->DEPARTAMENTO_ID ?? '') !!};
            var oficinaId = {!! json_encode($solicitud->conductor->OFICINA_ID ?? '') !!};

            
            var parentBox = conductorSelect.closest('.pasajero-box');
            var dependenciaSelect = parentBox.querySelector('.dependencia');
            var oficinaSelect = parentBox.querySelector('.oficina');


            if (conductorId !== '') {
                conductorSelect.disabled = false;
                conductorSelect.value = conductorId;
                console.log("Conductor $solicitud->conductor->id: ", conductorSelect);
            }

            if (ubicacionId !== '') {
                dependenciaSelect.disabled = false;
                dependenciaSelect.value = ubicacionId;
                console.log("Dependencia ubicacion $solicitud->conductor->UBICACION_ID: ", dependenciaSelect);
            } else if (departamentoId !== '') {
                dependenciaSelect.disabled = false;
                dependenciaSelect.value = departamentoId;
                console.log("Dependencia departamento $solicitud->conductor->DEPARTAMENTO_ID: ", dependenciaSelect);
            }

            if (oficinaId !== '') {
                oficinaSelect.value = oficinaId;
                console.log("Conductor $solicitud->conductor->OFICINA_ID: ", oficinaSelect);
            }

            // Después de asignar valores, llamar a la función de filtrado correspondiente
            filterDependenciaOptions(oficinaSelect, dependenciaSelect, conductorSelect);
        });

        function filterDependenciaOptions(oficinaSelect, dependenciaSelect, conductorSelect) {
    var selectedOficinaId = oficinaSelect.value;

    // Limpiar opciones anteriores
    dependenciaSelect.innerHTML = '';
    conductorSelect.innerHTML = '<option value="">-- Seleccione al conductor --</option>';

    // Agregar optgroup para ubicaciones
    var optgroupUbicaciones = document.createElement('optgroup');
    optgroupUbicaciones.label = 'Ubicaciones';

    // Agregar optgroup para departamentos
    var optgroupDepartamentos = document.createElement('optgroup');
    optgroupDepartamentos.label = 'Departamentos';

    // Filtrar y agregar opciones de ubicaciones y departamentos según la oficina seleccionada
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

    // Agregar optgroups al select dependencia
    dependenciaSelect.appendChild(optgroupUbicaciones);
    dependenciaSelect.appendChild(optgroupDepartamentos);

    if (selectedOficinaId === '') {
        dependenciaSelect.disabled = true;
        conductorSelect.disabled = true;
    } else {
        dependenciaSelect.disabled = false;
        conductorSelect.disabled = true;
    }
}

        oficinaSelects.forEach(function(oficinaSelect) {
            oficinaSelect.addEventListener('change', function() {
                var selectedOficinaId = this.value;
                var parentBox = this.closest('.pasajero-box');
                var dependenciaSelect = parentBox.querySelector('.dependencia');
                var conductorSelect = parentBox.querySelector('.conductor');

                // Limpiar opciones anteriores
                dependenciaSelect.innerHTML = '<option value="">   -- Seleccione una opción --</option>';
                conductorSelect.innerHTML= '<option value="">   -- Seleccione un conductor --</option>';

                // Agregar optgroup para ubicaciones
                var optgroupUbicaciones = document.createElement('optgroup');
                optgroupUbicaciones.label = 'Ubicaciones';

                // Agregar optgroup para departamentos
                var optgroupDepartamentos = document.createElement('optgroup');
                optgroupDepartamentos.label = 'Departamentos';

                // Filtrar y agregar opciones de ubicaciones y departamentos según la oficina seleccionada
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

                // Agregar optgroups al select dependencia
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
        dependenciaSelects.forEach(function(dependenciaSelect) {
            dependenciaSelect.addEventListener('change', function() {
                var selectedDependenciaId = this.value;
                var parentBox = this.closest('.pasajero-box');
                var conductorSelect = parentBox.querySelector('.conductor');

                // Limpiar opciones anteriores
                conductorSelect.innerHTML = '<option value="">-- Seleccione al conductor --</option>';

                // Agregar optgroup para conductores sin poliza (cualquier funcionario asociado a ubicacion o departamento)
                var optgroupConductoresAsociados = document.createElement('optgroup');
                optgroupConductoresAsociados.label = 'Funcionarios Asociados';

                // Agregar optgroup para conductores con póliza en la dirección regional
                var optgroupConductoresPoliza = document.createElement('optgroup');
                optgroupConductoresPoliza.label = 'Conductores con Póliza';

                // Filtrar y agregar opciones de conductores según la dependencia seleccionada
                conductores.forEach(function(conductor) {
                    if ((conductor.UBICACION_ID == selectedDependenciaId) || (conductor.DEPARTAMENTO_ID == selectedDependenciaId)) {
                        var option = document.createElement('option');
                        option.value = conductor.id;
                        option.textContent = conductor.USUARIO_NOMBRES + ' ' + conductor.USUARIO_APELLIDOS;
                        optgroupConductoresAsociados.appendChild(option);
                    }
                });

                // Agregar conductores con póliza al optgroup correspondiente
                conductoresConPoliza.forEach(function(conductorConPoliza) {
                    if ((conductorConPoliza.UBICACION_ID == selectedDependenciaId) || (conductorConPoliza.DEPARTAMENTO_ID == selectedDependenciaId)) {
                        var option = document.createElement('option');
                        option.value = conductorConPoliza.id;
                        option.textContent = conductorConPoliza.USUARIO_NOMBRES + ' ' + conductorConPoliza.USUARIO_APELLIDOS;
                        optgroupConductoresPoliza.appendChild(option);
                    }
                });

                // Agregar optgroups al select de conductores
                conductorSelect.appendChild(optgroupConductoresPoliza);
                conductorSelect.appendChild(optgroupConductoresAsociados);

                if (selectedDependenciaId === '') {
                    conductorSelect.disabled = true;
                } else {
                    conductorSelect.disabled = false;
                }
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
        const laborDiv = document.getElementById('motivoSolicitud')

        botonPasajeros.addEventListener('click', function() {
            console.log("Botón clickeado");
            if (pasajerosDiv.style.display === 'none') {
                console.log("Mostrando pasajeros");
                pasajerosDiv.style.display = 'block'; // Mostrar el div de los pasajeros    
                botonPasajeros.style.backgroundColor = '#E22C2C'; // Cambiar el color a rojo
                botonPasajeros.innerHTML = '<i class="fa-solid fa-user-minus"></i> Ocultar Pasajeros'; // Cambiar texto

                // Desplazar la página hasta la sección de pasajeros
                window.scrollTo({
                    top: pasajerosDiv.offsetTop,
                    behavior: "smooth" // Desplazamiento suave
                });
            } else {
                console.log("Ocultando pasajeros");
                pasajerosDiv.style.display = 'none'; // Ocultar el div de los pasajeros
                botonPasajeros.style.backgroundColor = '#00B050'; // Cambiar el color a verde
                botonPasajeros.innerHTML = '<i class="fa-solid fa-user-plus"></i> Mostrar Pasajeros'; // Cambiar texto

                // Desplazar la página hasta la sección de Solicitante
                window.scrollTo({
                    top: laborDiv.offsetTop,
                    behavior: "smooth" // Desplazamiento suave
                });
            }
        });
    });
</script>
@stop

