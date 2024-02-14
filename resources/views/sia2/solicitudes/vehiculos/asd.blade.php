// Evento de cambio en el tipo de vehículo
            document.getElementById('TIPO_VEHICULO_ID').addEventListener('change', function() {
                let tipoVehiculoIdSeleccionado = this.value;
                document.getElementById('SOLICITUD_VEHICULO_MOTIVO').style.display = 'block';
                //controlarVisibilidadRegistroPasajeros(tipoVehiculoIdSeleccionado);
                contadorFilas = 1;
                capacidadMaxima = 0;
                document.getElementById('agregarPasajeroBtn').innerHTML = '<i class="fas fa-plus"></i> Agregar Conductor';
                document.getElementById('eliminarPasajeroBtn').innerHTML = '<i class="fas fa-minus"></i> Eliminar Conductor';
                reiniciarPasajerosSeleccionados();

                if (tipoVehiculoIdSeleccionado === '') {
                    // Confirmar eliminación si se deselecciona el tipo de vehículo
                    let confirmacion = confirm('¿Está seguro de eliminar el tipo de vehículo solicitado y el registro de pasajeros asociados?');
                    if (!confirmacion) {
                        this.value = prevTipoVehiculoId;
                        return;
                    } else {
                        document.getElementById('SOLICITUD_VEHICULO_MOTIVO').style.display = 'none';
                        document.getElementById('agregarPasajeroBtn').style.display = 'none';
                        document.getElementById('eliminarPasajeroBtn').style.display = 'none';
                        document.getElementById('pasajeros').style.display = 'none';
                        prevTipoVehiculoId = '';
                        return;
                    }
                } else if (tipoVehiculoIdSeleccionado !== '' && prevTipoVehiculoId !== '') {
                    // Confirmar cambio si se selecciona un nuevo tipo de vehículo
                    let confirmacion = confirm('¿Está seguro de cambiar el tipo de vehículo solicitado?, se eliminará el registro de pasajeros asociados.');
                    if (!confirmacion) {
                        this.value = prevTipoVehiculoId;
                        return;
                    } else {
                        document.getElementById('SOLICITUD_VEHICULO_MOTIVO').style.display = 'block';
                    }
                }

                // Obtener información del tipo de vehículo seleccionado
                let tiposVehiculosJSON = JSON.parse('@json($tiposVehiculos)');
                let tipoVehiculoSeleccionado = tiposVehiculosJSON.find(function(tipoVehiculo) {
                    return tipoVehiculo.TIPO_VEHICULO_ID == tipoVehiculoIdSeleccionado;
                });

                let pasajeros = document.getElementById('pasajeros');
                pasajeros.innerHTML = '';

                // Configurar el registro de pasajeros según la capacidad del vehículo
                if (tipoVehiculoSeleccionado.TIPO_VEHICULO_CAPACIDAD > 0) {
                    capacidadMaxima = tipoVehiculoSeleccionado.TIPO_VEHICULO_CAPACIDAD;
                    agregarFila(contadorFilas);
                    document.getElementById('agregarPasajeroBtn').style.display = 'block';
                    document.getElementById('eliminarPasajeroBtn').style.display = 'block';
                    pasajeros.style.display = 'block';
                } else {
                    document.getElementById('agregarPasajeroBtn').style.display = 'none';
                    document.getElementById('eliminarPasajeroBtn').style.display = 'none';
                    pasajeros.style.display = 'none';
                }

                prevTipoVehiculoId = tipoVehiculoIdSeleccionado;
            });