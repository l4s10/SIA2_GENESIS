@extends('adminlte::page')

@section('title', 'Reportes de Vehículos')

@section('content_header')
    <h1>Reportes de Vehículos</h1>
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
                <div id="map" style="display: none;"></div>
                    <button id="refresh-button" class="btn guardar"><i class="fa-solid fa-rotate-right"></i> Actualizar</button>
                    <button id="map-open" class="btn btn-primary move-right" onclick="toggleMap()"><i class="fa-solid fa-map-location-dot"></i></button>
                    <button id="print-button" class="btn pdf"><i class="fa-solid fa-print"></i> / <i class="fa-solid fa-file-pdf"></i></button>
                </div>
            </div>
        </div>
    </div>

    {{-- Contenedor para las alertas de mensajes --}}
    <div class="row">
        <div class="col-12">
            <div id="filter-message" class="alert alert-color text-center" role="alert">
                <!-- El mensaje de fechas filtradas se mostrará aquí -->
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <canvas id="grafico1"></canvas>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <canvas id="grafico2"></canvas>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <canvas id="grafico3"></canvas>
                <div class="card-footer">
                    <button id="viewLargeChart" class="btn vergrafico"><i class="fa-solid fa-maximize"></i></button>
                    <button id="download-jpeg-button" class="btn descagargrafico"><i class="fa-solid fa-download"></i></button>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <canvas id="grafico4"></canvas>
                <div class="card-footer">
                    <button id="viewLargeChart1" class="btn vergrafico"><i class="fa-solid fa-maximize"></i></button>
                    <button id="download-jpeg-button-1" class="btn descagargrafico"><i class="fa-solid fa-download"></i></button>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <canvas id="grafico5"></canvas>
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
    <!-- Leaflet version mas reciente -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="crossorigin=""/>
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.css" />
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
        .pdf{
            background-color: #00B050;
            color: #fff;
        }
        .alert-color{
            background-color: #CEE7F6;
            color: #000;
        }
    </style>

    <!-- Estilos de la forma imprimir y descargar Pdf -->
    <style>
        @media print {
            /* Oculta el contenedor del formulario de filtrado */
            .accordion {
                display: none;
            }

            /* Oculta el mensaje de filtro */
            #filter-message {
                display: none;
            }

            /* Oculta los botones de descarga de los gráficos */
            .card-footer .btn.descagargrafico {
                display: none;
            }

            /* Oculta los botones de descarga de los gráficos */
            .card-footer .btn.vergrafico {
                display: none;
            }

            /* Oculta el botón  */
            .imprimir-ocultar {
                display: none;
            }
            /* Establece un diseño de cuadrícula para organizar los gráficos */
            .row {
                display: flex;
                flex-wrap: wrap;
            }

            .col-md-6 {
                width: 50%; /* Divide el ancho de la columna en dos */
            }

            .card {
                width: 100%; /* Ajusta el ancho de la tarjeta */
            }

            /* Ajusta el tamaño de los gráficos para que se ajusten a la cuadrícula */
            .card canvas {
                width: 100%;
                height: auto;
            }
        }
    </style>
@endsection

