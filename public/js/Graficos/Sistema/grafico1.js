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
        // Obtiene las categorías y cantidades directamente de data
        const categorias = Object.keys(data.data);
        const cantidades = Object.values(data.data);

        // Actualiza las propiedades del gráfico con los nuevos datos
        chart.data.labels = categorias; // Actualiza las etiquetas (categorias)
        chart.data.datasets[0].data = cantidades; // Actualiza las cantidades
        chart.data.datasets[0].backgroundColor = cantidades.map(() => getRandomColor()); // Asigna colores aleatorios

        // Actualiza el gráfico para reflejar los nuevos datos
        chart.update();
    }


    const currentDate = new Date();
    const firstDayOfMonth = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1);
    const formattedFirstDay = formatDate(firstDayOfMonth);
    const formattedCurrentDate = formatDate(currentDate);

    // Llama a la funcion para consumir la data en la carga inicial (del mes actual)
    window.getData.getFilteredChartData(formattedFirstDay, formattedCurrentDate)
        .then(data => {
            if (data.status === 'success') {
                // Utilizar directamente 'data.data.grafico1.original.data' para la actualización
                const categorias = Object.keys(data.data.grafico1.original.data);
                const cantidades = Object.values(data.data.grafico1.original.data);
                chart.data.labels = categorias;
                chart.data.datasets[0].data = cantidades;
                chart.data.datasets[0].backgroundColor = categorias.map(() => getRandomColor());
                chart.update();
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

    // Agregar evento al botón de filtro
        // Cuando se haga click en el boton de actualizar, hace un fetch de los datos
    document.querySelector('#refresh-button').addEventListener('click', function() {
        const fechaInicio = document.querySelector('#start-date').value;
        const fechaFin = document.querySelector('#end-date').value;

        // Validar que las fechas no estén vacías
        if (!fechaInicio || !fechaFin) {
            return;
        }

        fetch('/api/reportes/sistema/grafico-1', {
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
