@extends('adminlte::page')

@section('title', 'Ingreso de resolución delegatoria')

@section('content_header')
    <h1>Ingresar Resolución Delegatoria</h1>
@stop

@section('content')
<div class="container">
    <form action="{{ route('resoluciones.store') }}" method="POST" >        
        @csrf
        <div class="mb-3">
            <label for="RESOLUCION_NUMERO" class="form-label"><i class="fa-solid fa-book-bookmark"></i> N° Resolución:</label>
            <input type="text" class="form-control{{ $errors->has('RESOLUCION_NUMERO') ? ' is-invalid' : '' }}" id="RESOLUCION_NUMERO" name="RESOLUCION_NUMERO" value="{{ old('RESOLUCION_NUMERO') }}" min="0" max="9999" placeholder="Ej: 1234" required>
            @if ($errors->has('RESOLUCION_NUMERO'))
                <div class="invalid-feedback">
                    {{ $errors->first('RESOLUCION_NUMERO') }}
                </div>
            @endif
        </div>
        <div class="mb-3">
            <label for="RESOLUCION_FECHA" class="form-label"><i class="fa-solid fa-book-bookmark"></i> Fecha:</label>
            <input type="text" class="form-control{{ $errors->has('RESOLUCION_FECHA') ? ' is-invalid' : '' }}" id="RESOLUCION_FECHA" name="RESOLUCION_FECHA" value="{{ old('RESOLUCION_FECHA') }}" placeholder="Ej: 1996-08-24" required>
            @if ($errors->has('RESOLUCION_FECHA'))
                <div class="invalid-feedback">
                    {{ $errors->first('RESOLUCION_FECHA') }}
                </div>
            @endif
        </div>


        <div class="mb-3">
            <label for="TIPO_RESOLUCION_ID" class="form-label"><i class="fa-solid fa-book-bookmark"></i> Tipo de Resolución:</label>
            <select id="TIPO_RESOLUCION_ID" name="TIPO_RESOLUCION_ID" class="form-control @error('TIPO_RESOLUCION_ID') is-invalid @enderror" required>
                <option value="" selected>--Seleccione Tipo de Resolución--</option>

                @foreach ($tiposResoluciones as $tipoResolucion)
                    <option value="{{ $tipoResolucion->TIPO_RESOLUCION_ID }}">{{ $tipoResolucion->TIPO_RESOLUCION_NOMBRE }}</option>
                @endforeach

            </select>

            @error('TIPO_RESOLUCION_ID')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="mb-3">
            <label for="CARGO_ID" class="form-label"><i class="fa-solid fa-book-bookmark"></i> Firmante:</label>
            <select id="CARGO_ID" name="CARGO_ID" class="form-control @error('CARGO_ID') is-invalid @enderror" required>
                <option value="" selected>--Seleccione Firmante--</option>

                @foreach ($cargos as $cargo)
                    <option value="{{ $cargo->CARGO_ID }}">{{ $cargo->CARGO_NOMBRE }}</option>
                @endforeach

            </select>

            @error('CARGO_ID')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="mb-3">
            <label for="FACULTAD_ID" class="form-label"><i class="fa-solid fa-book-bookmark"></i> Facultad:</label>
            <select id="FACULTAD_ID" name="FACULTAD_ID" class="form-control @error('FACULTAD_ID') is-invalid @enderror" required>
                <option value="" selected>--Seleccione Facultad--</option>


                @foreach ($facultades as $facultad)
                    <option value="{{ $facultad->FACULTAD_ID }}">{{ $facultad->FACULTAD_NOMBRE }}</option>
                @endforeach

            </select>

            @error('FACULTAD_ID')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="DELEGADO_ID" class="form-label"><i class="fa-solid fa-book-bookmark"></i> Delegado:</label>
            <select id="DELEGADO_ID" name="DELEGADO_ID" class="form-control @error('DELEGADO_ID') is-invalid @enderror" required>
                <option value="" selected>--Seleccione Delegado--</option>
                
                @foreach ($cargos as $cargo)
                    <option value="{{ $cargo->CARGO_ID }}">{{ $cargo->CARGO_NOMBRE }}</option>
                @endforeach

            </select>

            @error('DELEGADO_ID')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="RESOLUCION_OBSERVACIONES" class="form-label"><i class="fa-solid fa-book-bookmark"></i> Observaciones:</label>
            <input type="text" class="form-control{{ $errors->has('RESOLUCION_OBSERVACIONES') ? ' is-invalid' : '' }}" id="RESOLUCION_OBSERVACIONES" name="RESOLUCION_OBSERVACIONES" value="{{ old('RESOLUCION_OBSERVACIONES') }}" placeholder="Ej: Autoriza Resolución '1024'" required>
            @if ($errors->has('RESOLUCION_OBSERVACIONES'))
                <div class="invalid-feedback">
                    {{ $errors->first('RESOLUCION_OBSERVACIONES') }}
                </div>
            @endif
        </div>
        <div class="mb-3">
            <label for="RESOLUCION_DOCUMENTO" class="form-label"><i class="fa-solid fa-book-bookmark"></i> Documento:</label>
            <input type="file" name="RESOLUCION_DOCUMENTO" id="RESOLUCION_DOCUMENTO" class="form-control{{ $errors->has('RESOLUCION_DOCUMENTO') ? ' is-invalid' : '' }}">
            @error('RESOLUCION_DOCUMENTO')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <a href="{{ route('resoluciones.index') }}" class="btn btn-secondary" tabindex="5"><i class="fa-solid fa-hand-point-left"></i> Cancelar</a>
        <button type="submit" class="btn btn-primary" tabindex="4"><i class="fa-solid fa-floppy-disk"></i> Registrar</button>
    </form>
</div>
@stop


@section('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@stop

@section('js')
    <!-- CONEXION FONT-AWESOME CON TOOLKIT -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>
    <script>
        $(function () {
            let fechaActual = new Date();

            // Calcular la fecha de inicio (un año atrás)
            let fechaInicio = new Date();
            fechaInicio.setFullYear(fechaInicio.getFullYear() - 1);

            // Calcular la fecha de fin (un mes adelante)
            let fechaFin = new Date();
            fechaFin.setMonth(fechaFin.getMonth() + 1);

            $('#RESOLUCION_FECHA').flatpickr({
                locale: 'es',
                minDate: fechaInicio.toISOString().split("T")[0],
                maxDate: fechaFin.toISOString().split("T")[0],
                dateFormat: "Y-m-d",
                altFormat: "d-m-Y",
                altInput: true,
                allowInput: true,
            });
        });
    </script>
@stop
