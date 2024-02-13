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
                    <button id="refresh-button" class="btn guardar">Actualizar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="accordion" id="accordionExample">
        <div class="card">
            <div class="card-header" id="headingOne">
                <h2 class="mb-0">
                    <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        Información adicional sobre el modulo.
                    </button>
                </h2>
            </div>

            <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="start-date">Ejemplo%</label>
                        </div>
                        <div class="col-md-6">
                            <label for="end-date">Ejemplo1%</label>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="start-date">Ejemplo2%</label>
                        </div>
                        <div class="col-md-6">
                            <label for="end-date">Ejemplo3%</label>
                        </div>
                    </div>
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

    <div class="card">
        <canvas id="grafico3"></canvas>
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

    <script>
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
    </script>


    @if(session('api_token'))
    <script>
        localStorage.setItem('api_token', '{{ session('api_token') }}');
        console.log('API Token stored:', localStorage.getItem('api_token'));
    </script>
    @endif

    {{-- Importar el archivo JS para el gráfico 1 --}}
    <script src="{{ asset('js/grafico1.js') }}"></script>
    {{-- Importar el archivo JS para el gráfico 2 --}}
    <script src="{{ asset('js/grafico2.js') }}"></script>
    {{-- Importamos el archivo JS para el grafico 3 --}}
    <script src="{{ asset('js/grafico3.js') }}"></script>
@endsection
