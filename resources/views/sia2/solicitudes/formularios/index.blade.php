@extends('adminlte::page')

@section('title', 'Solicitudes de formularios')

@section('content_header')
    <h1>Listado de solicitudes</h1>
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
            <thead>
                <tr>
                    <th scope="col">Solicitante</th>
                    <th scope="col">Estado</th>
                    <th scope="col">Fecha de solicitud</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($solicitudes as $solicitud)
                    <tr>
                        <td>{{ $solicitud->solicitante->USUARIO_NOMBRES }} {{$solicitud->solicitante->USUARIO_APELLIDOS}}</td>
                        <td>{{ $solicitud->SOLICITUD_ESTADO }}</td>
                        <td>{{ $solicitud->created_at }}</td>
                        <td>
                            <div class="d-flex justify-content-center">
                                <a href="{{ route('solicitudesformularios.show', $solicitud->SOLICITUD_ID) }}" class="btn btn-primary"><i class="fa-solid fa-eye"></i></a>
                                <a href="{{ route('solicitudesformularios.edit', $solicitud->SOLICITUD_ID) }}" class="btn btn-secondary ml-2"><i class="fa-solid fa-pencil"></i></a>
                                <form action="{{ route('solicitudesformularios.destroy', $solicitud->SOLICITUD_ID) }}" method="POST" class="d-inline">
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

@stop

@section('js')
    <script>
        $(document).ready(function () {
            // Inicialización de DataTables
            $('#solicitudes').DataTable({
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
