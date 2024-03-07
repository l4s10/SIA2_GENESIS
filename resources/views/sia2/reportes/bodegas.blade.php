@extends('adminlte::page')

@section('title', 'Reportes de Bodegas')

@section('content_header')
    <h1>Reportes de Bodegas</h1>
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>/* Estilos personalizados si es necesario */
        .guardar {
            background-color: #e6500a;
            color: #fff;
        }
    </style>
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    {{-- Inicializar calendarios --}}
    <script src="{{ asset('js/Components/fechasGraficos.js') }}"></script>
    {{-- Importamos la logica del boton y el mensaje de filtro --}}
    <script src="{{asset('js/Components/graficoAlertaMensaje.js')}}"></script>

    {{-- Importar el archivo JS para obtener los datos --}}
    <script src="{{ asset('js/Graficos/Bodegas/getData.js') }}"></script>

    {{-- Importar el archivo JS para el gráfico 1 --}}
    <script src="{{ asset('js/Graficos/Bodegas/grafico1.js') }}"></script>
    {{-- Importar el archivo JS para el gráfico 2 --}}
    <script src="{{ asset('js/Graficos/Bodegas/grafico2.js') }}"></script>
    {{-- Importamos el archivo JS para el grafico 3 --}}
    <script src="{{ asset('js/Graficos/Bodegas/grafico3.js') }}"></script>
    {{-- Importamos el archivo JS para el grafico 4 --}}
    <script src="{{ asset('js/Graficos/Bodegas/grafico4.js') }}"></script>
    {{-- Importamos el archivo JS para el grafico 5 --}}
    <script src="{{ asset('js/Graficos/Bodegas/grafico5.js') }}"></script>

@endsection
