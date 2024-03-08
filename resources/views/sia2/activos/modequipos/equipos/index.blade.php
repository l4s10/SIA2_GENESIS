@extends('adminlte::page')

@section('title', 'Gestión de Equipos')

@section('content_header')
    <h1>Listado de Equipos</h1>
    {{-- Lógica de roles --}}
    @role('ADMINISTRADOR')
    <div class="alert alert-info alert1" role="alert">
        <div><strong>Bienvenido Administrador:</strong> Acceso total al modulo.<div>
    </div>
    @endrole
    @role('SERVICIOS')
    <div class="alert alert-info" role="alert">
        <div><strong>Bienvenido Servicio:</strong> En esta tabla se muestra todos los equipos ya sea <strong>DISPONIBLES/ NO DISPONIBLES</strong> del sistema.<div>
    </div>
    @endrole
    @role('INFORMATICA')
    <div class="alert alert-info" role="alert">
        <div><strong>Bienvenido Informatica:</strong> En esta tabla se muestra todos los equipos ya sea <strong>DISPONIBLES/ NO DISPONIBLES</strong> del sistema.<div>
    </div>
    @endrole
    {{-- ... --}}
@stop

@section('content')
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

    {{-- Tabla de Equipos --}}
    <div class="table-responsive">
        <a class="btn agregar mb-3" href="{{ route('equipos.create') }}"><i class="fa-solid fa-plus"></i> Agregar Equipo</a>
        <a class="btn btn-secondary mb-3" href="{{route('tiposequipos.index')}}"><i class="fa-solid fa-eye"></i> Ver tipos de Equipos</a>

        <table id="equipos" class="table table-bordered mt-4">
            <thead class="tablacolor">
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
                        <td>
                            <div class="d-flex justify-content-center">
                                <a href="{{ route('equipos.edit', $equipo->EQUIPO_ID) }}" class="btn botoneditar">
                                    <i class="fa-solid fa-pen-to-square"></i> Editar
                                </a>
                                <form action="{{ route('equipos.destroy', $equipo->EQUIPO_ID) }}" method="POST" class="ml-2">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">
                                        <i class="fa-solid fa-trash"></i> Borrar
                                    </button>
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
        .agregar {
            background-color: #e6500a;
            color: #fff;
        }
        .botoneditar {
            background-color: #1aa16b;
            color: #fff;
        }
    </style>
    
    <!-- Color mensajes usuario -->
    <style>
        .alert {
            opacity: 0.7; /* Ajusta la opacidad del texto */
            background-color: #99CCFF;
            color:     #000000;
        }
        .alert1 {
            opacity: 0.7; /* Ajusta la opacidad del texto  */
            background-color: #FF8C40;
            color: #000000;
        }
    </style>
@stop

@section('js')
    <script>
        $(document).ready(function () {
            // Inicialización de DataTables
            $('#equipos').DataTable({
                "lengthMenu": [[5, 10, 50, -1], [5, 10, 50, "All"]],
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
