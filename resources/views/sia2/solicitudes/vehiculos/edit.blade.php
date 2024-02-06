@extends('adminlte::page')

@section('title', 'Editar Solicitud Vehicular')

@section('content_header')
    <h1>Revisión Solicitud Vehicular</h1>
@stop

@section('content')
    <div class="container">
        <form action="{{ route('solicitudesvehiculos.update', $solicitud->SOLICITUD_VEHICULO_ID) }}" method="POST">
            @csrf
            @method('PUT')
            <br>
            <br>
            <h3>Solicitante</h3>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="USUARIO_NOMBRES" class="form-label"><i class="fa-solid fa-user"></i> Nombres y Apellidos:</label>
                        <input type="text" id="USUARIO_NOMBRES" name="USUARIO_NOMBRES" class="form-control" value="{{ $solicitud->user->USUARIO_NOMBRES }} {{ $solicitud->user->USUARIO_APELLIDOS }}" readonly required>
                    </div>

                    <div class="mb-3">
                        <label for="USUARIO_RUT" class="form-label"><i class="fa-solid fa-id-card"></i> RUT:</label>
                        <input type="text" id="USUARIO_RUT" name="USUARIO_RUT" class="form-control" value="{{ $solicitud->user->USUARIO_RUT }}" readonly required>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="DEPARTAMENTO_O_UBICACION" class="form-label"><i class="fa-solid fa-building-user"></i> Ubicación o Departamento:</label>
                        <input type="text" id="DEPARTAMENTO_O_UBICACION" name="DEPARTAMENTO_O_UBICACION" class="form-control" value="{{ isset($solicitud->user->departamento) ? $solicitud->user->departamento->DEPARTAMENTO_NOMBRE : $solicitud->user->ubicacion->UBICACION_NOMBRE }}" readonly required>
                    </div>

                    <div class="mb-3">
                        <label for="EMAIL" class="form-label"><i class="fa-solid fa-envelope"></i> Email:</label>
                        <input type="email" id="EMAIL" name="EMAIL" class="form-control" value="{{ $solicitud->user->email }}" readonly required>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="OFICINA" class="form-label"><i class="fa-solid fa-map-location-dot"></i> Dirección Regional:</label>
                        <input type="text" id="OFICINA" class="form-control" name="OFICINA" value="{{ $solicitud->user->oficina->OFICINA_NOMBRE }}" required readonly>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="SOLICITUD_ESTADO">Estado de la Solicitud:</label>
                        <input type="text" class="form-control" id="SOLICITUD_ESTADO" name="SOLICITUD_ESTADO" value="POR INGRESAR" readonly style="color: green;">
                    </div>
                    <!-- Leyenda -->
                    <div class="mb-3">
                        <small>La solicitud todavía <strong>no</strong> ha sido ingresada</small>
                    </div>
                </div>
            </div>

            <h3>Especificaciones</h3>
                <div class="form-group">
                    <label for="TIPO_VEHICULO_ID" class="form-label"><i class="fa-solid fa-car-side"></i> Tipo de Vehículo</label>
                    <input type="text" class="form-control" value="{{ $solicitud->vehiculo->tipoVehiculo->TIPO_VEHICULO_NOMBRE }}" readonly>
                </div>

            <div class="form-group">
                <label for="SOLICITUD_VEHICULO_MOTIVO"><i class="fa-solid fa-file-pen"></i> Labor a realizar:</label>
                <textarea id="SOLICITUD_VEHICULO_MOTIVO" name="SOLICITUD_VEHICULO_MOTIVO" rows="5" class="form-control" placeholder="Indique la labor a realizar (MÁX 255 CARACTERES)" maxlength="255" disabled required>{{ $solicitud->SOLICITUD_VEHICULO_MOTIVO }}</textarea>
            </div>

            <!-- Datos Temporales -->
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label for="SOLICITUD_VEHICULO_FECHA_HORA_INICIO_SOLICITADA">Fecha y Hora de Inicio Solicitada</label>
                        <input type="datetime-local" class="form-control" id="SOLICITUD_VEHICULO_FECHA_HORA_INICIO_SOLICITADA" name="SOLICITUD_VEHICULO_FECHA_HORA_INICIO_SOLICITADA" value="{{ $solicitud->SOLICITUD_VEHICULO_FECHA_HORA_INICIO_SOLICITADA }}" disabled required>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label for="SOLICITUD_VEHICULO_FECHA_HORA_TERMINO_SOLICITADA">Fecha y Hora de Término Solicitada</label>
                        <input type="datetime-local" class="form-control" id="SOLICITUD_VEHICULO_FECHA_HORA_TERMINO_SOLICITADA" name="SOLICITUD_VEHICULO_FECHA_HORA_TERMINO_SOLICITADA" value="{{ $solicitud->SOLICITUD_VEHICULO_FECHA_HORA_TERMINO_SOLICITADA }}" disabled required>
                    </div>
                </div>
            </div>

            <!-- Datos Geográficos -->
            <div class="row">
                {{--<div class="col">
                    <div class="form-group">
                        <label for="SOLICITUD_VEHICULO_REGION_ORIGEN">Región de Origen</label>
                        <input type="text" class="form-control" value="{{ $solicitud->comunaOrigen->region->REGION_NOMBRE }}" readonly>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label for="SOLICITUD_VEHICULO_COMUNA_ORIGEN">Comuna de Origen</label>
                        <input type="text" class="form-control" value="{{ $solicitud->comunaOrigen->COMUNA_NOMBRE }}" readonly>
                    </div>
                </div>--}}
            </div>

            <div class="row">
                {{--<div class="col">
                    <div class="form-group">
                        <label for="SOLICITUD_VEHICULO_REGION_DESTINO">Región de Destino</label>
                        <input type="text" class="form-control" value="{{ $solicitud->comunaDestino->region->REGION_NOMBRE }}" readonly>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label for="SOLICITUD_VEHICULO_COMUNA_DESTINO">Comuna de Destino</label>
                        <input type="text" class="form-control" value="{{ $solicitud->comunaDestino->COMUNA_NOMBRE }}" readonly>
                    </div>
                </div>--}}
            </div>

            <br>
            <h3>Asignación</h3>

            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label for="VEHICULO_ID">Vehículo Asignado</label>
                        <select name="VEHICULO_ID" id="VEHICULO_ID" class="form-control" required>
                            <option value="">-- Seleccione un vehículo --</option>
                            @foreach ($vehiculos as $vehiculo)
                                <option value="{{ $vehiculo->VEHICULO_ID }}">{{ $vehiculo->VEHICULO_MARCA }} - {{ $vehiculo->VEHICULO_MODELO }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label for="SOLICITUD_VEHICULO_FECHA_HORA_INICIO_ASIGNADA">Fecha y Hora de Inicio Asignada</label>
                        <input type="datetime-local" class="form-control" id="SOLICITUD_VEHICULO_FECHA_HORA_INICIO_ASIGNADA" name="SOLICITUD_VEHICULO_FECHA_HORA_INICIO_ASIGNADA" required>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label for="SOLICITUD_VEHICULO_FECHA_HORA_TERMINO_ASIGNADA">Fecha y Hora de Término Asignada</label>
                        <input type="datetime-local" class="form-control" id="SOLICITUD_VEHICULO_FECHA_HORA_TERMINO_ASIGNADA" name="SOLICITUD_VEHICULO_FECHA_HORA_TERMINO_ASIGNADA" required>
                    </div>
                </div>
            </div>
            <br><br><br>
            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            <a href="{{ route('solicitudesvehiculos.exportar') }}" class="btn btn-primary">Descargar Excel</a>
            <a href="{{ route('descargar.plantilla', ['id' => $solicitud->SOLICITUD_VEHICULO_ID]) }}">Descargar Plantilla Excel</a>

        </form>
    </div>
@stop

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {


            // Después de 2 segundos, redirigir al usuario a la vista de edición
            setTimeout(function() {
                // Obtén el ID de la solicitud del objeto $solicitud
                var solicitudId = "{{ $solicitud->id }}";
                // Redirige al usuario a la ruta de edición con el ID de la solicitud
                window.location.href = "{{ route('solicitudesvehiculos.edit', ['solicitudesvehiculo' => 'solicitudId']) }}".replace('solicitudId', solicitudId);
            }, 2000); // Redirigir después de 2 segundos
        });
    </script>
@endsection