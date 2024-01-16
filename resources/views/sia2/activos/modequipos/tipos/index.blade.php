@extends('adminlte::page')

@section('title', 'Tipos de Equipos')

@section('content_header')
    <h1>Tipos de Equipos</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <table id="myTable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tiposEquipo as $tipoEquipo)
                        <tr>
                            <td>{{ $tipoEquipo->TIPO_EQUIPO_NOMBRE }}</td>
                            <td> Hey </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap4.min.css">
@stop

@section('js')
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap4.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#myTable').DataTable();
        });
    </script>
@stop
