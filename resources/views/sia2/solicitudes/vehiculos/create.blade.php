@extends('adminlte::page')

@section('title', 'Crear Solicitud Vehicular')

@section('content_header')
    <h1>Solicitud Vehicular</h1>
    <br>
    <br>

@stop

@section('content')


    <div class="container">
        <form action="{{ route('solicitudesvehiculos.store') }}" method="POST">
            @csrf

            {{-- ENCABEZADO DE LA SOLICITUD: FECHA DE CREACIÓN, TIPO Y ESTADO DE LA SOLICITUD --}}
            <h3>Encabezado</h3>
            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="fecha_solicitud"><i class="fa-regular fa-calendar-days"></i> Fecha de Solicitud:</label>
                        <input type="text" class="form-control" id="fecha_solicitud" name="fecha_solicitud" disabled required style="text-align: center;">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="tipo_solicitud"><i class="fa-solid fa-clipboard-question"></i> Tipo de Solicitud:</label>
                        <select class="form-control" id="tipo_solicitud" name="tipo_solicitud" required >
                            <option style="text-align: center;" value="" disabled selected>-- Seleccione Tipo de Solicitud --</option>
                            <option value="CUMPLIMIENTO TRIBUTARIO EN TERRENO">CUMPLIMIENTO TRIBUTARIO EN TERRENO</option>
                            <option value="GENERAL">GENERAL</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="SOLICITUD_ESTADO"><i class="fa-solid fa-right-to-bracket"></i> Estado de la Solicitud:</label>
                        <input type="text" class="form-control" id="SOLICITUD_ESTADO" name="SOLICITUD_ESTADO" value="POR INGRESAR" readonly style="color: green; text-align: center;">
                    </div>
                    {{-- Leyenda --}}
                    <div class="mb-3">
                        <small>La solicitud todavía <strong>no</strong> ha sido ingresada</small>
                    </div>
                </div>
            </div>

            {{-- DATOS DEL SOLICITANTE --}}
            <br>
            <h3>Solicitante</h3>
            <div class="row mb-4">
                <div class="col-md-6">
                    <input type="text" name="SOLICITANTE_ID" value="{{ auth()->user()->id    }}" hidden>
                        <label for="USUARIO_NOMBRES" class="form-label"><i class="fa-solid fa-user"></i> Nombres y Apellidos:</label>
                        <input type="text" id="USUARIO_NOMBRES" name="USUARIO_NOMBRES" class="form-control{{ $errors->has('USUARIO_NOMBRES') ? ' is-invalid' : '' }}" value="{{ auth()->user()->USUARIO_NOMBRES }} {{ auth()->user()->USUARIO_APELLIDOS }}" readonly required>
                        @if ($errors->has('USUARIO_NOMBRES'))
                            <div class="invalid-feedback">
                                {{ $errors->first('USUARIO_NOMBRES') }}
                            </div>
                        @endif
                </div>

                <div class="col-md-4">
                    <label for="EMAIL" class="form-label"><i class="fa-solid fa-envelope"></i> Email:</label>
                    <input type="email" id="EMAIL" name="EMAIL" class="form-control{{ $errors->has('EMAIL') ? ' is-invalid' : '' }}" value="{{ auth()->user()->email }}" readonly required>
                    @if ($errors->has('EMAIL'))
                        <div class="invalid-feedback">
                            {{ $errors->first('EMAIL') }}
                        </div>
                    @endif
                </div>

                <div class="col-md-2">
                    <label for="USUARIO_RUT" class="form-label"><i class="fa-solid fa-id-card"></i> RUT:</label>
                    <input type="text" id="USUARIO_RUT" name="USUARIO_RUT" class="form-control{{ $errors->has('USUARIO_RUT') ? ' is-invalid' : '' }}" value="{{ auth()->user()->USUARIO_RUT }}" readonly required>
                    @if ($errors->has('USUARIO_RUT'))
                    <div class="invalid-feedback">
                        {{ $errors->first('USUARIO_RUT') }}
                    </div>
                    @endif
                </div>
            </div>
            <div class="row mb-4">
                <div class="col-md-6">
                        <label for="DEPARTAMENTO_O_UBICACION" class="form-label"><i class="fa-solid fa-building-user"></i> Ubicación o Departamento:</label>
                        <input type="text" id="DEPARTAMENTO_O_UBICACION" name="DEPARTAMENTO_O_UBICACION" class="form-control{{ $errors->has('DEPARTAMENTO_O_UBICACION') ? ' is-invalid' : '' }}" value="{{ isset(auth()->user()->departamento) ? auth()->user()->departamento->DEPARTAMENTO_NOMBRE : auth()->user()->ubicacion->UBICACION_NOMBRE }}"  readonly required>
                        @if ($errors->has('DEPARTAMENTO_O_UBICACION'))
                            <div class="invalid-feedback">
                                {{ $errors->first('DEPARTAMENTO_O_UBICACION') }}
                            </div>
                        @endif
                </div>
                <div class="col-md-6">
                        <label for="OFICINA" class="form-label"><i class="fa-solid fa-map-location-dot"></i> Cargo:</label>
                        <input type="text" id="OFICINA" class="form-control" name="OFICINA" value="{{ auth::user()->cargo->CARGO_NOMBRE }}" required readonly>
                        @error('ID_REGION')
                            <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror
                </div>     
            </div>
                
            {{-- CAMPOS PARA SOLICITUD CTT --}}
            <div id="ordenTrabajoInputs" style="display: none;">
                <br>
                <h3>Orden de Trabajo (CTT)</h3>
                <div class="row mb-4">
                    <div class="form-group col-md-4">
                        <label for="TRABAJA_NUMERO_ORDEN_TRABAJO">Número de la Orden de Trabajo:</label>
                        <input type="number" class="form-control" id="TRABAJA_NUMERO_ORDEN_TRABAJO" name="TRABAJA_NUMERO_ORDEN_TRABAJO" required min="0" max="999999">
                        @if ($errors->has('TRABAJA_NUMERO_ORDEN_TRABAJO'))
                            <div class="invalid-feedback">
                                {{ $errors->first('TRABAJA_NUMERO_ORDEN_TRABAJO') }}
                            </div>
                        @endif
                    </div>
                    <div class="form-group col-md-2">
    
                    </div>
                    <div class="form-group col-md-3">
                        <label for="TRABAJA_HORA_INICIO_ORDEN_TRABAJO"> Inicio orden de trabajo:</label>
                        <input type="time" class="form-control" id="TRABAJA_HORA_INICIO_ORDEN_TRABAJO" name="TRABAJA_HORA_INICIO_ORDEN_TRABAJO" required placeholder="-- Seleccione la hora de inicio --" style="background-color: #fff; color: #000; text-align: center;">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="TRABAJA_HORA_TERMINO_ORDEN_TRABAJO"> Fin orden de trabajo:</label>
                        <input type="time" class="form-control" id="TRABAJA_HORA_TERMINO_ORDEN_TRABAJO" name="TRABAJA_HORA_TERMINO_ORDEN_TRABAJO" required placeholder="-- Seleccione la hora de término--" style="background-color: #fff; color: #000; text-align: center;" >
                    </div>
                </div>
            </div>
            

            {{-- VEHICULO, FECHAS Y HORAS DE EGRESO E INGRESO AL ESTACIONAMIENTO --}}
            <br>
            <h3>Vehículo</h3>
            <div class="row mb-4">
                <div class="col-md-4">
                    <label for="VEHICULO_ID" class="form-label"><i class="fa-solid fa-car-side"></i> Vehículo</label>
                    <select name="VEHICULO_ID" id="VEHICULO_ID" class="form-control" required>
                        <option style="text-align: center;" value="">-- Seleccione un vehículo --</option>
                        @foreach ($vehiculos as $vehiculo)
                            <option value="{{ $vehiculo->VEHICULO_ID }}" data-capacidad="{{ $vehiculo->tipoVehiculo->TIPO_VEHICULO_CAPACIDAD }}" >
                                {{ $vehiculo->VEHICULO_PATENTE }}  - {{ $vehiculo->tipoVehiculo->TIPO_VEHICULO_NOMBRE }} - Capacidad: {{ $vehiculo->tipoVehiculo->TIPO_VEHICULO_CAPACIDAD }}
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
                    <label for="fechaHoraInicioSolicitada"> Salida del estacionamiento:</label>
                    <input type="text" class="form-control" id="fechaHoraInicioSolicitada" name="SOLICITUD_VEHICULO_FECHA_HORA_INICIO_SOLICITADA" required placeholder="-- Seleccione la fecha y hora --" style="background-color: #fff; color: #000; text-align: center;"> 
                </div>
                <div class="col-md-3">
                    <label for="fechaHoraTerminoSolicitada"> Reingreso al estacionaiento</label>
                    <input type="text" class="form-control" id="fechaHoraTerminoSolicitada" name="SOLICITUD_VEHICULO_FECHA_HORA_TERMINO_SOLICITADA" required placeholder="-- Seleccione la fecha y hora --" style="background-color: #fff; color: #000; text-align: center;">
                </div>
            </div>

            
            <div class="form-group" id="motivoSolicitud" style="display: none;">
                <label for="SOLICITUD_VEHICULO_MOTIVO"><i class="fa-solid fa-file-pen"></i> Labor a realizar:</label>
                <textarea id="SOLICITUD_VEHICULO_MOTIVO" name="SOLICITUD_VEHICULO_MOTIVO" rows="5" class="form-control" placeholder="Indique la labor a realizar (MÁX 255 CARACTERES)" maxlength="255" required></textarea>
            </div>
             
        

            {{-- BOTONES PARA AGREGAR O ELIMINAR PASAJEROS/CONDUCTOR  (NO TOCAR) --}}
            <div id="pasajeros" style="display: none;">
            </div>
            <div class="col">
                <div class="row">
                    <button id="agregarPasajeroBtn" class="btn" style="background-color: #1aa16b; color: #fff;">
                        <i class="fas fa-plus"></i> Agregar Pasajero
                    </button>
                            
                    <button id="eliminarPasajeroBtn" class="btn" style="background-color: #dc3545; color: #fff;">
                        <i class="fas fa-minus"></i> Eliminar Pasajero
                    </button>
                </div>
            </div>


            {{-- VIATICO, HORAS DE INICIO Y FIN CONDUCCIÓN --}}
            {{--<div id="horaInicioConduccion" style="display: none;">
                <div class="form-group col-md-4">
                    <label for="SOLICITUD_VEHICULO_VIATICO"> Viático:</label>
                    <select class="form-control" id="SOLICITUD_VEHICULO_VIATICO" name="SOLICITUD_VEHICULO_VIATICO" >
                        <option style="text-align: center;" value="" required selected>-- Seleccione una opción --</option>
                        <option value="SI">SI</option>
                        <option value="NO">NO</option>
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label for="SOLICITUD_VEHICULO_HORA_INICIO_CONDUCCION">Hora de Inicio de Conducción:</label>
                    <input type="time" class="form-control" id="SOLICITUD_VEHICULO_HORA_INICIO_CONDUCCION" name="SOLICITUD_VEHICULO_HORA_INICIO_CONDUCCION" required placeholder="-- Seleccione la hora de inicio --" style="background-color: #fff; color: #000; text-align: center;">
                </div>
                <div class="form-group col-md-4">
                    <label for="SOLICITUD_VEHICULO_HORA_TERMINO_CONDUCCION">Hora de Término de Conducción:</label>
                    <input type="time" class="form-control" id="SOLICITUD_VEHICULO_HORA_TERMINO_CONDUCCION" name="SOLICITUD_VEHICULO_HORA_TERMINO_CONDUCCION" required placeholder="-- Seleccione la hora de término --" style="background-color: #fff; color: #000; text-align: center;">
                </div>
            </div>--}}

            {{-- DATOS DE SALIDA --}}
            <br>           
            <br>
            <h3>Salida</h3>      
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="SOLICITUD_VEHICULO_REGION">Región de Destino</label>
                        <select name="SOLICITUD_VEHICULO_REGION" id="SOLICITUD_VEHICULO_REGION" class="form-control" required>
                            <option value="">-- Seleccione la región de destino --</option>
                            @foreach ($regiones as $region)
                                <option value="{{ $region->REGION_ID }}">{{ $region->REGION_NOMBRE }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="SOLICITUD_VEHICULO_COMUNA">Comuna de Destino</label>
                        <select name="SOLICITUD_VEHICULO_COMUNA" id="SOLICITUD_VEHICULO_COMUNA" class="form-control" required>
                            <option value="">-- Seleccione la comuna de destino --</option>
                            <!-- Las opciones de las comunas se cargarán dinámicamente aquí -->
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <div id=jefeQueAutoriza>
                            <label for="SOLICITUD_VEHICULO_JEFE_QUE_AUTORIZA">Jefe que autoriza</label>
                            <select name="SOLICITUD_VEHICULO_JEFE_QUE_AUTORIZA" id="SOLICITUD_VEHICULO_JEFE_QUE_AUTORIZA" class="form-control" required>
                                <option value="">-- Seleccione el jefe que autoriza --</option>
                                @foreach ($jefesQueAutorizan as $jefe)
                                    <option value="{{ $jefe->CARGO_ID }}">{{ $jefe->CARGO_NOMBRE }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <br><br><br><br>
        <button type="submit" class="btn btn-primary">Crear Solicitud</button>

    </div>
     

@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">

@stop

@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

   

    <script>
        const fechaInput = document.getElementById('fecha_solicitud');

        const options = { 
            weekday: 'long', 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric' 
        };

        const fechaActual = new Date().toLocaleDateString('es-ES', options).toUpperCase();

        fechaInput.value = fechaActual;
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let regionSelect = document.getElementById('SOLICITUD_VEHICULO_REGION');
            let comunaSelect = document.getElementById('SOLICITUD_VEHICULO_COMUNA');
            let comunas = {!! json_encode($comunas) !!}; // Convertimos las comunas de PHP a JavaScript

            regionSelect.addEventListener('change', function() {
                let selectedRegionId = regionSelect.value;
                comunaSelect.innerHTML = ''; // Limpiamos las opciones de comuna

                if (selectedRegionId !== '') {
                    // Filtramos las comunas según la región seleccionada
                    let filteredComunas = comunas.filter(function(comuna) {
                        return comuna.REGION_ID == selectedRegionId;
                    });

                    // Agregamos las opciones de comuna filtradas al select de comuna
                    filteredComunas.forEach(function(comuna) {
                        let option = document.createElement('option');
                        option.value = comuna.COMUNA_ID;
                        option.textContent = comuna.COMUNA_NOMBRE;
                        comunaSelect.appendChild(option);
                    });
                } else {
                    // Si no se selecciona ninguna región, mostramos el mensaje predeterminado
                    let defaultOption = document.createElement('option');
                    defaultOption.value = '';
                    defaultOption.textContent = '-- Seleccione la comuna de destino --';
                    comunaSelect.appendChild(defaultOption);
                }
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let inputfechaHoraInicioSolicitada = document.getElementById('fechaHoraInicioSolicitada');
            let inputfechaHoraTerminoSolicitada = document.getElementById('fechaHoraTerminoSolicitada');

            let fechaActual = new Date(); // Fecha y hora actual
            let añoActual = fechaActual.getFullYear(); // Año actual
            let mesActual = fechaActual.getMonth(); // Mes actual
            let diaActual = fechaActual.getDate(); // Día actual
            let horaActual = fechaActual.getHours(); // Hora actual
            let minutoActual = fechaActual.getMinutes(); // Minuto actual

            // **Fecha mínima permitida (día actual)**
            let fechaMinimaPermitida = new Date(añoActual, mesActual, diaActual, horaActual, minutoActual);

            // **Fecha y hora predeterminada (fecha y hora actual)**
            let fechaHoraPredeterminada = new Date(añoActual, mesActual, diaActual, horaActual, minutoActual);

            // **Fecha máxima permitida**
            let fechaMaximaPermitida;

            // Si estamos en diciembre, permitir hasta febrero del próximo año
            if (mesActual === 11) {
                fechaMaximaPermitida = new Date(añoActual + 1, 1, 28);
            } else {
                // Permitir hasta diciembre del año actual
                fechaMaximaPermitida = new Date(añoActual, 11, 31);
            }

            /// **Configuración del selector de fecha y hora de inicio**
            flatpickr(inputfechaHoraInicioSolicitada, {
                enableTime: true,
                dateFormat: "Y-m-d H:i",
                minDate: fechaMinimaPermitida,
                maxDate: fechaMaximaPermitida,
                defaultDate: fechaHoraPredeterminada,
                locale: "es", // Establecer el idioma en español
                onChange: function(selectedDates, dateStr, instance) {
                    if (selectedDates[0] < fechaMinimaPermitida) {
                        alert("La fecha y hora seleccionada es menor a la hora mínima permitida");
                        inputfechaHoraInicioSolicitada.value = "";
                    } else {
                        // Habilitar el input de término una vez que se ha seleccionado la hora de inicio
                        inputfechaHoraTerminoSolicitada.disabled = false;
                        // Actualizar minDate para el input de término
                        inputfechaHoraTerminoSolicitada._flatpickr.set("minDate", selectedDates[0]);
                    }
                }
            });

            // **Configuración del selector de fecha y hora de término**
            flatpickr(inputfechaHoraTerminoSolicitada, {
                enableTime: true,
                dateFormat: "Y-m-d H:i",
                minDate: fechaMinimaPermitida, // Se establece inicialmente, luego se actualizará
                maxDate: fechaMaximaPermitida,
                locale: "es" // Establecer el idioma en español
            });

            // Deshabilitar el input de término al cargar la página y establecer su valor como vacío
            inputfechaHoraTerminoSolicitada.disabled = true;
            inputfechaHoraInicioSolicitada.value = "";
            inputfechaHoraTerminoSolicitada.value = "";
        });
    </script>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let tipoSolicitudSelect = document.getElementById('tipo_solicitud');
            let inputNumeroOrdenTrabajo = document.getElementById('TRABAJA_NUMERO_ORDEN_TRABAJO');
            let inputHoraInicioOrdenTrabajo = document.getElementById('TRABAJA_HORA_INICIO_ORDEN_TRABAJO');
            let inputHoraTerminoOrdenTrabajo = document.getElementById('TRABAJA_HORA_TERMINO_ORDEN_TRABAJO');
            let jefeQueAutorizaSelect = document.getElementById('SOLICITUD_VEHICULO_JEFE_QUE_AUTORIZA');
            let ordenTrabajoInputs = document.getElementById('ordenTrabajoInputs');


            tipoSolicitudSelect.addEventListener('change', function() {
                let tipoSolicitud = tipoSolicitudSelect.value;
                console.log(ordenTrabajoInputs);
                if (tipoSolicitud === 'CUMPLIMIENTO TRIBUTARIO EN TERRENO') {
                    ordenTrabajoInputs.style.display = 'block';
                    document.getElementById('jefeQueAutoriza').style.display = 'none';
                    jefeQueAutorizaSelect.required = false;

                } else {
                    ordenTrabajoInputs.style.display = 'none';
                    document.getElementById('jefeQueAutoriza').style.display = 'block';
                    inputNumeroOrdenTrabajo.required = false;
                    inputHoraInicioOrdenTrabajo.required = false;
                    inputHoraTerminoOrdenTrabajo.required = false;
                }

            });

            flatpickr(inputHoraInicioOrdenTrabajo, {
                enableTime: true,
                noCalendar: true,
                dateFormat: "H:i",
                onChange: function(selectedDates, dateStr, instance) {
                    let horaTerminoSelector = document.getElementById("TRABAJA_HORA_TERMINO_ORDEN_TRABAJO");
                    horaTerminoSelector._flatpickr.clear(); // Limpiar la selección anterior
                    horaTerminoSelector._flatpickr.set("minTime", dateStr); // Establecer la hora mínima
                    horaTerminoSelector.disabled = false; // Habilitar el input de hora de término
                }
            });

            flatpickr(inputHoraTerminoOrdenTrabajo, {
                enableTime: true,
                noCalendar: true,
                dateFormat: "H:i",
                placeholder: "-- Seleccione la hora --", // Establecer el marcador de posición
            });

            // Limpiar los valores iniciales
            inputHoraInicioOrdenTrabajo.value = "";
            inputHoraTerminoOrdenTrabajo.value = "";
        });

    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Variables de control
            let prevVehiculoId = ''; // Almacena el ID del vehículo previamente seleccionado
            let contadorFilas = 1; // Contador de filas de pasajeros
            let capacidadMaxima = 0; // Capacidad máxima de pasajeros para el vehículo seleccionado
            let pasajerosSeleccionados = new Set(); // Conjunto que almacena los IDs de pasajeros seleccionados

            // Ocultar botones y registro de pasajeros al inicio
            document.getElementById('agregarPasajeroBtn').style.display = 'none';
            document.getElementById('eliminarPasajeroBtn').style.display = 'none';


            // Función para reiniciar el conjunto de pasajeros seleccionados
            function reiniciarPasajerosSeleccionados() {
                pasajerosSeleccionados = new Set();
            }


            // Evento de cambio en el vehículo
            document.getElementById('VEHICULO_ID').addEventListener('change', function() {
                let vehiculoIdSeleccionado = this.value;
                document.getElementById('motivoSolicitud').style.display = 'block';

                contadorFilas = 1;
                capacidadMaxima = 0;
                document.getElementById('agregarPasajeroBtn').innerHTML = '<i class="fas fa-plus"></i> Agregar Conductor';
                document.getElementById('eliminarPasajeroBtn').innerHTML = '<i class="fas fa-minus"></i> Eliminar Conductor';
                reiniciarPasajerosSeleccionados();

                if (vehiculoIdSeleccionado === '') {
                    // Confirmar eliminación si se deselecciona el vehículo
                    let confirmacion = confirm('¿Está seguro de eliminar el vehículo solicitado y el registro de pasajeros asociados?');
                    if (!confirmacion) {
                        this.value = prevVehiculoId;
                        return;
                    } else {
                        document.getElementById('motivoSolicitud').style.display = 'none';
                        document.getElementById('agregarPasajeroBtn').style.display = 'none';
                        document.getElementById('eliminarPasajeroBtn').style.display = 'none';
                        document.getElementById('pasajeros').style.display = 'none';
                        prevVehiculoId = '';
                        return;
                    }
                } else if (vehiculoIdSeleccionado !== '' && prevVehiculoId !== '') {
                    // Confirmar cambio si se selecciona un nuevo vehículo
                    let confirmacion = confirm('¿Está seguro de cambiar el vehículo solicitado?, se eliminará el registro de pasajeros asociados.');
                    if (!confirmacion) {
                        this.value = prevVehiculoId;
                        return;
                    } else {
                        document.getElementById('motivoSolicitud').style.display = 'block';
                    }
                }

                // Obtener información del vehículo seleccionado
                let vehiculosJSON = JSON.parse('@json($vehiculos)');
                let vehiculoSeleccionado = vehiculosJSON.find(function(vehiculo) {
                    return vehiculo.VEHICULO_ID == vehiculoIdSeleccionado;
                });

                let pasajeros = document.getElementById('pasajeros');
                pasajeros.innerHTML = '';

                // Configurar el registro de pasajeros según la capacidad del vehículo
                if (vehiculoSeleccionado.tipo_vehiculo.TIPO_VEHICULO_CAPACIDAD > 0) {
                    capacidadMaxima = vehiculoSeleccionado.tipo_vehiculo.TIPO_VEHICULO_CAPACIDAD;
                    agregarFila(contadorFilas);
                    document.getElementById('agregarPasajeroBtn').style.display = 'block';
                    document.getElementById('eliminarPasajeroBtn').style.display = 'block';
                    pasajeros.style.display = 'block';
                } else {
                    document.getElementById('agregarPasajeroBtn').style.display = 'none';
                    document.getElementById('eliminarPasajeroBtn').style.display = 'none';
                    pasajeros.style.display = 'none';
                }

                prevVehiculoId = vehiculoIdSeleccionado;
            });

            // Evento de clic en el botón para agregar pasajero
            document.getElementById('agregarPasajeroBtn').addEventListener('click', function() {
                let todosSelectoresConValor = true;
                for (let i = 1; i <= contadorFilas; i++) {
                    let oficinaValue = document.getElementById('oficina_' + i).value;
                    let dependenciaValue = document.getElementById('dependencia_' + i).value;
                    let pasajeroValue = document.getElementById('pasajero' + i).value;
                   // Verificar campos del conductor (primera fila)
                    if (i === 1) {
                        let horaInicioValue = document.getElementById('SOLICITUD_VEHICULO_HORA_INICIO_CONDUCCION').value;
                        let horaTerminoValue = document.getElementById('SOLICITUD_VEHICULO_HORA_TERMINO_CONDUCCION').value;
                        let motivoValue = document.getElementById('SOLICITUD_VEHICULO_MOTIVO').value; // Cambiado el ID aquí
                        let viaticoValue = document.getElementById('SOLICITUD_VEHICULO_VIATICO').value;

                        if (motivoValue === '') {
                            todosSelectoresConValor = false;
                            alert('Por favor, especifique el motivo de la solicitud.');
                            break;
                        } else if (oficinaValue === '' || dependenciaValue === '' || pasajeroValue === '' || horaInicioValue === '' || horaTerminoValue === '' || viaticoValue === '') {
                            todosSelectoresConValor = false;
                            alert('Por favor, complete todos los campos requeridos para el Conductor.');
                            break;
                        }
                    } else { // Para los demás pasajeros
                        if (oficinaValue === '' || dependenciaValue === '' || pasajeroValue === '') {
                            todosSelectoresConValor = false;
                            alert('Por favor, complete todos los campos requeridos para el Pasajero N°' + (i - 1) + '.');
                            break;
                        }
                    }
                }

                if (contadorFilas === 1 && todosSelectoresConValor) {
                    document.getElementById('agregarPasajeroBtn').innerHTML = '<i class="fas fa-plus"></i> Agregar Pasajero';
                    document.getElementById('eliminarPasajeroBtn').innerHTML = '<i class="fas fa-minus"></i> Eliminar Pasajero';
                }

                if (todosSelectoresConValor && contadorFilas < capacidadMaxima) {
                    contadorFilas++;
                    agregarFila(contadorFilas);
                    let nuevaFila = document.getElementById('fila_' + contadorFilas);
                    nuevaFila.scrollIntoView({ behavior: 'smooth', block: 'center' });
                } else {
                    if (contadorFilas === capacidadMaxima && todosSelectoresConValor) {
                        alert('Se ha alcanzado la capacidad máxima de pasajeros para este vehículo.');
                    }
                }
            });

            // Evento de clic en el botón para eliminar pasajero
            document.getElementById('eliminarPasajeroBtn').addEventListener('click', function() {
                if (contadorFilas > 1) {
                    eliminarPasajero();
                } else {
                    let confirmacion = confirm('¿Está seguro de eliminar la información registrada para el conductor?');
                    if (confirmacion) {
                        eliminarConductor();
                    }
                }

                if (contadorFilas === 1) {
                    document.getElementById('agregarPasajeroBtn').innerHTML = '<i class="fas fa-plus"></i> Agregar Conductor';
                    document.getElementById('eliminarPasajeroBtn').innerHTML = '<i class="fas fa-minus"></i> Eliminar Conductor';
                }
            });

            // Función para eliminar la información del conductor
            function eliminarConductor() {
                document.getElementById('oficina_1').value = '';
                document.getElementById('dependencia_1').value = '';
                document.getElementById('dependencia_1').disabled = true;
                document.getElementById('pasajero1').value = '';
                document.getElementById('pasajero1').disabled = true;
                reiniciarPasajerosSeleccionados();
            }

            // Función para eliminar un pasajero
            function eliminarPasajero() {
                document.getElementById('fila_' + contadorFilas).remove();
                contadorFilas--;

                if (contadorFilas < capacidadMaxima) {
                    document.getElementById('agregarPasajeroBtn').style.display = 'block';
                }
            }

            // Función para agregar una fila de pasajero al registro
            function agregarFila(numeroFila) {
                let pasajeros = document.getElementById('pasajeros');
                let nuevaFila = document.createElement('div');
                nuevaFila.classList.add('pasajero-row', 'border', 'p-3', 'mb-3');
                nuevaFila.id = 'fila_' + numeroFila;

                let contenidoHTML = `
                    <h5>${numeroFila === 1 ? 'Conductor' : 'Pasajero N°' + (numeroFila - 1)}</h5>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="oficina_${numeroFila}">Oficina</label>
                            <select id="oficina_${numeroFila}" class="form-control oficina" data-row="${numeroFila}" required>
                                <option style="text-align: center;" value="">-- Seleccione una opción --</option>
                                @foreach($oficinas as $oficina)
                                    <option value="{{ $oficina->OFICINA_ID }}">{{ $oficina->OFICINA_NOMBRE }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="dependencia_${numeroFila}">Ubicación o Departamento</label>
                            <select id="dependencia_${numeroFila}" class="form-control dependencia" data-row="${numeroFila}" disabled required>
                                <option style="text-align: center;" value="">-- Seleccione una opción --</option>
                                <optgroup label="Ubicaciones">
                                    @foreach($ubicaciones as $ubicacion)
                                        <option value="{{ $ubicacion->UBICACION_ID }}" data-office-id="{{ $ubicacion->OFICINA_ID }}">{{ $ubicacion->UBICACION_NOMBRE }}</option>
                                    @endforeach
                                </optgroup>
                                <optgroup label="Departamentos">
                                    @foreach($departamentos as $departamento)
                                        <option value="{{ $departamento->DEPARTAMENTO_ID }}" data-office-id="{{ $departamento->OFICINA_ID }}">{{ $departamento->DEPARTAMENTO_NOMBRE }}</option>
                                    @endforeach
                                </optgroup>
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="pasajero${numeroFila}">Funcionario</label>
                            <select id="pasajero${numeroFila}" class="form-control pasajero" name="PASAJERO_${numeroFila}" data-row="${numeroFila}" disabled required>
                                <option style="text-align: center;" value="">${numeroFila === 1 ? '-- Seleccione al conductor --' : '-- Seleccione al pasajero N°' + (numeroFila - 1) + ' --'}</option>
                            {{-- <optgroup label="Conductores">
                                        @foreach ($conductores as $conductor)
                                            <option value="{{ $conductor->id }}">{{ $conductor->USUARIO_NOMBRES }} {{ $conductor->USUARIO_APELLIDOS }}</option>
                                        @endforeach
                                </optgroup>--}}
                                <optgroup label="Funcionarios Asociados">
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" data-ubicacion-id="{{ $user->UBICACION_ID }}" data-departamento-id="{{ $user->DEPARTAMENTO_ID }}">{{ $user->USUARIO_NOMBRES }} {{ $user->USUARIO_APELLIDOS }}</option>
                                    @endforeach
                                </optgroup>
                            </select>
                        </div>
                        ${numeroFila === 1 ? `
                            <div class="form-group col-md-4">
                                <label for="SOLICITUD_VEHICULO_VIATICO"> Viático:</label>
                                <select class="form-control" id="SOLICITUD_VEHICULO_VIATICO" name="SOLICITUD_VEHICULO_VIATICO" >
                                    <option style="text-align: center;" value="" required selected>-- Seleccione una opción --</option>
                                    <option value="SI">SI</option>
                                    <option value="NO">NO</option>
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="SOLICITUD_VEHICULO_HORA_INICIO_CONDUCCION">Hora de Inicio de Conducción:</label>
                                <input type="time" class="form-control" id="SOLICITUD_VEHICULO_HORA_INICIO_CONDUCCION" name="SOLICITUD_VEHICULO_HORA_INICIO_CONDUCCION" required placeholder="-- Seleccione la hora de inicio --" style="background-color: #fff; color: #000; text-align: center;">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="SOLICITUD_VEHICULO_HORA_TERMINO_CONDUCCION">Hora de Término de Conducción:</label>
                                <input type="time" class="form-control" id="SOLICITUD_VEHICULO_HORA_TERMINO_CONDUCCION" name="SOLICITUD_VEHICULO_HORA_TERMINO_CONDUCCION" required placeholder="-- Seleccione la hora de término --" style="background-color: #fff; color: #000; text-align: center;">
                            </div>
                        ` : ''}
                    </div>
                `;
                    
                nuevaFila.innerHTML = contenidoHTML;
                pasajeros.appendChild(nuevaFila);

                // Inicializar flatpickr después de que los elementos hayan sido insertados
                flatpickr("#SOLICITUD_VEHICULO_HORA_INICIO_CONDUCCION", {
                    enableTime: true,
                    noCalendar: true,
                    dateFormat: "H:i",
                });

                // Inicializar flatpickr después de que los elementos hayan sido insertados
                flatpickr("#SOLICITUD_VEHICULO_HORA_TERMINO_CONDUCCION", {
                    enableTime: true,
                    noCalendar: true,
                    dateFormat: "H:i",
                });

                var horaTerminoSelector = document.getElementById("SOLICITUD_VEHICULO_HORA_TERMINO_CONDUCCION");

                function configurarHoraTermino(horaInicio) {
                    flatpickr(horaTerminoSelector, {
                        enableTime: true,
                        noCalendar: true,
                        dateFormat: "H:i",
                        minTime: horaInicio,
                    });
                }

                flatpickr("#SOLICITUD_VEHICULO_HORA_INICIO_CONDUCCION", {
                    enableTime: true,
                    noCalendar: true,
                    dateFormat: "H:i",
                    onChange: function(selectedDates, dateStr, instance) {
                        var horaInicio = dateStr;
                        configurarHoraTermino(horaInicio);
                        document.getElementById("SOLICITUD_VEHICULO_HORA_TERMINO_CONDUCCION").disabled = false;
                    }
                });

                document.getElementById("SOLICITUD_VEHICULO_HORA_TERMINO_CONDUCCION").disabled = false;


                if (contadorFilas < capacidadMaxima) {
                    document.getElementById('agregarPasajeroBtn').style.display = 'block';
                } else {
                    document.getElementById('agregarPasajeroBtn').style.display = 'none';
                }

                document.getElementById('oficina_' + numeroFila).addEventListener('change', function() {
                    let selectedOfficeId = this.value;
                    let $dependenciaSelect = document.getElementById('dependencia_' + numeroFila);
                    let $pasajeroSelect = document.getElementById('pasajero' + numeroFila);
                    $dependenciaSelect.value = '';
                    $pasajeroSelect.value = '';
                    $dependenciaSelect.disabled = true;
                    $pasajeroSelect.disabled = true;

                    $dependenciaSelect.querySelectorAll('option').forEach(function(option) {
                        let optionOfficeId = option.getAttribute('data-office-id');
                        if (optionOfficeId == selectedOfficeId && optionOfficeId !== "") {
                            option.style.display = 'block';
                        } else {
                            option.style.display = 'none';
                        }
                    });

                    if (selectedOfficeId === '') {
                        $dependenciaSelect.value = '';
                    } else {
                        $dependenciaSelect.disabled = false;
                    }
                });

                document.getElementById('dependencia_' + numeroFila).addEventListener('change', function() {
                    let selectedDependenciaId = this.value;
                    let $pasajeroSelect = document.getElementById('pasajero' + numeroFila);

                    $pasajeroSelect.querySelectorAll('option').forEach(function(option) {
                        let optionUbicacionId = option.getAttribute('data-ubicacion-id');
                        let optionDepartamentoId = option.getAttribute('data-departamento-id');
                        if ((optionUbicacionId == selectedDependenciaId || optionDepartamentoId == selectedDependenciaId) && selectedDependenciaId !== "") {
                            option.style.display = 'block';
                        } else {
                            option.style.display = 'none';
                        }
                    });

                    $pasajeroSelect.value = '';

                    if (selectedDependenciaId === '') {
                        $pasajeroSelect.disabled = true;
                    } else {
                        $pasajeroSelect.disabled = false;
                    }
                });

                document.getElementById('pasajero' + numeroFila).addEventListener('change', function() {
                    let selectedPasajeroId = this.value;

                    if (pasajerosSeleccionados.has(selectedPasajeroId)) {
                        alert('Esta persona ya ha sido seleccionada como pasajero. Por favor, elija otra persona.');
                        this.value = '';
                        return;
                    }

                    pasajerosSeleccionados.add(selectedPasajeroId);

                    document.querySelectorAll('.pasajero').forEach(function(select) {
                        if (select.id !== 'pasajero' + numeroFila) {
                            select.querySelector('option[value="' + selectedPasajeroId + '"]').disabled = true;
                        }
                    });
                });

            }
        });

    </script>
@stop
