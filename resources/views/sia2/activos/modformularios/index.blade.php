{{-- Extendemos de la plantilla de adminlte --}}
@extends('adminlte::page')
{{-- Título de la pestaña --}}
@section('title', 'Gestión de Formularios')
{{-- Cabecera de la página (Titulo en grande y sweetalerts) --}}
@section('content_header')
    <h1>Gestión de Formularios</h1>
    {{-- Lógica de roles para los mensajes superiores --}}
@stop

{{-- Contenido principal --}}
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
    {{-- Boton para agregar --}}
    <a class="btn btn-primary mb-3" href="{{ route('formularios.create') }}"> <i class="fa-solid fa-plus"></i> Agregar formulario</a>

    {{-- Tabla de Formularios --}}
    <div class="table-responsive">
        <table id="formularios" class="table table-bordered mt-4">
            <thead class="bg-primary text-white">
                <tr>
                    <th scope="col">Nombre formulario</th>
                    <th scope="col">Tipo de formulario</th>
                    <th scope="col">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($formularios as $formulario)
                    <tr>
                        <td>{{ $formulario->FORMULARIO_NOMBRE }}</td>
                        <td>{{ $formulario->FORMULARIO_TIPO }}</td>
                        <td>
                            <div class="d-flex justify-content-center">
                                <a href="{{ route('formularios.edit', $formulario->FORMULARIO_ID) }}" class="btn btn-info">
                                    <i class="fa-solid fa-pen-to-square"></i> Editar
                                </a>
                                <form action="{{ route('formularios.destroy', $formulario->FORMULARIO_ID) }}" method="POST" class="ml-2">
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
        </table>
    </div>
@stop
{{-- Seccion para los estilos CSS --}}
@section('css')
@stop
{{-- Seccion para los Javascripts --}}
@section('js')
    <script>
        $(document).ready(function() {
            $('#formularios').DataTable({
                "lengthMenu": [[5, 10, 50, -1], [5, 10, 50, "All"]],
                "columnDefs": [
                    { "orderable": false, "targets": 2 }
                ],
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json"
                }
            });
        });
    </script>
@stop
