@extends('adminlte::page')

@section('title', 'Reportes de Materiales')

@section('content_header')
    <h1>Reportes de Materiales</h1>
@endsection

@section('content')

    <!-- Agregar los elementos input de fecha aquí -->
    <div class="container">
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
        <div class="row">
            <div class="col-md-12">
                <button id="refresh-button" class="btn btn-primary">Actualizar</button>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <canvas id="grafico1"></canvas>
        </div>
        <div class="col-md-6">
            <canvas id="grafico2"></canvas>
        </div>
    </div>
@endsection

@section('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
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
@endsection
