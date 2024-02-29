@extends('adminlte::page')

<!-- TITULO DE LA PESTAÑA -->
@section('title', 'Modificar Póliza')

<!-- CABECERA DE LA PAGINA -->
@section('content_header')
    <h1>Modificar Póliza</h1>
@stop

@section('content')
    <div class="container">
        <form action="{{ route('polizas.update', $poliza->POLIZA_ID) }}" method="POST">
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
            <div class="row">
                <div class="form-group col-md-6">
                    <label for="DEPENDENCIA_ID"><i class="fa-solid fa-building-user"></i> Ubicación o Departamento:</label>
                    <select id="DEPENDENCIA_ID" class="form-control dependencia" required>
                        <option style="text-align: center;" value="">-- Seleccione una dependencia --</option>
                        <optgroup label="Ubicaciones">
                            @foreach($ubicaciones as $ubicacion)
                                <option value="{{ $ubicacion->UBICACION_ID }}" @if ($poliza->user->ubicacion && $poliza->user->ubicacion->UBICACION_ID == $ubicacion->UBICACION_ID) selected @endif>{{ $ubicacion->UBICACION_NOMBRE }}</option>
                            @endforeach
                        </optgroup>
                        <optgroup label="Departamentos">
                            @foreach($departamentos as $departamento)
                                <option value="{{ $departamento->DEPARTAMENTO_ID }}" @if ($poliza->user->departamento && $poliza->user->departamento->DEPARTAMENTO_ID == $departamento->DEPARTAMENTO_ID) selected @endif>{{ $departamento->DEPARTAMENTO_NOMBRE }}</option>
                            @endforeach
                        </optgroup>
                    </select>
                    @if ($errors->has('DEPENDENCIA_ID'))
                        <div class="invalid-feedback">
                            {{ $errors->first('DEPENDENCIA_ID') }}
                        </div>
                    @endif
                </div>
                

                <div class="form-group col-md-6">
                    <label for="USUARIO_id" class="form-label"><i class="fa-solid fa-book-bookmark"></i> Funcionario:</label>
                    <select class="form-control{{ $errors->has('USUARIO_id') ? ' is-invalid' : '' }}" id="USUARIO_id" name="USUARIO_id" required>
                        <option style="text-align: center;" value="">-- Seleccione al conductor --</option>
                        <optgroup label="Funcionarios Asociados" id="funcionarios-options">
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" data-ubicacion-id="{{ $user->ubicacion ? $user->ubicacion->UBICACION_ID : '' }}" data-departamento-id="{{ $user->departamento ? $user->departamento->DEPARTAMENTO_ID : '' }}" {{ $poliza->USUARIO_id == $user->id ? 'selected' : '' }}>{{ $user->USUARIO_NOMBRES }} {{ $user->USUARIO_APELLIDOS }}</option>
                            @endforeach
                        </optgroup>
                    </select>
                    @if ($errors->has('USUARIO_id'))
                        <div class="invalid-feedback">
                            {{ $errors->first('USUARIO_id') }}
                        </div>
                    @endif
                </div>
                
            </div>
            <div class="row">
                <div class="form-group col-md-6">
                    <label for="POLIZA_NUMERO"><i class="fa-solid fa-book-bookmark"></i>  N° Póliza:</label>
                    <input type="text" name="POLIZA_NUMERO" id="POLIZA_NUMERO" class="form-control" placeholder="N° Póliza (Ej: 76206)" value="{{ $poliza->POLIZA_NUMERO }}" required autofocus>
                    @if ($errors->has('POLIZA_NUMERO'))
                        <div class="invalid-feedback">
                            {{ $errors->first('POLIZA_NUMERO') }}
                        </div>
                    @endif
                </div>

                <div class="form-group col-md-6">
                    <label for="POLIZA_FECHA_VENCIMIENTO_LICENCIA"><i class="fa-solid fa-book-bookmark"></i>  Vencimiento licencia de conducir:</label>
                    <input type="text" name="POLIZA_FECHA_VENCIMIENTO_LICENCIA" id="POLIZA_FECHA_VENCIMIENTO_LICENCIA" class="form-control" placeholder="Fecha: (Ej 2024-24-08)" value="{{ date('Y-m-d', strtotime($poliza->POLIZA_FECHA_VENCIMIENTO_LICENCIA)) }}" required autofocus>
                    @if ($errors->has('POLIZA_FECHA_VENCIMIENTO_LICENCIA'))
                        <div class="invalid-feedback">
                            {{ $errors->first('POLIZA_FECHA_VENCIMIENTO_LICENCIA') }}
                        </div>
                    @endif
                </div>
            </div>
            <br>
            <div class="form-group">
                <a href="{{ route('polizas.index') }}" class="btn btn-secondary"><i class="fa-solid fa-hand-point-left"></i> Cancelar</a>
                <button type="submit" id="submitBtn" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> Modificar póliza</button>
            </div>

           
        </form>

    </div>
@stop

@section('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endsection

@section('js')
    <!-- Incluir archivos JS flatpicker-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let fechaActual = new Date();

            // Calcular la fecha de inicio (1 año antes del día actual)
            let fechaInicio = new Date();
            fechaInicio.setFullYear(fechaInicio.getFullYear() - 1);

            // Calcular la fecha de fin (2 años después del día actual)
            let fechaFin = new Date();
            fechaFin.setFullYear(fechaFin.getFullYear() + 2);

            flatpickr('#POLIZA_FECHA_VENCIMIENTO_LICENCIA', {
                locale: 'es',
                minDate: fechaInicio.toISOString().split("T")[0],
                maxDate: fechaFin.toISOString().split("T")[0],
                dateFormat: "Y-m-d",
                altFormat: "d-m-Y",
                altInput: true,
                allowInput: true,
            });

            // Obtener referencias a los selectores
            var dependenciaSelector = document.getElementById('DEPENDENCIA_ID');
            var usuarioSelector = document.getElementById('USUARIO_id');

            usuarioSelector.disabled = true;

            // Agregar un evento de clic al botón de enviar
            var submitBtn = document.getElementById('submitBtn');
            submitBtn.addEventListener('click', function() {
                usuarioSelector.disabled = false; // Habilitar el selector
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Obtener referencias a los selectores
            var dependenciaSelector = document.getElementById('DEPENDENCIA_ID');
            var usuarioSelector = document.getElementById('USUARIO_id');

            usuarioSelector.disabled = true;


            // Agregar un evento de cambio al primer selector
            dependenciaSelector.addEventListener('change', function() {
                var selectedDependenciaId = dependenciaSelector.value;

                if (selectedDependenciaId === '') {
                    // Si no se selecciona ninguna opción en el primer selector, desactivar el segundo selector y ocultar todas las opciones
                    usuarioSelector.disabled = true;
                    usuarioSelector.value = '';
                    Array.from(usuarioSelector.options).forEach(function(option) {
                        option.style.display = 'none';
                    });
                } else {
                    // Habilitar el segundo selector
                    usuarioSelector.disabled = false;
                    usuarioSelector.value = '';

                    // Mostrar solo las opciones del segundo selector que coinciden con la selección del primer selector
                    Array.from(usuarioSelector.options).forEach(function(option) {
                        var ubicacionId = option.getAttribute('data-ubicacion-id');
                        var departamentoId = option.getAttribute('data-departamento-id');

                        if (ubicacionId == selectedDependenciaId || departamentoId == selectedDependenciaId) {
                            option.style.display = 'block';
                        } else {
                            option.style.display = 'none';
                        }
                    });
                }
            });
        });
    </script>
@endsection