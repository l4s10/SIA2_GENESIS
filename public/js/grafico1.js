// Objetivo: Generar el gráfico de gestiones por usuario

document.addEventListener('DOMContentLoaded', function() {
    // Obtener el contexto del canvas (Inicializar)
    const ctx1 = document.getElementById('grafico1').getContext('2d');

    // Inicializar el data [] del gráfico
    const initialChartData = {
        labels: [],
        datasets: [{
            label: 'Gestiones por Usuario',
            data: [],
            backgroundColor: [],
            borderWidth: 1
        }]
    };
    // Crear el gráfico
    const myChart = new Chart(ctx1, {
        type: 'pie',
        data: initialChartData,
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'top' },
                title: { display: true, text: 'Gestiones por Usuario' }
            }
        }
    });

    // Función para obtener un color aleatorio
    function getRandomColor() {
        const letters = '0123456789ABCDEF';
        let color = '#';
        for (let i = 0; i < 6; i++) {
            color += letters[Math.floor(Math.random() * 16)];
        }
        return color;
    }

    // Función para actualizar el gráfico
    function updateChart(data) {
        const newData = data.grafico1.ranking.map(item => ({
            label: item.nombre_completo,
            value: item.total_gestiones,
            color: getRandomColor()
        }));
        myChart.data.labels = newData.map(item => item.label);
        myChart.data.datasets[0].data = newData.map(item => item.value);
        myChart.data.datasets[0].backgroundColor = newData.map(item => item.color);
        myChart.update();
    }

    // Al cargar la página por primera vez, consumir la ruta sin fechas
    fetch('/api/reportes/materiales/get-graficos', {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'Authorization': 'Bearer ' + localStorage.getItem('api_token')
        }
    })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                updateChart(data.data); // Cambiado aquí para acceder directamente a data.grafico1
            }
        })
        .catch(error => console.error('Error:', error));

        // Al hacer click en el botón de actualizar (LISTENER)
        document.querySelector('#refresh-button').addEventListener('click', function () {
            var fechaInicio = document.querySelector('#start-date').value;
            var fechaFin = document.querySelector('#end-date').value;

            Swal.fire({
                title: 'Actualizando registros',
                timer: 2000,
                didOpen: () => {
                    Swal.showLoading();
                },
                willClose: () => {
                   // Al cerrarse
                }
            });

            fetch('/api/reportes/materiales/filtrar-general', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Authorization': 'Bearer ' + localStorage.getItem('api_token'),
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    fecha_inicio: fechaInicio,
                    fecha_fin: fechaFin
                })
            })
            .then(response => response.json())
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
