@extends('adminlte::page')

@section('title', 'Ingreso de Tipos')

@section('content_header')
    <h1>Ingresar Tipo de Vehículo</h1>
@stop

@section('content')
    <div class="container">
        <form action="{{ route('tiposvehiculos.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="TIPO_VEHICULO_NOMBRE" class="form-label"><i class="fa-solid fa-person-chalkboard"></i> Nombre Tipo:</label>
                <input id="TIPO_VEHICULO_NOMBRE" name="TIPO_VEHICULO_NOMBRE" type="text" class="form-control @error('TIPO_VEHICULO_NOMBRE') is-invalid @enderror" value="{{ old('TIPO_VEHICULO_NOMBRE') }}" placeholder="Ej: Sedan" tabindex="2" required>
                @error('TIPO_VEHICULO_NOMBRE')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="TIPO_VEHICULO_CAPACIDAD"><i class="fa-solid fa-car"></i> Capacidad del tipo de vehículo:</label>
                <input id="TIPO_VEHICULO_CAPACIDAD" name="TIPO_VEHICULO_CAPACIDAD" type="number" class="form-control @error('TIPO_VEHICULO_CAPACIDAD') is-invalid @enderror" value="{{ old('TIPO_VEHICULO_CAPACIDAD') }}" placeholder="Ej: 5" tabindex="3" required>
                @error('TIPO_VEHICULO_CAPACIDAD')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <a href="{{ route('tiposvehiculos.index') }}" class="btn btn-secondary" tabindex="5"><i class="fa-solid fa-hand-point-left"></i> Cancelar</a>
            <button type="submit" class="btn guardar" tabindex="4"><i class="fa-solid fa-floppy-disk"></i> Guardar tipo de vehículo </button>
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
