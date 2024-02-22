@extends('adminlte::page')

@section('title', 'Solicitudes de vehículos por aprobar')

@section('content_header')
    <h1>Listado de solicitudes de vehículos por rendir</h1>
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
                    <th scope="col">Estado</th>
                    <th scope="col">Fecha de solicitud</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($solicitudes as $solicitud)
                    <tr>
                        <td>{{ $solicitud->user->USUARIO_NOMBRES }} {{ $solicitud->user->USUARIO_APELLIDOS }}</td>
                        <td>{{ $solicitud->SOLICITUD_VEHICULO_ESTADO }}</td>
                        <td>{{ $solicitud->created_at }}</td>
                        <td>
                            <div class="d-flex justify-content-center">
                                {{--<a href="{{ route('solicitudesvehiculos.show', $solicitud->SOLICITUD_VEHICULO_ID) }}" class="btn btn-primary"><i class="fa-solid fa-eye"></i></a>--}}
                                <a href="{{ route('solicitudesvehiculos.edit', ['solicitudesvehiculo' => $solicitud->SOLICITUD_VEHICULO_ID, 'source' => 'indexPorRendir']) }}" class="btn btn-secondary ml-2"><i class="fa-solid fa-pencil"></i></a>

                                {{--<form action="{{ route('solicitud_vehiculos.destroy', $solicitud->SOLICITUD_VEHICULO_ID) }}" method="POST">--}}
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger ml-2"><i class="fa-solid fa-trash"></i></button>
                                    <a href="{{ route('descargar.plantilla', $solicitud->SOLICITUD_VEHICULO_ID) }}" class="btn btn-success ml-2" id="descargar-pdf-btn"><i class="fa-solid fa-file-pdf"></i></a>

                                    
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
                    { "orderable": false, "targets": 3 }
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

