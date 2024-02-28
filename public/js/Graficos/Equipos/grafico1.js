// grafico1.js
document.addEventListener('DOMContentLoaded', function() {
    const ctx1 = document.getElementById('grafico1').getContext('2d');
    const myChart = new Chart(ctx1, {
        type: 'pie',
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
                    text: 'Gestionadores de solicitudes de Equipos',
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
        myChart.data.labels = data.grafico1.ranking.map(item => item.nombre_completo);
        myChart.data.datasets[0].data = data.grafico1.ranking.map(item => item.total_gestiones);
        myChart.data.datasets[0].backgroundColor = data.grafico1.ranking.map(() => getRandomColor());
        myChart.update();
    }

    window.getData.getInitialChartData()
        .then(data => {
            if (data.status === 'success') {
                updateChart(data.data);
            }
        })
        .catch(error => console.error('Error:', error));

    document.querySelector('#refresh-button').addEventListener('click', function() {
        const fechaInicio = document.querySelector('#start-date').value;
        const fechaFin = document.querySelector('#end-date').value;

        Swal.fire({
            title: 'Actualizando registros',
            timer: 2000,
            didOpen: () => { Swal.showLoading(); },
        });

        window.getData.getFilteredChartData(fechaInicio, fechaFin)
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
