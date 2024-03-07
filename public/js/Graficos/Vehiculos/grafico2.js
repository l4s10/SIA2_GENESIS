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
                    text: 'Solicitudes de vehículos requeridos por ubicación/departamento',
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
                const grafico2Data = data.data.grafico2.original.data;
                myChart2.data.labels = grafico2Data.map(item => item.entidad);
                myChart2.data.datasets[0].data = grafico2Data.map(item => item.total_solicitudes);
                myChart2.data.datasets[0].backgroundColor = grafico2Data.map(() => getRandomColor());
                myChart2.update();
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


    // Cuando se haga click en el botón de actualizar, hace un fetch de los datos
    document.querySelector('#refresh-button').addEventListener('click', function() {
        const fechaInicio = document.querySelector('#start-date').value;
        const fechaFin = document.querySelector('#end-date').value;

        // Validar que las fechas no estén vacías
        if (!fechaInicio || !fechaFin) {
            return;
        }

        // Consumir endpoint para el gráfico 2 de materiales
        fetch('/api/reportes/vehiculos/grafico-2', {
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
                myChart2.data.labels = data.data.map(item => item.entidad);
                myChart2.data.datasets[0].data = data.data.map(item => item.total_solicitudes);
                myChart2.data.datasets[0].backgroundColor = data.data.map(() => getRandomColor());
                myChart2.update();
            }
        })
        .catch(error => console.error('Error:', error));
    });

});
