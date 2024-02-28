@extends('adminlte::page')

@section('title', 'Ingreso de resolución delegatoria')

@section('content_header')
    <h1>Ingresar Póliza</h1>
@stop


@section('content')
<div class="container">
    <form action="{{ route('polizas.store') }}" method="POST">
        @csrf
        <div class="row">

            <div class="form-group col-md-6">
                <label for="DEPENDENCIA_ID"><i class="fa-solid fa-building-user"></i> Ubicación o Departamento:</label>
                <select id="DEPENDENCIA_ID" class="form-control dependencia" required>
                    <option style="text-align: center;" value="">-- Seleccione una dependencia --</option>
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
                @if ($errors->has('DEPENDENCIA_ID'))
                    <div class="invalid-feedback">
                        {{ $errors->first('DEPENDENCIA_ID') }}
                    </div>
                @endif
            </div>

            <div class="form-group col-md-6">
                <label for="USUARIO_id" class="form-label"><i class="fa-solid fa-book-bookmark"></i> Funcionario:</label>
                <select class="form-control{{ $errors->has('USUARIO_id') ? ' is-invalid' : '' }}" id="USUARIO_id" name="USUARIO_id" disabled required>
                    <option style="text-align: center;" value="">-- Seleccione al conductor --</option>
                    <optgroup label="Funcionarios Asociados">
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" data-ubicacion-id="{{ $user->UBICACION_ID }}" data-departamento-id="{{ $user->DEPARTAMENTO_ID }}">{{ $user->USUARIO_NOMBRES }} {{ $user->USUARIO_APELLIDOS }}</option>
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
                <label for="POLIZA_NUMERO" class="form-label"><i class="fa-solid fa-book-bookmark"></i> N° Poliza:</label>
                <input type="text" class="form-control{{ $errors->has('POLIZA_NUMERO') ? ' is-invalid' : '' }}" id="POLIZA_NUMERO" name="POLIZA_NUMERO" value="{{ old('POLIZA_NUMERO') }}" placeholder="Ej: 76206" required>
                @if ($errors->has('POLIZA_NUMERO'))
                    <div class="invalid-feedback">
                        {{ $errors->first('POLIZA_NUMERO') }}
                    </div>
                @endif
            </div>

            <div class="form-group col-md-6">
                <label for="POLIZA_FECHA_VENCIMIENTO_LICENCIA" class="form-label"><i class="fa-solid fa-book-bookmark"></i> Fecha de vencimiento de licencia:</label>
                <input type="text" class="form-control{{ $errors->has('POLIZA_FECHA_VENCIMIENTO_LICENCIA') ? ' is-invalid' : '' }}" id="POLIZA_FECHA_VENCIMIENTO_LICENCIA" name="POLIZA_FECHA_VENCIMIENTO_LICENCIA" value="{{ old('POLIZA_FECHA_VENCIMIENTO_LICENCIA') }}" placeholder="Ej: 2024-08-24" required>
                @if ($errors->has('POLIZA_FECHA_VENCIMIENTO_LICENCIA'))
                    <div class="invalid-feedback">
                        {{ $errors->first('POLIZA_FECHA_VENCIMIENTO_LICENCIA') }}
                    </div>
                @endif
            </div>
        </div>
        <br>
        <a href="{{route('polizas.index')}}" class="btn btn-secondary" tabindex="5"><i class="fa-solid fa-hand-point-left"></i> Cancelar</a>
        <button type="submit" class="btn btn-primary" tabindex="4"><i class="fa-solid fa-floppy-disk"></i> Guardar póliza</button>
    </form>
</div>
@stop


@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <!-- CONEXION FONT-AWESOME CON TOOLKIT -->
    <script src="https://kit.fontawesome.com/742a59c628.js" crossorigin="anonymous"></script>
    <script>
        $(function () {
            // Calcular la fecha de fin (dos años después del día actual)
            let fechaFin = new Date();
            fechaFin.setFullYear(fechaFin.getFullYear() + 5);

            $('#POLIZA_FECHA_VENCIMIENTO_LICENCIA').flatpickr({
                locale: 'es',
                minDate: "today",
                maxDate: fechaFin.toISOString().split("T")[0],
                dateFormat: "Y-m-d",
                altFormat: "d-m-Y",
                altInput: true,
                allowInput: true,
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Obtener referencias a los selectores
            var dependenciaSelector = document.getElementById('DEPENDENCIA_ID');
            var usuarioSelector = document.getElementById('USUARIO_id');

            // Desactivar el segundo selector al cargar la página
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
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Obtener referencia al formulario
        var form = document.querySelector('form');

        // Agregar un evento de envío al formulario
        form.addEventListener('submit', function() {
            // Obtener referencia al botón de enviar dentro del formulario
            var submitButton = form.querySelector('button[type="submit"]');

            // Desactivar el botón de enviar
            submitButton.disabled = true;
        });
    });
</script>
    
@stop
