@extends('adminlte::page')

<!-- TITULO DE LA PESTAÑA -->
@section('title', 'Mostrar pólizas')

<!-- CABECERA DE LA PAGINA -->
@section('content_header')
    <h1>Listado de Pólizas de Conductores</h1>
    @role('ADMINISTRADOR')
    <div class="alert alert-info alert1" role="alert">
        <div><strong>Bienvenido Administrador:</strong> Acceso total al modulo.<div>
    </div>
    @endrole
    @role('INFORMATICA')
    <div class="alert alert-info" role="alert">
        <div><strong>Bienvenido Informatica:</strong> Aqui iria el texto donde le corresponde el rol INFORMATICA.<div>
    </div>
    @endrole
@stop

@section('content')
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

        <a class="btn agregar" href="{{ route('polizas.create') }}"><i class="fa-solid fa-plus"></i> Agregar Póliza</a>
        <br><br>

        <div class="table-responsive">
            <table id="polizas" class="table text-justify table-bordered mt-4 mx-auto" style="white-space:nowrap;">
                <thead class="bg-primary text-white">
                    <tr>
                        <th scope="col">N° Póliza</th>
                        <th scope="col">Conductor</th>
                        <th scope="col">Vencimiento</th>
                        <th scope="col">Administrar</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($usuariosConductores as $usuario)
                        @foreach($usuario->polizas as $poliza)
                            <tr>
                                <td>
                                    <div class="d-flex justify-content-center">
                                        {{$poliza->POLIZA_NUMERO}}
                                    </div>
                                </td>
                                <td>{{$usuario->USUARIO_NOMBRES}} {{$usuario->USUARIO_APELLIDOS}}</td>
                                <td>
                                    <div class="d-flex justify-content-center">
                                        {{ date('d-m-Y', strtotime($poliza->POLIZA_FECHA_VENCIMIENTO_LICENCIA)) }}
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center">
                                        <a href="{{ route('polizas.edit', $poliza->POLIZA_ID) }}" class="btn btn-secondary"><i class="fa-solid fa-pencil"></i></a>
                                        <form action="{{ route('polizas.destroy', $poliza->POLIZA_ID) }}" method="POST" style="display: inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger"><i class="fa-solid fa-trash"></i></button>
                                        </form>
                                    </div>      
                                </td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
    <style>
        .alert {
            opacity: 0.7; /* Ajusta la opacidad a tu gusto */
            background-color: #99CCFF;
            color:     #000000;
        }
    </style>
    <style>
        .alert1 {
            opacity: 0.7;
            /* Ajusta la opacidad a tu gusto */
            background-color: #FF8C40;
            /* Color naranjo claro (RGB: 255, 214, 153) */
            color: #000000;
        }
    </style>
    
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
@endsection

@section('js')
    <!-- Para inicializar -->
    <script>
        $(document).ready(function () {
            $('#polizas').DataTable({
                "lengthMenu": [[5,10, 50, -1], [5, 10, 50, "All"]],
                "responsive": true,
                "columnDefs": [
                    { "orderable": false, "targets": 3 } // La séptima columna no es ordenable
                ],
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/es_es.json"
                },
            });
        });
    </script>
@endsection