@extends('adminlte::page')

@section('title', 'Editar Sala o Bodega')

@section('content_header')
    <h1>Editar Sala o Bodega</h1>
@stop

@section('content')
<div class="container">
    <form action="{{ route('salasobodegas.update', $salaobodega->SALA_O_BODEGA_ID) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="SALA_O_BODEGA_NOMBRE" class="form-label"><i class="fas fa-book-bookmark"></i> Nombre de la sala o bodega:</label>
            <input type="text" class="form-control{{ $errors->has('SALA_O_BODEGA_NOMBRE') ? ' is-invalid' : '' }}" id="SALA_O_BODEGA_NOMBRE" name="SALA_O_BODEGA_NOMBRE" value="{{ old('SALA_O_BODEGA_NOMBRE', $salaobodega->SALA_O_BODEGA_NOMBRE) }}" required>
            @if ($errors->has('SALA_O_BODEGA_NOMBRE'))
                <div class="invalid-feedback">
                    {{ $errors->first('SALA_O_BODEGA_NOMBRE') }}
                </div>
            @endif
        </div>

        <div class="mb-3">
            <label for="SALA_O_BODEGA_TIPO" class="form-label"><i class="fas fa-person-chalkboard"></i> Tipo:</label>
            <select class="form-control{{ $errors->has('SALA_O_BODEGA_TIPO') ? ' is-invalid' : '' }}" aria-label="Seleccione el tipo de sala" id="SALA_O_BODEGA_TIPO" name="SALA_O_BODEGA_TIPO" required>
                <option value="SALA" {{ old('SALA_O_BODEGA_TIPO', $salaobodega->SALA_O_BODEGA_TIPO) == 'SALA' ? 'selected' : '' }}>SALA</option>
                <option value="BODEGA" {{ old('SALA_O_BODEGA_TIPO', $salaobodega->SALA_O_BODEGA_TIPO) == 'BODEGA' ? 'selected' : '' }}>BODEGA</option>
            </select>
            @if ($errors->has('SALA_O_BODEGA_TIPO'))
                <div class="invalid-feedback">
                    {{ $errors->first('SALA_O_BODEGA_TIPO') }}
                </div>
            @endif
        </div>

        <div class="mb-3">
            <label for="SALA_O_BODEGA_CAPACIDAD" class="form-label"><i class="fas fa-person-shelter"></i> Capacidad personas:</label>
            <input type="number" class="form-control{{ $errors->has('SALA_O_BODEGA_CAPACIDAD') ? ' is-invalid' : '' }}" id="SALA_O_BODEGA_CAPACIDAD" name="SALA_O_BODEGA_CAPACIDAD" value="{{ old('SALA_O_BODEGA_CAPACIDAD', $salaobodega->SALA_O_BODEGA_CAPACIDAD) }}" min="1" max="200" required>
            @if ($errors->has('SALA_O_BODEGA_CAPACIDAD'))
                <div class="invalid-feedback">
                    {{ $errors->first('SALA_O_BODEGA_CAPACIDAD') }}
                </div>
            @endif
        </div>

        <div class="mb-3">
            <label for="SALA_O_BODEGA_ESTADO" class="form-label"><i class="fas fa-person-chalkboard"></i> Estado:</label>
            <select class="form-control{{ $errors->has('SALA_O_BODEGA_ESTADO') ? ' is-invalid' : '' }}" aria-label="Seleccione el tipo de sala" id="SALA_O_BODEGA_ESTADO" name="SALA_O_BODEGA_ESTADO" required>
                <option value="" selected>--Seleccione un estado--</option>
                <option value="DISPONIBLE" {{ old('SALA_O_BODEGA_ESTADO', $salaobodega->SALA_O_BODEGA_ESTADO) == 'DISPONIBLE' ? 'selected' : '' }}>DISPONIBLE</option>
                <option value="OCUPADO" {{ old('SALA_O_BODEGA_ESTADO', $salaobodega->SALA_O_BODEGA_ESTADO) == 'OCUPADO' ? 'selected' : '' }}>OCUPADO</option>
                <option value="DESABILITADO" {{ old('SALA_O_BODEGA_ESTADO', $salaobodega->SALA_O_BODEGA_ESTADO) == 'DESABILITADO' ? 'selected' : '' }}>DESABILITADO</option>
            </select>
            @if ($errors->has('SALA_O_BODEGA_ESTADO'))
                <div class="invalid-feedback">
                    {{ $errors->first('SALA_O_BODEGA_ESTADO') }}
                </div>
            @endif
        </div>

        <a href="{{ route('salasobodegas.index') }}" class="btn btn-secondary" tabindex="5"><i class="fas fa-hand-point-left"></i> Cancelar</a>
        <button type="submit" class="btn guardar"><i class="fas fa-floppy-disk"></i> Guardar </button>
    </form>
</div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
    <style>/* Estilos personalizados si es necesario */
        .guardar {
            background-color: #e6500a;
            color: #fff;
        }
    </style>
@stop

@section('js')
    <!-- CONEXION FONT-AWESOME CON TOOLKIT -->
    <script src="https://kit.fontawesome.com/742a59c628.js" crossorigin="anonymous"></script>
@stop
