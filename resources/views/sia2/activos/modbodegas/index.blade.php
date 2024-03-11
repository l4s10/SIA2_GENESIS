@extends('adminlte::page')

@section('title', 'Bodegas')

@section('content_header')
    <h1>Listado de Bodegas</h1>
    {{-- roles --}}
    @role('ADMINISTRADOR')
    <div class="alert alert-info alert1" role="alert">
    <div><strong>Bienvenido Administrador:</strong> En esta tabla se muestran todas las bodegas registradas en el sistema.<div>
    </div>
    @endrole
    @role('SERVICIOS')
    <div class="alert alert-info" role="alert">
    <div><strong>Bienvenido Servicio:</strong> Aqui iria el texto donde le corresponde el rol SERVICIO.<div>
    </div>
    @endrole
    @role('INFORMATICA')
    <div class="alert alert-info" role="alert">
    <div><strong>Bienvenido Informatica:</strong> En esta tabla se muestran todas las bodegas registradas en el sistema.<div>
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
    <a class="btn agregar mb-3" href="{{ route('bodegas.create') }}"><i class="fa-solid fa-plus"></i> Agregar Bodega</a>

    {{-- Acordeón para filtrar bodegas --}}
    <div class="accordion" id="accordionFiltrarBodegas">
        <div class="card">
            <div class="card-header" id="headingFiltrarBodegas">
                <h2 class="mb-0">
                    <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapseFiltrarBodegas" aria-expanded="false" aria-controls="collapseFiltrarBodegas">
                        Filtrar Bodegas
                    </button>
                </h2>
            </div>
            <div id="collapseFiltrarBodegas" class="collapse" aria-labelledby="headingFiltrarBodegas" data-parent="#accordionFiltrarBodegas">
                <div class="card-body">
                    <form action="{{ route('bodegas.search') }}" method="GET">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="BODEGA_NOMBRE">Nombre de la Bodega</label>
                                <input type="text" class="form-control" id="BODEGA_NOMBRE" name="BODEGA_NOMBRE" placeholder="Nombre">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="BODEGA_ESTADO">Estado</label>
                                <select id="BODEGA_ESTADO" name="BODEGA_ESTADO" class="form-control">
                                    <option value="">Seleccione un estado</option>
                                    <option value="DISPONIBLE">DISPONIBLE</option>
                                    <option value="NO DISPONIBLE">NO DISPONIBLE</option>
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
        <table id="bodegas" class="table table-bordered mt-4">
            <thead class="tablacolor">
                <tr>
                    <th scope="col">Nombre</th>
                    <th scope="col">Estado</th>
                    <th scope="col">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($bodegas as $bodega)
                    <tr>
                        <td>{{ $bodega->BODEGA_NOMBRE }}</td>
                        <td><span class="badge rounded-pill estado-{{ strtolower(str_replace(' ', '-', $bodega->BODEGA_ESTADO)) }}">
                        {{ $bodega->BODEGA_ESTADO }}
                        </span>
                        </td>
                        <td>
                            <div class="d-flex justify-content-center">
                                <a href="{{ route('bodegas.edit', $bodega->BODEGA_ID) }}" class="btn botoneditar">
                                    <i class="fa-solid fa-pen-to-square"></i> Editar
                                </a>
                                <form action="{{ route('bodegas.destroy', $bodega->BODEGA_ID) }}" method="POST" class="ml-2">
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
    <script>
        $(document).ready(function () {
            $('#bodegas').DataTable({
                "lengthMenu": [[5,10, 50, -1], [5, 10, 50, "All"]],
                "columnDefs": [
                    { "orderable": false, "targets": 2 } // La segunda columna no es ordenable
                ],
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/es_es.json"
                },
            });
        });
    </script>
@stop
