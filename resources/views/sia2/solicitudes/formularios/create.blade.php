
@extends('adminlte::page')

@section('title', 'Solicitar formularios')

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

    <div class="container">
        {{-- Tabla de Formularios --}}
        <h3 class="centrar">Formularios Disponibles</h3>
        <table class="table table-bordered" id="formularios">
            <thead class="tablacolor">
                <tr>
                    <th>Tipo formulario</th>
                    <th>Nombre formulario</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($formularios as $formulario)
                    <tr>
                        <td>{{ $formulario->FORMULARIO_TIPO}}</td>
                        <td>{{ $formulario->FORMULARIO_NOMBRE }}</td>
                        <td>
                            <form action="{{ route('formularios.addToCart', $formulario->FORMULARIO_ID) }}" method="POST">
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
                    <th>Formulario</th>
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
                            <form action="{{ route('formularios.removeItem', $cartItem->rowId) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    <i class="fa-solid fa-trash"></i> Eliminar
                                </button>
                            </form>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Formulario de Solicitud --}}
        <form action="{{ route('solicitudesformularios.store') }}" method="POST">
            @csrf

            {{-- Motivo de la Solicitud --}}
            <div class="form-group {{ $errors->has('SOLICITUD_MOTIVO') ? 'has-error' : '' }}">
                <label for="SOLICITUD_MOTIVO"><i class="fa-solid fa-pen-to-square"></i> Motivo de la Solicitud</label>
                <input type="text" class="form-control" id="SOLICITUD_MOTIVO" name="SOLICITUD_MOTIVO" required>
                @error('SOLICITUD_MOTIVO')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            {{-- Estado de la Solicitud (Sin contenedor de error ya que es un campo de solo lectura) --}}
            <div class="form-group">
                <label for="SOLICITUD_ESTADO"><i class="fa-solid fa-file-circle-check"></i> Estado de la Solicitud</label>
                <input type="text" class="form-control" id="SOLICITUD_ESTADO" name="SOLICITUD_ESTADO" value="🟠INGRESADO" readonly>
            </div>

            {{-- Fecha y Hora de Inicio Solicitada --}}
            <div class="form-group {{ $errors->has('SOLICITUD_FECHA_HORA_INICIO_SOLICITADA') ? 'has-error' : '' }}">
                <label for="SOLICITUD_FECHA_HORA_INICIO_SOLICITADA"><i class="fa-solid fa-calendar-days"></i> Fecha y Hora de Inicio Solicitada</label>
                <input type="datetime-local" class="form-control" id="SOLICITUD_FECHA_HORA_INICIO_SOLICITADA" name="SOLICITUD_FECHA_HORA_INICIO_SOLICITADA" required>
                @error('SOLICITUD_FECHA_HORA_INICIO_SOLICITADA')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            {{-- Fecha y Hora de Término Solicitada --}}
            <div class="form-group {{ $errors->has('SOLICITUD_FECHA_HORA_TERMINO_SOLICITADA') ? 'has-error' : '' }}">
                <label for="SOLICITUD_FECHA_HORA_TERMINO_SOLICITADA"><i class="fa-solid fa-calendar-xmark"></i> Fecha y Hora de Término Solicitada</label>
                <input type="datetime-local" class="form-control" id="SOLICITUD_FECHA_HORA_TERMINO_SOLICITADA" name="SOLICITUD_FECHA_HORA_TERMINO_SOLICITADA" required>
                @error('SOLICITUD_FECHA_HORA_TERMINO_SOLICITADA')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="btn agregar"><i class="fa-solid fa-plus"></i> Crear Solicitud</button>
        </form>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
    <style>/* Estilos personalizados si es necesario */
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
    <script>
        // Aquí puedes agregar cualquier script JS que necesites
    </script>
    <script>
        $(document).ready(function () {
            $('#formularios').DataTable({
                "lengthMenu": [[5, 10, 50, -1], [5, 10, 50, "All"]],
                "columnDefs": [
                    { "orderable": false, "targets": 2 }
                ],
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/es_es.json"
                },
            });
            $('#carrito').DataTable({
                "lengthMenu": [[5, 10, 50, -1], [5, 10, 50, "All"]],
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/es_es.json"
                },
            });
        });
    </script>
@stop
