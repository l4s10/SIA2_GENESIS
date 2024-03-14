@extends('adminlte::page')

@section('title', 'Editar comuna')

@section('content_header')
    <h1>Editar Comuna</h1>
@stop

@section('content')
    <div class="container">
        <form action="{{route('panel.comunas.update',$comuna->COMUNA_ID)}}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="COMUNA_NOMBRE" class="form-label"><i class="fa-solid fa-book-bookmark"></i> Nombre de la comuna:</label>
                <input id="COMUNA_NOMBRE" name="COMUNA_NOMBRE" type="text" class="form-control{{$errors->has('COMUNA_NOMBRE') ? ' is-invalid' : '' }}" value="{{$comuna->COMUNA_NOMBRE}}" required>
                @if($errors->has('COMUNA_NOMBRE'))
                <div class="invalid-feedback">
                    {{$errors->first('COMUNA_NOMBRE')}}
                </div>
                @endif
            </div>
            <div class="mb-3">
                <label for="REGION_ID" class="form-label"><i class="fa-solid fa-person-chalkboard"></i> Región asociada:</label>
                <select id="REGION_ID" name="REGION_ID" class="form-control @error('REGION_ID') is-invalid @enderror" required>
                    <option value="" >Selecciona una región</option>
                    @foreach($regiones as $region)
                        <option value="{{ $region->REGION_ID }}" {{ old('REGION_ID', $comuna->REGION_ID) == $region->REGION_ID ? 'selected' : '' }}>{{ $region->REGION_NOMBRE }}</option>
                    @endforeach
                </select>
                @error('REGION_ID')
                    <div class="invalid-feedback">{{ $message }}</div>
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


