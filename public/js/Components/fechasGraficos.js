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
        minDate: '2019-01-01',
        maxDate: new Date(today.getFullYear(), 11, 31), // Permitir fechas hasta fin de año
        defaultDate: today, // Establecer la fecha por defecto como la fecha actual
        minTime: today.getHours() + ":" + today.getMinutes(), // Establecer la hora mínima como la hora actual
        maxTime: "19:00" // Hora máxima permitida
    };

    // Configuración para la fecha de término
    let fechaTerminoConfig = {
        enableTime: true,
        dateFormat: "Y-m-d H:i:s",
        altFormat: "d-m-Y H:i",
        altInput: true,
        locale: "es",
        minDate: '2019-01-01',
        maxDate: new Date(today.getFullYear() + 1, 1, 28), // Permitir fechas hasta febrero del siguiente año
        defaultDate: today, // Establecer la fecha por defecto como la fecha actual
        minTime: today.getHours() + ":" + today.getMinutes(), // Establecer la hora mínima como la hora actual
        maxTime: "19:00" // Hora máxima permitida
    };

    // Inicializar el calendario de fecha de inicio
    $('#start-date').flatpickr(fechaInicioConfig);

    // Inicializar el calendario de fecha de término
    $('#end-date').flatpickr(fechaTerminoConfig);
});
