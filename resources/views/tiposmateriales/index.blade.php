@extends('adminlte::page')

@section('title', 'Tipos de Material')

@section('content_header')
    <h1>Listado de Tipos de Material</h1>
    {{-- Lógica de roles --}}
    {{-- ... --}}
@stop

@section('content')
    <div class="container">
        {{-- Lógica para mostrar mensajes de éxito o error --}}
        {{-- ... --}}
        <a class="btn btn-primary" href="{{ route('tiposmateriales.create') }}"><i class="fa-solid fa-plus"></i> Agregar Tipo de Material</a>
        <a class="btn btn-secondary" href="{{ route('materiales.index')}}"><i class="fa-solid fa-eye"></i> Administrar Materiales</a>

        <div class="table-responsive">
            <table id="tiposMateriales" class="table table-bordered mt-4">
                <thead class="bg-primary text-white">
                    <tr>
                        <th scope="col">Tipo de Material</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tiposMaterial as $tipo)
                        <tr>
                            <td>{{ $tipo->TIPO_MATERIAL_NOMBRE }}</td>
                            <td style="text-align: center; vertical-align: middle;">
                                <form action="{{ route('tiposmateriales.destroy', $tipo->TIPO_MATERIAL_ID) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <a href="{{ route('tiposmateriales.edit', $tipo->TIPO_MATERIAL_ID) }}" class="btn btn-info"><i class="fa-solid fa-pen-to-square"></i> Editar</a>
                                    <button type="submit" class="btn btn-danger"><i class="fa-solid fa-trash"></i> Borrar</button>
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
        .alert {
            opacity: 0.7; /* Ajusta la opacidad a tu gusto */
            background-color: #99CCFF;
            color: #000000;
        }
    </style>
@stop

@section('js')
    <!-- Para inicializar -->
    <script>
        $(document).ready(function () {
            $('#tiposMateriales').DataTable({
                "lengthMenu": [[5, 10, 50, -1], [5, 10, 50, "All"]],
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/es_es.json"
                },
            });
        });
    </script>
@stop
