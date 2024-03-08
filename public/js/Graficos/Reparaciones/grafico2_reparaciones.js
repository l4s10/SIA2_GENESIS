document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('graficoEstadosReparacionesFisicas').getContext('2d');
    const chart = new Chart(ctx, {
        type: 'doughnut', // Cambia el tipo de gráfico según prefieras (ej., 'bar', 'pie')
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

    // Reutilización de la función para obtener colores aleatorios
    function getRandomColor() {
        let color = '#';
        for (let i = 0; i < 6; i++) {
            color += '0123456789ABCDEF'[Math.floor(Math.random() * 16)];
        }
        return color;
    }

    // Funcion para formatear la fecha
    function formatDate(date) {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    }

    // Función para actualizar el gráfico con los datos obtenidos
    function updateChart(data) {
        if (Array.isArray(data.data)) {
            chart.data.labels = data.data.map(item => item.estado);
            chart.data.datasets[0].data = data.data.map(item => item.cantidad);
            chart.data.datasets[0].backgroundColor = data.data.map(() => getRandomColor());
            chart.update();
        } else {
            console.error('Error: data.data no es un array');
        }
    }

    // Función para realizar la petición y actualizar el gráfico
    async function fetchDataAndUpdateChart() {
        try {
            const currentDate = new Date();
            const firstDayOfMonth = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1);
            const formattedFirstDay = formatDate(firstDayOfMonth);
            const formattedCurrentDate = formatDate(currentDate);

            await fetch('/api/reportes/reparaciones/grafico-3', {
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

    // Llama a fetchDataAndUpdateChart para obtener los datos iniciales y actualizar el gráfico.
    fetchDataAndUpdateChart();

    // Opcional: Inicia la actualización cada cierto tiempo si es necesario
    // setInterval(fetchDataAndUpdateChart, 5000);
    // Cuando se haga click en el boton de actualizar, hace un fetch de los datos
    document.querySelector('#refresh-button').addEventListener('click', function() {
        const fechaInicio = document.querySelector('#start-date').value;
        const fechaFin = document.querySelector('#end-date').value;

        // Validar que las fechas no estén vacías
        if (!fechaInicio || !fechaFin) {
            return;
        }

        fetch('/api/reportes/reparaciones/grafico-3', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            credentials: 'include',
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
