@extends('adminlte::page')

@section('title', 'Salas y Bodegas')

@section('content_header')
    <h1>Listado de Salas y Bodegas</h1>
    {{-- Lógica para roles - Puedes comentar esta sección si no es necesaria --}}
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
    <a class="btn agregar mb-3" href="{{ route('salasobodegas.create') }}"><i class="fa-solid fa-plus"></i> Agregar Sala o Bodega</a>
    {{-- Tabla de contenido --}}
    <div class="table-responsive">
        <table id="salasobodegas" class="table table-bordered mt-4">
            <thead class="tablacolor">
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
                        <td>
                            <div class="d-flex justify-content-center">
                                <a href="{{ route('salasobodegas.edit', $salaobodega->SALA_O_BODEGA_ID) }}" class="btn botoneditar">
                                    <i class="fa-solid fa-pen-to-square"></i> Editar
                                </a>
                                <form action="{{ route('salasobodegas.destroy', $salaobodega->SALA_O_BODEGA_ID) }}" method="POST" class="ml-2">
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
            $('#salasobodegas').DataTable({
                "lengthMenu": [[5,10, 50, -1], [5, 10, 50, "All"]],
                "columnDefs": [
                    { "orderable": false, "targets": 4 } // La segunda columna no es ordenable
                ],
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/es_es.json"
                },
            });
        });
    </script>
@stop
