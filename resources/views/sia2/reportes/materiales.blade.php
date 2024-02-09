@extends('adminlte::page')

@section('title', 'Estadísticas de materiales')

@section('content_header')
    <h1>Estadísticas de materiales</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <canvas id="graficoGestiones" width="100" height="100"></canvas>
            </div>
        </div>
    </div>
@stop

@section('css')
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Primero, solicita la cookie CSRF
            fetch('http://localhost:8000/sanctum/csrf-cookie', {
                method: 'GET',
                credentials: 'include', // Importante para incluir cookies con la solicitud
            }).then(response => {
                // La cookie CSRF se ha establecido, ahora puedes hacer solicitudes POST, PUT, etc.
                console.log('CSRF cookie set successfully');
                const apiToken = localStorage.getItem('api_token');
                console.log(localStorage.getItem('api_token'));
                // Solicitud GET a tu API
                fetch('http://localhost:8000/api/reportes/materiales/get-graficos', {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': 'Bearer ' + localStorage.getItem('api_token')
                    },
                    credentials: 'include' // Necesario para las cookies de sesión cuando se usa Sanctum
                })
                .then(response => response.json())
                .then(data => {
                    if(data.status === 'success') {
                        // Aquí es donde procesarías la data para cada gráfico.
                        // Por ejemplo, para grafico1:
                        if(data.data.grafico1 && data.data.grafico1.ranking) {
                            const ctx = document.getElementById('graficoGestiones').getContext('2d');
                            const chartGrafico1 = new Chart(ctx, {
                                type: 'pie', // Tipo de gráfico
                                data: {
                                    labels: data.data.grafico1.ranking.map(item => item.nombre_completo),
                                    datasets: [{
                                        data: data.data.grafico1.ranking.map(item => item.total_gestiones),
                                        backgroundColor: [
                                            'rgba(255, 99, 132, 0.8)', 'rgba(54, 162, 235, 0.8)',
                                            'rgba(255, 206, 86, 0.8)', 'rgba(75, 192, 192, 0.8)',
                                            'rgba(153, 102, 255, 0.8)', 'rgba(255, 159, 64, 0.8)',
                                            'rgba(201, 203, 207, 0.8)'
                                        ],
                                        borderColor: [
                                            'rgba(255, 99, 132, 1)', 'rgba(54, 162, 235, 1)',
                                            'rgba(255, 206, 86, 1)', 'rgba(75, 192, 192, 1)',
                                            'rgba(153, 102, 255, 1)', 'rgba(255, 159, 64, 1)',
                                            'rgba(201, 203, 207, 1)'
                                        ],
                                        borderWidth: 1
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    plugins: {
                                        legend: { position: 'top' },
                                        title: {
                                            display: true,
                                            text: 'Grafico 1: Ranking de Gestiones'
                                        }
                                    }
                                }
                            });
                        }
                        // Aquí podrías añadir más lógica para procesar y mostrar otros gráficos como grafico2, etc.
                    }
                })
                .catch(error => console.error('Error al cargar los datos de los gráficos:', error));
            }).catch(error => {
                console.error('Error al establecer la cookie CSRF:', error);
            });
        });
    </script>

@stop
