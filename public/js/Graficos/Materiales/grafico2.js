document.addEventListener('DOMContentLoaded', function() {
    const ctx2 = document.getElementById('grafico2').getContext('2d');

    const initialChartData = {
        labels: [],
        datasets: [{
            label: 'Solicitudes por Entidad',
            data: [],
            backgroundColor: [],
            borderWidth: 1
        }]
    };

    const myChart = new Chart(ctx2, {
        type: 'pie',
        data: initialChartData,
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: true,
                    labels: {
                        // Aquí puedes configurar las etiquetas de la leyenda
                    }
                },
                title: {
                    display: true,
                    text: 'Solicitudes de materiales requeridos por Ubicacion/Departamento',
                    padding: {
                        top: 10,
                        bottom: 30
                    }
                }
            }
        }
    });

    function getRandomColor() {
        const letters = '0123456789ABCDEF';
        let color = '#';
        for (let i = 0; i < 6; i++) {
            color += letters[Math.floor(Math.random() * 16)];
        }
        return color;
    }

    function updateChart(data) {
        const newData = data.grafico2.solicitudesPorEntidad.map(item => ({
            label: item.entidad,
            value: item.total_solicitudes,
            color: getRandomColor()
        }));
        myChart.data.labels = newData.map(item => item.label);
        myChart.data.datasets[0].data = newData.map(item => item.value);
        myChart.data.datasets[0].backgroundColor = newData.map(item => item.color);
        myChart.update();
    }

    // Al cargar la página por primera vez, consumir la ruta sin fechas
    fetch('/api/reportes/materiales/get-graficos', {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            // 'Authorization': 'Bearer ' + localStorage.getItem('api_token'),
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')

        },
    })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                updateChart(data.data); // Actualizar el segundo gráfico
            }
        })
        .catch(error => console.error('Error:', error));

        document.querySelector('#refresh-button').addEventListener('click', function () {
            var fechaInicio = document.querySelector('#start-date').value;
            var fechaFin = document.querySelector('#end-date').value;

            Swal.fire({
                title: 'Actualizando registros',
                timer: 2000,
                didOpen: () => {
                    Swal.showLoading();
                },
                willClose: () => {
                   // Al cerrarse
                }
            });

            fetch('/api/reportes/materiales/filtrar-general', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    // 'Authorization': 'Bearer ' + localStorage.getItem('api_token'),
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    fecha_inicio: fechaInicio,
                    fecha_fin: fechaFin
                })
            })
            .then(response => response.json())
            .then(data => {
                Swal.close();
                if (data.status === 'success') {
                    updateChart(data.data);
                }
            })
            .catch(error => {
                Swal.fire('Error', 'No se pudieron actualizar los datos.', 'error');
                console.error('Error:', error);
            });
        });
});
