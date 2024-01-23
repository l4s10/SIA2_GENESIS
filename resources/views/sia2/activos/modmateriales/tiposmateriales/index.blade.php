@extends('adminlte::page')

@section('title', 'Tipos de Material')

@section('content_header')
    <h1>Listado de Tipos de Material</h1>
    {{-- Lógica de roles --}}
    {{-- ... --}}
@stop

@section('content')
    {{-- sweetalerts de session --}}
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
    {{-- Botones de acceso rapido --}}
    <a class="btn agregar mb-3" href="{{ route('tiposmateriales.create') }}"><i class="fa-solid fa-plus"></i> Agregar Tipo de Material</a>
    <a class="btn btn-secondary mb-3" href="{{ route('materiales.index')}}"><i class="fa-solid fa-eye"></i> Administrar Materiales</a>

    <div class="table-responsive">
        <table id="tiposMateriales" class="table table-bordered mt-4">
            <thead class="tablacolor">
                <tr>
                    <th scope="col">Tipo de Material</th>
                    <th scope="col">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tiposMaterial as $tipo)
                    <tr>
                        <td>{{ $tipo->TIPO_MATERIAL_NOMBRE }}</td>
                        <td>
                            <div class="d-flex justify-content-center">
                                <a href="{{ route('tiposmateriales.edit', $tipo->TIPO_MATERIAL_ID) }}" class="btn botoneditar">
                                    <i class="fa-solid fa-pen-to-square"></i> Editar
                                </a>
                                <form action="{{ route('tiposmateriales.destroy', $tipo->TIPO_MATERIAL_ID) }}" method="POST" class="ml-2">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">
                                        <i class="fa-solid fa-trash"></i> Borrar
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
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
            $('#tiposMateriales').DataTable({
                "lengthMenu": [[5, 10, 50, -1], [5, 10, 50, "All"]],
                "columnDefs": [
                    { "orderable": false, "targets": 1 }
                ],
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/es_es.json"
                },
            });
        });
    </script>
@stop
