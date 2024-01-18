@extends('adminlte::page')

@section('title', 'Gestión de Materiales')

@section('content_header')
    <h1>Listado de Materiales</h1>
    {{-- Lógica de roles --}}
    {{-- ... --}}
@stop

@section('content')
    <div class="container">
        {{-- Lógica para mostrar mensajes de éxito o error --}}
        {{-- ... --}}
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        {{-- Enlace para exportar PDF --}}
        {{--<a href="{{ route('materiales.exportar-pdf') }}" class="btn btn-primary" target="_blank">
            <i class="fa-solid fa-file-pdf"></i> Exportar PDF
        </a>--}}

        {{-- Tabla de Materiales --}}
        <div class="table-responsive">
            <a class="btn agregar" href="{{ route('materiales.create') }}"><i class="fa-solid fa-plus"></i> Agregar Material</a>
            <a class="btn btn-secondary" href="{{route('tiposmateriales.index')}}"><i class="fa-solid fa-eye"></i> Ver tipos de materiales</a>

            <table id="materiales" class="table table-bordered mt-4">
                <thead class="tablacolor">
                    <tr>
                        <th scope="col">Tipo material</th>
                        <th scope="col">Nombre material</th>
                        <th scope="col">Stock</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($materiales as $material)
                        <tr>
                            <td>{{ $material->tipoMaterial->TIPO_MATERIAL_NOMBRE }}</td>
                            <td>{{ $material->MATERIAL_NOMBRE }}</td>
                            <td>{{ $material->MATERIAL_STOCK }}</td>
                            <td style="text-align: center; vertical-align: middle;">
                                <a href="{{ route('materiales.edit', $material->MATERIAL_ID) }}" class="btn botoneditar">
                                    <i class="fa-solid fa-pen-to-square"></i> Editar
                                </a>
                                <form action="{{ route('materiales.destroy', $material->MATERIAL_ID) }}" method="POST">
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
            $('#materiales').DataTable({
                "lengthMenu": [[5, 10, 50, -1], [5, 10, 50, "All"]],
                "responsive": true,
                "columnDefs": [
                    { "orderable": false, "targets": 3 }
                ],
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/es_es.json"
                },
            });
        });
    </script>
@stop
