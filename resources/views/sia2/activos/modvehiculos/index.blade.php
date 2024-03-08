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

    {{-- Tabla de Vehículos --}}
    <div class="table-responsive">
        <a class="btn agregar" href="{{ route('vehiculos.create') }}"><i class="fa-solid fa-plus"></i> Agregar Vehículo</a>
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
    <link rel="stylesheet" href="/css/admin_custom.css">
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
