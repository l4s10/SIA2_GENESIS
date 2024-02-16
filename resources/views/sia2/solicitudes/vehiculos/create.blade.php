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
                        <label for="fecha_solicitud"><i class="fa-regular fa-calendar-days"></i> Fecha de Solicitud:</label>
                        <input type="text" class="form-control" id="fecha_solicitud" name="fecha_solicitud" disabled required style="text-align: center;">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="OFICINA_ID"><i class="fa-solid fa-street-view"></i> Dirección Regional:</label>
                        <input type="text" class="form-control" id="OFICINA_ID" name="OFICINA_ID" value= "{{ Auth::user()->oficina->OFICINA_NOMBRE }}" disabled required style="text-align: center;">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="SOLICITUD_ESTADO"><i class="fa-solid fa-right-to-bracket"></i> Estado de la Solicitud:</label>
                        <input type="text" class="form-control" id="SOLICITUD_ESTADO" name="SOLICITUD_ESTADO" value="POR INGRESAR" readonly style="color: #E22C2C; text-align: center;">
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
                        <label for="CARGO" class="form-label"><i class="fa-solid fa-id-card"></i> Cargo:</label>
                        <input type="text" id="CARGO" class="form-control" name="CARGO" value="{{ auth::user()->cargo->CARGO_NOMBRE }}" required readonly>
                        @error('CARGO')
                        <div class="error" style="color: #E22C2C">{{ $message }}</div>
                        @enderror
                </div>     
            </div>
            

            {{-- VEHICULO, FECHAS Y HORAS DE EGRESO E INGRESO AL ESTACIONAMIENTO --}}
            <br>
            <h3>Vehículo</h3>            
            <div class="row mb-4">
                <div class="col-md-4">
                    <label for="VEHICULO_ID" class="form-label"><i class="fa-solid fa-car-side"></i> Vehículo:</label>
                    <select name="VEHICULO_ID" id="VEHICULO_ID" class="form-control" required>
                        <option style="text-align: center;" value="">-- Seleccione un vehículo --</option>
                        @foreach ($vehiculos as $vehiculo)
                            <option value="{{ $vehiculo->VEHICULO_ID }}" data-capacidad="{{ $vehiculo->tipoVehiculo->TIPO_VEHICULO_CAPACIDAD }}" >
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
                    <label for="fechaHoraInicioSolicitada"><i class="fa-solid fa-compass"></i> Salida del estacionamiento:</label>
                    <input type="text" class="form-control @error('SOLICITUD_VEHICULO_FECHA_HORA_INICIO_SOLICITADA') is-invalid @enderror" id="fechaHoraInicioSolicitada" name="SOLICITUD_VEHICULO_FECHA_HORA_INICIO_SOLICITADA" required placeholder="-- Seleccione la fecha y hora --" style="background-color: #fff; color: #000; text-align: center;"> 
                    @error('SOLICITUD_VEHICULO_FECHA_HORA_INICIO_SOLICITADA')
                    <div class="error" style="color: #E22C2C">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-3">
                    <label for="fechaHoraTerminoSolicitada"><i class="fa-solid fa-compass"></i> Reingreso al estacionaiento:</label>
                    <input type="text" class="form-control @error('SOLICITUD_VEHICULO_FECHA_HORA_TERMINO_SOLICITADA') is-invalid @enderror" id="fechaHoraTerminoSolicitada" name="SOLICITUD_VEHICULO_FECHA_HORA_TERMINO_SOLICITADA" required placeholder="-- Seleccione la fecha y hora --" style="background-color: #fff; color: #000; text-align: center;">
                    @error('SOLICITUD_VEHICULO_FECHA_HORA_TERMINO_SOLICITADA')
                        <div class="error" style="color: #E22C2C">{{ $message }}</div>
                    @enderror
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
                    <button id="agregarPasajeroBtn" class="btn" style="background-color: #00B050; color: #fff;" type="button">
                        <i class="fas fa-plus"></i> Agregar Pasajero
                    </button>
                            
                    <button id="eliminarPasajeroBtn" class="btn" style="background-color: #E22C2C; color: #fff;">
                        <i class="fas fa-minus"></i> Eliminar Pasajero
                    </button>
                </div>
            </div>

            
            {{-- DATOS DE SALIDA --}}
            <br>           
            <br>
            <h3>Salida</h3>      
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="SOLICITUD_VEHICULO_REGION"><i class="fa-solid fa-route"></i> Región de Destino:</label>
                        <select name="SOLICITUD_VEHICULO_REGION" id="SOLICITUD_VEHICULO_REGION" class="form-control" required>
                            <option value="">-- Seleccione la región de destino --</option>
                            @foreach ($regiones as $region)
                                <option value="{{ $region->REGION_ID }}" >{{ $region->REGION_NOMBRE }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="SOLICITUD_VEHICULO_COMUNA"><i class="fa-solid fa-route"></i> Comuna de Destino:</label>
                        <select name="SOLICITUD_VEHICULO_COMUNA" id="SOLICITUD_VEHICULO_COMUNA" class="form-control" required>
                            <option value="">-- Seleccione la comuna de destino --</option>
                            <!-- Las opciones de las comunas se cargarán dinámicamente aquí -->
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <div id="jefeQueAutoriza">
                            <label for="SOLICITUD_VEHICULO_JEFE_QUE_AUTORIZA"><i class="fa-solid fa-user-check"></i> Jefe que autoriza:</label>
                            <select name="SOLICITUD_VEHICULO_JEFE_QUE_AUTORIZA" id="SOLICITUD_VEHICULO_JEFE_QUE_AUTORIZA" class="form-control" required>
                                <option value="">-- Seleccione el jefe que autoriza --</option>
                                @foreach ($jefesQueAutorizan as $jefe)
                                    <option value="{{ $jefe->CARGO_ID }}" @if(old('SOLICITUD_VEHICULO_JEFE_QUE_AUTORIZA') == $jefe->CARGO_ID) selected @endif>{{ $jefe->CARGO_NOMBRE }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-4">
                    <!-- CHECKBOX PARA DESPLEGAR CAMPOS DE ORDEN DE TRABAJO -->
                    <div class="form-check">
                        <br>
                        <br>
                        <input class="form-check-input" type="checkbox" id="mostrarOrdenTrabajo">
                        <label class="form-check-label" for="mostrarOrdenTrabajo">Registrar Orden de Trabajo</label>
                    </div>
                </div>
            </div>
            <!-- DIV CONTENEDOR DE LOS CAMPOS DE ORDEN DE TRABAJO -->
            <div id="ordenTrabajoInputs" style="display: none;">
                <br>
                <h3>Orden de Trabajo</h3>
                <div class="row mb-4">
                    <div class="form-group col-md-4">
                        <label for="TRABAJA_NUMERO_ORDEN_TRABAJO"><i class="fa-solid fa-arrow-up-9-1"></i> Número de la Orden de Trabajo:</label>
                        <input type="number" class="form-control" id="TRABAJA_NUMERO_ORDEN_TRABAJO" name="TRABAJA_NUMERO_ORDEN_TRABAJO" placeholder="-- Ingrese el número de orden --" required min="0" max="999999" value="">
                        <div class="invalid-feedback" id="numeroOrdenTrabajoError"></div>
                    </div>
                    <div class="form-group col-md-2"></div>
                    <div class="form-group col-md-3">
                        <label for="TRABAJA_HORA_INICIO_ORDEN_TRABAJO"><i class="fa-solid fa-clock"></i> Inicio orden de trabajo:</label>
                        <input type="time" class="form-control" id="TRABAJA_HORA_INICIO_ORDEN_TRABAJO" name="TRABAJA_HORA_INICIO_ORDEN_TRABAJO" required placeholder="-- Seleccione la hora de inicio --" style="background-color: #fff; color: #000; text-align: center;" value="{{ old('TRABAJA_HORA_INICIO_ORDEN_TRABAJO') }}">
                        <div class="invalid-feedback" id="inicioOrdenTrabajoError"></div>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="TRABAJA_HORA_TERMINO_ORDEN_TRABAJO"><i class="fa-solid fa-clock"></i> Fin orden de trabajo:</label>
                        <input type="time" class="form-control" id="TRABAJA_HORA_TERMINO_ORDEN_TRABAJO" name="TRABAJA_HORA_TERMINO_ORDEN_TRABAJO" required placeholder="-- Seleccione la hora de término--" style="background-color: #fff; color: #000; text-align: center;" value="{{ old('TRABAJA_HORA_TERMINO_ORDEN_TRABAJO') }}">
                        <div class="invalid-feedback" id="terminoOrdenTrabajoError"></div>
                    </div>
                </div>
            </div>

            
            <br><br><br><br>
        <button type="submit" class="btn btn-primary">Crear Solicitud</button>

    </div>
     

@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
    <style>
        .error-message {
            color: #721c24;
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            padding: 8px 15px;
            border-radius: 4px;
            margin-top: 5px;
        }
</style>

<style>
    /* Estilos para el checkbox */
    #mostrarOrdenTrabajo {
        /* Aumenta el tamaño del checkbox */
        transform: scale(1.8);
        /* Cambia el color del borde */
        border-color: #000;
        /* Añade un margen derecho para separarlo del texto */
        margin-right: 10px;
    }

    /* Estilos para el texto junto al checkbox */
    label[for="mostrarOrdenTrabajo"] {
        /* Cambia el color del texto para que sea más visible */
        color: #333;
        /* Añade un tamaño de fuente más grande */
        font-size: 18px;
    }
</style>
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
    // Obtén una referencia al checkbox y al div contenedor de los campos de orden de trabajo
    var checkbox = document.getElementById('mostrarOrdenTrabajo');
    var ordenTrabajoInputs = document.getElementById('ordenTrabajoInputs');

    // Agrega un controlador de eventos al checkbox
    checkbox.addEventListener('change', function() {
        // Verifica si el checkbox está marcado
        if (checkbox.checked) {
            ordenTrabajoInputs.style.display = 'block'; // Muestra los campos de orden de trabajo
            // Obtén una referencia a los campos de orden de trabajo
            var numeroOrden = document.getElementById('TRABAJA_NUMERO_ORDEN_TRABAJO');
            var inicioOrden = document.getElementById('TRABAJA_HORA_INICIO_ORDEN_TRABAJO');
            var terminoOrden = document.getElementById('TRABAJA_HORA_TERMINO_ORDEN_TRABAJO');

            // Establece los campos de orden de trabajo como requeridos
            numeroOrden.required = true;
            inicioOrden.required = true;
            terminoOrden.required = true;

        } else {
            ordenTrabajoInputs.style.display = 'none'; // Oculta los campos de orden de trabajo

            // Obtén una referencia a los campos de orden de trabajo
            var numeroOrden = document.getElementById('TRABAJA_NUMERO_ORDEN_TRABAJO');
            var inicioOrden = document.getElementById('TRABAJA_HORA_INICIO_ORDEN_TRABAJO');
            var terminoOrden = document.getElementById('TRABAJA_HORA_TERMINO_ORDEN_TRABAJO');

            // Establece los campos de orden de trabajo como no requeridos
            numeroOrden.value = null;
            numeroOrden.required = false;
            inicioOrden.value = null;
            inicioOrden.required = false;
            terminoOrden.value = null;
            terminoOrden.required = false;
        }
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        let regionSelect = document.getElementById('SOLICITUD_VEHICULO_REGION');
        let comunaSelect = document.getElementById('SOLICITUD_VEHICULO_COMUNA');
        let comunas = {!! json_encode($comunas) !!}; // Convertimos las comunas de PHP a JavaScript

        // Recuperar la región y la comuna seleccionadas en caso de error de validación
        let selectedRegionId = "{{ old('SOLICITUD_VEHICULO_REGION') }}";
        let selectedComunaId = "{{ old('SOLICITUD_VEHICULO_COMUNA') }}";

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

                    // Establecer la opción seleccionada si coincide con la comuna seleccionada anteriormente
                    if (comuna.COMUNA_ID === selectedComunaId) {
                        option.selected = true;
                    }

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

        // Establecer la región seleccionada si se ha seleccionado previamente
        if (selectedRegionId !== '') {
            regionSelect.value = selectedRegionId;

            // Disparar el evento change manualmente para que se carguen las comunas correspondientes
            var event = new Event('change');
            regionSelect.dispatchEvent(event);
        }

        // Establecer la comuna seleccionada si se ha seleccionado previamente
        if (selectedComunaId !== '') {
            comunaSelect.value = selectedComunaId;
        }
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
            dateFormat: "d-m-Y H:i",
            minDate: fechaMinimaPermitida,
            maxDate: fechaMaximaPermitida,
            defaultDate: fechaMinimaPermitida,
            locale: "es", // Establecer el idioma en español
            onChange: function(selectedDates, dateStr, instance) {
                if (selectedDates[0] < fechaMinimaPermitida) {
                    alert("La fecha y hora seleccionada es menor a la hora mínima permitida");
                    inputfechaHoraInicioSolicitada.value = "";
                } else {
                    // Habilitar el input de término una vez que se ha seleccionado la hora de inicio
                    inputfechaHoraTerminoSolicitada.disabled = false;
                    // Actualizar minDate para el input de término
                    let fechaHoraInicioSeleccionada = selectedDates[0];
                    let horaInicioSeleccionada = fechaHoraInicioSeleccionada.getHours();
                    let minutoInicioSeleccionado = fechaHoraInicioSeleccionada.getMinutes();

                    let fechaMinimaTermino = new Date(fechaHoraInicioSeleccionada);
                    fechaMinimaTermino.setHours(horaInicioSeleccionada);
                    fechaMinimaTermino.setMinutes(minutoInicioSeleccionado);

                    inputfechaHoraTerminoSolicitada._flatpickr.set("minDate", fechaMinimaTermino);
                }
            }
        });

        // **Configuración del selector de fecha y hora de término**
        flatpickr(inputfechaHoraTerminoSolicitada, {
            enableTime: true,
            dateFormat: "d-m-Y H:i",
            minDate: fechaMinimaPermitida, // Se establece inicialmente, luego se actualizará
            maxDate: fechaMaximaPermitida,
            locale: "es", // Establecer el idioma en español
            onClose: function(selectedDates, dateStr, instance) {
                let fechaHoraTerminoSeleccionada = selectedDates[0];
                if (fechaHoraTerminoSeleccionada < inputfechaHoraInicioSolicitada._flatpickr.latestSelectedDateObj) {
                    alert("La fecha y hora seleccionada es anterior a la hora de inicio.");
                    inputfechaHoraTerminoSolicitada._flatpickr.setDate(null); // Limpiar la selección
                }
            }
        });

        // Deshabilitar el input de término al cargar la página y establecer su valor como vacío
        inputfechaHoraTerminoSolicitada.disabled = true;
        inputfechaHoraInicioSolicitada.value = null;
        inputfechaHoraTerminoSolicitada.value = null;
    });
