@extends('adminlte::page')

@section('title', 'Solicitudes de vehículos')

@section('content_header')
    <h1>Listado de Solicitudes de Vehículos</h1>
    @role('ADMINISTRADOR')
    <div class="alert alert-info alert1" role="alert">
    <div><strong>Bienvenido Administrador:</strong> Acceso total al modulo.<div>
    </div>
    @endrole
    @role('REQUIRENTE')
    <div class="alert alert-info alert1" role="alert">
    <div><strong>Bienvenido Requirente:</strong> En este módulo usted podrá administrar, modificar, asignar conductor y enviar a autorizar las solicitudes de vehículos.<div>
    </div>
    @endrole
    @role('SERVICIOS')
    <div class="alert alert-info" role="alert">
    <div><strong>Bienvenido Servicio:</strong> En este módulo usted podrá administrar, modificar, asignar conductor y enviar a autorizar las solicitudes de vehículos.<div>
    </div>
    @endrole
    @role('INFORMATICA')
    <div class="alert alert-info" role="alert">
    <div><strong>Bienvenido Informatica:</strong> En este módulo usted podrá verificar el estado de sus solicitudes y obtener la hoja de salida de vehículos totalmente autorizada y tramitada.<div>
    </div>
    @endrole
    @role('JURIDICO')
    <div class="alert alert-info" role="alert">
    <div><strong>Bienvenido Juridico:</strong> En este módulo usted podrá verificar el estado de sus solicitudes y obtener la hoja de salida de vehículos totalmente autorizada y tramitada.<div>
    </div>
    @endrole
    @role('FUNCIONARIO')
    <div class="alert alert-info" role="alert">
    <div><strong>Bienvenido Funcionario:</strong> En este módulo usted podrá verificar el estado de sus solicitudes y obtener la hoja de salida de vehículos totalmente autorizada y tramitada.<div>
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
                    <th>Exportables</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($solicitudes as $solicitud)
                    <tr>
                        <td>
                            <div class="d-flex justify-content-center">
                                {{ $solicitud->user->USUARIO_NOMBRES }} {{ $solicitud->user->USUARIO_APELLIDOS }}
                            </div>
                        </td>
                        <td>
                            <div class="d-flex justify-content-center">
                                {{ $solicitud->user->USUARIO_RUT }}
                            </div>
                        </td>
                        <td>
                            <div class="d-flex justify-content-center">
                                {{ $solicitud->user->ubicacion ? $solicitud->user->ubicacion->UBICACION_NOMBRE : $solicitud->user->departamento->DEPARTAMENTO_NOMBRE}}
                            </div>
                        </td>
                        <td>
                            <div class="d-flex justify-content-center">
                                {{ $solicitud->user->email}}
                            </div>
                        </td>
                        <td>
                            <span class="badge rounded-pill estado-{{ preg_replace('/\s+/u', '-', mb_strtolower($solicitud->SOLICITUD_VEHICULO_ESTADO)) }}">
                                {{ $solicitud->SOLICITUD_VEHICULO_ESTADO }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex justify-content-center">
                                {{ $solicitud->formatted_created_at }}
                            </div>
                        </td>
                        <td>
                            <div class="d-flex justify-content-center">
                                {{-- Boton de ver detalles --}}
                                <a href="{{ route('solicitudesvehiculos.timeline', $solicitud->SOLICITUD_VEHICULO_ID) }}" class="btn btn-primary"><i class="fa-solid fa-eye"></i></a>

                                @if ($solicitud->SOLICITUD_VEHICULO_ESTADO == 'INGRESADO' || $solicitud->SOLICITUD_VEHICULO_ESTADO == 'EN REVISIÓN')

                                    {{-- Boton de editar --}}
                                    @role('ADMINISTRADOR|SERVICIOS')
                                        <a href="{{ route('solicitudesvehiculos.edit', $solicitud->SOLICITUD_VEHICULO_ID) }}" class="btn btn-secondary ml-2"><i class="fa-solid fa-pencil"></i></a>
                                    @endrole

                                    {{-- Boton de eliminar --}}
                                    @role('ADMINISTRADOR')
                                        <form action="{{ route('solicitudesvehiculos.destroy', $solicitud->SOLICITUD_VEHICULO_ID) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger ml-2"><i class="fa-solid fa-trash"></i></button>
                                        </form>
                                    @endrole

                                @else

                                    {{-- Boton de eliminar --}}
                                    @role('ADMINISTRADOR')
                                    <form action="{{ route('solicitudesvehiculos.destroy', $solicitud->SOLICITUD_VEHICULO_ID) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger ml-2"><i class="fa-solid fa-trash"></i></button>
                                    </form>
                                    @endrole
                                @endif

                            </div>
                        </td>
                        <td>
                            <div class="d-flex justify-content-center">
                                <a href="{{ route('descargar.plantilla', $solicitud->SOLICITUD_VEHICULO_ID) }}" class="btn btn-success ml-2" id="descargar-pdf-btn"><i class="fa-solid fa-file-pdf"></i></a>
                            </div>
                        </td>

                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>
@stop


@section('css')

<style>
    /* Estilos personalizados si es necesario */
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

    .estado-ingresado {
        color: #000000;
        background-color: #FFA600;
        }

        .estado-en-revisión {
        color: #ffffff;
        background-color: #0064a0;
        }

        .estado-por-aprobar {
        color: #000000;
        background-color: #F7F70B;
        }

        .estado-por-autorizar {
        color: #FFFFFF;
        background-color: #0CB009;
        }
        .estado-por-rendir {
        color: #000000;
        background-color: #FFFFFF;
        }
        .estado-rechazado {
        color: #FFFFFF;
        background-color: #F70B0B;
        }
        .estado-terminado {
        color: #000000;
        background-color: #d9d9d9;
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
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.13.2/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.2/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function () {
            // Inicialización de DataTables
            $('#solicitudes').DataTable({
                "lengthMenu": [[5, 10, 50, -1], [5, 10, 50, "Todos"]],
                "columnDefs": [
                    { "orderable": false, "targets": 7 }
                ],
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.11.3/i18n/es_es.json"
                },
            });

            $('.btn-success').on('click', function(event) {
                event.preventDefault(); // Prevenir el comportamiento predeterminado del enlace

                var pdfUrl = $(this).attr('href'); // Obtener la URL del PDF

                // Iniciar la descarga del PDF en paralelo con el primer mensaje
                var downloadPromise = new Promise((resolve, reject) => {
                    setTimeout(() => {
                        window.location.href = pdfUrl; // Redirigir al enlace del botón
                        resolve();
                    }, 0); // Redirigir de forma inmediata
                });

                // Mostrar el segundo mensaje cuando la descarga se haya iniciado
                Promise.all([downloadPromise]).then(() => {
                    // Mostrar el primer mensaje emergente con SweetAlert2
                    Swal.fire({
                        title: 'Producción PDF',
                        text: 'La producción del PDF está en curso. Por favor, espera unos momentos.',
                        icon: 'info',
                        showConfirmButton: false, // No mostrar botón de confirmación
                        timer: 6000 // Tiempo en milisegundos antes de que se cierre automáticamente
                    }).then(() => {
                        // Mostrar el segundo mensaje emergente con SweetAlert2
                        Swal.fire({
                            title: 'Descarga PDF',
                            text: 'La descarga del PDF comenzará en breve. Por favor, espera unos momentos.',
                            icon: 'info',
                            showConfirmButton: false, // No mostrar botón de confirmación
                            timer: 6000 // Tiempo en milisegundos antes de que se cierre automáticamente
                        });
                    });
                });
            });
        });
    </script>
@stop

