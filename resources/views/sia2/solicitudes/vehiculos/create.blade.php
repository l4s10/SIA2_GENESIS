@extends('adminlte::page')

@section('title', 'Crear Solicitud Vehicular')

@section('content_header')
    <h1>Solicitud Vehicular</h1>
    <br>
    <br>

@stop

@section('content')
        <form action="{{ route('solicitudesvehiculos.store') }}" method="POST">
            @csrf
            <br>
            <br>
            <h3>Titular</h3>
            {{-- *CAMPOS FUNCIONARIO* --}}
            <div class="row">
                <div class="col-md-6">
                    <input type="text" name="SOLICITANTE_ID" value="{{ auth()->user()->id }}" hidden>
                    <div class="mb-3">
                        <label for="USUARIO_NOMBRES" class="form-label"><i class="fa-solid fa-user"></i> Nombres:</label>
                        <input type="text" id="USUARIO_NOMBRES" name="USUARIO_NOMBRES" class="form-control{{ $errors->has('USUARIO_NOMBRES') ? ' is-invalid' : '' }}" value="{{ auth()->user()->USUARIO_NOMBRES }} {{ auth()->user()->USUARIO_APELLIDOS }}" readonly required>
                        @if ($errors->has('USUARIO_NOMBRES'))
                        <div class="invalid-feedback">
                            {{ $errors->first('USUARIO_NOMBRES') }}
                        </div>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label for="USUARIO_RUT" class="form-label"><i class="fa-solid fa-id-card"></i> RUT:</label>
                        <input type="text" id="USUARIO_RUT" name="USUARIO_RUT" class="form-control{{ $errors->has('USUARIO_RUT') ? ' is-invalid' : '' }}" value="{{ auth()->user()->USUARIO_RUT }}" readonly required>
                        @if ($errors->has('USUARIO_RUT'))
                        <div class="invalid-feedback">
                            {{ $errors->first('USUARIO_RUT') }}
                        </div>
                        @endif
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="DEPARTAMENTO_O_UBICACION" class="form-label"><i class="fa-solid fa-building-user"></i> Ubicación o Departamento:</label>
                        <input type="text" id="DEPARTAMENTO_O_UBICACION" name="DEPARTAMENTO_O_UBICACION" class="form-control{{ $errors->has('DEPARTAMENTO_O_UBICACION') ? ' is-invalid' : '' }}" value="{{ isset(auth()->user()->departamento) ? auth()->user()->departamento->DEPARTAMENTO_NOMBRE : auth()->user()->ubicacion->UBICACION_NOMBRE }}"  readonly required>
                        @if ($errors->has('DEPARTAMENTO_O_UBICACION'))
                            <div class="invalid-feedback">
                                {{ $errors->first('DEPARTAMENTO_O_UBICACION') }}
                            </div>
                        @endif
                    </div>


                    <div class="mb-3">
                        <label for="EMAIL" class="form-label"><i class="fa-solid fa-envelope"></i> Email:</label>
                        <input type="email" id="EMAIL" name="EMAIL" class="form-control{{ $errors->has('EMAIL') ? ' is-invalid' : '' }}" value="{{ auth()->user()->email }}" readonly required>
                        @if ($errors->has('EMAIL'))
                        <div class="invalid-feedback">
                            {{ $errors->first('EMAIL') }}
                        </div>
                        @endif
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="OFICINA" class="form-label"><i class="fa-solid fa-map-location-dot"></i> Dirección Regional </label>
                        <input type="text" id="OFICINA" class="form-control" name="OFICINA" value="{{ auth::user()->oficina->OFICINA_NOMBRE }}" required readonly>
                        @error('ID_REGION')
                            <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <!-- Aquí está tu primer div -->
                    <div class="mb-3">
                        <label for="SOLICITUD_ESTADO"><i class="fa-solid fa-file-circle-check"></i> Estado de la Solicitud</label>
                        <input type="text" class="form-control" id="SOLICITUD_ESTADO" name="SOLICITUD_ESTADO" value="🟠POR INGRESAR" readonly>
                    </div>
                    <!-- Aquí se añade la leyenda -->
                    <div class="mb-3">
                        <small>La solicitud todavía <strong>no</strong> ha sido ingresada</small>
                    </div>
                </div>

            </div>

            <h3>Vehículo</h3>
            <div class="form-group">
                <label for="TIPO_VEHICULO_ID" class="form-label"><i class="fa-solid fa-car-side"></i> Tipo de Vehículo</label>
                <select name="TIPO_VEHICULO_ID" id="TIPO_VEHICULO_ID" class="form-control" required>
                    <option value="">-- Seleccione un tipo de vehiculo --</option>
                    @foreach ($tiposVehiculos as $tipoVehiculo)
                        <option value="{{ $tipoVehiculo->TIPO_VEHICULO_ID }}" data-capacidad="{{ $tipoVehiculo->TIPO_VEHICULO_CAPACIDAD }}">{{ $tipoVehiculo->TIPO_VEHICULO_NOMBRE }}</option>
                    @endforeach
                </select>
                @error('TIPO_VEHICULO_ID')
                    <div class="alert alert-danger mt-2">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group" id=SOLICITUD_VEHICULO_MOTIVO style="display: none;>
                <label for="SOLICITUD_VEHICULO_MOTIVO"><i class="fa-solid fa-file-pen"></i> Labor a realizar:</label>
                <textarea id="SOLICITUD_VEHICULO_MOTIVO" name="SOLICITUD_VEHICULO_MOTIVO" rows="5" class="form-control" placeholder="Indique la labor a realizar (MÁX 255 CARACTERES)"  maxlength="255" required></textarea>
            </div>



            <!-- Div del registro de pasajeros -->
            <div id="pasajeros" style="display: none;">
                <!-- Aquí va el contenido del registro de pasajeros -->
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

            <br>
            <br>
            <h3>Datos Temporales</h3>
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label for="SOLICITUD_VEHICULO_FECHA_HORA_INICIO_SOLICITADA"><i class="fa-solid fa-calendar-days"></i> Fecha y Hora de Inicio Solicitada</label>
                        <input type="datetime-local" class="form-control" id="SOLICITUD_VEHICULO_FECHA_HORA_INICIO_SOLICITADA" name="SOLICITUD_VEHICULO_FECHA_HORA_INICIO_SOLICITADA" required>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label for="SOLICITUD_VEHICULO_FECHA_HORA_TERMINO_SOLICITADA"><i class="fa-solid fa-calendar-xmark"></i> Fecha y Hora de Término Solicitada</label>
                        <input type="datetime-local" class="form-control" id="SOLICITUD_VEHICULO_FECHA_HORA_TERMINO_SOLICITADA" name="SOLICITUD_VEHICULO_FECHA_HORA_TERMINO_SOLICITADA" required>
                    </div>
                </div>
            </div>

            <br>
            <h3>Datos Geográficos</h3>
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label for="SOLICITUD_VEHICULO_REGION_ORIGEN"><i class="fa-solid fa-map-location-dot"></i> Región de Origen</label>
                        <select name="SOLICITUD_VEHICULO_REGION_ORIGEN" id="SOLICITUD_VEHICULO_REGION_ORIGEN" class="form-control" required>
                            <option value="">-- Seleccione la región de origen --</option>
                            @foreach ($regiones as $region)
                                <option value="{{ $region->REGION_ID }}">{{ $region->REGION_NOMBRE }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label for="SOLICITUD_VEHICULO_COMUNA_ORIGEN"><i class="fa-solid fa-map-location-dot"></i> Comuna de Origen</label>
                        <select name="SOLICITUD_VEHICULO_COMUNA_ORIGEN" id="SOLICITUD_VEHICULO_COMUNA_ORIGEN" class="form-control" required>
                            <option value="">-- Seleccione la comuna de origen --</option>
                            <!-- Aquí se cargarán las comunas dinámicamente según la región seleccionada -->
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label for="SOLICITUD_VEHICULO_REGION_DESTINO"><i class="fa-solid fa-map-location"></i> Región de Destino</label>
                        <select name="SOLICITUD_VEHICULO_REGION_DESTINO" id="SOLICITUD_VEHICULO_REGION_DESTINO" class="form-control" required>
                            <option value="">-- Seleccione la región de destino --</option>
                            @foreach ($regiones as $region)
                                <option value="{{ $region->REGION_ID }}">{{ $region->REGION_NOMBRE }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label for="SOLICITUD_VEHICULO_COMUNA_DESTINO"><i class="fa-solid fa-map-location"></i> Comuna de Destino</label>
                        <select name="SOLICITUD_VEHICULO_COMUNA_DESTINO" id="SOLICITUD_VEHICULO_COMUNA_DESTINO" class="form-control" required>
                            <option value="">-- Seleccione la comuna de destino --</option>
                            <!-- Aquí se cargarán las comunas dinámicamente según la región seleccionada -->
                        </select>
                    </div>
                </div>
            </div>
            <br>
        <button type="submit" class="btn agregar"><i class="fa-solid fa-plus"></i> Crear Solicitud</button>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
    <style>
        .agregar{
            background-color: #e6500a;
            color: #fff;
        }
    </style>
@stop

@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>

        // Obtener valores de inputs para llamar a la función que carga comunas filtradas por región
        document.addEventListener('DOMContentLoaded', function() {
            let selectRegionOrigen = document.getElementById('SOLICITUD_VEHICULO_REGION_ORIGEN');
            let selectComunaOrigen = document.getElementById('SOLICITUD_VEHICULO_COMUNA_ORIGEN');
            let selectRegionDestino = document.getElementById('SOLICITUD_VEHICULO_REGION_DESTINO');
            let selectComunaDestino = document.getElementById('SOLICITUD_VEHICULO_COMUNA_DESTINO');
            let comunasPorRegion = <?php echo json_encode($comunas); ?>; // Comunas desde el backend

            cargarComunas(selectRegionOrigen, selectComunaOrigen, comunasPorRegion);
            cargarComunas(selectRegionDestino, selectComunaDestino, comunasPorRegion);
        });

        // Función para cargar dinámicamente las comunas según la región seleccionada
        function cargarComunas(selectRegion, selectComuna, comunasPorRegion) {
            selectRegion.addEventListener('change', function() {
                let regionId = selectRegion.value;
                selectComuna.innerHTML = ''; // Limpiar las opciones del select de comunas

                // Crear opción por defecto
                let defaultOption = document.createElement('option');
                defaultOption.value = '';
                defaultOption.textContent = '-- Seleccione la comuna --';
                selectComuna.appendChild(defaultOption);

                // Filtrar las comunas por la región seleccionada
                if (regionId) {
                    let comunasFiltradas = comunasPorRegion.filter(function(comuna) {
                        return comuna.REGION_ID == regionId;
                    });

                    // Crear opciones para las comunas filtradas
                    comunasFiltradas.forEach(function(comuna) {
                        let option = document.createElement('option');
                        option.value = comuna.COMUNA_ID;
                        option.textContent = comuna.COMUNA_NOMBRE;
                        selectComuna.appendChild(option);
                    });
                }
            });
        }


    </script>





    <script>
         $(function () {
            // Configuración de Flatpickr para la selección de fechas y horas en la solicitud de vehículos

            // Validaciones: solo permitir solicitudes para el año actual en horario laboral,
            // a menos que la solicitud sea realizada en diciembre,
            // en cuyo caso se permitirá seleccionar un período de uso hasta enero y febrero del año siguiente.

            // Crear objeto que almacena la fecha y hora actual
            let today = new Date();
            // Crear variable que recibe el límite superior del calendario.
            let maxDate;
            if (today.getMonth() === 11) {
                // Si estamos en diciembre, permitir hasta febrero del año siguiente
                maxDate = new Date(today.getFullYear() + 1, 1, 28); // maxDate = año actual + 1, mes 1 (contando de 0), día 28.
            } else {
                // En cualquier otro mes, permitir hasta el último día de este año
                maxDate = new Date(today.getFullYear(), 11, 31); // maxDate = Último día del año actual
            }
            // Configuración para la fecha de inicio
            $('#SOLICITUD_VEHICULO_FECHA_HORA_INICIO_SOLICITADA').flatpickr({
                enableTime: true,
                dateFormat: "Y-m-d H:i:s",
                altFormat: "d-m-Y H:i",
                altInput: true,
                locale: "es",
                minDate: "today", // No permitir fechas anteriores al día actual
                maxDate: new Date(new Date().getFullYear(), 11, 31), // Permitir fechas hasta fin de año
                defaultDate: "today", // Establecer la fecha por defecto como la fecha actual
                minTime: "07:00", // Hora mínima permitida
                maxTime: "19:00", // Hora máxima permitida
                onChange: function(selectedDates, dateStr, instance) {
                    // Al cambiar la fecha de inicio, actualizamos la fecha mínima para la fecha de término
                    let selectedDate = selectedDates[0];
                    $('#SOLICITUD_VEHICULO_FECHA_HORA_TERMINO_SOLICITADA').flatpickr({
                        enableTime: true,
                        dateFormat: "Y-m-d H:i:s",
                        altFormat: "d-m-Y H:i",
                        altInput: true,
                        locale: "es",
                        minDate: selectedDate, // La fecha mínima para la fecha de término es la fecha seleccionada de inicio
                        maxDate: new Date(new Date().getFullYear() + 1, 1, 28), // Permitir fechas hasta febrero del siguiente año
                        defaultDate: selectedDate, // Establecer la fecha por defecto como la fecha de inicio seleccionada
                        minTime: "07:00", // Hora mínima permitida
                        maxTime: "19:00", // Hora máxima permitida
                        onReady: function(selectedDates, dateStr, instance) {
                            $('#clearButton').on('click', function() {
                                instance.clear();
                            });
                        }
                    });
                }
            });
        });

    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Variables de control
            let prevTipoVehiculoId = ''; // Almacena el ID del tipo de vehículo previamente seleccionado
            let contadorFilas = 1; // Contador de filas de pasajeros
            let capacidadMaxima = 0; // Capacidad máxima de pasajeros para el vehículo seleccionado
            let pasajerosSeleccionados = new Set(); // Conjunto que almacena los IDs de pasajeros seleccionados

            // Función para reiniciar el conjunto de pasajeros seleccionados
            function reiniciarPasajerosSeleccionados() {
                pasajerosSeleccionados = new Set();
            }

            // Ocultar botones y registro de pasajeros al inicio
            document.getElementById('agregarPasajeroBtn').style.display = 'none';
            document.getElementById('eliminarPasajeroBtn').style.display = 'none';

            // Evento de cambio en el tipo de vehículo
            document.getElementById('TIPO_VEHICULO_ID').addEventListener('change', function() {
                let tipoVehiculoIdSeleccionado = this.value;
                document.getElementById('SOLICITUD_VEHICULO_MOTIVO').style.display = 'block';
                //controlarVisibilidadRegistroPasajeros(tipoVehiculoIdSeleccionado);
                contadorFilas = 1;
                capacidadMaxima = 0;
                document.getElementById('agregarPasajeroBtn').innerHTML = '<i class="fas fa-plus"></i> Agregar Conductor';
                document.getElementById('eliminarPasajeroBtn').innerHTML = '<i class="fas fa-minus"></i> Eliminar Conductor';
                reiniciarPasajerosSeleccionados();

                if (tipoVehiculoIdSeleccionado === '') {
                    // Confirmar eliminación si se deselecciona el tipo de vehículo
                    let confirmacion = confirm('¿Está seguro de eliminar el tipo de vehículo solicitado y el registro de pasajeros asociados?');
                    if (!confirmacion) {
                        this.value = prevTipoVehiculoId;
                        return;
                    } else {
                        document.getElementById('SOLICITUD_VEHICULO_MOTIVO').style.display = 'none';
                        document.getElementById('agregarPasajeroBtn').style.display = 'none';
                        document.getElementById('eliminarPasajeroBtn').style.display = 'none';
                        document.getElementById('pasajeros').style.display = 'none';
                        prevTipoVehiculoId = '';
                        return;
                    }
                } else if (tipoVehiculoIdSeleccionado !== '' && prevTipoVehiculoId !== '') {
                    // Confirmar cambio si se selecciona un nuevo tipo de vehículo
                    let confirmacion = confirm('¿Está seguro de cambiar el tipo de vehículo solicitado?, se eliminará el registro de pasajeros asociados.');
                    if (!confirmacion) {
                        this.value = prevTipoVehiculoId;
                        return;
                    } else {
                        document.getElementById('SOLICITUD_VEHICULO_MOTIVO').style.display = 'block';
                    }
                }

                // Obtener información del tipo de vehículo seleccionado
                let tiposVehiculosJSON = JSON.parse('@json($tiposVehiculos)');
                let tipoVehiculoSeleccionado = tiposVehiculosJSON.find(function(tipoVehiculo) {
                    return tipoVehiculo.TIPO_VEHICULO_ID == tipoVehiculoIdSeleccionado;
                });

                let pasajeros = document.getElementById('pasajeros');
                pasajeros.innerHTML = '';

                // Configurar el registro de pasajeros según la capacidad del vehículo
                if (tipoVehiculoSeleccionado.TIPO_VEHICULO_CAPACIDAD > 0) {
                    capacidadMaxima = tipoVehiculoSeleccionado.TIPO_VEHICULO_CAPACIDAD;
                    agregarFila(contadorFilas);
                    document.getElementById('agregarPasajeroBtn').style.display = 'block';
                    document.getElementById('eliminarPasajeroBtn').style.display = 'block';
                    pasajeros.style.display = 'block';
                } else {
                    document.getElementById('agregarPasajeroBtn').style.display = 'none';
                    document.getElementById('eliminarPasajeroBtn').style.display = 'none';
                    pasajeros.style.display = 'none';
                }

                prevTipoVehiculoId = tipoVehiculoIdSeleccionado;
            });

            // Evento de clic en el botón para agregar pasajero
            document.getElementById('agregarPasajeroBtn').addEventListener('click', function() {
                let todosSelectoresConValor = true;
                for (let i = 1; i <= contadorFilas; i++) {
                    let oficinaValue = document.getElementById('oficina_' + i).value;
                    let dependenciaValue = document.getElementById('dependencia_' + i).value;
                    let pasajeroValue = document.getElementById('pasajero' + i).value;

                    if (oficinaValue === '' || dependenciaValue === '' || pasajeroValue === '') {
                        todosSelectoresConValor = false;
                        if (i === 1) {
                            alert('Antes de agregar un pasajero, por favor complete los datos para el Conductor.');
                        } else {
                            alert('Antes de agregar otro pasajero, por favor complete los datos requeridos para el "Pasajero  N°' + (i - 1) + '".');
                        }
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
                nuevaFila.innerHTML = `
                    <h5>${numeroFila === 1 ? 'Conductor' : 'Pasajero N°' + (numeroFila - 1)}</h5>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="oficina_${numeroFila}">Oficina</label>
                            <select id="oficina_${numeroFila}" class="form-control oficina" data-row="${numeroFila}" required>
                                <option value="">-- Seleccione una opción --</option>
                                    @foreach($oficinas as $oficina)
                                        <option value="{{ $oficina->OFICINA_ID }}">{{ $oficina->OFICINA_NOMBRE }}</option>
                                    @endforeach
                                </optgroup>
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="dependencia_${numeroFila}">Ubicación o Departamento</label>
                            <select id="dependencia_${numeroFila}" class="form-control dependencia" data-row="${numeroFila}" disabled required>
                                <option value="">-- Seleccione una opción --</option>
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
                                <option value="">${numeroFila === 1 ? '-- Seleccione al conductor --' : '-- Seleccione al pasajero N°' + (numeroFila - 1) + ' --'}</option>
                                <optgroup label="Funcionarios Asociados">
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" data-ubicacion-id="{{ $user->UBICACION_ID }}" data-departamento-id="{{ $user->DEPARTAMENTO_ID }}">{{ $user->USUARIO_NOMBRES }} {{ $user->USUARIO_APELLIDOS }}</option>
                                    @endforeach
                                </optgroup>
                            </select>
                        </div>
                    </div>
                `;
                pasajeros.appendChild(nuevaFila);

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
