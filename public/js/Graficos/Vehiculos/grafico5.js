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
                    text: 'Promedio en dias de los tiempos de atención de solicitudes de ve',
                    padding: { top: 10, bottom: 30 }
                }
            }
        }
    });

    const currentDate = new Date();
    const firstDayOfMonth = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1);
    const formattedFirstDay = formatDate(firstDayOfMonth);
    const formattedCurrentDate = formatDate(currentDate);

    // Luego, cuando recibas la respuesta del servidor:
    window.getData.getFilteredChartData(formattedFirstDay, formattedCurrentDate)
    .then(response => {
        if (response.status === 'success') {
            const promedios = [
                response.data.grafico5.original.data.promedioAtencion.promedio_creacion_atencion || 0,
                response.data.grafico5.original.data.promedioRevisionAprobacion.promedio_revision_aprobacion || 0,
                response.data.grafico5.original.data.promedioAprobacionEntrega.promedio_aprobacion_entrega || 0
            ].map(Number); // Asegúrate de convertir a número

            // Aquí actualizas el gráfico con los nuevos datos
            chart5.data.datasets[0].data = promedios;
            chart5.update();
        }
    })
    .catch(error => console.error('Error:', error));

    // Funcion para formatear la fecha
    function formatDate(date) {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    }

    function updateChart(data) {
        // Asegúrate de acceder correctamente a cada valor promedio
        const promedioCreacionAtencion = data.data.promedioAtencion.promedio_creacion_atencion || 0;
        const promedioRevisionAprobacion = data.data.promedioRevisionAprobacion.promedio_revision_aprobacion || 0;
        const promedioAprobacionEntrega = data.data.promedioAprobacionEntrega.promedio_aprobacion_entrega || 0;

        // Asigna los valores obtenidos al dataset del gráfico
        chart5.data.datasets[0].data = [parseFloat(promedioCreacionAtencion), parseFloat(promedioRevisionAprobacion), parseFloat(promedioAprobacionEntrega)];
        chart5.update();
    }

        // Cuando se haga click en el botón de actualizar, hace un fetch de los datos
        document.querySelector('#refresh-button').addEventListener('click', function() {
            const fechaInicio = document.querySelector('#start-date').value;
            const fechaFin = document.querySelector('#end-date').value;

            // Validar que las fechas no estén vacías
            if (!fechaInicio || !fechaFin) {
                return;
            }

            fetch('/api/reportes/vehiculos/grafico-5', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    fecha_inicio: fechaInicio,
                    fecha_fin: fechaFin
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    updateChart(data);
                }
            })
            .catch(error => console.error('Error:', error));
        });
});
