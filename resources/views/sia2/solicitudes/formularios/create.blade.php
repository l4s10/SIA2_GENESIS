
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
        <h3>Formularios Disponibles</h3>
        <table class="table table-bordered" id="formularios">
            <thead>
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
                                <button type="submit" class="btn btn-success">
                                    <i class="fa-solid fa-plus"></i> Agregar al Carrito
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Carrito --}}
        <h3>Carrito</h3>
        <table class="table table-bordered" id="carrito">
            <thead>
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

            <div class="form-group">
                <label for="SOLICITUD_MOTIVO">Motivo de la Solicitud</label>
                <input type="text" class="form-control" id="SOLICITUD_MOTIVO" name="SOLICITUD_MOTIVO" required>
            </div>

            <div class="form-group">
                <label for="SOLICITUD_ESTADO">Estado de la Solicitud</label>
                <input type="text" class="form-control" id="SOLICITUD_ESTADO" name="SOLICITUD_ESTADO" value="INGRESADO" readonly>
            </div>

            <div class="form-group">
                <label for="SOLICITUD_FECHA_HORA_INICIO_SOLICITADA">Fecha y Hora de Inicio Solicitada</label>
                <input type="datetime-local" class="form-control" id="SOLICITUD_FECHA_HORA_INICIO_SOLICITADA" name="SOLICITUD_FECHA_HORA_INICIO_SOLICITADA" required>
            </div>

            <div class="form-group">
                <label for="SOLICITUD_FECHA_HORA_TERMINO_SOLICITADA">Fecha y Hora de Término Solicitada</label>
                <input type="datetime-local" class="form-control" id="SOLICITUD_FECHA_HORA_TERMINO_SOLICITADA" name="SOLICITUD_FECHA_HORA_TERMINO_SOLICITADA" required>
            </div>

            <button type="submit" class="btn btn-primary">Crear Solicitud</button>
        </form>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
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