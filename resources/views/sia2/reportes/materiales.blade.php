@extends('adminlte::page')

@section('title', 'Reportes de Materiales')

@section('content_header')
    <h1>Reportes de Materiales</h1>
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
                    <button id="refresh-button" class="btn guardar">Actualizar</button>
                    <button id="map-open" class="btn btn-primary move-right" onclick="toggleMap()"><i class="fa-solid fa-map-location-dot"></i></button>
                </div>
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
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <canvas id="grafico4"></canvas>
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
    </style>
@endsection

@section('js')
    <!-- Leaflet version mas reciente -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="crossorigin=""></script>
    <script src="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    {{-- <script>
        // Inicializar Flatpickr
        document.addEventListener('DOMContentLoaded', function() {
            flatpickr('#start-date', {
                minDate: '2019-01-01',
                maxDate: 'today',
                dateFormat: 'Y-m-d', // Formato visible al usuario
                altFormat: 'd-m-Y', // Formato de envío al servidor
                altInput: true, // Habilitar el campo de entrada alternativo
                defaultDate: 'today'
            });
            flatpickr('#end-date', {
                minDate: '2019-01-01',
                maxDate: 'today',
                dateFormat: 'Y-m-d', // Formato visible al usuario
                altFormat: 'd-m-Y', // Formato de envío al servidor
                altInput: true, // Habilitar el campo de entrada alternativo
                defaultDate: 'today'
            });
        });
    </script> --}}


    {{-- @if(session('api_token'))
    <script>
        localStorage.setItem('api_token', '{{ session('api_token') }}');
        console.log('API Token stored:', localStorage.getItem('api_token'));
    </script>
    @endif --}}

    {{-- Inicializar calendarios --}}
    <script src="{{ asset('js/Components/fechasGraficos.js') }}"></script>

    {{-- Importar el archivo JS para obtener los datos --}}
    <script src="{{ asset('js/Graficos/Materiales/getData.js') }}"></script>

    {{-- Importar el archivo JS para el gráfico 1 --}}
    <script src="{{ asset('js/Graficos/Materiales/grafico1.js') }}"></script>
    {{-- Importar el archivo JS para el gráfico 2 --}}
    <script src="{{ asset('js/Graficos/Materiales/grafico2.js') }}"></script>
    {{-- Importamos el archivo JS para el grafico 3 --}}
    <script src="{{ asset('js/Graficos/Materiales/grafico3.js') }}"></script>
    {{-- Importamos el archivo JS para el grafico 4 --}}
    <script src="{{ asset('js/Graficos/Materiales/grafico4.js') }}"></script>
    {{-- Importamos el archivo JS para el grafico 5 --}}
    <script src="{{ asset('js/Graficos/Materiales/grafico5.js') }}"></script>

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
                var map = L.map('map').setView([-36.8261, -73.0498], 13); // Coordenadas de Concepción, Chile y nivel de zoom 13

                L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                }).addTo(map);

                // Carga el archivo GeoJSON y agrega las comunas como marcadores en el mapa
                fetch('json/comunasbiobio.geojson')
                    .then(response => response.json())
                    .then(data => {
                        L.geoJSON(data, {
                            pointToLayer: function (feature, latlng) {
                                return L.marker(latlng).bindPopup(feature.properties.comuna);
                            }
                        }).addTo(map);
                    })
                    .catch(error => {
                        console.error('Error al cargar el archivo GeoJSON:', error);
                    });

                // Agregar el control de enrutamiento
                L.Routing.control({
                    waypoints: [
                        L.latLng(-36.8261, -73.0498),  // Coordenadas de Concepción
                        L.latLng(-36.7167, -73.1167)   // Coordenadas de Talcahuano
                    ],
                    language: 'es',
                    lineOptions: {
                        styles: [
                            { color: 'red', opacity: 0.6, weight: 4 },
                        ]
                    }
                }).addTo(map);
            }
            mapOpenButton.addEventListener('click', toggleMap);
        });
</script>
@endsection
