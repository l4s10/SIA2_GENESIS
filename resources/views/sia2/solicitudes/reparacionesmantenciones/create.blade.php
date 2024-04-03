
@extends('adminlte::page')

@section('title', 'Solicitar Reparación o Mantención')

@section('content_header')
    <h1>Crear Solicitud</h1>
    @role('ADMINISTRADOR')
    <div class="alert alert-info alert1" role="alert">
    <div><strong>Bienvenido Administrador:</strong> Acceso total al modulo.<div>
    </div>
    @endrole
    @role('REQUIRENTE')
    <div class="alert alert-info alert1" role="alert">
    <div><strong>Bienvenido Requirente:</strong> En el presente módulo usted podrá solicitar Reparación o Mantención, tanto preventivas y/o correctivas, según sea el caso el Departamento de Administración analizará los antecedentes, y podrán aceptarla o rechazar la solicitud.<div>
    </div>
    @endrole
    @role('SERVICIOS')
    <div class="alert alert-info" role="alert">
    <div><strong>Bienvenido Servicio:</strong> En este módulo usted podrá administrar, modificar, las solicitudes de Reparación o Mantención.<div>
    </div>
    @endrole
    @role('INFORMATICA')
    <div class="alert alert-info" role="alert">
    <div><strong>Bienvenido Informatica:</strong> En el presente módulo usted podrá solicitar Reparación o Mantención, tanto preventivas y/o correctivas, según sea el caso el Departamento de Administración analizará los antecedentes, y podrán aceptarla o rechazar la solicitud.<div>
    </div>
    @endrole
    @role('JURIDICO')
    <div class="alert alert-info" role="alert">
    <div><strong>Bienvenido Juridico:</strong> En el presente módulo usted podrá solicitar Reparación o Mantención, tanto preventivas y/o correctivas, según sea el caso el Departamento de Administración analizará los antecedentes, y podrán aceptarla o rechazar la solicitud.<div>
    </div>
    @endrole
    @role('FUNCIONARIO')
    <div class="alert alert-info" role="alert">
    <div><strong>Bienvenido Funcionario:</strong> En el presente módulo usted podrá solicitar Reparación o Mantención, tanto preventivas y/o correctivas, según sea el caso el Departamento de Administración analizará los antecedentes, y podrán aceptarla o rechazar la solicitud.<div>
    </div>
    @endrole
@stop

@section('content')
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
            document.addEventListener('DOMContentLoaded', () => {
                Swal.fire({
                    icon: 'error',
                    title: '{{ session('error') }}',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#0064A0'
                });
            });
        </script>
    @endif

        {{-- Formulario de Solicitud --}}
        <form action="{{ route('solicitudes.reparaciones.store') }}" method="POST">
            @csrf

            {{-- Tipo de Solicitud --}}
            <div class="form-group {{ $errors->has('SOLICITUD_REPARACION_TIPO') ? 'has-error' : '' }}">
                <label for="SOLICITUD_REPARACION_TIPO"><i class="fa-solid fa-file-pen"></i> Tipo de solicitud</label>
                <select name="SOLICITUD_REPARACION_TIPO" id="SOLICITUD_REPARACION_TIPO" class="form-control" required>
                    <option value="">Seleccione un tipo</option>
                    <option value="REPARACION">Reparación</option>
                    <option value="MANTENCION">Mantención</option>
                </select>
                @error('SOLICITUD_REPARACION_TIPO')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            {{-- Categoría de Solicitud --}}
            <div class="form-group {{ $errors->has('CATEGORIA_REPARACION_ID') ? 'has-error' : '' }}">
                <label for="CATEGORIA_REPARACION_ID"><i class="fa-solid fa-warehouse"></i> / </i><i class="fa-solid fa-car-on"></i> Categoría de solicitud</label>
                <select name="CATEGORIA_REPARACION_ID" id="CATEGORIA_REPARACION_ID" class="form-control" required>
                    <option value="">Seleccione una categoría</option>
                    @foreach ($categorias as $categoria)
                        <option value="{{ $categoria->CATEGORIA_REPARACION_ID }}">{{ $categoria->CATEGORIA_REPARACION_NOMBRE }}</option>
                    @endforeach
                </select>
                @error('CATEGORIA_REPARACION_ID')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            {{-- Vehículo con Problemas --}}
            <div class="form-group {{ $errors->has('VEHICULO_ID') ? 'has-error' : '' }}">
                <label for="VEHICULO_ID"><i class="fa-solid fa-car-burst"></i> Vehículo con problemas</label>
                <select name="VEHICULO_ID" id="VEHICULO_ID" class="form-control">
                    <option value="">Seleccione un vehículo</option>
                    @foreach ($vehiculos as $vehiculo)
                    <option value="{{ $vehiculo->VEHICULO_ID }}">
                        {{ $vehiculo->VEHICULO_PATENTE }} - {{ $vehiculo->VEHICULO_MODELO }}, {{ $vehiculo->tipoVehiculo->TIPO_VEHICULO_NOMBRE }}
                    </option>
                    @endforeach
                </select>
                @error('VEHICULO_ID')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            {{-- Motivo de la Solicitud --}}
            <div class="form-group {{ $errors->has('SOLICITUD_REPARACION_MOTIVO') ? 'has-error' : '' }}">
                <label for="SOLICITUD_REPARACION_MOTIVO"><i class="fa-solid fa-pen-to-square"></i> Motivo de la Solicitud</label>
                <textarea class="form-control" id="SOLICITUD_REPARACION_MOTIVO" name="SOLICITUD_REPARACION_MOTIVO" rows="3" placeholder="Indique el problema. Máx 1000 caracteres" required></textarea>
                @error('SOLICITUD_REPARACION_MOTIVO')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>


            {{-- Estado de la Solicitud --}}
            <div class="form-group">
                <label for="SOLICITUD_ESTADO"><i class="fa-solid fa-file-circle-check"></i> Estado de la Solicitud</label>
                <input type="text" class="form-control" id="SOLICITUD_ESTADO" name="SOLICITUD_ESTADO" value="🟠INGRESADO" readonly>
            </div>


            <button href="{{ route('solicitudes.formularios.index') }}" class="btn btn-secondary "><i class="fa-solid fa-hand-point-left"></i> Cancelar</button>
            {{-- Botón de envío --}}
            <button type="submit" class="btn agregar"><i class="fa-solid fa-clipboard-check"></i> Crear Solicitud</button>
        </form>

