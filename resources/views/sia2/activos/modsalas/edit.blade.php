@extends('adminlte::page')

@section('title', 'Editar Sala')

@section('content_header')
    <h1>Editar Sala</h1>
@stop

@section('content')
<div class="container">
    <form action="{{ route('salas.update', $sala->SALA_ID) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="col-md-6">
            <div class="mb-3">
                <label for="OFICINA" class="form-label"><i class="fa-solid fa-map-location-dot"></i> Dirección Regional </label>
                <input type="text" id="OFICINA" class="form-control" name="OFICINA" value="{{ $oficinaAsociada->OFICINA_NOMBRE }}" required readonly>
                @error('ID_REGION')
                    <div class="alert alert-danger mt-2">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="mb-3">
            <label for="SALA_NOMBRE" class="form-label"><i class="fas fa-book-bookmark"></i> Nombre de la sala:</label>
            <input type="text" class="form-control{{ $errors->has('SALA_NOMBRE') ? ' is-invalid' : '' }}" id="SALA_NOMBRE" name="SALA_NOMBRE" value="{{ old('SALA_NOMBRE', $sala->SALA_NOMBRE) }}" maxlength="40" required>
            @if ($errors->has('SALA_NOMBRE'))
                <div class="invalid-feedback">
                    {{ $errors->first('SALA_NOMBRE') }}
                </div>
            @endif
        </div>


        <div class="mb-3">
            <label for="SALA_CAPACIDAD" class="form-label"><i class="fas fa-person-shelter"></i> Capacidad personas:</label>
            <input type="number" class="form-control{{ $errors->has('SALA_CAPACIDAD') ? ' is-invalid' : '' }}" id="SALA_CAPACIDAD" name="SALA_CAPACIDAD" value="{{ old('SALA_CAPACIDAD', $sala->SALA_CAPACIDAD) }}" min="1" max="200" required>
            @if ($errors->has('SALA_CAPACIDAD'))
                <div class="invalid-feedback">
                    {{ $errors->first('SALA_CAPACIDAD') }}
                </div>
            @endif
        </div>

        <div class="mb-3">
            <label for="SALA_ESTADO" class="form-label"><i class="fas fa-person-chalkboard"></i> Estado:</label>
            <select class="form-control{{ $errors->has('SALA_ESTADO') ? ' is-invalid' : '' }}" aria-label="Seleccione el tipo de sala" id="SALA_ESTADO" name="SALA_ESTADO" required>
                <option disabled value="" selected>-- Seleccione un estado --</option>
                <option value="DISPONIBLE" {{ old('SALA_ESTADO', $sala->SALA_ESTADO) == 'DISPONIBLE' ? 'selected' : '' }}>DISPONIBLE</option>
                <option value="OCUPADO" {{ old('SALA_ESTADO', $sala->SALA_ESTADO) == 'OCUPADO' ? 'selected' : '' }}>OCUPADO</option>
                <option value="DESABILITADO" {{ old('SALA_ESTADO', $sala->SALA_ESTADO) == 'DESABILITADO' ? 'selected' : '' }}>DESABILITADO</option>
            </select>
            @if ($errors->has('SALA_ESTADO'))
                <div class="invalid-feedback">
                    {{ $errors->first('SALA_ESTADO') }}
                </div>
            @endif
        </div>

        <a href="{{ route('salas.index') }}" class="btn btn-secondary" tabindex="5"><i class="fas fa-hand-point-left"></i> Cancelar</a>
        <button type="submit" class="btn guardar"><i class="fas fa-floppy-disk"></i> Guardar</button>
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

