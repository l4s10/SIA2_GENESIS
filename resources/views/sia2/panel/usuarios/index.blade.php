@extends('adminlte::page')

@section('title', 'Mostrar Usuarios')

@section('content_header')
    <h1>Lista de usuarios</h1>
    {{-- Lógica de roles --}}
    {{-- ... --}}
@stop

@section('content')
    {{-- sweetalerts de session --}}
<div class="container">
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
            document.addEventListener('DOMContentLoader', () => {
                Swal.fire([
                    icon: 'error',
                    title: '{{ session('error') }}',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#0064A0'
                ]);
            });
        </script>
    @endif
        <a class="btn agregar"><i class="fa-solid fa-plus"></i> Ingresar nuevo funcionario</a>
        <br><br>
        {{-- Tabla de usuario --}}
        <div class="table-responsive">
            <table id="usuarios" class="table table-bordered mt-4">
                <thead class="tablacolor">
                    <tr>
                        <th scope="col">Nombres</th>
                        <th scope="col">Apellidos</th>
                        <th scope="col">Rut</th>
                        <th scope="col">Correo</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                        @foreach($usuarios as $usuario)
                            <tr>
                                <td> {{$usuario->USUARIO_NOMBRES}} </td>
                                <td> {{$usuario->USUARIO_APELLIDOS}} </td>
                                <td> {{$usuario->USUARIO_RUT}} </td>
                                <td> {{$usuario->email}} </td>
                                <td><a class="btn botoneditar" ><i class="fa-solid fa-pen-to-square"></i> Administrar</a></td>
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
            $('#usuarios').DataTable({
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
