$(function () {
    // Configuración inicial para la fecha de inicio
    let fechaInicioConfig = {
        dateFormat: "Y-m-d",
        altFormat: "d-m-Y",
        altInput: true,
        locale: "es",
        minDate: '2019-01-01',
        maxDate: new Date().fp_incr(365), // Hasta un año a partir de hoy
        // No establecer defaultDate para iniciar vacío
        onChange: function(selectedDates, dateStr, instance) {
            // Actualizar la fecha mínima de fechaTerminoConfig
            fechaTerminoConfig.minDate = dateStr;
            // Recrear el Flatpickr de fecha de término
            let fechaTerminoPicker = $("#end-date").flatpickr(fechaTerminoConfig);
            fechaTerminoPicker.clear(); // Limpiar el campo para asegurar que inicie vacío
        }
    };

    // Configuración inicial para la fecha de término, sin defaultDate para iniciar vacío
    let fechaTerminoConfig = {
        dateFormat: "Y-m-d",
        altFormat: "d-m-Y",
        altInput: true,
        locale: "es",
        minDate: '2019-01-01',
        maxDate: new Date().fp_incr(365) // Hasta un año a partir de hoy
    };

    // Inicializar Flatpickr para los campos de fecha
    $("#start-date").flatpickr(fechaInicioConfig);
    $("#end-date").flatpickr(fechaTerminoConfig);
});
