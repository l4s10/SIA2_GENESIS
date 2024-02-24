@extends('adminlte::page')

@section('title', 'Solicitudes de bodegas')

@section('content_header')
    <h1>Listado de solicitudes de bodegas</h1>
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
                @foreach ($solicitudes as $solicitud)
                    <tr>
                        <td>
                            <div class="d-flex justify-content-center">
                                {{ $solicitud->solicitante->USUARIO_NOMBRES }} {{ $solicitud->solicitante->USUARIO_APELLIDOS }}
                            </div>
                        </td>
                        <td>
                            <div class="d-flex justify-content-center">
                                {{ $solicitud->solicitante->USUARIO_RUT }}
                            </div>
                        </td>                        
                        <td>
                            <div class="d-flex justify-content-center">
                                {{ $solicitud->solicitante->ubicacion ? $solicitud->solicitante->ubicacion->UBICACION_NOMBRE : $solicitud->solicitante->departamento->DEPARTAMENTO_NOMBRE}}
                            </div>
                        </td>
                        <td> 
                            <div class="d-flex justify-content-center">
                                {{ $solicitud->solicitante->email}}
                            </div>
                        </td>
                        <td>
                            <div class="d-flex justify-content-center">
                                @if ($solicitud->SOLICITUD_ESTADO == 'INGRESADO')
                                    <span style="color: #e6500a;">🟠 <span style="color: black; font-weight: bold;">INGRESADO</span></span>
                                @elseif ($solicitud->SOLICITUD_ESTADO == 'EN REVISIÓN')
                                    <span style="color: #0000ff;">🔵 <span style="color: black; font-weight: bold;">EN REVISIÓN</span></span>
                                @elseif ($solicitud->SOLICITUD_ESTADO == 'POR APROBAR')
                                    <span style="color: #ffff00;">🟡 <span style="color: black; font-weight: bold;">POR APROBAR</span></span>
                                @elseif ($solicitud->SOLICITUD_ESTADO == 'POR AUTORIZAR')
                                    <span style="color: #00ff00;">🟢 <span style="color: black; font-weight: bold;">POR AUTORIZAR</span></span>
                                @elseif ($solicitud->SOLICITUD_ESTADO == 'POR RENDIR')
                                    <span style="color: #ffffff;">⚪ <span style="color: black; font-weight: bold;">POR RENDIR</span></span>
                                @elseif ($solicitud->SOLICITUD_ESTADO == 'RECHAZADO')
                                    <span style="color: #ff0000;">🔴 <span style="color: black; font-weight: bold;">RECHAZADO</span></span>
                                @elseif ($solicitud->SOLICITUD_ESTADO == 'TERMINADO')
                                    <span style="color: #000000;">⚫ <span style="color: black; font-weight: bold;">TERMINADO</span></span>
                                @endif
                            </div>
                        </td>
                        <td>
                            <div class="d-flex justify-content-center">
                                {{ $solicitud->formatted_created_at }}
                            </div>
                        </td>
                        <td>
                            <div class="d-flex justify-content-center">
                                <a href="{{ route('solicitudes.bodegas.show', $solicitud->SOLICITUD_ID) }}" class="btn btn-primary"><i class="fa-solid fa-eye"></i></a>
                                <a href="{{ route('solicitudes.bodegas.edit', $solicitud->SOLICITUD_ID) }}" class="btn btn-secondary ml-2"><i class="fa-solid fa-pencil"></i></a>
                                <form action="{{ route('solicitudes.bodegas.destroy', $solicitud->SOLICITUD_ID) }}" method="POST">
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
    </style>
    <style>

        .estado-INGRESADO {
            background-color: #f58220;
        }
        
        .estado-EN_REVISIÓN {
            background-color: #ffff00;
        }
        
        .estado-POR_APROBAR {
            background-color: #00ff00;
        }
        
        .estado-POR_AUTORIZAR {
            background-color: #0000ff;
        }
        
        .estado-POR_RENDIR {
            background-color: #800080;
        }
        
        .estado-TERMINADO {
            background-color: #ff0000;
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
            });
        });
    </script>
@stop
