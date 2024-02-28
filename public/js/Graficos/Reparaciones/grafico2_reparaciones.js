document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('graficoEstadosReparacionesFisicas').getContext('2d');
    const chart = new Chart(ctx, {
        type: 'doughnut', // Puedes cambiar el tipo de gráfico según prefieras (ej., 'bar', 'pie')
        data: {
            labels: [],
            datasets: [{
                label: 'Estado de Reparaciones Físicas',
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
                    text: 'Ranking de Estados de Reparaciones Físicas',
                    padding: { top: 10, bottom: 30 }
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
        chart.data.labels = data.grafico3.rankingEstadosReparacionesFisicas.map(item => item.estado);
        chart.data.datasets[0].data = data.grafico3.rankingEstadosReparacionesFisicas.map(item => item.cantidad);
        chart.data.datasets[0].backgroundColor = data.grafico3.rankingEstadosReparacionesFisicas.map(() => getRandomColor());
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
