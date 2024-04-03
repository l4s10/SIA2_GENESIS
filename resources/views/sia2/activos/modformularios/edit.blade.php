@extends('adminlte::page')

@section('title', 'Editar Formulario')

@section('content_header')
    <h1>Editar formulario {{$formulario->FORMULARIO_NOMBRE}}</h1>
@stop

@section('content')
    <div class="container">
        <form action="{{ route('formularios.update', $formulario->FORMULARIO_ID) }}" method="POST">
            @csrf
            @method('PUT')
            {{-- Campo nombre del formulario (captura el nombre desde el controlador, en caso de errores se conservara el nombre a modificar) --}}
            <div class="mb-3">
                <label for="FORMULARIO_NOMBRE" class="form-label"><i class="fa-solid fa-person-chalkboard"></i> Nombre Tipo:</label>
                <input id="FORMULARIO_NOMBRE" name="FORMULARIO_NOMBRE" type="text" class="form-control @error('FORMULARIO_NOMBRE') is-invalid @enderror" value="{{ old('FORMULARIO_NOMBRE', $formulario->FORMULARIO_NOMBRE) }}" placeholder="Ej: 22895, F0024, F1575" tabindex="2" required>
                @error('FORMULARIO_NOMBRE')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            {{-- Campo tipo de formulario (captura el tipo desde el controlador, en caso de errores se conservara el tipo a modificar) --}}
            <div class="mb-3">
                <label for="FORMULARIO_TIPO" class="form-label"><i class="fa-solid fa-person-chalkboard @error('FORMULARIO_TIPO') is-invalid @enderror"></i> Tipo de formulario:</label>
                <select class="form-control" name="FORMULARIO_TIPO" id="FORMULARIO_TIPO" required>
                    <option value="" {{ old('FORMULARIO_TIPO', $formulario->FORMULARIO_TIPO) == '' ? 'selected' : '' }}>-- Seleccione un tipo de formulario --</option>
                    <option value="TIPO A" {{ old('FORMULARIO_TIPO', $formulario->FORMULARIO_TIPO) == 'TIPO A' ? 'selected' : '' }}>TIPO A</option>
                    <option value="TIPO B" {{ old('FORMULARIO_TIPO', $formulario->FORMULARIO_TIPO) == 'TIPO B' ? 'selected' : '' }}>TIPO B</option>
                    <option value="TIPO C" {{ old('FORMULARIO_TIPO', $formulario->FORMULARIO_TIPO) == 'TIPO C' ? 'selected' : '' }}>TIPO C</option>
                    <option value="OTRO" {{ old('FORMULARIO_TIPO', $formulario->FORMULARIO_TIPO) == 'OTRO' ? 'selected' : '' }}>OTRO</option>
                </select>
                @error('FORMULARIO_TIPO')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <a href="{{ route('formularios.index') }}" class="btn btn-secondary" tabindex="5"><i class="fa-solid fa-hand-point-left"></i> Cancelar</a>
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