@stop

@section('css')
    <style>
        .centrar{
            text-align: center;
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
        .botoneditar{
            background-color: #1aa16b;
            color: #fff;
        }
    </style>
    
    <!-- Color mensajes usuario -->
    <style>
        .alert {
            opacity: 0.7; /* Ajusta la opacidad del texto */
            background-color: #99CCFF;
            color:     #000000;
        }
        .alert1 {
            opacity: 0.7; /* Ajusta la opacidad del texto  */
            background-color: #FF8C40;
            color: #000000;
        }
    </style>
@stop

@section('js')
    <script>
        $(document).ready(function() {
            // Función para manejar la visibilidad, habilitación/deshabilitación y requerimiento del campo de vehículo
            function toggleVehiculoField(tipoSeleccionado) {
                var $campoVehiculo = $('#VEHICULO_ID');
                var $grupoCampoVehiculo = $campoVehiculo.closest('.form-group');

                if(tipoSeleccionado === 'MANTENCION') {
                    // Muestra y habilita el campo de vehículo para Mantención, y lo hace requerido
                    $grupoCampoVehiculo.show();
                    $campoVehiculo.prop('disabled', false);
                    $campoVehiculo.attr('required', true);
                } else {
                    // Oculta y deshabilita el campo de vehículo para Reparación, y lo hace no requerido
                    $grupoCampoVehiculo.hide();
                    $campoVehiculo.prop('disabled', true);
                    $campoVehiculo.removeAttr('required');
                }
            }

            $('#SOLICITUD_REPARACION_TIPO').change(function() {
                var tipoSeleccionado = $(this).val(); // Obtiene el tipo seleccionado

                // Llama a la función para ajustar el estado del campo de vehículo basado en la selección
                toggleVehiculoField(tipoSeleccionado);

                // Filtrado de categorías basado en la selección
                var opcionesCategoria = $('#CATEGORIA_REPARACION_ID option');
                opcionesCategoria.each(function() {
                    var opcion = $(this);
                    opcion.hide(); // Oculta todas las opciones primero para filtrar luego

                    if(tipoSeleccionado === 'REPARACION') {
                        if(!['MANTENCION CORRECTIVA', 'MANTENCION PREVENTIVA'].includes(opcion.text())) {
                            opcion.show();
                        }
                    } else if(tipoSeleccionado === 'MANTENCION') {
                        if(['MANTENCION CORRECTIVA', 'MANTENCION PREVENTIVA', 'OTRO'].includes(opcion.text())) {
                            opcion.show();
                        }
                    } else {
                        opcion.show();
                    }
                });

                // Reinicia la selección de categorías cada vez que se cambia el tipo
                $('#CATEGORIA_REPARACION_ID').val('').trigger('change');
            });

            // Llamada inicial para establecer el estado del campo de vehículo basado en la selección actual
            toggleVehiculoField($('#SOLICITUD_REPARACION_TIPO').val());
        });
    </script>
@stop
