document.addEventListener('DOMContentLoaded', function() {
    const ctx3 = document.getElementById('grafico3').getContext('2d');

    const initialChartData = {
        labels: [],
        datasets: [{
            label: 'Solicitudes por Estado',
            data: [],
            backgroundColor: [],
            borderWidth: 1
        }]
    };

    const myChart = new Chart(ctx3, {
        type: 'bar',
        data: initialChartData,
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'top' },
                title: { display: true, text: 'Solicitudes por Estado' }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0 // Evita los decimales en el eje y
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
        const newData = data.grafico3.rankingEstados.map(item => ({
            label: item.SOLICITUD_ESTADO,
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
            'Authorization': 'Bearer ' + localStorage.getItem('api_token')
        }
    })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                updateChart(data.data); // Actualizar el tercer gráfico
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
                'Authorization': 'Bearer ' + localStorage.getItem('api_token'),
                'Content-Type': 'application/json',
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
