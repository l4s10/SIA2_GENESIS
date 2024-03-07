// getData.js
window.getData = {
    getInitialChartData: async () => {
        try {
            const response = await fetch('/api/reportes/vehiculos/get-graficos', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
            });
            const data = await response.json();
            return data; // Retorna los datos recibidos
        } catch (error) {
            console.error('Error:', error);
            throw error; // Lanza el error para manejarlo en el llamador
        }
    },

    getFilteredChartData: async (fechaInicio, fechaFin) => {
        try {
            const response = await fetch('/api/reportes/vehiculos/filtrar-general', {
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
            });
            const data = await response.json();
            return data; // Retorna los datos recibidos
        } catch (error) {
            console.error('Error:', error);
            throw error; // Lanza el error para manejarlo en el llamador
        }
    }
};
