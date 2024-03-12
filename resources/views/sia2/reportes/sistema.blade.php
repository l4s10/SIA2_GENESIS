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
                    <button id="refresh-button" class="btn guardar"><i class="fa-solid fa-rotate-right"></i> Actualizar</button>
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
        <div class="col-md-12">
            <div class="card">
                <canvas id="graficoRankingSolicitudes"></canvas>
                <div class="card-footer">
                    <button id="download-jpeg-button" class="btn descagargrafico"><i class="fa-solid fa-download"></i></button>
                </div>
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
            .card-footer .btn.descagargrafico  {
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
