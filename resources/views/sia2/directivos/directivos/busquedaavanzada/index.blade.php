@extends('adminlte::page')

@section('title', 'busqueda de resoluciones')

@section('content_header')
    <h1>Búsqueda Avanzada de Resoluciones Delegatorias de Facultades</h1>
@stop

@section('content')
        <form id="searchForm" action="{{ route('directivos.indexBusquedaBasica') }}" method="GET">
            @csrf

            <div class="row">
                <div class="col-md-3 form-group">
                    <label for="RESOLUCION_NUMERO" class="form-label"><i class="fas fa-bookmark"></i> Número de Resolución:</label>
                    <select id="RESOLUCION_NUMERO" name="RESOLUCION_NUMERO" class="form-control" disabled>
                        <option value="" selected>--Seleccione Nro--</option>
                        @foreach ($nros as $nro)
                            <option value="{{ $nro->RESOLUCION_NUMERO }}" {{ old('RESOLUCION_NUMERO') == $nro->RESOLUCION_NUMERO ? 'selected' : '' }}>{{ $nro->RESOLUCION_NUMERO }}</option>
                        @endforeach
                    </select>
                    <div class="form-check mt-2">
                        <input class="form-check-input" type="checkbox" name="selectedFilters[RESOLUCION_NUMERO]" value="RESOLUCION_NUMERO" id="filter_RESOLUCION_NUMERO">
                        <label class="form-check-label" for="filter_RESOLUCION_NUMERO">Incluir como filtro de búsqueda</label>
                    </div>
                </div>

                <div class="col-md-3 form-group">
                    <label for="TIPO_RESOLUCION_ID" class="form-label"><i class="fas fa-bookmark"></i> Tipo Resolución:</label>
                    <select id="TIPO_RESOLUCION_ID" name="TIPO_RESOLUCION_ID" class="form-control" disabled>
                        <option value="" selected>--Seleccione Tipo--</option>
                        @foreach ($tipos as $tipo)
                            <option value="{{ $tipo->TIPO_RESOLUCION_ID }}" {{ old('TIPO_RESOLUCION_ID') == $tipo->TIPO_RESOLUCION_ID ? 'selected' : '' }}>{{ $tipo->TIPO_RESOLUCION_NOMBRE }}</option>
                        @endforeach
                    </select>
                    <div class="form-check mt-2">
                        <input class="form-check-input" type="checkbox" name="selectedFilters[TIPO_RESOLUCION_ID]" value="TIPO_RESOLUCION_ID" id="filter_TIPO_RESOLUCION_ID">
                        <label class="form-check-label" for="filter_TIPO_RESOLUCION_ID">Incluir como filtro de búsqueda</label>
                    </div>
                </div>
            

                <div class="col-md-3 form-group">
                    <label for="RESOLUCION_FECHA" class="form-label"><i class="fas fa-bookmark"></i> Fecha:</label>
                    <select id="RESOLUCION_FECHA" name="RESOLUCION_FECHA" class="form-control @error('RESOLUCION_FECHA') is-invalid @enderror" disabled>
                        <option value="" selected>--Seleccione Fecha--</option>
                        @foreach ($fechas as $fecha)
                            <option value="{{ $fecha->RESOLUCION_FECHA }}" {{ old('RESOLUCION_FECHA') == $fecha->RESOLUCION_FECHA ? 'selected' : '' }}>{{ date('d-m-Y', strtotime($fecha->RESOLUCION_FECHA)) }}</option>
                        @endforeach
                    </select>
                    @error('RESOLUCION_FECHA')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-check mt-2">
                        <input class="form-check-input" type="checkbox" name="selectedFilters[RESOLUCION_FECHA]" value="RESOLUCION_FECHA" id="filter_RESOLUCION_FECHA">
                        <label class="form-check-label" for="filter_RESOLUCION_FECHA">Incluir como filtro de búsqueda</label>                    
                    </div>
                </div>

                <div class="col-md-3 form-group">
                    <label for="FACULTAD_ID" class="form-label"><i class="fas fa-bookmark"></i> Facultad:</label>
                    <select id="FACULTAD_ID" name="FACULTAD_ID" class="form-control" disabled>
                        <option value="" selected>--Seleccione Facultad--</option>
                        @foreach ($facultades as $facultad)
                            <option value="{{ $facultad->FACULTAD_ID }}" {{ old('FACULTAD_ID') == $facultad->FACULTAD_ID ? 'selected' : '' }}>{{ $facultad->FACULTAD_NOMBRE }}</option>
                        @endforeach
                    </select>
                    <div class="form-check mt-2">
                        <input class="form-check-input" type="checkbox" name="selectedFilters[FACULTAD_ID]" value="FACULTAD_ID" id="filter_FACULTAD_ID">
                        <label class="form-check-label" for="filter_FACULTAD_ID">Incluir como filtro de búsqueda</label>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-3 form-group">
                    <label for="FACULTAD_LEY_ASOCIADA" class="form-label"><i class="fas fa-bookmark"></i> Ley asociada:</label>
                    <select id="FACULTAD_LEY_ASOCIADA" name="FACULTAD_LEY_ASOCIADA" class="form-control" disabled>
                        <option value="" selected>--Seleccione Ley--</option>
                        @foreach ($leyesAsociadas as $leyAsociada)
                            <option value="{{ $leyAsociada }}" {{ old('FACULTAD_LEY_ASOCIADA') == $leyAsociada ? 'selected' : '' }}>{{ $leyAsociada }}</option>
                        @endforeach
                    </select>
                    <div class="form-check mt-2">
                        <input class="form-check-input" type="checkbox" name="selectedFilters[FACULTAD_LEY_ASOCIADA]" value="FACULTAD_LEY_ASOCIADA" id="filter_FACULTAD_LEY_ASOCIADA">
                        <label class="form-check-label" for="filter_FACULTAD_LEY_ASOCIADA">Incluir como filtro de búsqueda</label>
                    </div>
                </div>
                

                
                <div class="col-md-3 form-group">
                    <label for="FACULTAD_ART_LEY_ASOCIADA" class="form-label"><i class="fas fa-bookmark"></i> Artículo de ley:</label>
                    <select id="FACULTAD_ART_LEY_ASOCIADA" name="FACULTAD_ART_LEY_ASOCIADA" class="form-control" disabled>
                        <option value="" selected>--Seleccione Artículo--</option>
                        @foreach ($articulosDeLeyAsociadas as $articuloDeLeyAsociada)
                            <option value="{{ $articuloDeLeyAsociada }}" {{ old('FACULTAD_ART_LEY_ASOCIADA') == $articuloDeLeyAsociada ? 'selected' : '' }}>{{ $articuloDeLeyAsociada }}</option>
                        @endforeach
                    </select>
                    @error('FACULTAD_ART_LEY_ASOCIADA')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-check mt-2">
                        <input class="form-check-input" type="checkbox" name="selectedFilters[FACULTAD_ART_LEY_ASOCIADA]" value="FACULTAD_ART_LEY_ASOCIADA" id="filter_FACULTAD_ART_LEY_ASOCIADA">
                        <label class="form-check-label" for="filter_FACULTAD_ART_LEY_ASOCIADA">Incluir como filtro de búsqueda</label>
                    </div>
                </div>
                


                <div class="col-md-3 form-group">
                    <label for="CARGO_ID" class="form-label"><i class="fas fa-bookmark"></i> Firmante:</label>
                    <select id="CARGO_ID" name="CARGO_ID" class="form-control" disabled>
                        <option value="" selected>--Seleccione Firmante--</option>
                        @foreach ($firmantes as $firmante)
                            <option value="{{ $firmante->CARGO_ID }}" {{ old('CARGO_ID') == $firmante->CARGO_ID ? 'selected' : '' }}>{{ $firmante->CARGO_NOMBRE }}</option>
                        @endforeach
                    </select>
                    <div class="form-check mt-2">
                        <input class="form-check-input" type="checkbox" name="selectedFilters[CARGO_ID]" value="CARGO_ID" id="filter_CARGO_ID">
                        <label class="form-check-label" for="filter_CARGO_ID">Incluir como filtro de búsqueda</label>
                    </div>
                </div>

                <div class="col-md-3 form-group">
                    <label for="DELEGADO_ID" class="form-label"><i class="fas fa-bookmark"></i> Delegado:</label>
                    <select id="DELEGADO_ID" name="DELEGADO_ID" class="form-control" disabled>
                        <option value="" selected>--Seleccione Delegado--</option>
                        @foreach ($delegados as $delegado)
                            <option value="{{ $delegado->CARGO_ID }}" {{ old('DELEGADO_ID') == $delegado->CARGO_ID ? 'selected' : '' }}>{{ $delegado->CARGO_NOMBRE }}</option>
                        @endforeach
                    </select>
                    <div class="form-check mt-2">
                        <input class="form-check-input" type="checkbox" name="selectedFilters[DELEGADO_ID]" value="DELEGADO_ID" id="filter_DELEGADO_ID">
                        <label class="form-check-label" for="filter_DELEGADO_ID">Incluir como filtro de búsqueda</label>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary" name="buscar"><i class="fas fa-search"></i> Buscar</button>
                </div>
            </div>
        </form>

        @if(count($resoluciones) > 0)
        <div class="table custom-table-responsive">
            <table id="resoluciones" class="table table-bordered mt-4 custom-table">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th scope="col">Resolución</th>
                            <th scope="col">Fecha</th>
                            <th scope="col">Tipo Resolucion</th>
                            <th scope="col">Firmante</th>
                            <th scope="col">Delegado</th>
                            <th scope="col">Facultad</th>
                            <th scope="col">Ley asociada</th>
                            <th scope="col">Artículo de ley</th>
                            <th scope="col">Glosa</th>
                            <th scope="col">Observación</th>
                            <th scope="col">Documento</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($resoluciones as $resolucion)
                            <tr>
                                <td>
                                    <div class="d-flex justify-content-center">
                                        {{ $resolucion->RESOLUCION_NUMERO }}
                                    </div>
                                </td>
                                <td>{{ date('d/m/Y', strtotime($resolucion->RESOLUCION_FECHA)) }}</td>
                                <td>{{ $resolucion->tipoResolucion->TIPO_RESOLUCION_NOMBRE }}</td>
                                <td>{{ $resolucion->firmante->CARGO_NOMBRE }}</td>
                                <td>
                                    @foreach($resolucion->obedientes as $obediente)
                                        {{ $obediente->cargo->CARGO_NOMBRE }}<br>
                                    @endforeach
                                </td>
                                <td>
                                    @foreach($resolucion->delegacion as $delegacion)
                                        {{ $delegacion->facultad->FACULTAD_NOMBRE }}<br>
                                    @endforeach
                                </td>
                                <td>
                                    @foreach($resolucion->delegacion as $delegacion)
                                        {{ $delegacion->facultad->FACULTAD_LEY_ASOCIADA }}<br>
                                    @endforeach
                                </td>                                
                                <td>
                                    @foreach($resolucion->delegacion as $delegacion)
                                        {{ $delegacion->facultad->FACULTAD_ART_LEY_ASOCIADA }}<br>
                                    @endforeach
                                </td>
                                <td>
                                    @foreach($resolucion->delegacion as $delegacion)
                                        <span class="glosa-abreviada">{{ substr($delegacion->facultad->FACULTAD_CONTENIDO, 0, 0) }}</span>
                                        <button class="btn btn-sia-primary btn-block btn-expand" data-glosa="{{ $delegacion->facultad->FACULTAD_CONTENIDO }}">
                                            <i class="fa-solid fa-square-plus"></i>
                                        </button>
                                        <button class="btn btn-sia-primary btn-block btn-collapse" style="display: none;">
                                            <i class="fa-solid fa-square-minus"></i>
                                        </button>
                                        <span class="glosa-completa" style="display: none;">{{ $delegacion->facultad->FACULTAD_CONTENIDO }}</span><br>
                                    @endforeach
                                </td>
                                <td>
                                    <span class="observaciones-abreviada">{{ substr($resolucion->RESOLUCION_OBSERVACIONES, 0, 0) }}</span>
                                    <button class="btn btn-sia-primary btn-block btn-expand-obs" data-obs="{{ $resolucion->RESOLUCION_OBSERVACIONES }}">
                                        <i class="fa-solid fa-square-plus"></i>
                                    </button>
                                    <button class="btn btn-sia-primary btn-block btn-collapse-obs" style="display: none;">
                                        <i class="fa-solid fa-square-minus"></i>
                                    </button>
                                    
                                    <span class="observaciones-completa" style="display: none;">{{ $resolucion->RESOLUCION_OBSERVACIONES }}</span>
                                </td>
                                <td>
                                    @if ($resolucion->RESOLUCION_DOCUMENTO)
                                        <a href="{{ asset('storage/resoluciones/' . $resolucion->RESOLUCION_DOCUMENTO) }}" class="btn btn-sia-primary btn-block" target="_blank">
                                            <i class="fa-solid fa-file-pdf" style="color: green;"></i>
                                        </a>
                                    @else
                                        Sin documento
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div style="margin-top: 60px;"></div> <!-- Espacio entre las funcionalidades -->
            <div class="alert alert-info">Ingrese algún parámetro para obtener resoluciones</div>
        @endif
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        .textarea-container {
            margin-top: 10px; /* Ajusta el valor según sea necesario */
        }
    </style>
        <style>
        .alert {
        opacity: 0.7; 
        background-color: #99CCFF;
        color:     #000000;
        }
    </style>

    
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(function () {
            // Configuración checkboxes

            // Obtén todos los checkboxes y selects
            const checkboxes = document.querySelectorAll('input[type="checkbox"]');
            const selects = document.querySelectorAll('select');

            // Recorre los checkboxes y agrega el evento de cambio
            checkboxes.forEach((checkbox) => {
                checkbox.addEventListener('change', (event) => {
                    const checkbox = event.target;
                    const select = document.querySelector(`select#${checkbox.getAttribute('id').replace('filter_', '')}`);

                    if (checkbox.checked) {
                        select.disabled = false; // Habilita el select
                    } else {
                        select.disabled = true; // Deshabilita el select
                    }
                });
            });

            // Configuración botón '+' o '-' de la glosa en la tabla:
            // Agrega evento de clic al botón de expansión
            $('.btn-expand').on('click', function () {
                var glosaAbreviada = $(this).siblings('.glosa-abreviada');
                var glosaCompleta = $(this).siblings('.glosa-completa');
                var btnExpand = $(this);
                var btnCollapse = $(this).siblings('.btn-collapse');

                glosaAbreviada.hide();
                glosaCompleta.show();
                btnExpand.hide();
                btnCollapse.show();
                //$(this).hide();
            });

            // Agregar evento de clic al botón de colapso
            $('.btn-collapse').on('click', function () {
                    var glosaAbreviada = $(this).siblings('.glosa-abreviada');
                    var glosaCompleta = $(this).siblings('.glosa-completa');
                    var btnExpand = $(this).siblings('.btn-expand');
                    var btnCollapse = $(this);

                    glosaAbreviada.show();
                    glosaCompleta.hide();
                    btnExpand.show();
                    btnCollapse.hide();
            });
            
            // Código para expandir y colapsar las observaciones
            $('.btn-expand-obs').click(function() {
                $(this).hide();
                $(this).siblings('.btn-collapse-obs').show();
                $(this).siblings('.observaciones-abreviada').hide();
                $(this).siblings('.observaciones-completa').show();
            });

            $('.btn-collapse-obs').click(function() {
                $(this).hide();
                $(this).siblings('.btn-expand-obs').show();
                $(this).siblings('.observaciones-abreviada').show();
                $(this).siblings('.observaciones-completa').hide();
            });

            $('#resoluciones').DataTable({
                "lengthMenu": [
                    [5, 10, 50, -1],
                    [5, 10, 50, "All"]
                ],
                "columnDefs": [{
                    "orderable": false,
                    "targets": 10
                }],
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/es_es.json"
                }
            });
        });
    </script>
@stop