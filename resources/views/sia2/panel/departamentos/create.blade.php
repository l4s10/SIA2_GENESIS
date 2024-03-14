@extends('adminlte::page')

@section('title', 'Ingreso de Departamento')

@section('content_header')
    <h1>Ingresar Departamento</h1>
@stop

@section('content')
<div class="container">
    <form action="{{ route('panel.departamentos.store') }}" method="POST">
        @csrf


        <div class="mb-3">
            <label for="DEPARTAMENTO_NOMBRE" class="form-label"><i class="fa-solid fa-book-bookmark"></i> Nombre:</label>
            <input type="text" class="form-control{{ $errors->has('DEPARTAMENTO_NOMBRE') ? ' is-invalid' : '' }}" id="DEPARTAMENTO_NOMBRE" name="DEPARTAMENTO_NOMBRE" value="{{ old('DEPARTAMENTO_NOMBRE') ?? '' }}" placeholder="Ej: DEPARTAMENTO DE ADMINISTRACIÓN" required>
            @if ($errors->has('DEPARTAMENTO_NOMBRE'))
                <div class="invalid-feedback">
                    {{ $errors->first('DEPARTAMENTO_NOMBRE') }}
                </div>
            @endif
        </div>
        
        {{-- OPCION QUE PERMITE SELECCIONAR DIRECCION REGIONAL (PARA REGION METROPOLITANA) --}}
        <div class="mb-3 form-group">
            <label for="OFICINA_ID"><i class="fa-solid fa-book-bookmark"></i> Dirección Regional asociada:</label>
            <select name="OFICINA_ID" id="OFICINA_ID" class="form-control">
                @foreach($oficinas as $oficina)
                    <option value="{{ $oficina->OFICINA_ID }}" {{ old('OFICINA_ID') == $oficina->OFICINA_ID ? 'selected' : '' }}>
                        {{ $oficina->OFICINA_NOMBRE }}
                    </option>
                @endforeach
            </select>
        </div>
        <a href="{{route('panel.departamentos.index')}}" class="btn btn-secondary" tabindex="5"><i class="fa-solid fa-hand-point-left"></i> Cancelar</a>
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

