@extends('adminlte::page')

@section('title', 'Ingreso de Comuna')

@section('content_header')
    <h1>Ingresar Comuna</h1>
@stop

@section('content')
<div class="container">
    <form action="{{ route('panel.comunas.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="COMUNA_NOMBRE" class="form-label"><i class="fa-solid fa-book-bookmark"></i> Nombre de la comuna:</label>
            <input type="text" class="form-control{{ $errors->has('COMUNA_NOMBRE') ? ' is-invalid' : '' }}" id="COMUNA_NOMBRE" name="COMUNA_NOMBRE" value="{{ old('COMUNA_NOMBRE') }}" placeholder="Ej: CONCEPCION" required>
            @if ($errors->has('COMUNA_NOMBRE'))
                <div class="invalid-feedback">
                    {{ $errors->first('COMUNA_NOMBRE') }}
                </div>
            @endif
        </div>

        <div class="mb-3 form-group">
            <label for="REGION_ID"><i class="fa-solid fa-book-bookmark"></i> Región asociada:</label>
            <select name="REGION_ID" id="REGION_ID" class="form-control @error('REGION_ID') is-invalid @enderror" required>
                <option value="" disabled selected>-- Seleccione una región --</option>
                @foreach($regiones as $region)
                    <option value="{{ $region->REGION_ID }}" {{ old('REGION_ID') == $region->REGION_ID ? 'selected' : '' }}>{{ $region->REGION_NOMBRE }}</option>
                @endforeach
            </select>
            @error('REGION_ID')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
       

        <a href="{{route('panel.comunas.index')}}" class="btn btn-secondary" tabindex="5"><i class="fa-solid fa-hand-point-left"></i> Cancelar</a>
        <button type="submit" class="btn btn-primary" tabindex="4"><i class="fa-solid fa-floppy-disk"></i> Guardar comuna</button>
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


