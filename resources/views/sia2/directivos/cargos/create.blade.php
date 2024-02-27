@extends('adminlte::page')

@section('title', 'Ingreso de Cargo')

@section('content_header')
    <h1>Ingresar Cargo</h1>
@stop

@section('content')
<div class="container">
    <form action="{{ route('cargos.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="CARGO_NOMBRE" class="form-label"><i class="fa-solid fa-book-bookmark"></i> Nombre del cargo:</label>
            <input type="text" class="form-control{{ $errors->has('CARGO_NOMBRE') ? ' is-invalid' : '' }}" id="CARGO_NOMBRE" name="CARGO_NOMBRE" value="{{ old('CARGO_NOMBRE') }}" placeholder="Ej: JEFE DE DEPARTAMENTO" required>
            @if ($errors->has('CARGO_NOMBRE'))
                <div class="invalid-feedback">
                    {{ $errors->first('CARGO_NOMBRE') }}
                </div>
            @endif
        </div>

        <div class="mb-3 form-group">
            <label for="DIRECCION_ID"><i class="fa-solid fa-book-bookmark"></i> Direccion Regional asociada:</label>
            <input type="text" class="form-control{{ $errors->has('DIRECCION_ID') ? ' is-invalid' : '' }}" id="DIRECCION_ID" name="DIRECCION_ID" value="{{ old('DIRECCION_ID', $direccion) }}" placeholder="Ej: JEFE DE DEPARTAMENTO" disabled>
            @if ($errors->has('DIRECCION_ID'))
                <div class="invalid-feedback">
                    {{ $errors->first('DIRECCION_ID') }}
                </div>
            @endif
        </div>

        <a href="{{route('cargos.index')}}" class="btn btn-secondary" tabindex="5"><i class="fa-solid fa-hand-point-left"></i> Cancelar</a>
        <button type="submit" class="btn btn-primary" tabindex="4"><i class="fa-solid fa-floppy-disk"></i> Guardar cargo</button>
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

