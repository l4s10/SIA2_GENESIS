document.addEventListener('DOMContentLoaded', function() {
    // Obtiene el contexto del canvas
    const ctx = document.getElementById('graficoDistribucionGenero').getContext('2d');
    // Configuración del gráfico
    const chart = new Chart(ctx, {
        type: 'doughnut', // Tipo de gráfico adecuado para representar proporciones
        data: {
            labels: ['Femenino', 'Masculino'], // Etiquetas predefinidas
            datasets: [{
                label: 'Distribución por Género',
                data: [], // Los datos se llenarán con la respuesta de la API
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)', // Rosa para femenino
                    'rgba(54, 162, 235, 0.2)' // Azul para masculino
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)', // Borde rosa para femenino
                    'rgba(54, 162, 235, 1)' // Borde azul para masculino
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: true
                },
                title: {
                    display: true,
                    text: 'Distribución de Género',
                    padding: { top: 10, bottom: 30 }
                }
            }
        }
    });

    // Función para actualizar el gráfico con los datos obtenidos
    function updateChart(data) {
        const generos = data.data.map(item => item.sexo);
        const totales = data.data.map(item => item.total);
        const totalUsuarios = data.total; // Total de usuarios obtenido del backend

        chart.data.labels = generos;
        chart.data.datasets[0].data = totales;

        // Calcula los porcentajes
        const porcentajes = totales.map(total => ((total / totalUsuarios) * 100).toFixed(2));

        // Opciones para mostrar los porcentajes y el mensaje del total
        chart.options.plugins.tooltip = {
            callbacks: {
                label: function(context) {
                    const label = context.label || '';
                    const value = context.parsed;
                    const percentage = porcentajes[context.dataIndex];
                    return `${label}: ${value} (${percentage}%)`;
                },
                footer: function(context) {
                    return `Total: ${totalUsuarios} funcionarios`;
                }
            }
        };

        // Actualiza el gráfico para reflejar los nuevos datos y opciones
        chart.update();
    }

    // Función para realizar la petición y actualizar el gráfico
    async function fetchDataAndUpdateChart() {
        try {
            await fetch('/api/reportes/sistema/grafico-2', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
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

    // Llama a la función para obtener los datos iniciales y actualizar el gráfico
    fetchDataAndUpdateChart();

    // Actualizar el gráfico en función de un rango de fechas seleccionado por el usuario
    document.querySelector('#refresh-button').addEventListener('click', function() {
        try {
            const fechaInicio = document.querySelector('#start-date').value;
            const fechaFin = document.querySelector('#end-date').value;

            // Validar que las fechas no estén vacías
            if (!fechaInicio || !fechaFin) {
                return;
            }
            fetch('/api/reportes/sistema/grafico-2', {
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
        } catch (error) {
            console.error('Error al hacer la petición:', error);
        }
    });
});
