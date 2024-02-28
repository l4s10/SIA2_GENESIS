document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('graficoRankingSolicitudes').getContext('2d');
    const chart = new Chart(ctx, {
        type: 'bar', // O el tipo de gráfico que prefieras
        data: {
            labels: [],
            datasets: [{
                label: 'Número de Solicitudes',
                data: [],
                backgroundColor: [],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                title: {
                    display: true,
                    text: 'Ranking de Solicitudes por Categoría',
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
        const categorias = Object.keys(data.grafico1.rankingSolicitudes);
        const cantidades = Object.values(data.grafico1.rankingSolicitudes);
        chart.data.labels = categorias;
        chart.data.datasets[0].data = cantidades;
        chart.data.datasets[0].backgroundColor = categorias.map(() => getRandomColor());
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