@section('js')
    <!-- Leaflet version mas reciente -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="crossorigin=""></script>
    <script src="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    {{-- Inicializar calendarios --}}
    <script src="{{ asset('js/Components/fechasGraficos.js') }}"></script>
    {{-- Importamos la logica del boton y el mensaje de filtro --}}
    <script src="{{asset('js/Components/graficoAlertaMensaje.js')}}"></script>

    {{-- Importar el archivo JS para obtener los datos --}}
    <script src="{{ asset('js/Graficos/Vehiculos/getData.js') }}"></script>

    {{-- Importar el archivo JS para el gráfico 1 --}}
    <script src="{{ asset('js/Graficos/Vehiculos/grafico1.js') }}"></script>
    {{-- Importar el archivo JS para el gráfico 2 --}}
    <script src="{{ asset('js/Graficos/Vehiculos/grafico2.js') }}"></script>
    {{-- Importamos el archivo JS para el grafico 3 --}}
    <script src="{{ asset('js/Graficos/Vehiculos/grafico3.js') }}"></script>
    {{-- Importamos el archivo JS para el grafico 4 --}}
    <script src="{{ asset('js/Graficos/Vehiculos/grafico4.js') }}"></script>
    {{-- Importamos el archivo JS para el grafico 5 --}}
    <script src="{{ asset('js/Graficos/Vehiculos/grafico5.js') }}"></script>

    <!-- Scrip para inicizaliar el mapa -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var mapDiv = document.getElementById('map');
            var mapOpenButton = document.getElementById('map-open');
            var map;
            var featureGroup;

            function toggleMap() {
                if (mapDiv.style.display === 'none') {
                    mapDiv.style.display = 'block';
                    mapDiv.requestFullscreen(); // Solicita el modo de pantalla completa para el elemento del mapa
                    initializeMap();
                } else {
                    mapDiv.style.display = 'none';
                    document.exitFullscreen(); // Sale del modo de pantalla completa si el mapa ya no está visible
                    mapOpenButton.disabled = false; // Restablece el botón a su estado original
                }
            }

            function initializeMap() {
                var map = L.map('map');

                L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                }).addTo(map);

                fetch('/api/reportes/vehiculos/georeferenciacion', {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                })
                .then(response => response.json())
                .then(data => {
                    // Setear la view en la coordenada de la comuna de salida
                    map.setView([data.comunaSalida.coordinates[0],data.comunaSalida.coordinates[1]], 13);

                    // Obtener las comunas filtradas y la comuna de salida desde la respuesta
                    const { comunasFiltradas, comunaSalida } = data;

                    // Crear un marcador para la comuna de salida
                    const salidaMarker = L.marker(comunaSalida.coordinates).addTo(map);
                    salidaMarker.bindPopup(comunaSalida.comuna + ' (Salida)');

                    // Configurar el enrutamiento desde la comuna de salida a cada comuna filtrada
                    comunasFiltradas.forEach(comuna => {
                        // Crear un marcador para cada comuna en las coordenadas recibidas
                        const marker = L.marker(comuna.coordinates).addTo(map);
                        marker.bindPopup(comuna.comuna);
                    });

                    // Configurar el enrutamiento desde la comuna de salida a cada comuna filtrada
                    const waypoints = [L.latLng(comunaSalida.coordinates)];
                    comunasFiltradas.forEach(comuna => {
                        waypoints.push(L.latLng(comuna.coordinates));
                    });

                    L.Routing.control({
                        waypoints: waypoints,
                        language: 'es',
                        lineOptions: {
                            styles: [
                                { color: 'blue', opacity: 0.6, weight: 4 },
                            ]
                        },
                        show: true, // Oculta la lista de instrucciones de ruta
                    }).addTo(map);
                })
                .catch(error => {
                    console.error('Error al cargar los datos:', error);
                });

            }

            mapOpenButton.addEventListener('click', toggleMap);
        });
    </script>

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
                showChart('grafico3');
            });

            // Manejar el evento de clic en el enlace del segundo gráfico (grafico4 de barra)
            $('#viewLargeChart1').click(function(e) {
                e.preventDefault();
                showChart('grafico4');
            });

        });
    </script>

    <!-- Scrip para la descargar de los graficos -->
    <script>
        // Agrega un controlador de eventos al botón de descarga en formato JPEG
            const downloadJPEGButton = document.getElementById('download-jpeg-button');
            downloadJPEGButton.addEventListener('click', function () {
                // Selecciona el elemento del gráfico que deseas capturar
                const chartContainer = document.getElementById('grafico3');

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
                const chartContainer1 = document.getElementById('grafico4');

                html2canvas(chartContainer1).then(function (canvas) {
                    const imageDataURL = canvas.toDataURL('image/jpeg');

                    const link = document.createElement('a');
                    link.href = imageDataURL;
                    link.download = 'Grafico1.jpg';

                    link.click();
                });
            });
    </script>

    <!-- Script para Imprimir/Descargar Pdf -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const printButton = document.getElementById('print-button');
            printButton.addEventListener('click', function () {
                window.print();
            });
        });
    </script>

@endsection
