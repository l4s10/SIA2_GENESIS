document.addEventListener('DOMContentLoaded', function() {
    // Obtiene el contexto del canvas
    const ctx5 = document.getElementById('grafico5').getContext('2d');
    // Configuración del gráfico
    const chart5 = new Chart(ctx5, {
        type: 'line', // Cambia según necesites
        data: {
            labels: ['Promedio Creación-Atención', 'Promedio Revisión-Tramitación', 'Promedio Creación-Terminado'],
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
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Promedio de Respuesta (días)'
                    },
                }
            },
            plugins: {
                legend: { display: true },
                title: {
                    display: true,
                    text: 'Promedio en dias de los tiempos de atención de solicitudes de salas',
                    padding: { top: 10, bottom: 30 }
                }
            }
        }
    });

    // Funcion para formatear la fecha
    function formatDate(date) {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    }

    // Función para actualizar el gráfico con los datos obtenidos
    function updateChart(data) {
        // Asegúrate de acceder correctamente a cada valor promedio
        const promedioCreacionAtencion = data.data.promedioAtencion.promedio_creacion_atencion || 0;
        const promedioRevisionAprobacion = data.data.promedioRevisionAprobacion.promedio_revision_aprobacion || 0;
        const promedioAprobacionEntrega = data.data.promedioAprobacionEntrega.promedio_aprobacion_entrega || 0;

        // Asigna los valores obtenidos al dataset del gráfico
        chart5.data.datasets[0].data = [parseFloat(promedioCreacionAtencion), parseFloat(promedioRevisionAprobacion), parseFloat(promedioAprobacionEntrega)];
        chart5.update();
    }

    // Función para realizar la petición y actualizar el gráfico
    async function fetchDataAndUpdateChart() {
        try {
            const currentDate = new Date();
            const firstDayOfMonth = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1);
            const formattedFirstDay = formatDate(firstDayOfMonth);
            const formattedCurrentDate = formatDate(currentDate);

            await fetch('/api/reportes/salas/grafico-5', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                credentials: 'include',
                body: JSON.stringify({
                    fecha_inicio: formattedFirstDay,
                    fecha_fin: formattedCurrentDate
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    updateChart(data);
                }
            })
        } catch (error) {
            console.error('Error al hacer la petición:', error);
        }
    }

    // Cuando se haga click en el botón de actualizar, hace un fetch de los datos
    document.querySelector('#refresh-button').addEventListener('click', function() {
        const fechaInicio = document.querySelector('#start-date').value;
        const fechaFin = document.querySelector('#end-date').value;

        // Validar que las fechas no estén vacías
        if (!fechaInicio || !fechaFin) {
            return;
        }

        fetch('/api/reportes/salas/grafico-5', {
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

    // Llama a la función para hacer la petición y actualizar el gráfico
    fetchDataAndUpdateChart();
});
