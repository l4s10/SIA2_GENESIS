@extends('adminlte::page')

@section('title', 'Reportes de Reparaciones/Mantenciones')

@section('content_header')
    <h1>Reportes de reparaciones y mantenciones</h1>
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
        <div class="col-md-6">
            <div class="card">
                <canvas id="graficoReparacionesPorCategoria"></canvas>
                <div class="card-footer">
                    <button id="viewLargeChart" class="btn vergrafico"><i class="fa-solid fa-maximize"></i></button>
                    <button id="download-jpeg-button" class="btn descagargrafico"><i class="fa-solid fa-download"></i></button>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <canvas id="graficoMantencionesPorCategoria"></canvas>
                <div class="card-footer">
                    <button id="viewLargeChart1" class="btn vergrafico"><i class="fa-solid fa-maximize"></i></button>
                    <button id="download-jpeg-button-1" class="btn descagargrafico"><i class="fa-solid fa-download"></i></button>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <canvas id="graficoEstadosReparacionesFisicas"></canvas>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <canvas id="graficoEstadosMantencionesFisicas"></canvas>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <canvas id="graficoSolicitudesReparacionesPorDepartamento"></canvas>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <canvas id="graficoSolicitudesMantencionesPorDepartamento"></canvas>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <canvas id="graficoVehiculosConMasReparaciones"></canvas>
                <div class="card-footer">
                    <button id="download-jpeg-button-2" class="btn descagargrafico"><i class="fa-solid fa-download"></i></button>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <canvas id="graficoGestionadoresSolicitudesInmuebles"></canvas>
                <div class="card-footer">
                    <button id="viewLargeChart2" class="btn vergrafico"><i class="fa-solid fa-maximize"></i></button>
                    <button id="download-jpeg-button-3" class="btn descagargrafico"><i class="fa-solid fa-download"></i></button>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <canvas id="graficoGestionadoresSolicitudesMantenciones"></canvas>
                <div class="card-footer">
                    <button id="viewLargeChart3" class="btn vergrafico"><i class="fa-solid fa-maximize"></i></button>
                    <button id="download-jpeg-button-4" class="btn descagargrafico"><i class="fa-solid fa-download"></i></button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para mostrar el gráfico en grande -->
    <div class="modal fade" id="chart-modal" tabindex="-1" aria-labelledby="chart-modal-label" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="chart-modal-label">Gráfico en grande</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" ><i class="fa-solid fa-xmark"></i></button>
                </div>
                    <div class="modal-body">
                    <!-- Aquí se mostrará el gráfico en grande -->
                    <canvas id="modalChart"></canvas>
                </div>
        </div>
    </div>
@endsection

