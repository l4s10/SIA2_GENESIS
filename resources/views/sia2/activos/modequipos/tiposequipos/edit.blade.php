@extends('adminlte::page')

@section('title', 'Editar Tipo')

@section('content_header')
    <h1>Editar Tipo Equipo</h1>
@stop

@section('content')
    <div class="container">
        <form action="{{ route('tiposequipos.update', $tipoEquipo->TIPO_EQUIPO_ID) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="TIPO_EQUIPO_NOMBRE" class="form-label"><i class="fa-solid fa-person-chalkboard"></i> Nombre Tipo:</label>
                <input id="TIPO_EQUIPO_NOMBRE" name="TIPO_EQUIPO_NOMBRE" type="text" class="form-control @error('TIPO_EQUIPO_NOMBRE') is-invalid @enderror" value="{{ $tipoEquipo->TIPO_EQUIPO_NOMBRE }}">
                @error('TIPO_EQUIPO_NOMBRE')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="OFICINA_ID" class="form-label"><i class="fa-solid fa-person-chalkboard"></i> Dirección Regional Asociada:</label>
                <input type="text" id="OFICINA_ID" name="OFICINA_ID" class="form-control" value="{{ $oficina->OFICINA_NOMBRE }}" readonly>
                <input type="hidden" name="OFICINA_ID" value="{{ $oficina->OFICINA_ID }}">
            </div>

            <a href="{{ route('tiposequipos.index') }}" class="btn btn-secondary" tabindex="5"><i class="fa-solid fa-hand-point-left"></i> Cancelar</a>
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
