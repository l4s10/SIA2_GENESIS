@extends('adminlte::page')

@section('title', 'Ingreso de resolución delegatoria')

@section('content_header')
    <h1>Ingresar Resolución Delegatoria</h1>
@stop

@section('content')
<div class="container">
    <form action="{{ route('resoluciones.store') }}" method="POST"  enctype="multipart/form-data">        
        @csrf
        <div class="mb-3">
            <label for="RESOLUCION_NUMERO" class="form-label"><i class="fa-solid fa-book-bookmark"></i> N° Resolución:</label>
            <input type="text" class="form-control{{ $errors->has('RESOLUCION_NUMERO') ? ' is-invalid' : '' }}" id="RESOLUCION_NUMERO" name="RESOLUCION_NUMERO" value="{{ old('RESOLUCION_NUMERO') }}" min="0" max="9999" placeholder="Ej: 1234" required>
            @if ($errors->has('RESOLUCION_NUMERO'))
                <div class="invalid-feedback">
                    {{ $errors->first('RESOLUCION_NUMERO') }}
                </div>
            @endif
        </div>
        <div class="mb-3">
            <label for="RESOLUCION_FECHA" class="form-label"><i class="fa-solid fa-book-bookmark"></i> Fecha:</label>
            <input type="text" class="form-control{{ $errors->has('RESOLUCION_FECHA') ? ' is-invalid' : '' }}" id="RESOLUCION_FECHA" name="RESOLUCION_FECHA" value="{{ old('RESOLUCION_FECHA') }}" placeholder="Ej: 1996-08-24" required>
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
                    <option value="{{ $tipoResolucion->TIPO_RESOLUCION_ID }}" {{ old('TIPO_RESOLUCION_ID') == $tipoResolucion->TIPO_RESOLUCION_ID ? 'selected' : '' }}>
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
                    <option value="{{ $cargo->CARGO_ID }}" {{ old('CARGO_ID') == $cargo->CARGO_ID  ? 'selected' : ''}}>
                        {{ $cargo->CARGO_NOMBRE }}
                    </option>
                @endforeach

            </select>

            @error('CARGO_ID')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="mb-3">
            <label class="form-label"><i class="fa-solid fa-book-bookmark"></i> Facultades:</label>
            <div class="table-responsive">
                <table id="facultades" class="table text-justify table-bordered mt-4 mx-auto">
                    <thead class="tablacolor">
                        <tr>
                            <th scope="col">N° Facultad</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">Contenido</th>
                            <th scope="col">Ley asociada</th>
                            <th scope="col">Artículo de ley</th>
                            <th scope="col">Seleccionar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($facultades as $facultad)
                        <tr>
                            <td>
                                <div class="d-flex justify-content-center">
                                    {{$facultad->FACULTAD_NUMERO}}
                                </div>
                            </td>
                            <td>{{$facultad->FACULTAD_NOMBRE}}</td>
                            <td>{{$facultad->FACULTAD_CONTENIDO}}</td>
                            <td>{{$facultad->FACULTAD_LEY_ASOCIADA}}</td>
                            <td>{{$facultad->FACULTAD_ART_LEY_ASOCIADA}}</td>
                            <td class="text-center align-middle">
                                <div class="form-check d-flex justify-content-center">
                                    <input class="form-check-input custom-checkbox" type="checkbox" id="FACULTAD_{{ $facultad->FACULTAD_ID }}" name="ARRAYCHECKBOXES[]" value="{{ $facultad->FACULTAD_ID }}" {{ in_array($facultad->FACULTAD_ID, old('FACULTADES', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="FACULTAD_{{ $facultad->FACULTAD_ID }}"></label>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
        </div>
        <input type="hidden" name="FACULTADES[]" id="array_facultades" value="{{ old('FACULTADES', '') }}">

        
        
        <div class="mb-3">
            <label for="DELEGADO_ID" class="form-label"><i class="fa-solid fa-book-bookmark"></i> Delegado:</label>
            <select id="DELEGADO_ID" name="DELEGADO_ID" class="form-control @error('DELEGADO_ID') is-invalid @enderror" required>
                <option value="" selected>--Seleccione Delegado--</option>
                
                @foreach ($cargos as $cargo)
                    <option value="{{ $cargo->CARGO_ID }}" {{ old('CARGO_ID') == $cargo->CARGO_ID  ? 'selected' : ''}}>
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
            <input type="text" class="form-control{{ $errors->has('RESOLUCION_OBSERVACIONES') ? ' is-invalid' : '' }}" id="RESOLUCION_OBSERVACIONES" name="RESOLUCION_OBSERVACIONES" value="{{ old('RESOLUCION_OBSERVACIONES') }}" placeholder="Ej: Autoriza Resolución '1024'" required>
            @if ($errors->has('RESOLUCION_OBSERVACIONES'))
                <div class="invalid-feedback">
                    {{ $errors->first('RESOLUCION_OBSERVACIONES') }}
                </div>
            @endif
        </div>
        <div class="mb-3">
            <label for="RESOLUCION_DOCUMENTO" class="form-label"><i class="fa-solid fa-book-bookmark"></i> Documento:</label>
            <input type="file" name="RESOLUCION_DOCUMENTO" id="RESOLUCION_DOCUMENTO" class="form-control{{ $errors->has('RESOLUCION_DOCUMENTO') ? ' is-invalid' : '' }}">
            @error('RESOLUCION_DOCUMENTO')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <a href="{{ route('resoluciones.index') }}" class="btn btn-secondary" tabindex="5"><i class="fa-solid fa-hand-point-left"></i> Cancelar</a>
        <button type="submit" class="btn btn-primary" tabindex="4"><i class="fa-solid fa-floppy-disk"></i> Registrar</button>
    </form>
</div>
@stop


@section('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<style>
    /* Estilos personalizados para el tamaño del checkbox */
    .form-check-input.custom-checkbox {
        width: 1.25rem; /* Anchura personalizada */
        height: 1.25rem; /* Altura personalizada */
    }
</style>
@stop

@section('js')
    <!-- CONEXION FONT-AWESOME CON TOOLKIT -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>


    <script>
        $(function () {
            let fechaActual = new Date();

            // Calcular la fecha de inicio (un año atrás)
            let fechaInicio = new Date();
            fechaInicio.setFullYear(fechaInicio.getFullYear() - 1);

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
    </script>

    <script>

        $(document).ready(function () {
            // Inicialización de DataTables
            var table = $('#facultades').DataTable({
                "lengthMenu": [[5, 10, 50, -1], [5, 10, 50, "All"]],
                "columnDefs": [
                    { "orderable": false, "targets": 5 }
                ],
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/es_es.json"
                },
            });

            // Almacenar las selecciones de checkboxes al cambiar
            $('#facultades').on('change', '.custom-checkbox', function() {
                updateSelectedFacultades(table);
            });
        });

        function updateSelectedFacultades(table) {
            var selectedFacultades = [];

            // Recorrer todas las páginas y recopilar las facultades seleccionadas
            table.$('.custom-checkbox:checked').each(function() {
                selectedFacultades.push($(this).val());
            });

            // Actualizar el campo oculto con las facultades seleccionadas
            $('#array_facultades').val(selectedFacultades.join(','));

            // Actualizar el arreglo FACULTADES[]
            var facultadesArray = [];
            selectedFacultades.forEach(function(facultadId) {
                facultadesArray.push(facultadId);
            });

            // Asignar el arreglo FACULTADES[] al campo oculto
            $('#array_facultades').val(JSON.stringify(facultadesArray));
        }

    </script>

    
    
@stop
