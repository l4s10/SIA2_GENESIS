@extends('adminlte::page')

<!-- TITULO DE LA PESTAÑA -->
@section('title', 'Modificar Cargo')

<!-- CABECERA DE LA PAGINA -->
@section('content_header')
    <h1>Modificar Cargo</h1>
@stop

@section('content')
<div class="container">
    <form action="{{route('cargos.update',$cargo->CARGO_ID)}}" method="POST">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col">
                <div class="form-group">
                    <label for="CARGO_NOMBRE">Nombre cargo:</label>
                    <input type="text" name="CARGO_NOMBRE" id="CARGO_NOMBRE" class="form-control" placeholder="Nombre del cargo" value="{{ $cargo->CARGO_NOMBRE }}" required autofocus>

                    @error('CARGO_NOMBRE')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
        </div>

        <div class="mb-3 form-group">
            <label for="DIRECCION_ID"><i class="fa-solid fa-book-bookmark"></i> Direccion Regional asociada:</label>
            <input type="text" class="form-control{{ $errors->has('DIRECCION_ID') ? ' is-invalid' : '' }}" 
                   id="DIRECCION_ID" name="DIRECCION_ID" 
                   value="{{ old('DIRECCION_ID', $cargo->oficina->OFICINA_NOMBRE) }}" 
                   placeholder="Ej: JEFE DE DEPARTAMENTO" 
                   readonly>
            @if ($errors->has('DIRECCION_ID'))
                <div class="invalid-feedback">
                    {{ $errors->first('DIRECCION_ID') }}
                </div>
            @endif
        </div>
        
        <div class="form-group">
            <a href="{{route('cargos.index')}}" class="btn btn-secondary"><i class="fa-solid fa-hand-point-left"></i> Cancelar</a>
            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> Modificar cargo</button>
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
