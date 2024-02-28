document.addEventListener('DOMContentLoaded', function() {
    const ctx2 = document.getElementById('grafico2').getContext('2d');
    const myChart2 = new Chart(ctx2, {
        type: 'pie',
        data: {
            labels: [],
            datasets: [{
                label: 'Solicitudes por Entidad',
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
                    text: 'Solicitudes de salas requeridos por Ubicacion/Departamento',
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

    function updateChart2(data) {
        const newData = data.grafico2.solicitudesPorEntidad.map(item => ({
            label: item.entidad,
            value: item.total_solicitudes,
            color: getRandomColor() // Asumiendo que getRandomColor también está global
        }));
        myChart2.data.labels = newData.map(item => item.label);
        myChart2.data.datasets[0].data = newData.map(item => item.value);
        myChart2.data.datasets[0].backgroundColor = newData.map(item => item.color);
        myChart2.update();
    }

    // Utiliza getData global para obtener y actualizar los datos del gráfico
    window.getData.getInitialChartData()
        .then(data => {
            if (data.status === 'success') {
                updateChart2(data.data); // Asegúrate de que data.data contenga la estructura correcta
            }
        })
        .catch(error => console.error('Error:', error));

    document.querySelector('#refresh-button').addEventListener('click', function() {
        const fechaInicio = document.querySelector('#start-date').value;
        const fechaFin = document.querySelector('#end-date').value;

        Swal.fire({
            title: 'Actualizando registros',
            timer: 2000,
            didOpen: () => { Swal.showLoading(); }
        });

        window.getData.getFilteredChartData(fechaInicio, fechaFin)
            .then(data => {
                Swal.close();
                if (data.status === 'success') {
                    updateChart2(data.data); // Asegúrate de que data.data contenga la estructura correcta
                }
            })
            .catch(error => {
                Swal.fire('Error', 'No se pudieron actualizar los datos.', 'error');
                console.error('Error:', error);
            });
    });
});
