@extends('adminlte::page')

@section('title', 'Crear Solicitud Vehicular')

@section('content_header')
    <h1>Crear Solicitud Vehicular</h1>
@stop

@section('content')
    <div class="container">
        {{-- *CAMPOS FUNCIONARIO* --}}
        <div class="row">
            <div class="col-md-6">
                <input type="text" name="SOLICITANTE_ID" value="{{ auth()->user()->id }}" hidden>
                <div class="mb-3">
                    <label for="USUARIO_NOMBRES" class="form-label"><i class="fa-solid fa-user"></i> Nombre del solicitante:</label>
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
                    <label for="SOLICITUD_ESTADO">Estado de la Solicitud</label>
                    <input type="text" class="form-control" id="SOLICITUD_ESTADO" name="SOLICITUD_ESTADO" value="POR INGRESAR" readonly style="color: green;">
                </div>
            </div>
    
        </div>

        
        <div class="form-group">
            <label for="SOLICITUD_VEHICULO_MOTIVO"><i class="fa-solid fa-file-pen"></i> Labor a realizar:</label>
            <textarea id="SOLICITUD_VEHICULO_MOTIVO" name="SOLICITUD_VEHICULO_MOTIVO" rows="5" class="form-control" placeholder="Indique la labor a realizar (MÁX 500 CARACTERES)" maxlength="500" required></textarea>
        </div>

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
        


        <div id="ocupantes" style="display: none;">

            
        </div>

        
        <button id="agregarPasajeroBtn" class="btn" style="background-color: #1aa16b; color: #fff;">
            <i class="fas fa-plus"></i> Agregar Pasajero
        </button>
        
        <button id="eliminarPasajeroBtn" class="btn" style="background-color: #dc3545; color: #fff;">
            <i class="fas fa-minus"></i> Eliminar Pasajero
        </button>

       {{-- <div class="form-group">
            <label for="SOLICITUD_FECHA_HORA_INICIO_SOLICITADA">Fecha y Hora de Inicio Solicitada</label>
            <input type="datetime-local" class="form-control" id="SOLICITUD_FECHA_HORA_INICIO_SOLICITADA" name="SOLICITUD_FECHA_HORA_INICIO_SOLICITADA" required>
        </div>

        <div class="form-group">
            <label for="SOLICITUD_FECHA_HORA_TERMINO_SOLICITADA">Fecha y Hora de Término Solicitada</label>
            <input type="datetime-local" class="form-control" id="SOLICITUD_FECHA_HORA_TERMINO_SOLICITADA" name="SOLICITUD_FECHA_HORA_TERMINO_SOLICITADA" required>
        </div> --}}

        <br><br><br><br>
        <button type="submit" class="btn btn-primary">Crear Solicitud</button>

    </div>
     

@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">

@stop