</script>



<script>
    document.addEventListener('DOMContentLoaded', function() {
        let inputHoraInicioOrdenTrabajo = document.getElementById('TRABAJA_HORA_INICIO_ORDEN_TRABAJO');
        let inputHoraTerminoOrdenTrabajo = document.getElementById('TRABAJA_HORA_TERMINO_ORDEN_TRABAJO');

        // Configurar Flatpickr para el campo de hora de inicio
        let flatpickrInicioOrdenTrabajo = flatpickr(inputHoraInicioOrdenTrabajo, {
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

        // Configurar Flatpickr para el campo de hora de término (inicialmente deshabilitado)
        let flatpickrTerminoOrdenTrabajo = flatpickr(inputHoraTerminoOrdenTrabajo, {
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i",
            placeholder: "-- Seleccione la hora --", // Establecer el marcador de posición
        });

        // Deshabilitar el input de hora de término inicialmente
        inputHoraTerminoOrdenTrabajo.disabled = true;

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

            // Satisfacer selección opcional de los campos de orden de trabajo, hasta que el usuario decida lo contrario utilizando chekbox
            document.getElementById('TRABAJA_NUMERO_ORDEN_TRABAJO').required = false;
            document.getElementById('TRABAJA_HORA_INICIO_ORDEN_TRABAJO').required = false;
            document.getElementById('TRABAJA_HORA_TERMINO_ORDEN_TRABAJO').required = false;

            let regionSelect = document.getElementById('SOLICITUD_VEHICULO_REGION');
            let comunaSelect = document.getElementById('SOLICITUD_VEHICULO_COMUNA');
            let comunas = {!! json_encode($comunas) !!};


            // Función para reiniciar el conjunto de pasajeros seleccionados
            function reiniciarPasajerosSeleccionados() {
                pasajerosSeleccionados = new Set();
            }


            

            // Evento de cambio en el tipo de vehículo
            document.getElementById('VEHICULO_ID').addEventListener('change', function() {
                let vehiculoIdSeleccionado = this.value;
                document.getElementById('motivoSolicitud').style.display = 'block';


                contadorFilas = 1;
                capacidadMaxima = 0;
                document.getElementById('agregarPasajeroBtn').innerHTML = '<i class="fas fa-plus"></i> Agregar Conductor';
                document.getElementById('eliminarPasajeroBtn').innerHTML = '<i class="fas fa-minus"></i> Eliminar Conductor';
                reiniciarPasajerosSeleccionados();

                if (vehiculoIdSeleccionado === '') {
                    // Confirmar eliminación si se selecciona el vehículo ' '
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
                    let confirmacion = confirm('¿Está seguro de cambiar el vehículo solicitado?, se eliminará el registro de conductor y pasajeros asociados.');
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


            // Función para verificar si el pasajero o conductor está seleccionado en otra fila
            function pasajeroSeleccionadoEnOtraFila(selectedPasajeroId, filaActual) {
                let pasajeroRepetido = false;
                for (let i = 1; i <= contadorFilas; i++) {
                    if (i !== filaActual) {
                        let pasajeroValue = document.getElementById('pasajero_' + i).value;
                        if (pasajeroValue === selectedPasajeroId) {
                            if (i === 1 ) {
                                // Si es el primer pasajero y es conductor, hay un mensaje especial
                                alert('Este funcionario ya figura como "Conductor". Por favor, seleccione otra persona.');
                            } else {
                                // Si no, mostrar mensaje genérico para pasajero
                                alert('Este funcionario ya figura como "Pasajero N°' + (i - 1) + '". Por favor, seleccione otra persona.');
                            }
                            pasajeroRepetido = true;
                            break;
                        }
                    }
                }
                return pasajeroRepetido;
            }

            // Evento de clic en el botón para agregar pasajero
            document.getElementById('agregarPasajeroBtn').addEventListener('click', function() {
                let todosSelectoresConValor = true;
                for (let i = 1; i <= contadorFilas; i++) {
                    let oficinaValue = document.getElementById('oficina_' + i).value;
                    let dependenciaValue = document.getElementById('dependencia_' + i).value;
                    let pasajeroValue = document.getElementById('pasajero_' + i).value;
                    let motivoValue = document.getElementById('SOLICITUD_VEHICULO_MOTIVO').value;

                    if (i === 1) {
                        let horaInicioValue = document.getElementById('SOLICITUD_VEHICULO_HORA_INICIO_CONDUCCION').value;
                        let horaTerminoValue = document.getElementById('SOLICITUD_VEHICULO_HORA_TERMINO_CONDUCCION').value;
                        let viaticoValue = document.getElementById('SOLICITUD_VEHICULO_VIATICO').value;

                        if (motivoValue === '') {
                            todosSelectoresConValor = false;
                            alert('Por favor, especifique la labor a realizar.');
                            break;
                        } else if (oficinaValue === '' || dependenciaValue === '' || pasajeroValue === '' || horaInicioValue === '' || horaTerminoValue === '' || viaticoValue === ''  ) {
                            todosSelectoresConValor = false;
                            alert('Por favor, complete todos los campos requeridos para el Conductor.');
                            break;
                        } else if ( horaInicioValue>horaTerminoValue ) {
                            todosSelectoresConValor = false;
                            alert('La hora de inicio de conducción debe anterior a la hora de término de conducción, por favor reingrese los horarios de conducción.');
                            break;
                        }
                    } else { // Para los demás pasajeros
                        if (oficinaValue === '' || dependenciaValue === '' || pasajeroValue === '') {
                            todosSelectoresConValor = false;
                            alert('Por favor, complete todos los campos requeridos para el Pasajero N°' + (i - 1) + '.');
                            break;
                        }
                    }

                    // Verificar si el pasajero o conductor fue seleccionado en otra fila
                    if (pasajeroSeleccionadoEnOtraFila(pasajeroValue, i)) {
                        todosSelectoresConValor = false;
                        break;
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
                document.getElementById('pasajero_1').value = '';
                document.getElementById('pasajero_1').disabled = true;
                document.getElementById('SOLICITUD_VEHICULO_VIATICO').value = '';
                document.getElementById('SOLICITUD_VEHICULO_HORA_INICIO_CONDUCCION').value = '';
                document.getElementById('SOLICITUD_VEHICULO_HORA_TERMINO_CONDUCCION').value = '';
                
                
                reiniciarPasajerosSeleccionados();
                alert('Conductor eliminado del registro, por favor ingrese uno nuevamente.');
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
                            <label for="oficina_${numeroFila}"><i class="fa-solid fa-street-view"></i> Oficina:</label>
                            <select id="oficina_${numeroFila}" class="form-control oficina" data-row="${numeroFila}" required>
                                <option style="text-align: center;" value="">-- Seleccione una opción --</option>
                                @foreach($oficinas as $oficina)
                                    <option value="{{ $oficina->OFICINA_ID }}">{{ $oficina->OFICINA_NOMBRE }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="dependencia_${numeroFila}"><i class="fa-solid fa-building-user"></i> Ubicación o Departamento:</label>
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
                            <label for="pasajero_${numeroFila}"><i class="fa-solid fa-person-circle-check"></i> Funcionario:</label>
                            <select id="pasajero_${numeroFila}" class="form-control pasajero" name="PASAJERO_${numeroFila}" data-row="${numeroFila}" disabled required>
                                <option style="text-align: center;" value="">${numeroFila === 1 ? '-- Seleccione al conductor --' : '-- Seleccione al pasajero N°' + (numeroFila - 1) + ' --'}</option>
                                <optgroup label="Funcionarios Asociados">
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" data-ubicacion-id="{{ $user->UBICACION_ID }}" data-departamento-id="{{ $user->DEPARTAMENTO_ID }}">{{ $user->USUARIO_NOMBRES }} {{ $user->USUARIO_APELLIDOS }}</option>
                                    @endforeach
                                </optgroup>
                            </select>
                        </div>
                        ${numeroFila === 1 ? `
                            <div class="form-group col-md-4">
                                <label for="SOLICITUD_VEHICULO_VIATICO"><i class="fa-solid fa-money-bill-wheat"></i> Viático:</label>
                                <select class="form-control" id="SOLICITUD_VEHICULO_VIATICO" name="SOLICITUD_VEHICULO_VIATICO" >
                                    <option style="text-align: center;" value="" required selected>-- Seleccione una opción --</option>
                                    <option value="SI">SI</option>
                                    <option value="NO">NO</option>
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="SOLICITUD_VEHICULO_HORA_INICIO_CONDUCCION"><i class="fa-solid fa-clock"></i> Hora de Inicio de Conducción:</label>
                                <input type="time" class="form-control" id="SOLICITUD_VEHICULO_HORA_INICIO_CONDUCCION" name="SOLICITUD_VEHICULO_HORA_INICIO_CONDUCCION" required placeholder="-- Seleccione la hora de inicio --" style="background-color: #fff; color: #000; text-align: center;">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="SOLICITUD_VEHICULO_HORA_TERMINO_CONDUCCION"><i class="fa-solid fa-clock"></i> Hora de Término de Conducción:</label>
                                <input type="time" class="form-control" id="SOLICITUD_VEHICULO_HORA_TERMINO_CONDUCCION" name="SOLICITUD_VEHICULO_HORA_TERMINO_CONDUCCION" required placeholder="-- Seleccione la hora de término --" style="background-color: #fff; color: #000; text-align: center;">
                            </div>
                        ` : ''}
                    </div>
                `;
                    
                nuevaFila.innerHTML = contenidoHTML;
                pasajeros.appendChild(nuevaFila);

               // Configurar Flatpickr para el campo de hora de inicio de conducción
                let horaInicioConduccion = document.getElementById(`SOLICITUD_VEHICULO_HORA_INICIO_CONDUCCION`);
                let horaTerminoConduccion = document.getElementById(`SOLICITUD_VEHICULO_HORA_TERMINO_CONDUCCION`);

                flatpickr(horaInicioConduccion, {
                    enableTime: true,
                    noCalendar: true,
                    dateFormat: "H:i",
                    onChange: function(selectedDates, dateStr, instance) {
                        horaTerminoConduccion._flatpickr.clear(); // Limpiar la selección anterior
                        horaTerminoConduccion._flatpickr.set("minTime", dateStr); // Establecer la hora mínima
                        horaTerminoConduccion.disabled = false; // Habilitar el input de hora de término
                    }
                });

                flatpickr(horaTerminoConduccion, {
                    enableTime: true,
                    noCalendar: true,
                    dateFormat: "H:i",
                    placeholder: "-- Seleccione la hora --", // Establecer el marcador de posición
                });

                // Deshabilitar el input de hora de término inicialmente
                horaTerminoConduccion.disabled = true;

                // Limpiar los valores iniciales
                horaInicioConduccion.value = "";
                horaTerminoConduccion.value = "";

                if (contadorFilas < capacidadMaxima) {
                    document.getElementById('agregarPasajeroBtn').style.display = 'block';
                } else {
                    document.getElementById('agregarPasajeroBtn').style.display = 'none';
                }
               

              document.getElementById('oficina_' + numeroFila).addEventListener('change', function() {
                    let selectedOfficeId = this.value;
                    let $dependenciaSelect = document.getElementById('dependencia_' + numeroFila);
                    let $pasajeroSelect = document.getElementById('pasajero_' + numeroFila);
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
                    let $pasajeroSelect = document.getElementById('pasajero_' + numeroFila);

                    // Limpiar el selector de pasajeros antes de agregar nuevos elementos
                    $pasajeroSelect.innerHTML = '';
                    $pasajeroSelect.innerHTML = `<option style="text-align: center;" value="">${numeroFila === 1 ? '-- Seleccione al conductor --' : '-- Seleccione al pasajero N°' + (numeroFila - 1) + ' --'}</option>`;


                    // Verificar si numeroFila es igual a 1 para mostrar los conductores
                    if (numeroFila === 1) {
                        let conductores = JSON.parse('@json($conductores)');
                        let optgroupConductores = document.createElement('optgroup');
                        optgroupConductores.label = 'Conductores Asociados';

                        conductores.forEach(function(conductor) {
                            if (conductor.UBICACION_ID == selectedDependenciaId || conductor.DEPARTAMENTO_ID == selectedDependenciaId) {
                                let option = document.createElement('option');
                                option.value = conductor.id;
                                option.textContent = conductor.USUARIO_NOMBRES + ' ' + conductor.USUARIO_APELLIDOS;
                                optgroupConductores.appendChild(option);
                            }
                        });

                        $pasajeroSelect.appendChild(optgroupConductores);
                    } else {
                        let pasajeros = JSON.parse('@json($users)');
                        let optgroupPasajeros = document.createElement('optgroup');
                        optgroupPasajeros.label = 'Funcionarios Asociados';

                        pasajeros.forEach(function(pasajero) {
                            if (pasajero.UBICACION_ID == selectedDependenciaId || pasajero.DEPARTAMENTO_ID == selectedDependenciaId) {
                                let option = document.createElement('option');
                                option.value = pasajero.id;
                                option.textContent = pasajero.USUARIO_NOMBRES + ' ' + pasajero.USUARIO_APELLIDOS;
                                optgroupPasajeros.appendChild(option);
                            }
                        });

                        $pasajeroSelect.appendChild(optgroupPasajeros);
                    }

                    if (selectedDependenciaId === '') {
                        $pasajeroSelect.disabled = true;
                    } else {
                        $pasajeroSelect.disabled = false;
                    }
                    // Resto del código para manejar los pasajeros en las filas diferentes de 1...
                });



                document.getElementById('pasajero_' + numeroFila).addEventListener('change', function() {
                    let selectedPasajeroId = this.value;

                    // Permitir la selección del pasajero nuevamente si lo cambias por otro
                    if (selectedPasajeroId !== '') {
                        pasajerosSeleccionados.delete(selectedPasajeroId);
                    }

                    // Verificar si el pasajero ya fue seleccionado en otra fila
                    if (pasajeroSeleccionadoEnOtraFila(selectedPasajeroId, numeroFila)) {
                        //alert('Este funcionario ya fue seleccionado como pasajero en otra fila. Por favor, elija otra persona.');
                        this.value = '';
                        return;
                    }

                    pasajerosSeleccionados.add(selectedPasajeroId);

                    // Deshabilitar la opción del pasajero seleccionado en otras filas
                    document.querySelectorAll('.pasajero_').forEach(function(select) {
                        if (select.id !== 'pasajero_' + numeroFila) {
                            select.querySelector('option[value="' + selectedPasajeroId + '"]').disabled = true;
                        }
                    });
                });

            }
        });

    </script>
@stop