@section('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>/* Estilos personalizados si es necesario */
        .guardar {
            background-color: #e6500a;
            color: #fff;
        }
        .vergrafico{
            background-color: #0F69B4;
            color: #fff;
        }
        .descagargrafico{
            background-color: #00B050;
            color: #fff;
        }
    </style>
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    {{-- Inicializar calendarios --}}
    <script src="{{ asset('js/Components/fechasGraficos.js') }}"></script>

    {{-- Importar el archivo JS para obtener los datos --}}
    <script src="{{ asset('js/Graficos/Reparaciones/getData.js') }}"></script>

    {{-- Importar el archivo JS para el gráfico 1 --}}
    <script src="{{ asset('js/Graficos/Reparaciones/grafico1_reparaciones.js') }}"></script>
    {{-- Importar el archivo JS para el gráfico 2 --}}
    <script src="{{ asset('js/Graficos/Reparaciones/grafico1_mantenciones.js') }}"></script>
    {{-- Importar el archivo JS para el gráfico 3 --}}
    <script src="{{ asset('js/Graficos/Reparaciones/grafico2_reparaciones.js') }}"></script>
    {{-- Importar el archivo JS para el gráfico 4 --}}
    <script src="{{ asset('js/Graficos/Reparaciones/grafico2_mantenciones.js') }}"></script>
    {{-- Importar el archivo JS para el gráfico 5 --}}
    <script src="{{ asset('js/Graficos/Reparaciones/grafico3_reparaciones.js') }}"></script>
    {{-- Importar el archivo JS para el gráfico 6 --}}
    <script src="{{ asset('js/Graficos/Reparaciones/grafico3_mantenciones.js') }}"></script>
    {{-- Importar el archivo JS para el gráfico 7 --}}
    <script src="{{ asset('js/Graficos/Reparaciones/grafico4_mantenciones.js') }}"></script>
    {{-- Importar el archivo JS para el gráfico 8 --}}
    <script src="{{ asset('js/Graficos/Reparaciones/grafico5_mantenciones.js') }}"></script>
    {{-- Importar el archivo JS para el gráfico 9 --}}
    <script src="{{ asset('js/Graficos/Reparaciones/grafico4_reparaciones.js') }}"></script>
    {{-- Importamos la logica del boton y el mensaje de filtro --}}
    <script src="{{asset('js/Components/graficoAlertaMensaje.js')}}"></script>


    <!-- Scrip para sacar pantallazo a los graficos y hacerlos grandes. -->
    <script>
        function showChart(chartId) {
            // Obtener la imagen del gráfico
            var chartImage = $('#' + chartId)[0].toDataURL();
            // Mostrar el gráfico en un modal
            var modalContent = '<img src="' + chartImage + '" alt="Gráfico" style="width: 100%;">';
            $('#chart-modal .modal-body').html(modalContent);
            $('#chart-modal').modal('show');
        }

        $(document).ready(function() {
            // Manejar el evento de clic en el enlace del primer gráfico (grafico3 de barra)
            $('#viewLargeChart').click(function(e) {
                e.preventDefault();
                showChart('graficoReparacionesPorCategoria');
            });

            // Manejar el evento de clic en el enlace del segundo gráfico (grafico4 de barra)
            $('#viewLargeChart1').click(function(e) {
                e.preventDefault();
                showChart('graficoMantencionesPorCategoria');
            });

            // Manejar el evento de clic en el enlace del segundo gráfico (grafico4 de barra)
            $('#viewLargeChart2').click(function(e) {
                e.preventDefault();
                showChart('graficoGestionadoresSolicitudesInmuebles');
            });

            // Manejar el evento de clic en el enlace del segundo gráfico (grafico4 de barra)
            $('#viewLargeChart3').click(function(e) {
                e.preventDefault();
                showChart('graficoGestionadoresSolicitudesMantenciones');
            });

        });
    </script>

    <!-- Scrip para la descargar de los graficos -->
    <script>
        // Agrega un controlador de eventos al botón de descarga en formato JPEG
            const downloadJPEGButton = document.getElementById('download-jpeg-button');
            downloadJPEGButton.addEventListener('click', function () {
                // Selecciona el elemento del gráfico que deseas capturar
                const chartContainer = document.getElementById('graficoReparacionesPorCategoria');

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

            const downloadJPEGButton1 = document.getElementById('download-jpeg-button-1');
            downloadJPEGButton1.addEventListener('click', function () {
                const chartContainer1 = document.getElementById('graficoMantencionesPorCategoria');

                html2canvas(chartContainer1).then(function (canvas) {
                    const imageDataURL = canvas.toDataURL('image/jpeg');

                    const link = document.createElement('a');
                    link.href = imageDataURL;
                    link.download = 'Grafico1.jpg';

                    link.click();
                });
            });

            const downloadJPEGButton2 = document.getElementById('download-jpeg-button-2');
            downloadJPEGButton2.addEventListener('click', function () {
                const chartContainer2 = document.getElementById('graficoVehiculosConMasReparaciones');

                html2canvas(chartContainer2).then(function (canvas) {
                    const imageDataURL = canvas.toDataURL('image/jpeg');

                    const link = document.createElement('a');
                    link.href = imageDataURL;
                    link.download = 'Grafico1.jpg';

                    link.click();
                });
            });

            const downloadJPEGButton3 = document.getElementById('download-jpeg-button-3');
            downloadJPEGButton3.addEventListener('click', function () {
                const chartContainer3 = document.getElementById('graficoVehiculosConMasReparaciones');

                html2canvas(chartContainer3).then(function (canvas) {
                    const imageDataURL = canvas.toDataURL('image/jpeg');

                    const link = document.createElement('a');
                    link.href = imageDataURL;
                    link.download = 'Grafico1.jpg';

                    link.click();
                });
            });

            const downloadJPEGButton4 = document.getElementById('download-jpeg-button-4');
            downloadJPEGButton4.addEventListener('click', function () {
                const chartContainer4 = document.getElementById('graficoVehiculosConMasReparaciones');

                html2canvas(chartContainer4).then(function (canvas) {
                    const imageDataURL = canvas.toDataURL('image/jpeg');

                    const link = document.createElement('a');
                    link.href = imageDataURL;
                    link.download = 'Grafico1.jpg';

                    link.click();
                });
            });
    </script>
@endsection
