@extends('adminlte::page')

<!-- TITULO DE LA PESTAÑA -->
@section('title', 'Mostrar comunas')

<!-- CABECERA DE LA PAGINA -->
@section('content_header')
    <h1>Lista de comunas</h1>
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

    <a class="btn agregar mb-4"><i class="fa-solid fa-plus"></i> Ingresar nueva comuna</a>

    <div class="table-responsive">
        <table id="comuna" class="table table-bordered mt-4">
            <thead class="tablacolor">
                <tr>
                    <th scope="col">Comuna</th>
                    <th scope="col">Region</th>
                    <th scope="col">Administrar</th>
                </tr>
            </thead>
            <tbody>
                @foreach($comunas as $comuna)
                <tr>
                    <td>{{$comuna->COMUNA_NOMBRE}}</td>
                    <td>{{$comuna->region->REGION_NOMBRE}}</td>
                    <td>
                        <div class="d-flex justify-content-center">
                            <a class="btn botoneditar"><i class="fa-solid fa-gear"></i></a>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection

@section('css')
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
            $('#comuna').DataTable({
                "lengthMenu": [[5 ,10, 50, -1], [5, 10, 50, "All"]],
                "columnDefs": [
                    { "orderable": false, "targets": 2 } // La séptima columna no es ordenable
                ],
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/es_es.json"
                },
            });
        });
    </script>
@endsection
