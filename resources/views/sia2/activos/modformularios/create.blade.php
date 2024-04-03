@extends('adminlte::page')

@section('title', 'Ingreso de formulario')

@section('content_header')
    <h1>Ingresar formulario</h1>
@stop

@section('content')
    <div class="container">
        <form action="{{ route('formularios.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="FORMULARIO_NOMBRE" class="form-label"><i class="fa-solid fa-person-chalkboard"></i> Nombre Tipo:</label>
                <input id="FORMULARIO_NOMBRE" name="FORMULARIO_NOMBRE" type="text" class="form-control @error('FORMULARIO_NOMBRE') is-invalid @enderror" value="{{ old('FORMULARIO_NOMBRE') }}" placeholder="Ej: 22895, F0024, F1575" tabindex="2" required>
                @error('FORMULARIO_NOMBRE')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="FORMULARIO_TIPO" class="form-label"><i class="fa-solid fa-person-chalkboard @error('FORMULARIO_TIPO') is-invalid @enderror" value="{{ old('FORMULARIO_TIPO') }}" ></i> Tipo de formulario:</label>
                <select class="form-control" name="FORMULARIO_TIPO" id="FORMULARIO_TIPO" required>
                    <option value="" selected>-- Seleccione un tipo de formulario --</option>
                    <option value="TIPO A">TIPO A</option>
                    <option value="TIPO B">TIPO B</option>
                    <option value="TIPO C">TIPO C</option>
                    <option value="OTRO">OTRO</option>
                </select>
                @error('FORMULARIO_TIPO')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <a href="{{ route('formularios.index') }}" class="btn btn-secondary" tabindex="5"><i class="fa-solid fa-hand-point-left"></i> Cancelar</a>
            <button type="submit" class="btn guardar" tabindex="4"><i class="fa-solid fa-floppy-disk"></i> Guardar formulario </button>
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
