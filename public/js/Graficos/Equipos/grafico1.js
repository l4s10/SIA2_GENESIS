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
                    text: 'Gestionadores de solicitudes de Equipos',
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


    const currentDate = new Date();
    const firstDayOfMonth = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1);
    const formattedFirstDay = formatDate(firstDayOfMonth);
    const formattedCurrentDate = formatDate(currentDate);

    // Llama a la funcion para consumir la data en la carga inicial (del mes actual)
    window.getData.getFilteredChartData(formattedFirstDay, formattedCurrentDate)
        .then(data => {
            if (data.status === 'success') {
                //Acceder a la data de la respuesta y actualizar el grafico con ella
                const grafico1Data = data.data.grafico1.original.data;
                myChart.data.labels = grafico1Data.map(item => item.nombre_completo);
                myChart.data.datasets[0].data = grafico1Data.map(item => item.total_gestiones);
                myChart.data.datasets[0].backgroundColor = grafico1Data.map(() => getRandomColor());
                myChart.update();
            }
        })
        .catch(error => console.error('Error:', error));

    // Funcion para formatear la fecha en la primera carga (obtener mes y dia actual y primer dia del mes)
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

        if (!fechaInicio || !fechaFin) {
            // console.log('Fechas no especificadas. Cancelando la petición.');
            return;
        }

        fetch('/api/reportes/equipos/grafico-1', {
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
                myChart.data.labels = data.data.map(item => item.nombre_completo);
                myChart.data.datasets[0].data = data.data.map(item => item.total_gestiones);
                myChart.data.datasets[0].backgroundColor = data.data.map(() => getRandomColor());
                myChart.update();
            }
        })
        .catch(error => console.error('Error:', error));
    });

});
