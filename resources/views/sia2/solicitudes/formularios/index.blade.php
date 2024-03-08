@extends('adminlte::page')

@section('title', 'Solicitudes de formularios')

@section('content_header')
    <h1>Listado de solicitudes de formularios</h1>
    @role('ADMINISTRADOR')
    <div class="alert alert-info alert1" role="alert">
    <div><strong>Bienvenido Administrador:</strong> Acceso total al modulo.<div>
    </div>
    @endrole
    @role('SERVICIOS')
    <div class="alert alert-info" role="alert">
    <div><strong>Bienvenido Servicio:</strong> En este módulo usted podrá verificar el estado de sus solicitudes de formularios.<div>
    </div>
    @endrole
    @role('INFORMATICA')
    <div class="alert alert-info" role="alert">
    <div><strong>Bienvenido Informatica:</strong> En este módulo usted podrá verificar el estado de sus solicitudes de formularios.<div>
    </div>
    @endrole
    @role('JURIDICO')
    <div class="alert alert-info" role="alert">
    <div><strong>Bienvenido Juridico:</strong> En este módulo usted podrá verificar el estado de sus solicitudes de formularios.<div>
    </div>
    @endrole
    @role('FUNCIONARIO')
    <div class="alert alert-info" role="alert">
    <div><strong>Bienvenido Funcionario:</strong> En este módulo usted podrá verificar el estado de sus solicitudes de formularios.<div>
    </div>
    @endrole
    {{-- Logica de roles --}}
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

    {{-- Enlaces para exportables --}}
    {{-- Boton exportable PDF --}}
    {{-- Boton exportable excel --}}

    {{-- Tabla de solicitudes --}}
    <div class="table-responsive">
        <table id="solicitudes" class="table table-bordered mt-4">
            <thead class="tablacolor">
                <tr>
                    <th scope="col">Solicitante</th>
                    <th scope="col">Rut</th>
                    <th scope="col">Dependencia</th>
                    <th scope="col">Email</th>
                    <th scope="col">Estado</th>
                    <th scope="col">Fecha de Ingreso</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($solicitudes as $solicitud)
                <tr>
                        <td>{{ $solicitud->solicitante->USUARIO_NOMBRES }} {{ $solicitud->solicitante->USUARIO_APELLIDOS }}</td>
                        <td>{{ $solicitud->solicitante->USUARIO_RUT }}</td>
                        <td>{{ $solicitud->solicitante->ubicacion ? $solicitud->solicitante->ubicacion->UBICACION_NOMBRE : $solicitud->solicitante->departamento->DEPARTAMENTO_NOMBRE}}</td>
                        <td>{{ $solicitud->solicitante->email }}</td>
                        <td><span class="badge rounded-pill estado-{{ strtolower(str_replace(' ', '-', $solicitud->SOLICITUD_ESTADO)) }}">
                        {{ $solicitud->SOLICITUD_ESTADO }}
                        </span>
                        </td>
                        <td>{{ $solicitud->mostrarFecha($solicitud->created_at) }}</td>
                        <td>
                            <div class="d-flex justify-content-center">
                                <a href="{{ route('solicitudes.formularios.show', $solicitud->SOLICITUD_ID) }}" class="btn btn-primary"><i class="fa-solid fa-eye"></i></a>
                                <a href="{{ route('solicitudes.formularios.edit', $solicitud->SOLICITUD_ID) }}" class="btn botoneditar ml-2"><i class="fa-solid fa-pencil"></i></a>
                                <form action="{{ route('solicitudes.formularios.destroy', $solicitud->SOLICITUD_ID) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger ml-2"><i class="fa-solid fa-trash"></i></button>
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

        /*Colores de los estados*/
        .estado-ingresado {
        color: #000000;
        background-color: #FFA600;
        }

        .estado-en-revision {
        color: #000000;
        background-color: #F7F70B;
        }

        .estado-aprobado {
        color: #ffffff;
        background-color: #0CB009;
        }

        .estado-rechazado {
        color: #FFFFFF;
        background-color: #F70B0B;
        }

        .estado-terminado {
        color: #000000;
        background-color: #d9d9d9;
        }

        .estado-autorizado {
        color: #ffffff;
        background-color: #0CB009;
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
            $('#solicitudes').DataTable({
                "lengthMenu": [[5, 10, 50, -1], [5, 10, 50, "All"]],
                "columnDefs": [
                    { "orderable": false, "targets": 6 }
                ],
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/es_es.json"
                },
                "order": [[ 2, "desc" ]],
            });
        });
    </script>
@stop
