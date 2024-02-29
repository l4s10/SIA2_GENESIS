
@extends('adminlte::page')

@section('title', 'Solicitar Sala')

@section('content_header')
    <h1>Crear Solicitud</h1>
@stop

@section('content')
    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                Swal.fire({
                    icon: 'success',
                    title: '{{ session('success') }}',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#0064A0'
                });
            });
        </script>
    @elseif(session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                Swal.fire({
                    icon: 'error',
                    title: '{{ session('error') }}',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#0064A0'
                });
            });
        </script>
    @endif

{{-- Tabla de Equipos --}}
<h3 class="centrar">Tipos de equipos disponibles (opcional)</h3>
<table class="table table-bordered" id="equipos">
    <thead class="tablacolor">
        <tr>
            <th>Tipo equipo</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach($tiposEquipos as $tipoEquipo)
            <tr>
                <td>{{ $tipoEquipo->TIPO_EQUIPO_NOMBRE }}</td>
                <td>
                    <form action="{{ route('tiposequipos.addToCart', $tipoEquipo->TIPO_EQUIPO_ID) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn botoneditar">
                            <i class="fa-solid fa-plus"></i> Agregar al Carrito
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

{{-- Carrito --}}
<h3 class="centrar">Carrito</h3>
<table class="table table-bordered" id="carrito">
    <thead class="tablacarrito">
        <tr>
            <th>Tipo equipo</th>
            <th>Cantidad</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach($cartItems as $cartItem)
            <tr>
                <td>{{ $cartItem->name }}</td>
                <td>{{ $cartItem->qty }}</td>
                <td>
                    <form action="{{ route('tiposequipos.removeItem', $cartItem->rowId) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fa-solid fa-trash"></i> Eliminar
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

    {{-- Formulario de Solicitud --}}
    <form action="{{ route('solicitudes.salas.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="SALA_ID"><i class="fa-solid fa-door-open"></i> Sala</label>
            <select name="SALA_ID" id="SALA_ID" class="form-control" required>
                <option value="">Seleccione una sala</option>
                @foreach($salas as $sala)
                    <option value="{{ $sala->SALA_ID }}">{{ $sala->SALA_NOMBRE }}</option>
                @endforeach
            </select>
        </div>

        {{-- Motivo de la Solicitud --}}
        <div class="form-group {{ $errors->has('SOLICITUD_MOTIVO') ? 'has-error' : '' }}">
            <label for="SOLICITUD_MOTIVO"><i class="fa-solid fa-pen-to-square"></i> Motivo de la Solicitud</label>
            <textarea class="form-control" id="SOLICITUD_MOTIVO" name="SOLICITUD_MOTIVO" rows="3" required>{{ old('SOLICITUD_MOTIVO') }}</textarea>
            @error('SOLICITUD_MOTIVO')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="SOLICITUD_ESTADO"><i class="fa-solid fa-file-circle-check"></i> Estado de la Solicitud</label>
            <input type="text" class="form-control" id="SOLICITUD_ESTADO" name="SOLICITUD_ESTADO" value="🟠INGRESADO" readonly>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="SOLICITUD_FECHA_HORA_INICIO_SOLICITADA"><i class="fa-solid fa-calendar-days"></i> Fecha y Hora de Inicio Solicitada</label>
                    <input type="datetime-local" class="form-control" id="SOLICITUD_FECHA_HORA_INICIO_SOLICITADA" name="SOLICITUD_FECHA_HORA_INICIO_SOLICITADA" required>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="SOLICITUD_FECHA_HORA_TERMINO_SOLICITADA"><i class="fa-solid fa-calendar-xmark"></i> Fecha y Hora de Término Solicitada</label>
                    <input type="datetime-local" class="form-control" id="SOLICITUD_FECHA_HORA_TERMINO_SOLICITADA" name="SOLICITUD_FECHA_HORA_TERMINO_SOLICITADA" required>
                </div>
            </div>
        </div>
        <br>
        {{-- Botones de envio y retorno --}}
        <a href="{{ route('solicitudes.salas.index') }}" class="btn btn-secondary"><i class="fa-solid fa-hand-point-left"></i> Cancelar</a>
        <button type="submit" class="btn agregar"><i class="fa-solid fa-clipboard-check"></i> Crear Solicitud</button>
    </form>
@stop

@section('css')
    <style>
        .centrar{
            text-align: center;
        }
        .tablacolor {
            background-color: #723E72; /* Color de fondo personalizado */
            color: #fff; /* Color de texto personalizado */
        }
        .tablacarrito {
            background-color: #956E95;
            color: #fff;
        }
        .agregar{
            background-color: #e6500a;
            color: #fff;
        }
        .botoneditar{
            background-color: #1aa16b;
            color: #fff;
        }
    </style>
@stop

@section('js')
    {{-- Llamar a componente fechas solicitadas --}}
    <script src="{{ asset('js/Components/fechasSolicitadas.js') }}"></script>
    {{-- Llamar a componente de DataTables --}}
    <script>
        $(document).ready(function () {
            $('#equipos').DataTable({
                "lengthMenu": [[5, 10, 50, -1], [5, 10, 50, "All"]],
                "columnDefs": [
                    { "orderable": false, "targets": 1 }
                ],
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/es_es.json"
                },
            });
            $('#carrito').DataTable({
                "lengthMenu": [[5, 10, 50, -1], [5, 10, 50, "All"]],
                "columnDefs": [
                    { "orderable": false, "targets": 2 }
                ],
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/es_es.json"
                },
            });
        });
    </script>
@stop
