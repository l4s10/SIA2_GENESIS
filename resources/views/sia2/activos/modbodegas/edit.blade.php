@extends('adminlte::page')

@section('title', 'Editar Bodega')

@section('content_header')
    <h1>Editar Bodega</h1>
@stop

@section('content')
<div class="container">
    <form action="{{ route('bodegas.update', $bodega->BODEGA_ID) }}" method="POST">
        @csrf
        @method('PUT')


        <div class="col-md-6">
            <div class="mb-3">
                <label for="OFICINA" class="form-label"><i class="fa-solid fa-map-location-dot"></i> Dirección Regional </label>
                <select id="OFICINA" class="form-control" name="OFICINA" required disabled>
                    <option value="{{ $oficinaAsociada->OFICINA_ID }}">{{ $oficinaAsociada->OFICINA_NOMBRE }}</option>
                </select>
                @error('OFICINA')
                    <div class="alert alert-danger mt-2">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="mb-3">
            <label for="BODEGA_NOMBRE" class="form-label"><i class="fas fa-book-bookmark"></i> Nombre de la bodega:</label>
            <input type="text" class="form-control{{ $errors->has('BODEGA_NOMBRE') ? ' is-invalid' : '' }}" id="BODEGA_NOMBRE" name="BODEGA_NOMBRE" value="{{ old('BODEGA_NOMBRE', $bodega->BODEGA_NOMBRE) }}" maxlength="40" required>
            @if ($errors->has('BODEGA_NOMBRE'))
                <div class="invalid-feedback">
                    {{ $errors->first('BODEGA_NOMBRE') }}
                </div>
            @endif
        </div>

        <div class="mb-3">
            <label for="BODEGA_ESTADO" class="form-label"><i class="fas fa-person-chalkboard"></i> Estado:</label>
            <select class="form-control{{ $errors->has('BODEGA_ESTADO') ? ' is-invalid' : '' }}" aria-label="Seleccione el tipo de sala" id="BODEGA_ESTADO" name="BODEGA_ESTADO" required>
                <option disabled value="" selected>-- Seleccione un estado --</option>
                <option value="DISPONIBLE" {{ old('BODEGA_ESTADO', $bodega->BODEGA_ESTADO) == 'DISPONIBLE' ? 'selected' : '' }}>DISPONIBLE</option>
                <option value="OCUPADO" {{ old('BODEGA_ESTADO', $bodega->BODEGA_ESTADO) == 'NO DISPONIBLE' ? 'selected' : '' }}>NO DISPONIBLE</option>
                <option value="DESABILITADO" {{ old('BODEGA_ESTADO', $bodega->BODEGA_ESTADO) == 'DESABILITADO' ? 'selected' : '' }}>DESABILITADO</option>
            </select>
            @if ($errors->has('BODEGA_ESTADO'))
                <div class="invalid-feedback">
                    {{ $errors->first('BODEGA_ESTADO') }}
                </div>
            @endif
        </div>

        <a href="{{ route('bodegas.index') }}" class="btn btn-secondary" tabindex="5"><i class="fas fa-hand-point-left"></i> Cancelar</a>
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

