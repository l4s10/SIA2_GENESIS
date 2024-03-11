document.addEventListener('DOMContentLoaded', function() {
    // Obtiene el contexto del canvas
    const ctx = document.getElementById('graficoRankingSolicitudes').getContext('2d');
    // Configuración del gráfico
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
                legend: {
                    display: true,
                    labels: {
                        generateLabels: function (chart) {
                            const data = chart.data;
                            if (data.labels.length && data.datasets.length) {
                                return data.labels.map(function (label, index) {
                                    const dataset = data.datasets[0];
                                    const backgroundColor = dataset.backgroundColor[index];
                                    return {
                                        text: label,
                                        fillStyle: backgroundColor,
                                        hidden: false,
                                        lineCap: 'round',
                                        lineDash: [],
                                        lineDashOffset: 0,
                                        lineJoin: 'round',
                                        lineWidth: 1,
                                        strokeStyle: backgroundColor,
                                        pointStyle: 'circle',
                                        rotation: 0
                                    };
                                });
                            }
                            return [];
                        }
                    }
                },
                title: {
                    display: true,
                    text: 'Ranking de Solicitudes por Categoría',
                    padding: { top: 10, bottom: 30 }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Cantidad'
                    },
                    ticks: {
                        precision: 0
                    }
                }
            }
        }
    });

    // Función para obtener un color aleatorio
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

    // Función para realizar la petición y actualizar el gráfico
    async function fetchDataAndUpdateChart() {
        try {
            const currentDate = new Date();
            const firstDayOfMonth = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1);
            const formattedFirstDay = formatDate(firstDayOfMonth);
            const formattedCurrentDate = formatDate(currentDate);

            await fetch('/api/reportes/sistema/grafico-1', {
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

    // Llama a la función para obtener los datos y actualizar el gráfico
    fetchDataAndUpdateChart();
});
