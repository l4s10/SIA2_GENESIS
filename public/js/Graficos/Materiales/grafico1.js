// grafico1.js
document.addEventListener('DOMContentLoaded', function() {
    const ctx1 = document.getElementById('grafico1').getContext('2d');
    const myChart = new Chart(ctx1, {
        type: 'pie',
        data: {
            labels: [],
            datasets: [{
                label: 'Gestiones por Usuario',
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
                    text: 'Gestionadores de solicitudes de Materiales',
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

    function updateChart(data) {
        myChart.data.labels = data.grafico1.ranking.map(item => item.nombre_completo);
        myChart.data.datasets[0].data = data.grafico1.ranking.map(item => item.total_gestiones);
        myChart.data.datasets[0].backgroundColor = data.grafico1.ranking.map(() => getRandomColor());
        myChart.update();
    }

    const currentDate = new Date();
    const firstDayOfMonth = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1);
    const formattedFirstDay = formatDate(firstDayOfMonth);
    const formattedCurrentDate = formatDate(currentDate);

    // Llama a la funcion para consumir la data en la carga inicial (del mes actual)
    window.getData.getFilteredChartData(formattedFirstDay, formattedCurrentDate)
        .then(data => {
            if (data.status === 'success') {
                actualizarMensajeFecha(firstDayOfMonth, currentDate);
                updateChart(data.data);
            }
        })
        .catch(error => console.error('Error:', error));

    function formatDate(date) {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    }

    // Cuando se haga click en el boton de actualizar, hace un fetch de los datos
    document.querySelector('#refresh-button').addEventListener('click', function() {
        const fechaInicio = document.querySelector('#start-date').value;
        const fechaFin = document.querySelector('#end-date').value;

        window.getData.getFilteredChartData(fechaInicio, fechaFin)
            .then(data => {
                if (data.status === 'success') {
                    actualizarMensajeFecha(new Date(fechaInicio), new Date(fechaFin));
                    updateChart(data.data);
                }
            })
            .catch(error => {
                Swal.fire('Error', 'No se pudieron actualizar los datos.', 'error');
                console.error('Error:', error);
            });
    });


    // Función que actualiza el mensaje con las fechas de filtro
    function actualizarMensajeFecha(fechaInicio, fechaFin) {
        const elementoMensaje = document.getElementById('fecha-filtro-info');
        //Mostrar mensaje con fecha formateada
        elementoMensaje.textContent = `Mostrando datos desde ${formatDate(fechaInicio)} hasta ${formatDate(fechaFin)}`;
    }

});
