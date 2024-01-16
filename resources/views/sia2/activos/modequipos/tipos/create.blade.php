@extends('adminlte::page')

@section('title', 'Ingreso de Tipos')

@section('content_header')
    <h1>Ingresar Tipo de Equipo</h1>
@stop

@section('content')
    <div class="container">
        <form action="{{ route('tiposequipos.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="TIPO_EQUIPO_NOMBRE" class="form-label"><i class="fa-solid fa-person-chalkboard"></i> Nombre Tipo:</label>
                <input id="TIPO_EQUIPO_NOMBRE" name="TIPO_EQUIPO_NOMBRE" type="text" class="form-control @error('TIPO_EQUIPO_NOMBRE') is-invalid @enderror" value="{{ old('TIPO_EQUIPO_NOMBRE') }}" placeholder="Ej: DATA, COMPUTADOR, PIZZARRA" tabindex="2" required>
                @error('TIPO_EQUIPO_NOMBRE')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <a href="{{ route('tiposequipos.index') }}" class="btn btn-secondary" tabindex="5"><i class="fa-solid fa-hand-point-left"></i> Cancelar</a>
            <button type="submit" class="btn btn-primary" tabindex="4"><i class="fa-solid fa-floppy-disk"></i> Guardar tipo</button>
        </form>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <!-- CONEXION FONT-AWESOME CON TOOLKIT -->
    <script src="https://kit.fontawesome.com/742a59c628.js" crossorigin="anonymous"></script>
@stop