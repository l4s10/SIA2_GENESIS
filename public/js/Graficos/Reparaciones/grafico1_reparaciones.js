document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('graficoReparacionesPorCategoria').getContext('2d');
    const chart = new Chart(ctx, {
        type: 'bar', // Puedes cambiar esto según necesites, por ejemplo, a 'pie'
        data: {
            labels: [],
            datasets: [{
                label: 'Reparaciones por Categoría',
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
                    text: 'Reparaciones por Categoría',
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
        chart.data.labels = data.grafico1.reparacionesPorCategoria.map(item => item.CATEGORIA_REPARACION_NOMBRE);
        chart.data.datasets[0].data = data.grafico1.reparacionesPorCategoria.map(item => item.cantidad);
        chart.data.datasets[0].backgroundColor = data.grafico1.reparacionesPorCategoria.map(() => getRandomColor());
        chart.update();
    }

    // Suponiendo que window.getData.getInitialChartData() trae los datos necesarios
    window.getData.getInitialChartData()
        .then(data => {
            if (data.status === 'success') {
                updateChart(data.data);
            }
        })
        .catch(error => console.error('Error:', error));
});
