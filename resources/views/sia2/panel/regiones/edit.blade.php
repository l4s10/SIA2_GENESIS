@extends('adminlte::page')

<!-- TITULO DE LA PESTAÑA -->
@section('title', 'Modificar Region')

<!-- CABECERA DE LA PAGINA -->
@section('content_header')
    <h1>Modificar Región</h1>
@stop

@section('content')
<div class="container">
    <form action="{{route('panel.regiones.update',$region->REGION_ID)}}" method="POST">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col">
                <div class="form-group">
                    <label for="REGION_NOMBRE"><i class="fa-solid fa-book-bookmark"></i> Nombre región:</label>
                    <input type="text" name="REGION_NOMBRE" id="REGION_NOMBRE" class="form-control{{ $errors->has('REGION_NOMBRE') ? ' is-invalid' : '' }}" placeholder="Nombre de la region" value="{{ $region->REGION_NOMBRE }}" required autofocus>
                    @if ($errors->has('REGION_NOMBRE'))
                    <div class="invalid-feedback">
                        {{ $errors->first('REGION_NOMBRE') }}
                    </div>
                @endif
                </div>
            </div>
        </div>
        <div class="form-group">
            <a href="{{ route('panel.regiones.index') }}" class="btn btn-secondary" tabindex="5"><i class="fa-solid fa-hand-point-left"></i> Cancelar</a>
            <button type="submit" class="btn btn-primary" tabindex="4"><i class="fa-solid fa-floppy-disk"></i> Guardar</button>
        </div>
    </form>
</div>
@endsection

@section('css')
@endsection

@section('js')

@endsection
