@extends('adminlte::page')

<!-- TITULO DE LA PESTAÑA -->
@section('title', 'Modificar Facultad')

<!-- CABECERA DE LA PAGINA -->
@section('content_header')
    <h1>Modificar Facultad</h1>
@stop

@section('content')
<div class="container">
    <form action="{{route('facultades.update',$facultad->FACULTAD_ID)}}" method="POST">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col">
                
                <div class="mb-3">
                    <label for="FACULTAD_NUMERO" class="form-label"><i class="fa-solid fa-book-bookmark"></i> Número de Facultad:</label>
                    <input type="number" class="form-control{{ $errors->has('FACULTAD_NUMERO') ? ' is-invalid' : '' }}" id="FACULTAD_NUMERO" name="FACULTAD_NUMERO" value="{{ $facultad->FACULTAD_NUMERO }}" min="0" max="9999" placeholder="Ej: 123" required>
                    @if ($errors->has('FACULTAD_NUMERO'))
                        <div class="invalid-feedback">
                            {{ $errors->first('FACULTAD_NUMERO') }}
                        </div>
                    @endif
                </div>

                <div class="mb-3">
                    <label for="FACULTAD_NOMBRE" class="form-label"><i class="fa-solid fa-book-bookmark"></i> Nombre:</label>
                    <input type="text" class="form-control{{ $errors->has('FACULTAD_NOMBRE') ? ' is-invalid' : '' }}" id="FACULTAD_NOMBRE" name="FACULTAD_NOMBRE" value="{{ $facultad->FACULTAD_NOMBRE }}" placeholder="Ej: APLICAR SANCIONES ADMINISTRATIVAS" required>
                    @if ($errors->has('FACULTAD_NOMBRE'))
                        <div class="invalid-feedback">
                            {{ $errors->first('FACULTAD_NOMBRE') }}
                        </div>
                    @endif
                </div>
        
                <div class="mb-3">
                    <label for="FACULTAD_CONTENIDO" class="form-label"><i class="fa-solid fa-book-bookmark"></i> Contenido:</label>
                    <textarea class="form-control{{ $errors->has('FACULTAD_CONTENIDO') ? ' is-invalid' : '' }}" id="FACULTAD_CONTENIDO" name="FACULTAD_CONTENIDO" rows="4" required>{{ $facultad->FACULTAD_CONTENIDO }}</textarea>
                    @if ($errors->has('FACULTAD_CONTENIDO'))
                        <div class="invalid-feedback">
                            {{ $errors->first('FACULTAD_CONTENIDO') }}
                        </div>
                    @endif
                </div>
        
                <div class="mb-3">
                    <label for="LEY_ASOCIADA" class="form-label"><i class="fa-solid fa-book-bookmark"></i> Ley asociada:</label>
                    <input type="text" class="form-control{{ $errors->has('FACULTAD_LEY_ASOCIADA') ? ' is-invalid' : '' }}" id="FACULTAD_LEY_ASOCIADA" name="FACULTAD_LEY_ASOCIADA" value="{{ $facultad->FACULTAD_LEY_ASOCIADA }}" placeholder="Ej: Código Tributario" required>
                    @if ($errors->has('FACULTAD_LEY_ASOCIADA'))
                        <div class="invalid-feedback">
                            {{ $errors->first('FACULTAD_LEY_ASOCIADA') }}
                        </div>
                    @endif
                </div>
        
                <div class="mb-3">
                    <label for="FACULTAD_ART_LEY_ASOCIADA" class="form-label"><i class="fa-solid fa-book-bookmark"></i> Art. de ley asociada:</label>
                    <input type="text" class="form-control{{ $errors->has('FACULTAD_ART_LEY_ASOCIADA') ? ' is-invalid' : '' }}" id="FACULTAD_ART_LEY_ASOCIADA" name="FACULTAD_ART_LEY_ASOCIADA" value="{{ $facultad->FACULTAD_ART_LEY_ASOCIADA }}" placeholder="Ej: 165" required>
                    @if ($errors->has('FACULTAD_ART_LEY_ASOCIADA'))
                        <div class="invalid-feedback">
                            {{ $errors->first('FACULTAD_ART_LEY_ASOCIADA') }}
                        </div>
                    @endif
                </div>

            </div>
        </div>
        <div class="form-group">
            <a href="{{route('facultades.index')}}" class="btn btn-secondary" tabindex="5"><i class="fa-solid fa-hand-point-left"></i> Cancelar</a>
            <button type="submit" class="btn btn-primary" tabindex="4"><i class="fa-solid fa-floppy-disk"></i> Guardar</button>
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
