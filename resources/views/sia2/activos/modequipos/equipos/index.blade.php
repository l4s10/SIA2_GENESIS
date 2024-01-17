@extends('adminlte::page')

@section('title', 'Gestión de Equipos')

@section('content_header')
    <h1>Listado de Equipos</h1>
    {{-- Lógica de roles --}}
    {{-- ... --}}
@stop

@section('content')
    <div class="container">
        {{-- Lógica para mostrar mensajes de éxito o error --}}
        {{-- ... --}}
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
        @elseif (session('error'))
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
        {{-- Enlace para exportar PDF --}}
        {{--<a href="{{ route('materiales.exportar-pdf') }}" class="btn btn-primary" target="_blank">
            <i class="fa-solid fa-file-pdf"></i> Exportar PDF
        </a>--}}

        {{-- Tabla de Materiales --}}
        <div class="table-responsive">
            <a class="btn btn-primary" href="{{ route('equipos.create') }}"><i class="fa-solid fa-plus"></i> Agregar Equipo</a>
            <a class="btn btn-secondary" href="{{route('tiposequipos.index')}}"><i class="fa-solid fa-eye"></i> Ver tipos de Equipos</a>

            <table id="equipos" class="table table-bordered mt-4">
                <thead class="bg-primary text-white">
                    <tr>
                        <th scope="col">Tipo equipo</th>
                        <th scope="col">Modelo equipo</th>
                        <th scope="col">Marca equipo</th>
                        <th scope="col">Stock</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($equipos as $equipo)
                        <tr>
                            <td>{{$equipo->tipoEquipo->TIPO_EQUIPO_NOMBRE }}</td>
                            <td>{{$equipo->EQUIPO_MODELO}}</td>
                            <td>{{$equipo->EQUIPO_MARCA}}</td>
                            <td>{{ $equipo->EQUIPO_STOCK }}</td>
                            <td style="text-align: center; vertical-align: middle;">
                                <a href="{{ route('equipos.edit', $equipo->EQUIPO_ID) }}" class="btn btn-info">
                                    <i class="fa-solid fa-pen-to-square"></i> Editar
                                </a>
                                <form action="{{ route('equipos.destroy', $equipo->EQUIPO_ID) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">
                                        <i class="fa-solid fa-trash"></i> Borrar
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
    <style>
        /* Estilos personalizados si es necesario */
    </style>
@stop

@section('js')
    <script>
        $(document).ready(function () {
            // Inicialización de DataTables
            $('#equipos').DataTable({
                "lengthMenu": [[5, 10, 50, -1], [5, 10, 50, "All"]],
                "responsive": true,
                "columnDefs": [
                    { "orderable": false, "targets": 4 }
                ],
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/es_es.json"
                },
            });
        });
    </script>
@stop
