@extends('adminlte::page')

@section('title', 'Ingreso de Facultad')

@section('content_header')
    <h1>Ingresar Facultad</h1>
@stop

@section('content')
<div class="container">
    <form action="{{ route('facultades.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="FACULTAD_NUMERO" class="form-label"><i class="fa-solid fa-book-bookmark"></i> Número de Facultad:</label>
            <input type="number" class="form-control{{ $errors->has('FACULTAD_NUMERO') ? ' is-invalid' : '' }}" id="FACULTAD_NUMERO" name="FACULTAD_NUMERO" value="{{ old('FACULTAD_NUMERO') }}" min="0" max="9999" placeholder="Ej: 12345" required>
            @if ($errors->has('FACULTAD_NUMERO'))
                <div class="invalid-feedback">
                    {{ $errors->first('FACULTAD_NUMERO') }}
                </div>
            @endif
        </div>

        <div class="mb-3">
            <label for="FACULTAD_NOMBRE" class="form-label"><i class="fa-solid fa-book-bookmark"></i> Nombre:</label>
            <input type="text" class="form-control{{ $errors->has('FACULTAD_NOMBRE') ? ' is-invalid' : '' }}" id="FACULTAD_NOMBRE" name="FACULTAD_NOMBRE" value="{{ old('FACULTAD_NOMBRE') }}" placeholder="Ej: APLICAR SANCIONES ADMINISTRATIVAS" required>
            @if ($errors->has('FACULTAD_NOMBRE'))
                <div class="invalid-feedback">
                    {{ $errors->first('FACULTAD_NOMBRE') }}
                </div>
            @endif
        </div>
        

        <div class="mb-3">
            <label for="FACULTAD_CONTENIDO" class="form-label"><i class="fa-solid fa-book-bookmark"></i> Contenido:</label>
            <input type="text" class="form-control{{ $errors->has('FACULTAD_CONTENIDO') ? ' is-invalid' : '' }}" id="FACULTAD_CONTENIDO" name="FACULTAD_CONTENIDO" value="{{ old('FACULTAD_CONTENIDO') }}" placeholder="Ej: La facultad de aplicar las sanciones..." required>
            @if ($errors->has('FACULTAD_CONTENIDO'))
                <div class="invalid-feedback">
                    {{ $errors->first('FACULTAD_CONTENIDO') }}
                </div>
            @endif
        </div>

        <div class="mb-3">
            <label for="FACULTAD_LEY_ASOCIADA" class="form-label"><i class="fa-solid fa-book-bookmark"></i> Ley asociada: </label>
            <input type="text" class="form-control{{ $errors->has('FACULTAD_LEY_ASOCIADA') ? ' is-invalid' : '' }}" id="FACULTAD_LEY_ASOCIADA" name="FACULTAD_LEY_ASOCIADA" value="{{ old('FACULTAD_LEY_ASOCIADA') }}" placeholder="Ej: Código Tributario" required>
            @if ($errors->has('FACULTAD_LEY_ASOCIADA'))
                <div class="invalid-feedback">
                    {{ $errors->first('FACULTAD_LEY_ASOCIADA') }}
                </div>
            @endif
        </div>

        <div class="mb-3">
            <label for="FACULTAD_ART_LEY_ASOCIADA" class="form-label"><i class="fa-solid fa-book-bookmark"></i> Art. de ley asociada:</label>
            <input type="text" class="form-control{{ $errors->has('FACULTAD_ART_LEY_ASOCIADA') ? ' is-invalid' : '' }}" id="FACULTAD_NOMBRE" name="FACULTAD_ART_LEY_ASOCIADA" value="{{ old('FACULTAD_ART_LEY_ASOCIADA') }}" placeholder="Ej: 165" required>
            @if ($errors->has('FACULTAD_ART_LEY_ASOCIADA'))
                <div class="invalid-feedback">
                    {{ $errors->first('FACULTAD_ART_LEY_ASOCIADA') }}
                </div>
            @endif
        </div>



        <a href="{{route('facultades.index')}}" class="btn btn-secondary" tabindex="5"><i class="fa-solid fa-hand-point-left"></i> Cancelar</a>
        <button type="submit" class="btn btn-primary" tabindex="4"><i class="fa-solid fa-floppy-disk"></i> Registrar</button>
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

