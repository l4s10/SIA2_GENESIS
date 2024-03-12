@extends('adminlte::page')

@section('title', 'Gestión de Materiales')

@section('content_header')
    <h1>Listado de Materiales</h1>
    {{-- Lógica de roles --}}
    @role('ADMINISTRADOR')
    <div class="alert alert-info alert1" role="alert">
    <div><strong>Bienvenido Administrador:</strong> En este módulo usted podrá administrar, consultar, modificar (ingresos y egresos), del inventario, este módulo cuenta con un módulo de historial para consultar los movimientos de este.<div>
    </div>
    @endrole
    @role('SERVICIOS')
    <div class="alert alert-info" role="alert">
    <div><strong>Bienvenido Servicio:</strong> En este módulo usted podrá administrar, consultar, modificar (ingresos y egresos), del inventario, este módulo cuenta con un módulo de historial para consultar los movimientos de este.<div>
    </div>
    @endrole
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

    <a class="btn agregar" href="{{ route('materiales.create') }}"><i class="fa-solid fa-plus"></i> Agregar Material</a>
    <a class="btn btn-secondary" href="{{route('tiposmateriales.index')}}"><i class="fa-solid fa-eye"></i> Ver tipos de materiales</a>
    {{-- Boton para exportar a EXCEL --}}
    <a href="{{ route('exportar-materiales-excel') }}" class="btn pdf">
        <i class="fa-solid fa-file-excel"></i> Exportar Excel
    </a>
    {{-- Boton para exportar a PDF --}}
    <a href="{{ route('exportar-materiales-pdf') }}" class="btn pdf" target="_blank">
        <i class="fa-solid fa-file-pdf"></i> Exportar PDF
    </a>
    
    {{-- Acordeón para filtrar materiales --}}
    <div class="accordion" id="accordionFiltrarMateriales">
        <div class="card my-4">
            <div class="card-header" id="headingOne">
                <h2 class="mb-0">
                    <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                        Filtrar Materiales
                    </button>
                </h2>
            </div>

            <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordionFiltrarMateriales">
                <div class="card-body">
                    <form action="{{ route('materiales.search') }}" method="GET">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="TIPO_MATERIAL_ID">Tipo de Material</label>
                                    <select class="form-control" id="TIPO_MATERIAL_ID" name="TIPO_MATERIAL_ID">
                                        {{-- Opción para no seleccionar un tipo específico --}}
                                        <option value="">Seleccione un tipo</option>
                                        {{-- Suponiendo que tienes una colección $tiposMateriales disponible --}}
                                        @foreach($tiposMateriales as $tipo)
                                            <option value="{{ $tipo->TIPO_MATERIAL_ID }}">{{ $tipo->TIPO_MATERIAL_NOMBRE }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="MATERIAL_NOMBRE">Nombre del Material</label>
                                    <input type="text" class="form-control" id="MATERIAL_NOMBRE" name="MATERIAL_NOMBRE" placeholder="Ingrese nombre">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="STOCK_MIN">Stock Mínimo</label>
                                    <input type="number" class="form-control" id="STOCK_MIN" name="STOCK_MIN" placeholder="Stock mínimo">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="STOCK_MAX">Stock Máximo</label>
                                    <input type="number" class="form-control" id="STOCK_MAX" name="STOCK_MAX" placeholder="Stock máximo">
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Filtrar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

        {{-- Tabla de Materiales --}}
        <div class="table-responsive">
            <table id="materiales" class="table table-bordered mt-4">
                <thead class="tablacolor">
                    <tr>
                        <th scope="col">Tipo material</th>
                        <th scope="col">Nombre material</th>
                        <th scope="col">Stock</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($materiales as $material)
                        <tr>
                            <td>{{ $material->tipoMaterial->TIPO_MATERIAL_NOMBRE }}</td>
                            <td>{{ $material->MATERIAL_NOMBRE }}</td>
                            <td class="{{ $material->MATERIAL_STOCK <= 5 ? 'estado-critico' : '' }}">{{ $material->MATERIAL_STOCK }}</td>
                            <td>
                                <div class="d-flex justify-content-center">
                                    <a href="{{ route('materiales.edit', $material->MATERIAL_ID) }}" class="btn botoneditar">
                                        <i class="fa-solid fa-pen-to-square"></i> Editar
                                    </a>
                                    <form action="{{ route('materiales.destroy', $material->MATERIAL_ID) }}" method="POST" class="ml-2">
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
        .estado-critico {
        color: #F70B0B;
        }
        .pdf{
            background-color: #00B050;
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

@section('js')
    <script>
        $(document).ready(function () {
            // Inicialización de DataTables
            $('#materiales').DataTable({
                "lengthMenu": [[5, 10, 50, -1], [5, 10, 50, "All"]],
                "columnDefs": [
                    { "orderable": false, "targets": 3 }
                ],
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/es_es.json"
                },
            });
        });
    </script>
@stop
