@extends('adminlte::page')

@section('title', 'Auditorías de materiales')

@section('content_header')
    <h1>Auditorías de materiales</h1>
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

    <div class="table-responsive">
        <table id="auditorias" class="table table-bordered mt-4">
            <thead>
                <tr class="tablacolor">
                    <th scope="col">Titular</th>
                    <th scope="col">Objeto</th>
                    <th scope="col">Tipo de objeto</th>
                    <th scope="col">Tipo de movimiento</th>
                    <th scope="col">Cantidad previa</th>
                    <th scope="col">Cantidad a modificar</th>
                    <th scope="col">Cantidad resultante</th>
                    <th scope="col">Detalle</th>
                    <th scope="col">Fecha de movimiento</th>
                    <th scope="col">Acciones</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($auditorias as $auditoria)
                    <tr>
                        <td>{{ $auditoria->MOVIMIENTO_TITULAR }}</td>
                        <td>{{ $auditoria->MOVIMIENTO_OBJETO }}</td>
                        <td>{{ $auditoria->MOVIMIENTO_TIPO_OBJETO }}</td>
                        <td><span class="badge rounded-pill estado-{{ strtolower(str_replace(' ', '-', $auditoria->MOVIMIENTO_TIPO)) }}">
                        {{ $auditoria->MOVIMIENTO_TIPO }}
                        </span>
                        </td>
                        <td>{{ $auditoria->MOVIMIENTO_STOCK_PREVIO }}</td>
                        <td>{{ $auditoria->MOVIMIENTO_CANTIDAD_A_MODIFICAR }}</td>
                        <td>{{ $auditoria->MOVIMIENTO_STOCK_RESULTANTE }}</td>
                        <td>{{ $auditoria->MOVIMIENTO_DETALLE }}</td>
                        <td>{{date_format($auditoria->created_at, 'd-m-Y H:i')}}</td>
                        <td>
                            Exportar
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
        .estado-resta {
        color: #FFFFFF;
        background-color: #F70B0B;
        }

        .estado-ingreso {
        color: #ffffff;
        background-color: #0CB009;
        }
    </style>
@stop

@section('js')
    <script>
        $(document).ready(function () {
            // Inicialización de DataTables
            $('#auditorias').DataTable({
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
