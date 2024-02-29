@extends('adminlte::page')

<!-- TITULO DE LA PESTAÑA -->
@section('title', 'Mostrar facultades')

<!-- CABECERA DE LA PAGINA -->
@section('content_header')
    <h1>Listado de Facultades</h1>
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
    
    <a class="btn agregar" href="{{ route('facultades.create') }}"><i class="fa-solid fa-plus"></i> Agregar Facultad</a>
    {{--<a href="{{route('facultades.create')}}" class="btn" style="background-color: #0099FF; color: white;">Ingresar nueva facultad</a>--}}
    <br><br>
    <div class="table-responsive">
        <table id="facultades" class="table text-justify table-bordered mt-4 mx-auto" >
            <thead class="bg-primary text-white">
                <tr>
                    <th scope="col">N° Facultad</th>
                    <th scope="col">Nombre</th>
                    <th scope="col">Contenido</th>
                    <th scope="col">Ley asociada</th>
                    <th scope="col">Artículo de ley</th>
                    <th scope="col">Administrar</th>
                </tr>
            </thead>
            <tbody>
                @foreach($facultades as $facultad)
                <tr>
                    <td>
                        <div class="d-flex justify-content-center">
                            {{$facultad->FACULTAD_NUMERO}}
                        </div>
                    </td>
                    <td>{{$facultad->FACULTAD_NOMBRE}}</td>
                    <td>{{$facultad->FACULTAD_CONTENIDO}}</td>
                    <td>{{$facultad->FACULTAD_LEY_ASOCIADA}}</td>
                    <td>{{$facultad->FACULTAD_ART_LEY_ASOCIADA}}</td>
                    <td>
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('facultades.edit', $facultad->FACULTAD_ID) }}" class="btn btn-secondary"><i class="fa-solid fa-pencil"></i></a>
                            <form action="{{ route('facultades.destroy', $facultad->FACULTAD_ID) }}" method="POST" style="display: inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger"><i class="fa-solid fa-trash"></i></button>
                            </form>
                        </div>            
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

@endsection

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
    <style>
        .alert {
        opacity: 0.7; 
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
            // Inicialización de DataTables
            $('#facultades').DataTable({
                "lengthMenu": [[5, 10, 50, -1], [5, 10, 50, "All"]],
                "columnDefs": [
                    { "orderable": false, "targets": 5 }
                ],
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/es_es.json"
                },
            });
        });

        $('.btn-expand-atributo').click(function() {
            $(this).hide();
            $(this).siblings('.btn-collapse-atributo').show();
            $(this).siblings('.atributo-abreviado').hide();
            $(this).siblings('.atributo-completo').show();
        });

        $('.btn-collapse-atributo').click(function() {
            $(this).hide();
            $(this).siblings('.btn-expand-atributo').show();
            $(this).siblings('.atributo-abreviado').show();
            $(this).siblings('.atributo-completo').hide();
        });
    </script>
@endsection
