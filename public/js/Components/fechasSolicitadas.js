$(function () {
    // Configuración de Flatpickr para la selección de fechas y horas en la solicitud de vehículos

    // Obtener la fecha y hora actual
    let today = new Date();

    // Configuración para la fecha de inicio
    let fechaInicioConfig = {
        enableTime: true,
        dateFormat: "Y-m-d H:i:s",
        altFormat: "d-m-Y H:i",
        altInput: true,
        locale: "es",
        minDate: today, // Establecer la fecha mínima como la fecha actual
        maxDate: new Date(today.getFullYear(), 11, 31), // Permitir fechas hasta fin de año
        // defaultDate: today, // Esta línea se comenta para evitar una fecha por defecto
        minTime: today.getHours() + ":" + today.getMinutes(), // Establecer la hora mínima como la hora actual
        maxTime: "19:00", // Hora máxima permitida
        placeholder: 'Seleccione la fecha y hora de inicio' // Placeholder para el campo
    };

    // Configuración para la fecha de término
    let fechaTerminoConfig = {
        enableTime: true,
        dateFormat: "Y-m-d H:i:s",
        altFormat: "d-m-Y H:i",
        altInput: true,
        locale: "es",
        minDate: today, // Establecer la fecha mínima como la fecha actual
        maxDate: new Date(today.getFullYear() + 1, 1, 28), // Permitir fechas hasta febrero del siguiente año
        // defaultDate: today, // Esta línea se comenta para evitar una fecha por defecto
        minTime: today.getHours() + ":" + today.getMinutes(), // Establecer la hora mínima como la hora actual
        maxTime: "19:00", // Hora máxima permitida
        placeholder: 'Seleccione la fecha y hora de término' // Placeholder para el campo
    };

    // Inicializar el calendario de fecha de inicio
    $('#SOLICITUD_FECHA_HORA_INICIO_SOLICITADA').flatpickr(fechaInicioConfig);

    // Inicializar el calendario de fecha de término
    $('#SOLICITUD_FECHA_HORA_TERMINO_SOLICITADA').flatpickr(fechaTerminoConfig);
});
