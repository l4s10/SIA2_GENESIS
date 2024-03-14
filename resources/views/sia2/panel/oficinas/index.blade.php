@extends('adminlte::page')

<!-- TITULO DE LA PESTAÑA -->
@section('title', 'Mostrar direcciones regionales')

<!-- CABECERA DE LA PAGINA -->
@section('content_header')
    <h1>Listado de Direcciones Regionales</h1>
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
    <a href="{{route('panel.oficinas.create')}}" class="btn agregar" ><i class="fa-solid fa-plus"></i> Ingresar nueva dirección regional</a>
    <br><br>
        <div class="table-responsive">
            <table id="oficinas" class="table table-bordered mt-4">
                <thead class="tablacolor">
                    <tr>
                        <th scope="col">Nombre</th>
                        <th scope="col">Comuna</th>
                        <th scope="col">Región</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($oficinas as $oficina)
                    <tr>
                        <td>{{$oficina->OFICINA_NOMBRE}}</td>
                        <td>{{$oficina->comuna->COMUNA_NOMBRE}}</td>
                        <td>{{$oficina->comuna->region->REGION_NOMBRE}}</td>
                        <td>
                            <div class="d-flex justify-content-center">
                                <a href="{{route('panel.oficinas.edit',$oficina->OFICINA_ID)}}"class="btn botoneditar"> 
                                    <i class="fa-solid fa-pen-to-square"></i> Editar
                                </a>
                                @role('ADMINISTRADOR')
                                    <form action="{{route('panel.oficinas.destroy',$oficina->OFICINA_ID)}}" method="POST">
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
    <style>
        .tablacolor {
            background-color: #0064a0; 
            color: #fff;
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
|@stop

@section('js')
    <script>
        $(document).ready(function () {
            $('#oficinas').DataTable({
                "lengthMenu": [[5,10, 50, -1], [5, 10, 50, "All"]],
                "responsive": false,
                "columnDefs": [
                    { "orderable": false, "targets": 3 }
                ],
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json"
                },
            });
        });
    </script>
@endsection
