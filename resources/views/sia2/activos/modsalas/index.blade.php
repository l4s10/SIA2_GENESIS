@extends('adminlte::page')

@section('title', 'Salas')

@section('content_header')
    <h1>Listado de Salas</h1>
    {{-- roles --}}
    @role('ADMINISTRADOR')
    <div class="alert alert-info alert1" role="alert">
    <div><strong>Bienvenido Administrador:</strong> En esta tabla se muestran todas las salas registradas en el sistema.<div>
    </div>
    @endrole
    @role('SERVICIOS')
    <div class="alert alert-info" role="alert">
    <div><strong>Bienvenido Servicio:</strong> Aqui iria el texto donde le corresponde el rol SERVICIO.<div>
    </div>
    @endrole
    @role('INFORMATICA')
    <div class="alert alert-info" role="alert">
    <div><strong>Bienvenido Informatica:</strong> En esta tabla se muestran todas las salas registradas en el sistema.<div>
    </div>
    @endrole
    @role('JURIDICO')
    <div class="alert alert-info" role="alert">
    <div><strong>Bienvenido Juridico:</strong> Aqui iria el texto donde le corresponde el rol JURIDICO.<div>
    </div>
    @endrole
    @role('FUNCIONARIO')
    <div class="alert alert-info" role="alert">
    <div><strong>Bienvenido Funcionario:</strong> Aqui iria el texto donde le corresponde el rol FUNCIONARIO.<div>
    </div>
    @endrole
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
    <a class="btn agregar mb-3" href="{{ route('salas.create') }}"><i class="fa-solid fa-plus"></i> Agregar Sala</a>

    {{-- Acordeón para filtrar salas --}}
    <div class="accordion" id="accordionFiltrarSalas">
        <div class="card">
            <div class="card-header" id="headingFiltrarSalas">
                <h2 class="mb-0">
                    <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapseFiltrarSalas" aria-expanded="false" aria-controls="collapseFiltrarSalas">
                        Filtrar Salas
                    </button>
                </h2>
            </div>
            <div id="collapseFiltrarSalas" class="collapse" aria-labelledby="headingFiltrarSalas" data-parent="#accordionFiltrarSalas">
                <div class="card-body">
                    <form action="{{ route('salas.search') }}" method="GET">
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="SALA_NOMBRE">Nombre de la Sala</label>
                                <input type="text" class="form-control" id="SALA_NOMBRE" name="SALA_NOMBRE" placeholder="Nombre">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="SALA_CAPACIDAD">Capacidad Mínima</label>
                                <input type="number" class="form-control" id="SALA_CAPACIDAD" name="SALA_CAPACIDAD" placeholder="Capacidad">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="SALA_ESTADO">Estado</label>
                                <select id="SALA_ESTADO" name="SALA_ESTADO" class="form-control">
                                    <option value="">Seleccione un estado</option>
                                    <option value="DISPONIBLE">DISPONIBLE</option>
                                    <option value="OCUPADO">OCUPADO</option>
                                    <option value="DESHABILITADO">DESHABILITADO</option>
                                </select>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Filtrar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    {{-- Tabla de contenido --}}
    <div class="table-responsive">
        <table id="salas" class="table table-bordered mt-4">
            <thead class="tablacolor">
                <tr>
                    <th scope="col">Nombre</th>
                    <th scope="col">Capacidad</th>
                    <th scope="col">Estado</th>
                    <th scope="col">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($salas as $sala)
                    <tr>
                        <td>{{ $sala->SALA_NOMBRE }}</td>
                        <td>{{ $sala->SALA_CAPACIDAD }}</td>
                        <td><span class="badge rounded-pill estado-{{ strtolower(str_replace(' ', '-', $sala->SALA_ESTADO )) }}">
                        {{ $sala->SALA_ESTADO  }}
                        </span>
                        </td>
                        <td>
                            <div class="d-flex justify-content-center">
                                <a href="{{ route('salas.edit', $sala->SALA_ID) }}" class="btn botoneditar">
                                    <i class="fa-solid fa-pen-to-square"></i> Editar
                                </a>
                                <form action="{{ route('salas.destroy', $sala->SALA_ID) }}" method="POST" class="ml-2">
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

        .estado-disponible {
        color: #ffffff;
        background-color: #0CB009;
        }

        .estado-ocupado {
            color: #FFFFFF;
            background-color: #F70B0B;
        }

        .estado-desabilitado {
        color: #000000;
        background-color: #F7F70B;
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

@section('js')
    {{-- Script cooldown envio formulario (evita entradas repetidas) --}}
    <script src="{{ asset('js/Components/cooldownSendForm.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('#salas').DataTable({
                "lengthMenu": [[5,10, 50, -1], [5, 10, 50, "All"]],
                "columnDefs": [
                    { "orderable": false, "targets": 3 } // La segunda columna no es ordenable
                ],
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/es_es.json"
                },
            });
        });
    </script>
@stop
