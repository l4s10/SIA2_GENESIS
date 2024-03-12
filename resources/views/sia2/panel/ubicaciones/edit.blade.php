@extends('adminlte::page')

<!-- TITULO DE LA PESTAÑA -->
@section('title', 'Editar Ubicación')

<!-- CABECERA DE LA PAGINA -->
@section('content_header')
    <h1>Editar Ubicación</h1>
@stop

@section('content')
<div class="container">
    <form action="{{route('panel.ubicaciones.update',$ubicacion->UBICACION_ID)}}" method="POST">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col">
                <div class="form-group">
                    <label for="UBICACION_NOMBRE"><i class="fa-solid fa-book-bookmark"></i> Nombre:</label>
                    <input type="text" name="UBICACION_NOMBRE" id="UBICACION_NOMBRE" class="form-control{{ $errors->has('UBICACION_NOMBRE') ? ' is-invalid' : '' }}" placeholder="Nombre de la dirección regional" placeholder="Ej: DEPARTAMENTO DE ADMINISTRACION / UNIDAD DE CONCEPCIÓN" value="{{ $ubicacion->UBICACION_NOMBRE }}" required autofocus>

                    @error('UBICACION_NOMBRE')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
        </div>

        {{-- OPCION QUE PERMITE SELECCIONAR DIRECCION REGIONAL (PARA REGION METROPOLITANA) --}}
        <div class="mb-3 form-group">
            <label for="OFICINA_ID"><i class="fa-solid fa-book-bookmark"></i> Dirección Regional asociada:</label>
            <select name="OFICINA_ID" id="OFICINA_ID" class="form-control">
                @foreach($oficinas as $oficina)
                    <option value="{{ $oficina->OFICINA_ID }}" {{ $ubicacion->OFICINA_ID == $oficina->OFICINA_ID ? 'selected' : '' }}>
                        {{ $oficina->OFICINA_NOMBRE }}
                    </option>
                @endforeach
            </select>
        </div>
        
        <div class="form-group">
            <a href="{{route('panel.ubicaciones.index')}}" class="btn btn-secondary">Cancelar</a>
            <button type="submit" class="btn btn-primary">Guardar cambios</button>
        </div>
    </form>
</div>
@endsection

@section('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endsection

@section('js')
    <!-- Incluir archivos JS flatpicker-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>
    
@endsection
