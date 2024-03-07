document.addEventListener('DOMContentLoaded', function() {
    // Calcula el último mes
    const hoy = new Date();
    const mesActual = hoy.toLocaleDateString('es-CL', { month: 'long' });
    const añoActual = hoy.getFullYear();

    // Actualiza el mensaje con el último mes
    const mensajeInicial = `Datos filtrados del último mes (${mesActual} ${añoActual})`;
    document.getElementById('filter-message').innerHTML = mensajeInicial;

    document.getElementById('refresh-button').addEventListener('click', function() {
        const startDateValue = document.getElementById('start-date').value;
        const endDateValue = document.getElementById('end-date').value;

        if (startDateValue && endDateValue) {
            // Descomponer las fechas en componentes
            const startDateComponents = startDateValue.split('-');
            const endDateComponents = endDateValue.split('-');

            // Crear objetos de fecha con los componentes para asegurarse de que son fechas locales
            const startDate = new Date(startDateComponents[0], startDateComponents[1] - 1, startDateComponents[2]);
            const endDate = new Date(endDateComponents[0], endDateComponents[1] - 1, endDateComponents[2]);

            // Formatear las fechas según el formato es-CL (Chile)
            const options = { year: 'numeric', month: 'long', day: 'numeric' };
            const formattedStartDate = startDate.toLocaleDateString('es-CL', options);
            const formattedEndDate = endDate.toLocaleDateString('es-CL', options);

            Swal.fire({
                didOpen: () => {
                    Swal.showLoading()
                },
                title: 'Filtrando datos',
                text: 'Espere un momento por favor...',
                icon: 'info',
                timer: 2000,
                showConfirmButton: false,
                allowOutsideClick: false
            }).then(() => {
                // Mostrar el mensaje con fechas formateadas
                const message = `Datos filtrados desde el ${formattedStartDate} hasta el ${formattedEndDate}`;
                document.getElementById('filter-message').innerHTML = message;
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: '¡Debes seleccionar ambas fechas para filtrar!',
            });
        }
    });
});
