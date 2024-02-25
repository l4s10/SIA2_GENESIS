document.addEventListener('DOMContentLoaded', function() {
    const ctx4 = document.getElementById('grafico4').getContext('2d');
    const chart4 = new Chart(ctx4, {
        type: 'bar',
        data: {
            labels: [],
            datasets: [{
                label: 'Solicitudes por Estado',
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
                    text: 'Ranking de materiales mas solicitados',
                    padding: { top: 10, bottom: 30 }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { precision: 0 }
                }
            },
            barThickness: 65,
        }
    });

    function getRandomColor() {
        let color = '#';
        for (let i = 0; i < 6; i++) {
            color += '0123456789ABCDEF'[Math.floor(Math.random() * 16)];
        }
        return color;
    }

    function updateChart4(data) {
        chart4.data.labels = data.grafico4.rankingTiposMateriales.map(item => item.MATERIAL_NOMBRE);
        chart4.data.datasets[0].data = data.grafico4.rankingTiposMateriales.map(item => item.total_solicitudes);
        chart4.data.datasets[0].backgroundColor = data.grafico4.rankingTiposMateriales.map(() => getRandomColor());
        chart4.update();
    }

    window.getData.getInitialChartData().then(data => updateChart4(data.data)).catch(error => console.error(error));
});
