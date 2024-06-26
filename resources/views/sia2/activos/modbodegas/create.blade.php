@extends('adminlte::page')

@section('title', 'Ingresar Bodega')

@section('content_header')
    <h1>Ingresar Bodega</h1>
@stop

@section('content')
<div class="container">
    <form action="{{ route('bodegas.store') }}" method="POST">
        @csrf


        <div class="mb-3">
            <label for="OFICINA" class="form-label"><i class="fa-solid fa-map-location-dot"></i> Dirección Regional </label>
            <select id="OFICINA" class="form-control" name="OFICINA" required disabled>
                <option value="{{ $oficinaAsociada->OFICINA_ID }}">{{ $oficinaAsociada->OFICINA_NOMBRE }}</option>
            </select>
            @error('ID_REGION')
                <div class="alert alert-danger mt-2">{{ $message }}</div>
            @enderror
        </div>


        <div class="mb-3">
            <label for="BODEGA_NOMBRE" class="form-label"><i class="fas fa-book-bookmark"></i> Nombre de la bodega:</label>
            <input type="text" class="form-control{{ $errors->has('BODEGA_NOMBRE') ? ' is-invalid' : '' }}" id="BODEGA_NOMBRE" name="BODEGA_NOMBRE" value="{{ old('BODEGA_NOMBRE') }}" placeholder="BODEGA N°4" maxlength="40" required>
            @if ($errors->has('BODEGA_NOMBRE'))
                <div class="invalid-feedback">
                    {{ $errors->first('BODEGA_NOMBRE') }}
                </div>
            @endif
        </div>

        <div class="mb-3">
            <label for="BODEGA_ESTADO" class="form-label"><i class="fas fa-person-chalkboard"></i> Estado:</label>
            <select class="form-control{{ $errors->has('BODEGA_ESTADO') ? ' is-invalid' : '' }}" aria-label="Seleccione el estado" id="BODEGA_ESTADO" name="BODEGA_ESTADO" required>
                <option value="" selected>-- Seleccione un estado --</option>
                <option value="DISPONIBLE">DISPONIBLE</option>
                <option value="NO DISPONIBLE">NO DISPONIBLE</option>
                <option value="DESABILITADO">DESABILITADO</option>
            </select>
            @if ($errors->has('BODEGA_ESTADO'))
                <div class="invalid-feedback">
                    {{ $errors->first('BODEGA_ESTADO') }}
                </div>
            @endif
        </div>

        <a href="{{route('bodegas.index')}}" class="btn btn-secondary" tabindex="5"><i class="fas fa-hand-point-left"></i> Cancelar</a>
        <button type="submit" class="btn guardar"><i class="fas fa-floppy-disk"></i> Guardar Bodega</button>
    </form>
</div>
@stop

@section('css')
    <style>/* Estilos personalizados si es necesario */
        .guardar {
            background-color: #e6500a;
            color: #fff;
        }
    </style>
@stop

@section('js')
    {{-- Script cooldown envio formulario (evita entradas repetidas) --}}
    <script src="{{ asset('js/Components/cooldownSendForm.js') }}"></script>
@stop
