@extends('adminlte::page')

@section('title', 'Gestión de Vehículos')

@section('content_header')
    <h1>Listado de Vehículos</h1>
    {{-- Lógica de roles --}}
    @role('ADMINISTRADOR')
    <div class="alert alert-info alert1" role="alert">
    <div><strong>Bienvenido Administrador:</strong> En este módulo usted podrá registrar, visualizar, modificar o eliminar los vehículos del sistema.<div>
    </div>
    @endrole
    @role('SERVICIOS')
    <div class="alert alert-info" role="alert">
    <div><strong>Bienvenido Servicio:</strong>  En este módulo usted podrá registrar, visualizar, modificar o eliminar los vehículos del sistema.<div>
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

    <a class="btn agregar mb-4" href="{{ route('vehiculos.create') }}"><i class="fa-solid fa-plus"></i> Agregar Vehículo</a>

    {{-- Acordeón para filtrar vehículos --}}
    <div class="accordion" id="accordionFiltrarVehiculos">
        <div class="card">
            <div class="card-header" id="headingFiltrarVehiculos">
                <h2 class="mb-0">
                    <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapseFiltrarVehiculos" aria-expanded="false" aria-controls="collapseFiltrarVehiculos">
                        Filtrar Vehículos
                    </button>
                </h2>
            </div>
            <div id="collapseFiltrarVehiculos" class="collapse" aria-labelledby="headingFiltrarVehiculos" data-parent="#accordionFiltrarVehiculos">
                <div class="card-body">
                    <form action="{{ route('vehiculos.search') }}" method="GET">
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="VEHICULO_PATENTE">Patente</label>
                                <input type="text" class="form-control" id="VEHICULO_PATENTE" name="VEHICULO_PATENTE" placeholder="Patente">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="VEHICULO_MARCA">Marca</label>
                                <input type="text" class="form-control" id="VEHICULO_MARCA" name="VEHICULO_MARCA" placeholder="Marca">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="VEHICULO_MODELO">Modelo</label>
                                <input type="text" class="form-control" id="VEHICULO_MODELO" name="VEHICULO_MODELO" placeholder="Modelo">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label for="VEHICULO_ANO">Año</label>
                                <input type="text" class="form-control" id="VEHICULO_ANO" name="VEHICULO_ANO" placeholder="Año">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="VEHICULO_ESTADO">Estado</label>
                                <select class="form-control" id="VEHICULO_ESTADO" name="VEHICULO_ESTADO">
                                    <option value="">Seleccione un estado</option>
                                    <option value="DISPONIBLE">DISPONIBLE</option>
                                    <option value="OCUPADO">OCUPADO</option>
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="VEHICULO_KILOMETRAJE">Kilometraje</label>
                                <input type="number" class="form-control" id="VEHICULO_KILOMETRAJE" name="VEHICULO_KILOMETRAJE" placeholder="Kilometraje">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="VEHICULO_NIVEL_ESTANQUE">Nivel de Estanque</label>
                                <select class="form-control" id="VEHICULO_NIVEL_ESTANQUE" name="VEHICULO_NIVEL_ESTANQUE">
                                    <option value="">Seleccione un nivel</option>
                                    <option value="VACIO">VACIO</option>
                                    <option value="BAJO">BAJO</option>
                                    <option value="MEDIO BAJO">MEDIO BAJO</option>
                                    <option value="MEDIO">MEDIO</option>
                                    <option value="MEDIO LLENO">MEDIO LLENO</option>
                                    <option value="LLENO">LLENO</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="TIPO_VEHICULO_ID">Tipo de Vehículo</label>
                                <select class="form-control" id="TIPO_VEHICULO_ID" name="TIPO_VEHICULO_ID">
                                    <option value="">Seleccione un tipo</option>
                                    {{-- Itera sobre los tipos de vehículos disponibles --}}
                                    @foreach ($tiposVehiculos as $tipoVehiculo)
                                        <option value="{{ $tipoVehiculo->TIPO_VEHICULO_ID }}">{{ $tipoVehiculo->TIPO_VEHICULO_NOMBRE }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="UBICACION_ID">Ubicación</label>
                                <select class="form-control" id="UBICACION_ID" name="UBICACION_ID">
                                    <option value="">Seleccione una ubicación</option>
                                    {{-- Itera sobre las ubicaciones disponibles --}}
                                    @foreach ($ubicaciones as $ubicacion)
                                        <option value="{{ $ubicacion->UBICACION_ID }}">{{ $ubicacion->UBICACION_NOMBRE }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="DEPARTAMENTO_ID">Departamento</label>
                                <select class="form-control" id="DEPARTAMENTO_ID" name="DEPARTAMENTO_ID">
                                    <option value="">Seleccione un departamento</option>
                                    {{-- Itera sobre los departamentos disponibles --}}
                                    @foreach ($departamentos as $departamento)
                                        <option value="{{ $departamento->DEPARTAMENTO_ID }}">{{ $departamento->DEPARTAMENTO_NOMBRE }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Filtrar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabla de Vehículos --}}
    <div class="table-responsive">
        <table id="vehiculos" class="table table-bordered mt-4">
            <thead class="tablacolor">
                <tr>
                    <th scope="col">Tipo Vehículo</th>
                    <th scope="col">Patente</th>
                    <th scope="col">Marca</th>
                    <th scope="col">Modelo</th>
                    <th scope="col">Año</th>
                    <th scope="col">Estado</th>
                    <th scope="col">Kilometraje</th>
                    <th scope="col">Nivel de Estanque</th>
                    <th scope="col">Ubicación/Departamento</th>
                    <th scope="col">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($vehiculos as $vehiculo)
                    <tr>
                        <td>{{ $vehiculo->tipoVehiculo->TIPO_VEHICULO_NOMBRE }}</td>
                        <td>{{ $vehiculo->VEHICULO_PATENTE }}</td>
                        <td>{{ $vehiculo->VEHICULO_MARCA }}</td>
                        <td>{{ $vehiculo->VEHICULO_MODELO }}</td>
                        <td>{{ $vehiculo->VEHICULO_ANO }}</td>
                        <td><span class="badge rounded-pill estado-{{ strtolower(str_replace(' ', '-', $vehiculo->VEHICULO_ESTADO )) }}">
                        {{ $vehiculo->VEHICULO_ESTADO  }}
                        </span>
                        </td>
                        <td>{{ $vehiculo->VEHICULO_KILOMETRAJE }}</td>
                        <td>{{ $vehiculo->VEHICULO_NIVEL_ESTANQUE }}</td>
                        <td>
                            {{--- (Si la relación no existe (es decir, es null), entonces isset($vehiculo->ubicacion->UBICACION_NOMBRE) devolverá "false") --}}
                            @if ($vehiculo->ubicacion && isset($vehiculo->ubicacion->UBICACION_NOMBRE)) {{-- Si el vehículo tiene ubicación y la propiedad UBICACION_NOMBRE está definida --}}
                                {{ $vehiculo->ubicacion->UBICACION_NOMBRE }}
                            {{--- (Si la relación no existe (es decir, es null), entonces isset($vehiculo->departamento->DEPARTAMENTO_NOMBRE) devolverá "false") --}}
                            @elseif ($vehiculo->departamento && isset($vehiculo->departamento->DEPARTAMENTO_NOMBRE)) {{-- Si el vehículo tiene departamento y la propiedad DEPARTAMENTO_NOMBRE está definida --}}
                                {{ $vehiculo->departamento->DEPARTAMENTO_NOMBRE }}
                            @elseif ($vehiculo->ubicacion && $vehiculo->departamento) {{-- Si el vehículo tiene tanto ubicación como departamento --}}
                                {{ $vehiculo->ubicacion->UBICACION_NOMBRE }} - {{ $vehiculo->departamento->DEPARTAMENTO_NOMBRE }}
                            @else
                                Sin Ubicación/Departamento Asociado {{-- Si no cumple ninguna de las condiciones anteriores --}}
                            @endif
                        </td>

                        <td>
                            <div class="d-flex justify-content-center">
                                <a href="{{ route('vehiculos.edit', $vehiculo->VEHICULO_ID) }}" class="btn botoneditar">
                                    <i class="fa-solid fa-pen-to-square"></i> Editar
                                </a>
                                <form action="{{ route('vehiculos.destroy', $vehiculo->VEHICULO_ID) }}" method="POST" class="ml-2">
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
            $('#vehiculos').DataTable({
                "lengthMenu": [[5, 10, 50, -1], [5, 10, 50, "All"]],
                "columnDefs": [
                    { "orderable": false, "targets": 9 }
                ],
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/es_es.json"
                },
            });
        });
    </script>
@stop
