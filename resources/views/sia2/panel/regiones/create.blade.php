@extends('adminlte::page')

@section('title', 'Ingreso de Region')

@section('content_header')
    <h1>Ingresar Región</h1>
@stop

@section('content')
<div class="container">
    <form action="{{ route('panel.regiones.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="REGION_NOMBRE_NOMBRE" class="form-label"><i class="fa-solid fa-book-bookmark"></i> Nombre de la región:</label>
            <input type="text" class="form-control{{ $errors->has('REGION_NOMBRE') ? ' is-invalid' : '' }}" id="REGION_NOMBRE" name="REGION_NOMBRE" value="{{ old('REGION_NOMBRE') }}" placeholder="Ej: VIII DIRECCION REGION_NOMBREAL CONCEPCION" required>
            @if ($errors->has('REGION_NOMBRE'))
                <div class="invalid-feedback">
                    {{ $errors->first('REGION_NOMBRE') }}
                </div>
            @endif
        </div>

        <a href="{{route('panel.regiones.index')}}" class="btn btn-secondary" tabindex="5"><i class="fa-solid fa-hand-point-left"></i> Cancelar</a>
        <button type="submit" class="btn btn-primary" tabindex="4"><i class="fa-solid fa-floppy-disk"></i> Guardar</button>
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

