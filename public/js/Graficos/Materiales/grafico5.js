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
                    text: 'Promedio en dias de los tiempos de atención de solicitudes de materiales',
                    padding: { top: 10, bottom: 30 }
                }
            }
        }
    });

    function updateChart5(data) {
        chart5.data.datasets[0].data = [
            data.grafico5.promedioAtencion.promedio_creacion_atencion,
            data.grafico5.promedioRevisionAprobacion.promedio_revision_aprobacion || 0, // Usar 0 si es null
            data.grafico5.promedioAprobacionEntrega.promedio_aprobacion_entrega
        ];
        chart5.update();
    }

    window.getData.getInitialChartData().then(data => updateChart5(data.data)).catch(error => console.error(error));
});
