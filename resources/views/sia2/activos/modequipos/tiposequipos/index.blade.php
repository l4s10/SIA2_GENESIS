@extends('adminlte::page')

@section('title', 'Tipos de Equipo')

@section('content_header')
    <h1>Listado de Tipos de Equipo</h1>
    {{-- Lógica de roles --}}
    {{-- ... --}}
@stop

@section('content')
    <div class="container">
        {{-- Lógica para mostrar mensajes de éxito o error --}}
        {{-- ... --}}
        <a class="btn btn-primary" href="{{ route('tiposequipos.create') }}"><i class="fa-solid fa-plus"></i> Agregar Tipo de Equipo</a>
        <a class="btn btn-secondary" href="{{ route('equipos.index')}}"><i class="fa-solid fa-eye"></i>Administrar Equipos</a>

        <div class="table-responsive">
            <table id="tiposEquipos" class="table table-bordered mt-4">
                <thead class="bg-primary text-white">
                    <tr>
                        <th scope="col">Tipo de Material</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tiposEquipo as $tipo)
                        <tr>
                            <td>{{ $tipo->TIPO_EQUIPO_NOMBRE }}</td>
                            <td style="text-align: center; vertical-align: middle;">
                                <form action="{{ route('tiposequipos.destroy', $tipo->TIPO_EQUIPO_ID) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <a href="{{ route('tiposequipos.edit', $tipo->TIPO_EQUIPO_ID) }}" class="btn btn-info"><i class="fa-solid fa-pen-to-square"></i> Editar</a>
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
            $('#tiposEquipos').DataTable({
                "lengthMenu": [[5, 10, 50, -1], [5, 10, 50, "All"]],
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/es_es.json"
                },
            });
        });
    </script>
@stop
