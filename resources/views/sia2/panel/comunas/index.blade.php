@extends('adminlte::page')

<!-- TITULO DE LA PESTAÑA -->
@section('title', 'Mostrar comunas')

<!-- CABECERA DE LA PAGINA -->
@section('content_header')
    <h1>Listado de Comunas</h1>
    {{--<br>
    @role('ADMINISTRADOR')
    <div class="alert alert-info alert1" role="alert">
    <div><strong>Bienvenido Administrador:</strong> Acceso total al modulo.<div>
    </div>
    @endrole
    @role('INFORMATICA')
    <div class="alert alert-info" role="alert">
    <div><strong>Bienvenido Informatica:</strong> Aqui iria el texto donde le corresponde el rol INFORMATICA.<div>
    </div>
    @endrole--}}
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
    <br>
    <a href="{{route('panel.comunas.create')}}" class="btn agregar" ><i class="fa-solid fa-plus"></i> Ingresar nueva comuna</a>
    <br><br>
        <div class="table-responsive">
            <table id="comunas" class="table table-bordered mt-4">
                <thead class="tablacolor">
                    <tr>
                        <th scope="col">Nombre</th>
                        <th scope="col">Región
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($comunas as $comuna)
                    <tr>
                        <td>{{$comuna->COMUNA_NOMBRE}}</td>
                        <td>{{$comuna->region->REGION_NOMBRE}}</td>
                        <td>
                            <div class="d-flex justify-content-center">
                                <a href="{{route('panel.comunas.edit',$comuna->COMUNA_ID)}}"class="btn botoneditar"> 
                                    <i class="fa-solid fa-pen-to-square"></i> Editar
                                </a>
                                @role('ADMINISTRADOR')
                                    <form action="{{route('panel.comunas.destroy',$comuna->COMUNA_ID)}}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                            <button type="submit" class="btn btn-danger">
                                                <i class="fa-solid fa-trash"></i> Borrar
                                            </button>
                                    </form>
                                @endrole
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
</div>
@endsection


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
    <!-- Para inicializar -->
    <script>
        $(document).ready(function () {
            $('#comunas').DataTable({
                "lengthMenu": [[5,10, 50, -1], [5, 10, 50, "All"]],
                "responsive": false,
                "columnDefs": [
                    { "orderable": false, "targets": 2 }
                ],
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json"
                },
            });
        });
    </script>
@endsection
