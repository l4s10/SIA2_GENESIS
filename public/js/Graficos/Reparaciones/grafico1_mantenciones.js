document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('graficoMantencionesPorCategoria').getContext('2d');
    const chart = new Chart(ctx, {
        type: 'bar', // O 'pie', dependiendo de cómo quieras visualizar los datos
        data: {
            labels: [],
            datasets: [{
                label: 'Mantenciones por Categoría',
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
                    text: 'Mantenciones por Categoría',
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
        chart.data.labels = data.grafico2.mantencionesPorCategoria.map(item => item.CATEGORIA_REPARACION_NOMBRE);
        chart.data.datasets[0].data = data.grafico2.mantencionesPorCategoria.map(item => item.cantidad);
        chart.data.datasets[0].backgroundColor = data.grafico2.mantencionesPorCategoria.map(() => getRandomColor());
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
