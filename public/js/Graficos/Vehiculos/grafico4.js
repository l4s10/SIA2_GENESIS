document.addEventListener('DOMContentLoaded', function() {
    const ctx4 = document.getElementById('grafico4').getContext('2d');
    const chart4 = new Chart(ctx4, {
        type: 'bar',
        data: {
            labels: [],
            datasets: [{
                label: 'Solicitudes por Vehículo',
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
                    text: 'Ranking de vehículos más solicitados',
                    padding: { top: 10, bottom: 30 }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { precision: 0 }
                }
            },
            barThickness: 65,
        }
    });

    function getRandomColor() {
        let color = '#';
        for (let i = 0; i < 6; i++) {
            color += '0123456789ABCDEF'[Math.floor(Math.random() * 16)];
        }
        return color;
    }

    const currentDate = new Date();
    const firstDayOfMonth = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1);
    const formattedFirstDay = formatDate(firstDayOfMonth);
    const formattedCurrentDate = formatDate(currentDate);

    // Llama a la funcion para consumir la data en la carga inicial (del mes actual)
    window.getData.getFilteredChartData(formattedFirstDay, formattedCurrentDate)
        .then(data => {
            if (data.status === 'success') {
                const grafico4Data = data.data.grafico4.original.data;

                chart4.data.labels = grafico4Data.map(item => item.VEHICULO_PATENTE);
                chart4.data.datasets[0].data = grafico4Data.map(item => item.total_solicitudes);
                chart4.data.datasets[0].backgroundColor = grafico4Data.map(() => getRandomColor());

                chart4.update();
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


    // Funcion para actualizar el grafico cuando se filtra por fechas despues de la carga inicial
    function updateChart(data) {
        const newData = data.map(item => ({
            label: item.VEHICULO_PATENTE,
            value: item.total_solicitudes,
            color: getRandomColor() // Assuming you have a function to generate colors
        }));
        chart4.data.labels = newData.map(item => item.label);
        chart4.data.datasets[0].data = newData.map(item => item.value);
        chart4.data.datasets[0].backgroundColor = newData.map(item => item.color);
        chart4.update();
    }



        // Cuando se haga click en el botón de actualizar, hace un fetch de los datos
        document.querySelector('#refresh-button').addEventListener('click', function() {
            const fechaInicio = document.querySelector('#start-date').value;
            const fechaFin = document.querySelector('#end-date').value;

            // Validar que las fechas no estén vacías
            if (!fechaInicio || !fechaFin) {
                return;
            }

            fetch('/api/reportes/vehiculos/grafico-4', {
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
                    // Assumes updateChart is a function to update the chart with new data
                    updateChart(data.data);
                }
            })
            .catch(error => console.error('Error:', error));
        });
});
