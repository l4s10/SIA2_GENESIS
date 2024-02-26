document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('graficoVehiculosConMasReparaciones').getContext('2d');
    const chart = new Chart(ctx, {
        type: 'bar', // Puedes elegir el tipo de gráfico que prefieras
        data: {
            labels: [],
            datasets: [{
                label: 'Vehículos con Más Reparaciones',
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
                    text: 'Vehículos con Más Reparaciones',
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
        chart.data.labels = data.grafico7.vehiculosConMasReparaciones.map(item => item.patente);
        chart.data.datasets[0].data = data.grafico7.vehiculosConMasReparaciones.map(item => item.cantidad);
        chart.data.datasets[0].backgroundColor = data.grafico7.vehiculosConMasReparaciones.map(() => getRandomColor());
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
