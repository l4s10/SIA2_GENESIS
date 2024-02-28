@extends('adminlte::page')

@section('title', 'Modificar Resolución Delegatoria')

@section('content_header')
    <h1>Modificar Resolución Delegatoria</h1>
@stop

@section('content')
    <div class="container">
        <form action="{{ route('resoluciones.update', $resolucion->RESOLUCION_ID) }}" method="POST" >
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label for="RESOLUCION_NUMERO">N° Resolución:</label>
                        <input type="text" name="RESOLUCION_NUMERO" id="RESOLUCION_NUMERO" class="form-control" placeholder="N° Resolución (Ej: 1234)" value="{{ $resolucion->RESOLUCION_NUMERO }}" required autofocus>
                        @error('RESOLUCION_NUMERO')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="RESOLUCION_FECHA" class="form-label"><i class="fa-solid fa-book-bookmark"></i> Fecha:</label>
                        <input type="text" class="form-control{{ $errors->has('RESOLUCION_FECHA') ? ' is-invalid' : '' }}" id="RESOLUCION_FECHA" name="RESOLUCION_FECHA" value="{{ old('RESOLUCION_FECHA', $resolucion->RESOLUCION_FECHA) }}" placeholder="Ej: 1996-08-24" required>
                        @if ($errors->has('RESOLUCION_FECHA'))
                            <div class="invalid-feedback">
                                {{ $errors->first('RESOLUCION_FECHA') }}
                            </div>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label for="TIPO_RESOLUCION_ID" class="form-label"><i class="fa-solid fa-book-bookmark"></i> Tipo de Resolución:</label>
                        <select id="TIPO_RESOLUCION_ID" name="TIPO_RESOLUCION_ID" class="form-control @error('TIPO_RESOLUCION_ID') is-invalid @enderror" required>
                            <option value="" selected>--Seleccione Tipo de Resolución--</option>
            
                            @foreach ($tiposResoluciones as $tipoResolucion)
                                <option value="{{ $tipoResolucion->TIPO_RESOLUCION_ID }}" {{ $resolucion->TIPO_RESOLUCION_ID == $tipoResolucion->TIPO_RESOLUCION_ID ? 'selected' : '' }}>
                                    {{ $tipoResolucion->TIPO_RESOLUCION_NOMBRE }}
                                </option>
                            @endforeach
            
                        </select>
            
                        @error('TIPO_RESOLUCION_ID')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="CARGO_ID" class="form-label"><i class="fa-solid fa-book-bookmark"></i> Firmante:</label>
                        <select id="CARGO_ID" name="CARGO_ID" class="form-control @error('CARGO_ID') is-invalid @enderror" required>
                            <option value="" selected>--Seleccione Firmante--</option>

                            @foreach ($cargos as $cargo)
                                <option value="{{ $cargo->CARGO_ID }}" {{ $resolucion->CARGO_ID == $cargo->CARGO_ID ? 'selected' : '' }}>
                                    {{ $cargo->CARGO_NOMBRE }}
                                </option>
                            @endforeach

                        </select>

                        @error('CARGO_ID')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="FACULTAD_ID" class="form-label"><i class="fa-solid fa-book-bookmark"></i> Facultad:</label>
                        <select id="FACULTAD_ID" name="FACULTAD_ID" class="form-control @error('FACULTAD_ID') is-invalid @enderror" required>
                            <option value="" selected>--Seleccione Facultad--</option>
            
                            @foreach ($facultades as $facultad)
                                <option value="{{ $facultad->FACULTAD_ID }}" {{ $delegacion->facultad->FACULTAD_ID == $facultad->FACULTAD_ID ? 'selected' : '' }}>
                                    {{ $facultad->FACULTAD_NOMBRE }}
                                </option>
                            @endforeach
            
                        </select>
            
                        @error('FACULTAD_ID')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="DELEGADO_ID" class="form-label"><i class="fa-solid fa-book-bookmark"></i> Delegado:</label>
                        <select id="DELEGADO_ID" name="DELEGADO_ID" class="form-control @error('DELEGADO_ID') is-invalid @enderror" required>
                            <option value="" selected>--Seleccione Delegado--</option>
            
                            @foreach ($cargos as $cargo)
                                <option value="{{ $cargo->CARGO_ID }}" {{ $obediencia->cargo->CARGO_ID == $cargo->CARGO_ID ? 'selected' : '' }}>
                                    {{ $cargo->CARGO_NOMBRE }}
                                </option>
                            @endforeach
            
                        </select>
            
                        @error('DELEGADO_ID')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="RESOLUCION_OBSERVACIONES" class="form-label"><i class="fa-solid fa-book-bookmark"></i> Observaciones:</label>
                        <textarea class="form-control @error('RESOLUCION_OBSERVACIONES') is-invalid @enderror" id="RESOLUCION_OBSERVACIONES" name="RESOLUCION_OBSERVACIONES" placeholder="Observaciones">{{ old('RESOLUCION_OBSERVACIONES', $resolucion->RESOLUCION_OBSERVACIONES) }}</textarea>
                        @error('RESOLUCION_OBSERVACIONES')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="RESOLUCION_DOCUMENTO" class="form-label"><i class="fa-solid fa-book-bookmark"></i> Documento:</label>
                        <div class="input-group">
                            <input type="file" name="RESOLUCION_DOCUMENTO" id="RESOLUCION_DOCUMENTO" class="input-group-text btn btn" >
                        </div>
                        <small id="documentoActualHelp" class="form-text text-muted">
                            @if ($resolucion->RESOLUCION_DOCUMENTO)
                                Archivo adjunto actual:<strong> {{ $resolucion->RESOLUCION_DOCUMENTO }}</strong>
                            @else
                                <strong>Ningún archivo adjunto seleccionado.</strong>
                            @endif
                        </small>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="ELIMINAR_DOCUMENTO" id="ELIMINAR_DOCUMENTO" value="1" {{ $resolucion->RESOLUCION_DOCUMENTO ? '' : 'disabled' }}>
                            <label class="form-check-label" for="ELIMINAR_DOCUMENTO">
                                Eliminar archivo adjunto actual
                            </label>
                        </div>
                        @error('DOCUMENTO')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="form-group text-right">
                <a href="{{ route('resoluciones.index') }}" class="btn btn-secondary" tabindex="5"><i class="fa-solid fa-hand-point-left"></i> Cancelar</a>
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
    <script>
        $(function () {
            let fechaActual = new Date();

            // Calcular la fecha de inicio (dos años atrás)
            let fechaInicio = new Date();
            fechaInicio.setFullYear(fechaInicio.getFullYear() - 2);

            // Calcular la fecha de fin (un mes adelante)
            let fechaFin = new Date();
            fechaFin.setMonth(fechaFin.getMonth() + 1);

            $('#RESOLUCION_FECHA').flatpickr({
                locale: 'es',
                minDate: fechaInicio.toISOString().split("T")[0],
                maxDate: fechaFin.toISOString().split("T")[0],
                dateFormat: "Y-m-d",
                altFormat: "d-m-Y",
                altInput: true,
                allowInput: true,
            });
        });

        document.getElementById('DOCUMENTO').addEventListener('change', function() {
        var input = this;
        var output = document.getElementById('documentoActualHelp');
        if (input.files && input.files[0]) {
            output.textContent = 'Archivo adjunto actual: ' + input.files[0].name;
        } else {
            output.textContent = 'Ningún archivo adjunto seleccionado.';
        }
    });
    </script>
@endsection