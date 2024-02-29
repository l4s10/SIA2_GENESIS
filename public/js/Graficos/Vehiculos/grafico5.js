document.addEventListener('DOMContentLoaded', function() {
    const ctx5 = document.getElementById('grafico5').getContext('2d');
    const chart5 = new Chart(ctx5, {
        type: 'line', // Cambia según necesites
        data: {
            labels: ['Promedio Creación-Atención', 'Promedio Revisión-Aprobación', 'Promedio Aprobación-Entrega'],
            datasets: [{
                label: 'Tiempos Promedio (días)',
                data: [],
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            plugins: {
                legend: { display: true },
                title: {
                    display: true,
                    text: 'Promedio en dias de los tiempos de atención de solicitudes de vehículos',
                    padding: { top: 10, bottom: 30 }
                }
            }
        }
    });

    function updateChart5(data) {
        chart5.data.datasets[0].data = [
            data.grafico5.promedioAtencion || 0,
            data.grafico5.promedioRevisionAprobacion || 0, // Usar 0 si es null
            data.grafico5.promedioAprobacionEntrega || 0
        ];
        chart5.update();
    }

    // Utiliza getData global para obtener y actualizar los datos del gráfico
    window.getData.getInitialChartData()
        .then(data => {
            if (data.status === 'success') {
                updateChart5(data.data); // Asegúrate de que data.data contenga la estructura correcta
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
                    updateChart5(data.data);
                }
            })
            .catch(error => {
                Swal.fire('Error', 'No se pudieron actualizar los datos.', 'error');
                console.error('Error:', error);
            });
    });
});
