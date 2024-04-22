@extends('adminlte::page')

@section('title', 'Editar Tipo')

@section('content_header')
    <h1>Editar Tipo Vehículo</h1>
@stop

@section('content')
    <div class="container">
        <form action="{{ route('tiposvehiculos.update', $tipoVehiculo->TIPO_VEHICULO_ID) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="TIPO_VEHICULO_NOMBRE" class="form-label"><i class="fa-solid fa-person-chalkboard"></i> Nombre Tipo:</label>
                <input id="TIPO_VEHICULO_NOMBRE" name="TIPO_VEHICULO_NOMBRE" type="text" class="form-control @error('TIPO_VEHICULO_NOMBRE') is-invalid @enderror" value="{{ $tipoVehiculo->TIPO_VEHICULO_NOMBRE }}">
                @error('TIPO_VEHICULO_NOMBRE')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="TIPO_VEHICULO_CAPACIDAD"></label>
                <input id="TIPO_VEHICULO_CAPACIDAD" name="TIPO_VEHICULO_CAPACIDAD" type="number" class="form-control @error('TIPO_VEHICULO_CAPACIDAD') is-invalid @enderror" value="{{ $tipoVehiculo->TIPO_VEHICULO_CAPACIDAD }}">
                @error('TIPO_VEHICULO_CAPACIDAD')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <a href="{{ route('tiposvehiculos.index') }}" class="btn btn-secondary" tabindex="5"><i class="fa-solid fa-hand-point-left"></i> Cancelar</a>
            <button type="submit" class="btn guardar" tabindex="4"><i class="fa-solid fa-floppy-disk"></i> Guardar </button>
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
