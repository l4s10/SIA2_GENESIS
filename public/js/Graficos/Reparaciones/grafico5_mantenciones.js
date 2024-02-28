document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('graficoGestionadoresSolicitudesMantenciones').getContext('2d');
    const chart = new Chart(ctx, {
        type: 'bar', // O el tipo de gráfico que prefieras
        data: {
            labels: [],
            datasets: [{
                label: 'Gestiones por Usuario',
                data: [],
                backgroundColor: [],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: true },
                title: {
                    display: true,
                    text: 'Gestionadores de Solicitudes de Mantenciones Vehiculares',
                    padding: { top: 10, bottom: 30 }
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    function getRandomColor() {
        let color = '#';
        for (let i = 0; i < 6; i++) {
            color += '0123456789ABCDEF'[Math.floor(Math.random() * 16)];
        }
        return color;
    }

    function updateChart(data) {
        chart.data.labels = data.grafico8.gestionadoresSolicitudesVehiculos.map(item => item.nombre_completo);
        chart.data.datasets[0].data = data.grafico8.gestionadoresSolicitudesVehiculos.map(item => item.total_gestiones);
        chart.data.datasets[0].backgroundColor = data.grafico8.gestionadoresSolicitudesVehiculos.map(() => getRandomColor());
        chart.update();
    }

    window.getData.getInitialChartData()
        .then(data => {
            if (data.status === 'success') {
                updateChart(data.data);
            }
        })
        .catch(error => console.error('Error:', error));
});