@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            let contadorFilas = 1;
            let capacidadMaxima = 0;

            $('#agregarPasajeroBtn').hide();
            $('#eliminarPasajeroBtn').hide();

            $('#TIPO_VEHICULO_ID').change(function() {
                let tipoVehiculoIdSeleccionado = $(this).val();
                contadorFilas = 1;
                capacidadMaxima = 0;
                
                // Verificar si se ha seleccionado la opción por defecto
                if (tipoVehiculoIdSeleccionado === "") {
                    $('#agregarPasajeroBtn').hide();
                    $('#eliminarPasajeroBtn').hide();
                    $('#ocupantes').hide();
                    return; // Salir de la función si se selecciona la opción por defecto
                }

                
                let tiposVehiculosJSON = @json($tiposVehiculos);
                let tipoVehiculoSeleccionado = tiposVehiculosJSON.find(function(tipoVehiculo) {
                    return tipoVehiculo.TIPO_VEHICULO_ID == tipoVehiculoIdSeleccionado;
                });

                let ocupantes = $('#ocupantes');
                ocupantes.empty();

                if (tipoVehiculoSeleccionado.TIPO_VEHICULO_CAPACIDAD > 0) {
                    console.log('Tipo de vehículo seleccionado:', tipoVehiculoSeleccionado);
                    capacidadMaxima = tipoVehiculoSeleccionado.TIPO_VEHICULO_CAPACIDAD;

                    // Agregar la primera fila de selectores para el conductor
                    agregarFila(contadorFilas);

                    $('#agregarPasajeroBtn').show();
                    $('#eliminarPasajeroBtn').show();

                    // Mostrar los selectores
                    ocupantes.show();
                } else {
                    $('#agregarPasajeroBtn').hide();
                    $('#eliminarPasajeroBtn').hide();
                    ocupantes.hide();
                }
            });

            $('#agregarPasajeroBtn').click(function() {
                // Verificar si todos los selectores de la última fila tienen un valor seleccionado
                let todosSelectoresConValor = true;

                // Iterar sobre la última fila de selectores
                for (let i = 1; i <= contadorFilas ; i++) {
                    let oficinaValue = $('#oficina_' + i).val();
                    let dependenciaValue = $('#dependencia_' + i).val();
                    let ocupanteValue = $('#ocupante' + i).val();

                    // Verificar si algún selector de la fila no tiene un valor seleccionado
                    if (oficinaValue === '' || dependenciaValue === '' || ocupanteValue === '') {
                        todosSelectoresConValor = false;
                        if(i===1){
                        alert('Antes de agregar otro pasajero, por favor revise los datos ingresados para Conductor.');

                        } else {
                            alert('Antes de agregar otro pasajero, por favor revise los datos ingresados para el Ocupante  "'+i+'".');
                        }
                        break;
                    }
                }

                // Si todos los selectores de la última fila tienen un valor seleccionado y aún no se ha alcanzado la capacidad máxima, agregar una nueva fila
                if (todosSelectoresConValor && contadorFilas < capacidadMaxima) {
                    contadorFilas++;
                    agregarFila(contadorFilas);                   
                } else {
                    if (contadorFilas === capacidadMaxima){
                        // Mostrar un mensaje de error si todos los selectores de la última fila tienen un valor seleccionado pero se ha alcanzado la capacidad máxima
                        alert('Se ha alcanzado la capacidad máxima de pasajeros para este vehículo.');
                    }
                }
            });


            // Escuchador de eventos para el botón "Eliminar pasajero"
            $('#eliminarPasajeroBtn').click(function() {
                if (contadorFilas > 1) {
                    $('#fila_' + contadorFilas).remove();
                    contadorFilas--;
                } else {
                                        // Restablecer los selectores a sus opciones predeterminadas
                    $('#oficina_1').val('');
                    $('#dependencia_1').val('').prop('disabled', true);
                    $('#ocupante1').val('').prop('disabled', true);
                    alert('Conductor eliminado del registro, por favor ingrese uno nuevamente.');
                    ocupantes.empty();
                }
            });

            function agregarFila(numeroFila) {
                let ocupantes = $('#ocupantes');
                ocupantes.append(`
                    <div class="ocupante-row border p-3 mb-3" id="fila_${numeroFila}">
                        <h5>${numeroFila === 1 ? 'Conductor' : 'Ocupante ' + numeroFila}</h5>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="oficina_${numeroFila}">Oficina</label>
                                <select id="oficina_${numeroFila}" class="form-control oficina" data-row="${numeroFila}" required>
                                    <option value="">-- Seleccione una opción --</option>
                                    @foreach($oficinas as $oficina)
                                        <option value="{{ $oficina->OFICINA_ID }}">{{ $oficina->OFICINA_NOMBRE }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="dependencia_${numeroFila}">Ubicación o Departamento</label>
                                <select id="dependencia_${numeroFila}" class="form-control dependencia" data-row="${numeroFila}" disabled required>
                                    <option value="">-- Seleccione una opción --</option>
                                    @foreach($ubicaciones as $ubicacion)
                                        <option value="{{ $ubicacion->UBICACION_ID }}" data-office-id="{{ $ubicacion->OFICINA_ID }}">{{ $ubicacion->UBICACION_NOMBRE }}</option>
                                    @endforeach
                                    @foreach($departamentos as $departamento)
                                        <option value="{{ $departamento->DEPARTAMENTO_ID }}" data-office-id="{{ $departamento->OFICINA_ID }}">{{ $departamento->DEPARTAMENTO_NOMBRE }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="ocupante${numeroFila}">Funcionario</label>
                                <select id="ocupante${numeroFila}" class="form-control ocupante" name="OCUPANTE_${numeroFila}" data-row="${numeroFila}" disabled required>
                                    <option value="">${numeroFila === 1 ? '-- Seleccione al conductor --' : '-- Seleccione al pasajero ' + numeroFila + ' --'}</option>
                                    @foreach($users as $user)
                                    <option value="{{ $user->id }}" data-ubicacion-id="{{ $user->UBICACION_ID }}" data-departamento-id="{{ $user->DEPARTAMENTO_ID }}">{{ $user->USUARIO_NOMBRES }} {{ $user->USUARIO_APELLIDOS }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                `);

                // Evento change para el selector de oficinas
                $('#oficina_' + numeroFila).change(function() {
                    let selectedOfficeId = $(this).val();
                    let $dependenciaSelect = $('#dependencia_' + numeroFila);
                    let $ocupanteSelect = $('#ocupante' + numeroFila);
                    $dependenciaSelect.val('');
                    $ocupanteSelect.val('').prop('disabled', true);


                    // Mostrar todas las opciones del selector de ubicación/departamento
                    $dependenciaSelect.find('option').show();
                    
                    // Mostrar las opciones que pertenecen a la oficina seleccionada y ocultar las que no.
                    $dependenciaSelect.find('option').each(function() {
                        let optionOfficeId = $(this).data('office-id');
                        if (optionOfficeId == selectedOfficeId && optionOfficeId !== "") {
                            $(this).show();
                        } else {
                            $(this).hide();
                        }
                    });

                    // Restablecer el selector de ubicación/departamento seleccionado
                    $dependenciaSelect.val('');

                    // Si se selecciona la opción vacía en el selector de oficinas, deshabilita el selector de dependencias y de ocupantes
                    if (selectedOfficeId === '') {
                        $dependenciaSelect.val('').prop('disabled', true);
                        $ocupanteSelect.val('').prop('disabled', true);
                    } else {
                        // Si se selecciona una oficina, habilita el selector de dependencias
                        $dependenciaSelect.prop('disabled', false);
                    }
                });

                // Evento change para el selector de dependencias
                $('#dependencia_' + numeroFila).change(function() {
                    let selectedDependenciaId = $(this).val();
                    let $ocupanteSelect = $('#ocupante' + numeroFila);

                    // Mostrar todas las opciones del selector de ocupantes
                    $ocupanteSelect.find('option').show();

                    // Mostrar los ocupantes que pertenecen a la dependencia_ seleccionada y ocultar las que no.
                    $ocupanteSelect.find('option').each(function() {
                        let optionUbicacionId = $(this).data('ubicacion-id');
                        let optionDepartamentoId = $(this).data('departamento-id');
                        if ((optionUbicacionId == selectedDependenciaId || optionDepartamentoId == selectedDependenciaId) && selectedDependenciaId !== "") {
                            $(this).show();
                        } else {
                            $(this).hide();
                        }
                    });

                    // Restablecer el selector de ocupantes seleccionado
                    $ocupanteSelect.val('');

                    // Si se selecciona la opción vacía en el selector de dependencias, deshabilita el selector de ocupantes
                    if (selectedDependenciaId === '') {
                        $ocupanteSelect.val('').prop('disabled', true);
                    } else {
                        // Si se selecciona una dependencia, habilita el selector de ocupantes
                        $ocupanteSelect.prop('disabled', false);
                    }
                });

            }
        });

    </script>
@stop
