@extends('adminlte::page')

@section('title', 'Reportes de Sistema')

@section('content_header')
    <h1>Reportes de Sistema</h1>
@endsection

@section('content')

    <div class="accordion" id="accordionExample">
        <div class="card">
            <div class="card-header" id="headingOne">
                <h2 class="mb-0">
                    <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        Filtrar por fechas
                    </button>
                </h2>
            </div>

            <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="start-date">Fecha de inicio:</label>
                            <input type="date" id="start-date" name="start-date" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label for="end-date">Fecha de fin:</label>
                            <input type="date" id="end-date" name="end-date" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button id="refresh-button" class="btn guardar">Actualizar</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Contenedor para las alertas de mensajes --}}
    <div class="row">
        <div class="col-12">
            <div id="filter-message" class="alert alert-info text-center" role="alert">
                <!-- El mensaje de fechas filtradas se mostrará aquí -->
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <canvas id="graficoRankingSolicitudes"></canvas>
                <div class="card-footer">
                    <button id="download-jpeg-button" class="btn descagargrafico"><i class="fa-solid fa-download"></i></button>
                </div>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="card">
                <canvas id="graficoDistribucionGenero"></canvas>
                <div class="card-footer">
                    <button id="download-jpeg-button" class="btn descagargrafico"><i class="fa-solid fa-download"></i></button>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabla de contingencia --}}
    {{-- Objetivo a traves de filtros (REGION_ID, OFICINA_ID, UBICACION_ID/DEPARTAMENTO_ID) -> USUARIOS MASCULINOS Y FEMENINOS POR UBICACION/DEPARTAMETO DE ESA OFICINA CONTENIDA EN ESA REGION --}}
    <div class="container">
        <h2>Tabla de contingencia</h2>
        <h5>Filtros (llenar todos los campos)</h5>
        <div class="row" style="padding-bottom:3%;">
            {{-- <div class="col-lg-4">
                <label for="">Region:</label>
                <select name="region" id="region-select" class="form-control">
                    <option value="">Selecciona la región</option>
                    @foreach($regiones as $region)
                        <option value="{{ $region->REGION_ID }}">{{ $region->REGION_NOMBRE }}</option>
                    @endforeach
                </select>
            </div> --}}
            <div class="col-lg-4">
                <label for="">Jurisdicción</label>
                <select name="direccion" id="direccion-select" class="form-control">
                    <option value="">Selecciona la dirección regional</option>
                    @foreach ($oficinas as $oficina)
                        <option value="{{ $oficina->OFICINA_ID }}">{{ $oficina->OFICINA_NOMBRE }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-4">
                <label for="">Ubicación</label>
                <select name="ubicacion" id="ubicacion-select" class="form-control">
                    <option value="">Selecciona la ubicación</option>
                </select>
            </div>
            <div class="col-lg-4">
                <label for="">Departamento</label>
                <select name="departamento" id="departamento-select" class="form-control">
                    <option value="">Selecciona el departamento</option>
                </select>
            </div>
        </div>
        <table id="ubicaciones-table" class="table table-striped">
            <thead>
                <tr>
                    <th>Ubicación</th>
                    <th>Hombres</th>
                    <th>Mujeres</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
            <tfoot>
                <tr>
                    <th>Total:</th>
                    <th id="total-hombres">0</th>
                    <th id="total-mujeres">0</th>
                    <th id="total-total">0</th>
                </tr>
            </tfoot>
        </table>
    </div>
@endsection

@section('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>/* Estilos personalizados si es necesario */
        .guardar {
            background-color: #e6500a;
            color: #fff;
        }
        .descagargrafico{
            background-color: #00B050;
            color: #fff;
        }
    </style>
@endsection

@section('js')
    {{-- Disparar sweetalert cuando se filtre --}}
    <script>
        document.getElementById('refresh-button').addEventListener('click', function() {
            var startDate = document.getElementById('start-date').value;
            var endDate = document.getElementById('end-date').value;

            if (startDate === '' || endDate === '') {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Por favor, ingrese ambas fechas.',
                });
                // y no hacer la petición
                return;
            }

            Swal.fire({
                didOpen: () => {
                    Swal.showLoading()
                },
                title: 'Filtrando datos',
                text: 'Espere un momento por favor...',
                icon: 'info',
                timer: 2000,
                showConfirmButton: false,
                allowOutsideClick: false
            });
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    {{-- Inicializar calendarios --}}
    <script src="{{ asset('js/Components/fechasGraficos.js') }}"></script>
    {{-- Importamos la logica del boton y el mensaje de filtro --}}
    <script src="{{asset('js/Components/graficoAlertaMensaje.js')}}"></script>

    {{-- Importar el archivo JS para obtener los datos --}}
    <script src="{{ asset('js/Graficos/Sistema/getData.js') }}"></script>

    {{-- Importar el archivo JS para el gráfico 1 --}}
    <script src="{{ asset('js/Graficos/Sistema/grafico1.js') }}"></script>

    {{-- Importar el archivo JS para el gráfico 2 --}}
    <script src="{{ asset('js/Graficos/Sistema/grafico2.js') }}"></script>

    <!-- Scrip para la descargar de los graficos -->
    <script>
        // Agrega un controlador de eventos al botón de descarga en formato JPEG
            const downloadJPEGButton = document.getElementById('download-jpeg-button');
            downloadJPEGButton.addEventListener('click', function () {
                // Selecciona el elemento del gráfico que deseas capturar
                const chartContainer = document.getElementById('graficoRankingSolicitudes');

            // Utiliza html2canvas para capturar el gráfico como una imagen
            html2canvas(chartContainer).then(function (canvas) {
                // Convierte el canvas a una imagen en formato JPEG
                const imageDataURL = canvas.toDataURL('image/jpeg');

                // Crea un enlace de descarga
                const link = document.createElement('a');
                link.href = imageDataURL;
                link.download = 'Grafico.jpg'; // Cambia el nombre del archivo según tu preferencia

                // Haz clic en el enlace para iniciar la descarga
                link.click();
            });
        });
    </script>


    {{--!! tabla contingencia (FILTROS) --}}
    <script>
        $(document).ready(function () {
            var selectedUbicaciones = {};
            var selectedDepartamentos = {};

            //Datatable inicial
            // Inicializa la tabla como DataTable
            var table = $('#ubicaciones-table').DataTable({
                "lengthMenu": [[5,10, 50, -1], [5, 10, 50, "All"]],
                "responsive": true,
                "columnDefs": [
                    { "orderable": false, "targets": 3 }
                ],
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/es_es.json"
                },
            });

            //Select region
            // $('#region-select').on('change', function() {
            //     var regionId = $(this).val();

            //     // Limpia los selectores de direcciones regionales y ubicaciones
            //     $('#direccion-select').empty();
            //     $('#direccion-select').append('<option>Selecciona una dirección regional</option>'); // Agrega nuevamente la opción predeterminada

            //     $('#ubicacion-select').empty();
            //     $('#ubicacion-select').append('<option>Selecciona una ubicación</option>'); // Agrega nuevamente la opción predeterminada

            //     if(regionId) {
            //         $.ajax({
            //             url: '/api/get-direcciones/'+regionId,
            //             type: 'GET',
            //             dataType: 'json',
            //             success: function(data) {
            //                 $.each(data, function(key, value) {
            //                     $('#direccion-select').append('<option value="'+ value.OFICINA_ID +'">'+ value.OFICINA_NOMBRE +'</option>');
            //                 });
            //             }
            //         });
            //     }
            // });
            //Select direccion regional
            $('#direccion-select').on('change', function() {
                var direccionId = $(this).val();

                // Limpia el selector de ubicaciones
                $('#ubicacion-select').empty();
                $('#ubicacion-select').append('<option>Selecciona una ubicación</option>'); // Agrega nuevamente la opción predeterminada

                if(direccionId) {
                    $.ajax({
                        url: '/api/get-ubicaciones/'+direccionId,
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            $.each(data, function(key, value) {
                                if(!selectedUbicaciones[value.UBICACION_ID]) { // Si la ubicación no ha sido seleccionada previamente
                                    $('#ubicacion-select').append('<option value="'+ value.UBICACION_ID +'">'+ value.UBICACION_NOMBRE +'</option>');
                                }
                            });
                        }
                    });

                    $.ajax({
                        url: '/api/get-departamentos/'+direccionId,
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            $.each(data, function(key, value) {
                                if(!selectedDepartamentos[value.DEPARTAMENTO_ID]) { // Si la ubicación no ha sido seleccionada previamente
                                    $('#departamento-select').append('<option value="'+ value.DEPARTAMENTO_ID +'">'+ value.DEPARTAMENTO_NOMBRE +'</option>');
                                }
                            });
                        }
                    });
                }
            });
            //FIN FILTROS
            //select ubicacion y filtrado
            $('#ubicacion-select').on('change', function() {
                var ubicacionId = $(this).val();

                if(ubicacionId && !selectedUbicaciones[ubicacionId]) {
                    $.ajax({
                        url: '/api/get-totals/ubicaciones/'+ubicacionId,
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            var tableRow = [data.ubicacion, data.hombres, data.mujeres, data.total];
                            table.row.add(tableRow).draw(); // Agrega la nueva fila y re-renderiza la tabla
                            selectedUbicaciones[ubicacionId] = true; // Agrega el ubicacionId seleccionado al objeto selectedUbicaciones

                            // Actualizar los totales
                            $('#total-hombres').text(Number($('#total-hombres').text()) + data.hombres);
                            $('#total-mujeres').text(Number($('#total-mujeres').text()) + data.mujeres);
                            $('#total-total').text(Number($('#total-total').text()) + data.total);

                            // Obtener el ID de la dirección actualmente seleccionada
                            var direccionId = $('#direccion-select').val();

                            // Realizar una nueva solicitud AJAX para obtener las ubicaciones
                            $.ajax({
                                url: '/api/get-ubicaciones/'+direccionId,
                                type: 'GET',
                                dataType: 'json',
                                success: function(data) {
                                    $('#ubicacion-select').empty();
                                    $('#ubicacion-select').append('<option>Selecciona una ubicación</option>'); // Agrega nuevamente la opción predeterminada

                                    $.each(data, function(key, value) {
                                        if(!selectedUbicaciones[value.UBICACION_ID]) { // Si la ubicación no ha sido seleccionada previamente
                                            $('#ubicacion-select').append('<option value="'+ value.UBICACION_ID +'">'+ value.UBICACION_NOMBRE +'</option>');
                                        }
                                    });
                                }
                            });


                        }
                    });
                }
            });
            //select ubicacion y filtrado
            $('#departamento-select').on('change', function() {
                var departamentoId = $(this).val();

                if(departamentoId && !selectedUbicaciones[departamentoId]) {
                    $.ajax({
                        url: '/api/get-totals/departamentos/'+departamentoId,
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            var tableRow = [data.departamento, data.hombres, data.mujeres, data.total];
                            table.row.add(tableRow).draw(); // Agrega la nueva fila y re-renderiza la tabla
                            selectedDepartamentos[departamentoId] = true; // Agrega el departamentoId seleccionado al objeto selectedDepartamentos

                            // Actualizar los totales
                            $('#total-hombres').text(Number($('#total-hombres').text()) + data.hombres);
                            $('#total-mujeres').text(Number($('#total-mujeres').text()) + data.mujeres);
                            $('#total-total').text(Number($('#total-total').text()) + data.total);

                            // Obtener el ID de la dirección actualmente seleccionada
                            var direccionId = $('#direccion-select').val();

                            // Realizar una nueva solicitud AJAX para obtener los departamentos
                            $.ajax({
                                url: '/api/get-departamentos/'+direccionId,
                                type: 'GET',
                                dataType: 'json',
                                success: function(data) {
                                    $('#departamento-select').empty();
                                    $('#departamento-select').append('<option>Selecciona un departamento</option>'); // Agrega nuevamente la opción predeterminada

                                    $.each(data, function(key, value) {
                                        if(!selectedDepartamentos[value.DEPARTAMENTO_ID]) { // Si la ubicación no ha sido seleccionada previamente
                                            $('#departamento-select').append('<option value="'+ value.DEPARTAMENTO_ID +'">'+ value.DEPARTAMENTO_NOMBRE +'</option>');
                                        }
                                    });
                                }
                            });
                        }
                    });
                }
            });
        });
    </script>
@endsection
