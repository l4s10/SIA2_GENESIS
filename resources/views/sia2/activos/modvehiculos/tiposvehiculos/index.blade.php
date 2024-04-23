@extends('adminlte::page')

@section('title', 'Tipos de Vehiculos')

@section('content_header')
    <h1>Listado de Tipos de Vehiculos</h1>
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
    <a class="btn agregar mb-3" href="{{ route('tiposvehiculos.create') }}"><i class="fa-solid fa-plus"></i> Agregar Tipo de Vehiculo</a>
    <a class="btn btn-secondary mb-3" href="{{ route('vehiculos.index')}}"><i class="fa-solid fa-eye"></i> Administrar Vehiculos</a>

    <div class="table-responsive">
        <table id="tiposVehiculos" class="table table-bordered mt-4">
            <thead class="tablacolor">
                <tr>
                    <th scope="col">Tipo de Vehiculo</th>
                    <th scope="col">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tiposVehiculos as $tipo)
                    <tr>
                        <td>{{ $tipo->TIPO_VEHICULO_NOMBRE }}</td>
                        <td>
                            <div class="d-flex justify-content-center">
                                @role('ADMINISTRADOR|SERVICIOS')
                                    <a href="{{ route('tiposvehiculos.edit', $tipo->TIPO_VEHICULO_ID) }}" class="btn botoneditar">
                                        <i class="fa-solid fa-pen-to-square"></i> Editar
                                    </a>
                                @endrole

                                @role('ADMINISTRADOR')
                                    <form action="{{ route('tiposvehiculos.destroy', $tipo->TIPO_VEHICULO_ID) }}" method="POST" class="ml-2">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">
                                            <i class="fa-solid fa-trash"></i> Borrar
                                        </button>
                                    </form>
                                @endrole
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
        .alert {
            opacity: 0.7; /* Ajusta la opacidad a tu gusto */
            background-color: #99CCFF;
            color: #000000;
        }
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
    {{-- Script cooldown envio formulario (evita entradas repetidas) --}}
    <script src="{{ asset('js/Components/cooldownSendForm.js') }}"></script>
    <!-- Para inicializar -->
    <script>
        $(document).ready(function () {
            $('#tiposVehiculos').DataTable({
                "lengthMenu": [[5, 10, 50, -1], [5, 10, 50, "All"]],
                "columnDefs": [
                    { "orderable": false, "targets": 1 }
                ],
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/es_es.json"
                },
            });
        });
    </script>
@stop
