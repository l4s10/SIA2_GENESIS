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
        


        <div id="pasajeros" style="display: none;">

            
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
            let prevTipoVehiculoId = ''; // Variable para almacenar el valor previo del TIPO_VEHICULO_ID
            let contadorFilas = 1;
            let capacidadMaxima = 0;
            let pasajerosSeleccionados = new Set(); // Conjunto para almacenar los IDs de pasajeros seleccionados y evitar duplicados

            // Funcion para reiniciar el conjunto de IDs de pasajeros seleccionados
            function reiniciarPasajerosSeleccionados() {
                pasajerosSeleccionados = new Set();
            }

            
            // Cambiar texto del botón de eliminar pasajero (caso default (eliminar conductor) para fila 1)

    
            $('#agregarPasajeroBtn').hide();
            $('#eliminarPasajeroBtn').hide();
    
            $('#TIPO_VEHICULO_ID').change(function() {
                let tipoVehiculoIdSeleccionado = $(this).val();
                contadorFilas = 1;
                capacidadMaxima = 0;
                $('#agregarPasajeroBtn').html('<i class="fas fa-plus"></i> Agregar Conductor');
                $('#eliminarPasajeroBtn').html('<i class="fas fa-minus"></i> Eliminar Conductor');

                // Restablecer el conjunto de pasajeros seleccionados al cambiar TIPO_VEHICULO_ID
                reiniciarPasajerosSeleccionados();

    
                // Verificar si se ha seleccionado la opción por defecto
                if (tipoVehiculoIdSeleccionado === '') {
                    let confirmacion = confirm('¿Está seguro de eliminar el tipo de vehículo solicitado y el registro de pasajeros asociados?');
                    if (!confirmacion) {
                        // Si el usuario presiona "Cancelar", se cancela el cambio y se restaura el valor anterior
                        $(this).val(prevTipoVehiculoId);
                        return;
                    } else {
                        prevTipoVehiculoId = '';
                    }
                    $('#agregarPasajeroBtn').hide();
                    $('#eliminarPasajeroBtn').hide();
                    $('#pasajeros').hide();
                    return; // Salir de la función si se selecciona la opción por defecto
                } else if   (tipoVehiculoIdSeleccionado !== '' && prevTipoVehiculoId !== '') {
                    // Mostrar advertencia al usuario
                    let confirmacion = confirm('¿Está seguro de cambiar el tipo de vehículo solicitado?, se eliminará el registro de pasajeros asociados.');
                    if (!confirmacion) {
                        // Si el usuario presiona "Cancelar", se cancela el cambio y se restaura el valor anterior
                        $(this).val(prevTipoVehiculoId);
                        return;
                    }
                }
    
                
                let tiposVehiculosJSON = @json($tiposVehiculos);
                let tipoVehiculoSeleccionado = tiposVehiculosJSON.find(function(tipoVehiculo) {
                    return tipoVehiculo.TIPO_VEHICULO_ID == tipoVehiculoIdSeleccionado;
                });
    
                let pasajeros = $('#pasajeros');
                pasajeros.empty();
    
                if (tipoVehiculoSeleccionado.TIPO_VEHICULO_CAPACIDAD > 0) {
                    console.log('Tipo de vehículo seleccionado:', tipoVehiculoSeleccionado);
                    capacidadMaxima = tipoVehiculoSeleccionado.TIPO_VEHICULO_CAPACIDAD;
    
                    // Agregar la primera fila de selectores para el conductor
                    agregarFila(contadorFilas);
    
                    $('#agregarPasajeroBtn').show();
                    $('#eliminarPasajeroBtn').show();
    
                    // Mostrar los selectores
                    pasajeros.show();
                } else {
                    $('#agregarPasajeroBtn').hide();
                    $('#eliminarPasajeroBtn').hide();
                    pasajeros.hide();
                }
    
                prevTipoVehiculoId = tipoVehiculoIdSeleccionado;
            });
    
            $('#agregarPasajeroBtn').click(function() {
                // Verificar si todos los selectores de la última fila tienen un valor seleccionado
                let todosSelectoresConValor = true;
                // Iterar sobre la última fila de selectores
                for (let i = 1; i <= contadorFilas ; i++) {
                    let oficinaValue = $('#oficina_' + i).val();
                    let dependenciaValue = $('#dependencia_' + i).val();
                    let pasajeroValue = $('#pasajero' + i).val();

                    // Verificar si algún selector de la fila no tiene un valor seleccionado
                    if (oficinaValue === '' || dependenciaValue === '' || pasajeroValue === '') {
                        todosSelectoresConValor = false;
                        if (i === 1) {
                            alert('Antes de agregar un pasajero, por favor complete los datos para el Conductor.');
                        } else {
                            alert('Antes de agregar otro pasajero, por favor complete los datos requeridos para el "Pasajero  N°'+(i-1)+'".');
                        }
                        break;
                    }
                }
                
                // Cambiar texto de botones para eliminar y agregar pasajero, después de  igresar conductor
                if ( contadorFilas === 1 && todosSelectoresConValor)  {
                    $('#agregarPasajeroBtn').html('<i class="fas fa-plus"></i> Agregar Pasajero');
                    $('#eliminarPasajeroBtn').html('<i class="fas fa-minus"></i> Eliminar Pasajero');
                }

                // Si todos los selectores de la última fila tienen un valor seleccionado y aún no se ha alcanzado la capacidad máxima, agregar una nueva fila
                if (todosSelectoresConValor && contadorFilas < capacidadMaxima) {
                    contadorFilas++;
                    agregarFila(contadorFilas);

                    // Obtener el elemento de la fila recién agregada
                    let nuevaFila = document.getElementById('fila_' + contadorFilas);

                    // Desplazar la vista hasta el centro de la fila recién agregada
                    nuevaFila.scrollIntoView({ behavior: 'smooth', block: 'center' });
                } else {
                    if (contadorFilas === capacidadMaxima && todosSelectoresConValor ) {
                        // Mostrar un mensaje de error si todos los selectores de la última fila tienen un valor seleccionado pero se ha alcanzado la capacidad máxima
                        alert('Se ha alcanzado la capacidad máxima de pasajeros para este vehículo.');
                    }
                }
            });

    
            // Escuchador de eventos para el botón "Eliminar pasajero"
            $('#eliminarPasajeroBtn').click(function() {
                if (contadorFilas > 1) {
                    eliminarPasajero();

                } else {
                    let confirmacion = confirm('¿Está seguro de eliminar la información registrada para el conductor?');
                    if (confirmacion) {
                        eliminarConductor();
                    }
                } 

                // Cambiar texto de botones para eliminar y agregar conductor apenas estemos en la fila 1 (durante la navegación intermedia)
                if (contadorFilas === 1) {
                    $('#agregarPasajeroBtn').html('<i class="fas fa-plus"></i> Agregar Conductor');
                    $('#eliminarPasajeroBtn').html('<i class="fas fa-minus"></i> Eliminar Conductor');
                }
            });
                  

            function eliminarConductor() {
                // Restablecer los selectores a sus opciones predeterminadas o defaults luego de hacer clic
   //             $('#eliminarPasajeroBtn').html('<i class="fas fa-minus"></i> Eliminar Conductor');
                $('#oficina_1').val('');
                $('#dependencia_1').val('').prop('disabled', true);
                $('#pasajero1').val('').prop('disabled', true);
                // Reiniciar el buffer de usuarios seleccionados
                reiniciarPasajerosSeleccionados();

            }
            function eliminarPasajero() {
                $('#fila_' + contadorFilas).remove();
                contadorFilas--;

                // Mostrar el botón de agregar pasajero si no se ha alcanzado la capacidad máxima
                if (contadorFilas < capacidadMaxima) {
                    $('#agregarPasajeroBtn').show();
                }
            }
    
            function agregarFila(numeroFila) {
                let pasajeros = $('#pasajeros');
                pasajeros.append(`
                    <div class="pasajero-row border p-3 mb-3" id="fila_${numeroFila}">
                        <h5>${numeroFila === 1 ? 'Conductor' : 'Pasajero N°' + (numeroFila - 1)}</h5>
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
                                <label for="pasajero${numeroFila}">Funcionario</label>
                                <select id="pasajero${numeroFila}" class="form-control pasajero" name="PASAJERO_${numeroFila}" data-row="${numeroFila}" disabled required>
                                    <option value="">${numeroFila === 1 ? '-- Seleccione al conductor --' : '-- Seleccione al pasajero N°' + (numeroFila - 1) + ' --'}</option>
                                    @foreach($users as $user)
                                    <option value="{{ $user->id }}" data-ubicacion-id="{{ $user->UBICACION_ID }}" data-departamento-id="{{ $user->DEPARTAMENTO_ID }}">{{ $user->USUARIO_NOMBRES }} {{ $user->USUARIO_APELLIDOS }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                `); 


                // Controlar desaparición del botón 'agregar pasajero' en la última fila de pasajeros
                if (contadorFilas < capacidadMaxima) {
                    $('#agregarPasajeroBtn').show();
                } else {
                    $('#agregarPasajeroBtn').hide();

                }
    
                // Evento change para el selector de oficinas
                $('#oficina_' + numeroFila).change(function() {
                    let selectedOfficeId = $(this).val();
                    let $dependenciaSelect = $('#dependencia_' + numeroFila);
                    let $pasajeroSelect = $('#pasajero' + numeroFila);
                    $dependenciaSelect.val('');
                    $pasajeroSelect.val('').prop('disabled', true);
    
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
    
                    // Si se selecciona la opción vacía en el selector de oficinas, deshabilita el selector de dependencias y de pasajeros
                    if (selectedOfficeId === '') {
                        $dependenciaSelect.val('').prop('disabled', true);
                        $pasajeroSelect.val('').prop('disabled', true);
                    } else {
                        // Si se selecciona una oficina, habilita el selector de dependencias
                        $dependenciaSelect.prop('disabled', false);
                    }
                });
    
                // Evento change para el selector de dependencias
                $('#dependencia_' + numeroFila).change(function() {
                    let selectedDependenciaId = $(this).val();
                    let $pasajeroSelect = $('#pasajero' + numeroFila);
    
                    // Mostrar todas las opciones del selector de pasajeros
                    $pasajeroSelect.find('option').show();
    
                    // Mostrar los pasajeros que pertenecen a la dependencia_ seleccionada y ocultar las que no.
                    $pasajeroSelect.find('option').each(function() {
                        let optionUbicacionId = $(this).data('ubicacion-id');
                        let optionDepartamentoId = $(this).data('departamento-id');
                        if ((optionUbicacionId == selectedDependenciaId || optionDepartamentoId == selectedDependenciaId) && selectedDependenciaId !== "") {
                            $(this).show();
                        } else {
                            $(this).hide();
                        }
                    });
    
                    // Restablecer el selector de pasajeros seleccionado
                    $pasajeroSelect.val('');
    
                    // Si se selecciona la opción vacía en el selector de dependencias, deshabilita el selector de pasajeros
                    if (selectedDependenciaId === '') {
                        $pasajeroSelect.val('').prop('disabled', true);
                    } else {
                        // Si se selecciona una dependencia, habilita el selector de pasajeros
                        $pasajeroSelect.prop('disabled', false);
                    }
                });
    
                // Evento change para el selector de pasajeros
                $('#pasajero' + numeroFila).change(function() {
                    let selectedPasajeroId = $(this).val();
    
                    // Verificar si el pasajero ya ha sido seleccionado
                    if (pasajerosSeleccionados.has(selectedPasajeroId)) {
                        alert('Esta persona ya ha sido seleccionada como pasajero. Por favor, elija otra persona.');
                        $(this).val('');
                        return;
                    }
    
                    // Si el pasajero es nuevo, agregarlo al conjunto de pasajeros seleccionados
                    pasajerosSeleccionados.add(selectedPasajeroId);
    
                    // Iterar sobre todos los selectores de pasajeros excepto el actual
                    $('.pasajero').not(this).each(function() {
                        // Deshabilitar la opción seleccionada en otros selectores
                        $(this).find('option[value="' + selectedPasajeroId + '"]').prop('disabled', true);
                    });
                });
            }
        });
    </script>
@stop
