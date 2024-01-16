@extends('adminlte::page')

@section('title', 'Editar Tipo')

@section('content_header')
    <h1>Editar Tipo Material</h1>
@stop

@section('content')
    <div class="container">
        <form action="{{ route('tiposmateriales.update', $tipoMaterial->TIPO_MATERIAL_ID) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="TIPO_MATERIAL_NOMBRE" class="form-label"><i class="fa-solid fa-person-chalkboard"></i> Nombre Tipo:</label>
                <input id="TIPO_MATERIAL_NOMBRE" name="TIPO_MATERIAL_NOMBRE" type="text" class="form-control @error('TIPO_MATERIAL_NOMBRE') is-invalid @enderror" value="{{ $tipoMaterial->TIPO_MATERIAL_NOMBRE }}">
                @error('TIPO_MATERIAL_NOMBRE')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="OFICINA_ID" class="form-label"><i class="fa-solid fa-person-chalkboard"></i> Direccion Regional:</label>
                <select id="OFICINA_ID" name="OFICINA_ID" class="form-control" disabled>
                    <option value="{{ $oficina->OFICINA_ID }}" selected>{{ $oficina->OFICINA_NOMBRE }}</option>
                </select>
            </div>
            
            <a href="{{ route('tiposmateriales.index') }}" class="btn btn-secondary" tabindex="5"><i class="fa-solid fa-hand-point-left"></i> Cancelar</a>
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
