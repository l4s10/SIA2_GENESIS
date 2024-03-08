document.addEventListener('DOMContentLoaded', function() {
    // Obtiene el contexto del canvas
    const ctx2 = document.getElementById('grafico2').getContext('2d');
    // Configuración del gráfico
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
                    text: 'Solicitudes de bodegas requeridos por Ubicación/Departamento',
                    padding: { top: 10, bottom: 30 }
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

    // Función para actualizar el gráfico con los datos obtenidos de la API
    function updateChart(data) {
        if (Array.isArray(data.data)) {
            myChart2.data.labels = data.data.map(item => item.entidad);
            myChart2.data.datasets[0].data = data.data.map(item => item.total_solicitudes);
            myChart2.data.datasets[0].backgroundColor = data.data.map(() => getRandomColor());
            myChart2.update();
        } else {
            console.error('Error: data.data no es un array');
        }
    }

    // Función para realizar la petición y actualizar el gráfico (CARGA INICIAL)
    async function fetchDataAndUpdateChart() {
        try {
            const currentDate = new Date();
            const firstDayOfMonth = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1);
            const formattedFirstDay = formatDate(firstDayOfMonth);
            const formattedCurrentDate = formatDate(currentDate);

            await fetch('/api/reportes/bodegas/grafico-2', {
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

    // Función para actualizar el gráfico con los datos obtenidos (CARGA FILTRADA)
    document.querySelector('#refresh-button').addEventListener('click', function() {
        const fechaInicio = document.querySelector('#start-date').value;
        const fechaFin = document.querySelector('#end-date').value;

        // Validar que las fechas no estén vacías
        if (!fechaInicio || !fechaFin) {
            return;
        }

        fetch('/api/reportes/bodegas/grafico-2', {
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

    // Llama a la función para que se ejecute al cargar la página
    fetchDataAndUpdateChart();

    // Llama a la función para que se ejecute cada 5 minutos
    //setInterval(fetchDataAndUpdateChart, 300000);
});
