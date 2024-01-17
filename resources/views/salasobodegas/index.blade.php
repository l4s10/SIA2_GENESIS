@extends('adminlte::page')

@section('title', 'Salas y Bodegas')

@section('content_header')
    <h1>Listado de Salas y Bodegas</h1>
    {{-- Lógica para roles - Puedes comentar esta sección si no es necesaria --}}
    {{-- ... --}}
@stop

@section('content')
    <div class="container">
        {{-- Lógica para mensajes de éxito o error - Puedes comentar esta sección si no es necesaria --}}
        {{-- ... --}}

        <div class="table-responsive">
            <table id="salasobodegas" class="table table-bordered mt-4">
                <thead class="bg-primary text-white">
                    <tr>
                        <th scope="col">Nombre</th>
                        <th scope="col">Tipo</th>
                        <th scope="col">Capacidad</th>
                        <th scope="col">Estado</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($salasobodegas as $salaobodega)
                        <tr>
                            <td>{{ $salaobodega->SALA_O_BODEGA_NOMBRE }}</td>
                            <td>{{ $salaobodega->SALA_O_BODEGA_TIPO }}</td>
                            <td>{{ $salaobodega->SALA_O_BODEGA_CAPACIDAD }}</td>
                            <td>{{ $salaobodega->SALA_O_BODEGA_ESTADO }}</td>
                            
                            <td style="text-align: center; vertical-align: middle;">
                                <a href="{{ route('salasobodegas.edit', $salaobodega->SALA_O_BODEGA_ID) }}" class="btn btn-info"><i class="fa-solid fa-pen-to-square"></i> Editar</a>
                                <form action="{{ route('salasobodegas.destroy', $salaobodega->SALA_O_BODEGA_ID) }}" method="POST"> @csrf @method('DELETE') 
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
        /* Estilos adicionales según necesidad */
    </style>
@stop

@section('js')
    <script>
        $(document).ready(function () {
            $('#salasobodegas').DataTable({
                "lengthMenu": [[5,10, 50, -1], [5, 10, 50, "All"]],
                "responsive": true,
                "columnDefs": [
                    { "orderable": false, "targets": 1 } // La segunda columna no es ordenable
                ],
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/es_es.json"
                },
            });
        });
    </script>
@stop
