{{-- Extendemos de la plantilla de adminlte --}}
@extends('adminlte::page')
{{-- Título de la pestaña --}}
@section('title', 'Gestión de Formularios')
{{-- Cabecera de la página (Titulo en grande y sweetalerts) --}}
@section('content_header')
    <h1>Gestión de Formularios</h1>
    {{-- Lógica de roles para los mensajes superiores --}}
    @role('ADMINISTRADOR')
    <div class="alert alert-info alert1" role="alert">
    <div><strong>Bienvenido Administrador:</strong> Acceso total al modulo.<div>
    </div>
    @endrole
    @role('SERVICIOS')
    <div class="alert alert-info" role="alert">
    <div><strong>Bienvenido Servicio:</strong> En esta tabla puede editar las categorías (agregar, cambiar o eliminar), y que dicha acción no puede revertirse.<div>
    </div>
    @endrole
    @role('INFORMATICA')
    <div class="alert alert-info" role="alert">
    <div><strong>Bienvenido Informatica:</strong> En esta tabla puede editar las categorías (agregar, cambiar o eliminar), y que dicha acción no puede revertirse.<div>
    </div>
    @endrole
    @role('JURIDICO')
    <div class="alert alert-info" role="alert">
    <div><strong>Bienvenido Juridico:</strong> En esta tabla puede editar las categorías (agregar, cambiar o eliminar), y que dicha acción no puede revertirse.<div>
    </div>
    @endrole
    @role('FUNCIONARIO')
    <div class="alert alert-info" role="alert">
    <div><strong>Bienvenido Funcionario:</strong> En esta tabla puede editar las categorías (agregar, cambiar o eliminar), y que dicha acción no puede revertirse.<div>
    </div>
    @endrole
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
    <a class="btn agregar mb-3" href="{{ route('formularios.create') }}"> <i class="fa-solid fa-plus"></i> Agregar formulario</a>

    {{-- Acordeón para filtrar formularios --}}
    <div class="accordion" id="accordionFiltrarFormularios">
        <div class="card">
            <div class="card-header" id="headingFiltrarFormularios">
                <h2 class="mb-0">
                    <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapseFiltrarFormularios" aria-expanded="false" aria-controls="collapseFiltrarFormularios">
                        Filtrar Formularios
                    </button>
                </h2>
            </div>
            <div id="collapseFiltrarFormularios" class="collapse" aria-labelledby="headingFiltrarFormularios" data-parent="#accordionFiltrarFormularios">
                <div class="card-body">
                    <form action="{{ route('formularios.search') }}" method="GET">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="FORMULARIO_NOMBRE">Nombre del Formulario</label>
                                <input type="text" class="form-control" id="FORMULARIO_NOMBRE" name="FORMULARIO_NOMBRE" placeholder="Nombre">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="FORMULARIO_TIPO">Tipo de Formulario</label>
                                <select id="FORMULARIO_TIPO" name="FORMULARIO_TIPO" class="form-control">
                                    <option value="">Seleccione un tipo</option>
                                    <option value="TIPO A">TIPO A</option>
                                    <option value="TIPO B">TIPO B</option>
                                    <option value="TIPO C">TIPO C</option>
                                </select>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Filtrar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    {{-- Tabla de Formularios --}}
    <div class="table-responsive">
        <table id="formularios" class="table table-bordered mt-4">
            <thead class="tablacolor">
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
                                <a href="{{ route('formularios.edit', $formulario->FORMULARIO_ID) }}" class="btn botoneditar">
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
<style>/* Estilos personalizados si es necesario */
        .tablacolor {
            background-color: #0064a0; /* Color de fondo personalizado */
            color: #fff; /* Color de texto personalizado */
        }
        .agregar {
            background-color: #e6500a;
            color: #fff;
        }
        .botoneditar {
            background-color: #1aa16b;
            color: #fff;
        }
    </style>

    <!-- Color mensajes usuario -->
    <style>
        .alert {
            opacity: 0.7; /* Ajusta la opacidad del texto */
            background-color: #99CCFF;
            color:     #000000;
        }
        .alert1 {
            opacity: 0.7; /* Ajusta la opacidad del texto  */
            background-color: #FF8C40;
            color: #000000;
        }
    </style>
@stop
{{-- Seccion para los Javascripts --}}
@section('js')
    {{-- Script cooldown envio formulario (evita entradas repetidas) --}}
    <script src="{{ asset('js/Components/cooldownSendForm.js') }}"></script>
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
