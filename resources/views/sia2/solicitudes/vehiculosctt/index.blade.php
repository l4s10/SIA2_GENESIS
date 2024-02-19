@extends('adminlte::page')

@section('title', 'Solicitudes de vehículos')

@section('content_header')
    <h1>Listado de solicitudes de vehículos</h1>
@stop

@section('content')
    {{-- sweetalerts de session --}}
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

    {{-- Tabla de solicitudes --}}
    <div class="table-responsive">
        <table id="solicitudes" class="table table-bordered mt-4">
            <thead class="tablacolor">
                <tr>
                    <th scope="col">Solicitante</th>
                    <th scope="col">Estado</th>
                    <th scope="col">Fecha de solicitud</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($solicitudes as $solicitud)
                    <tr>
                        <td>{{ $solicitud->user->USUARIO_NOMBRES }} {{ $solicitud->user->USUARIO_APELLIDOS }}</td>
                        <td>{{ $solicitud->SOLICITUD_VEHICULO_ESTADO }}</td>
                        <td>{{ $solicitud->created_at }}</td>
                        <td>
                            <div class="d-flex justify-content-center">
                                {{--<a href="{{ route('solicitud_vehiculos.show', $solicitud->SOLICITUD_VEHICULO_ID) }}" class="btn btn-primary"><i class="fa-solid fa-eye"></i></a>--}}
                                <a href="{{ route('solicitudesvehiculos.edit', $solicitud->SOLICITUD_VEHICULO_ID) }}" class="btn btn-secondary ml-2"><i class="fa-solid fa-pencil"></i></a>
                                {{--<form action="{{ route('solicitud_vehiculos.destroy', $solicitud->SOLICITUD_VEHICULO_ID) }}" method="POST">--}}
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger ml-2"><i class="fa-solid fa-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@stop

@section('css')
<style>/* Estilos personalizados si es necesario */
        .tablacolor {
            background-color: #0064a0; /* Color de fondo personalizado */
            color: #fff; /* Color de texto personalizado */
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
        $(document).ready(function () {
            // Inicialización de DataTables
            $('#solicitudes').DataTable({
                "lengthMenu": [[5, 10, 50, -1], [5, 10, 50, "Todos"]],
                "columnDefs": [
                    { "orderable": false, "targets": 3 }
                ],
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.11.3/i18n/es_es.json"
                },
            });
        });
    </script>
@stop