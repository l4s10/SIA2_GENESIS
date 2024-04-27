
@extends('adminlte::page')

@section('title', 'Solicitar Equipos')

@section('content_header')
    <h1>Crear Solicitud</h1>
    @role('ADMINISTRADOR')
    <div class="alert alert-info alert1" role="alert">
        <div>Bienvenido al módulo de <strong>solicitud de reservas de equipos</strong>. En este módulo, usted podrá pedir a través del carro de compras los distintos materiales catalogados. En caso de requerir otro material, favor contactar al Departamento de Administración.</div>
    </div>
    @else
    <div class="alert alert-info" role="alert">
        <div>Bienvenido al módulo de <strong>solicitud de reservas de equipos</strong>. En el presente módulo usted podrá reserva de equipos, según sea el caso el Departamento de Administración analizará los antecedentes, y podrá aceptar o rechazar la solicitud.</div>
    </div>
    @endrole
@stop

@section('content')
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

    {{-- Tabla de Equipos --}}
    <h3 class="centrar">Tipos de equipos disponibles</h3>
    <table class="table table-bordered" id="equipos">
        <thead class="tablacolor">
            <tr>
                <th>Tipo equipo</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tiposEquipos as $tipoEquipo)
                @php
                    // Suma el stock de todos los equipos de este tipo
                    $stockTotal = $tipoEquipo->equipos->sum('EQUIPO_STOCK');
                @endphp

                @if ($stockTotal > 0)
                    <tr>
                        <td>{{ $tipoEquipo->TIPO_EQUIPO_NOMBRE }}</td>
                        <td>
                            <button type="button" class="btn botoneditar" data-tipo-equipo-id="{{ $tipoEquipo->TIPO_EQUIPO_ID }}" data-form-action="{{ route('tiposequipos.addToCart', $tipoEquipo->TIPO_EQUIPO_ID) }}">
                                <i class="fa-solid fa-plus"></i> Agregar al Carrito
                            </button>
                        </td>
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>

    {{-- Carrito --}}
    <h3 class="centrar">Carrito</h3>
    <table class="table table-bordered" id="carrito">
        <thead class="tablacarrito">
            <tr>
                <th>Tipo equipo</th>
                <th>Cantidad</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($cartItems as $cartItem)
                <tr>
                    <td>{{ $cartItem->name }}</td>
                    <td>{{ $cartItem->qty }}</td>
                    <td>
                        <form action="{{ route('tiposequipos.removeItem', $cartItem->rowId) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="fa-solid fa-trash"></i> Eliminar
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>


    {{-- Modal para cantidad de equipos personalizada --}}
    <div class="modal fade" id="cantidadEquipoModal" tabindex="-1" data-bs-backdrop="static" aria-labelledby="cantidadEquipoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cantidadEquipoModalLabel">Agregar equipo</h5>
                </div>
                <form id="formAgregarEquipoCarrito" method="POST">
                    @csrf
                    <div class="modal-body">
                        <input type="number" name="cantidad" class="form-control" required min="1" value="1">
                        <input type="hidden" name="tipo_equipo_id" id="tipo_equipo_id">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Agregar al Carrito</button>
                    </div>
                </form>
            </div>
        </div>
    </div>



    {{-- Formulario de Solicitud --}}
    <form action="{{ route('solicitudes.equipos.store') }}" method="POST">
        @csrf

        {{-- Motivo de la Solicitud --}}
        <div class="form-group {{ $errors->has('SOLICITUD_MOTIVO') ? 'has-error' : '' }}">
            <label for="SOLICITUD_MOTIVO"><i class="fa-solid fa-pen-to-square"></i> Motivo de la Solicitud</label>
            <textarea class="form-control" id="SOLICITUD_MOTIVO" name="SOLICITUD_MOTIVO" rows="3" required>{{ old('SOLICITUD_MOTIVO') }}</textarea>
            @error('SOLICITUD_MOTIVO')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        {{-- Estado de la Solicitud (Sin contenedor de error ya que es un campo de solo lectura) --}}
        <div class="form-group">
            <label for="SOLICITUD_ESTADO"><i class="fa-solid fa-file-circle-check"></i> Estado de la Solicitud</label>
            <input type="text" class="form-control" id="SOLICITUD_ESTADO" name="SOLICITUD_ESTADO" value="🟠INGRESADO" readonly>
        </div>

        <div class="row">
            <div class="col-md-6">
                {{-- Fecha y Hora de Inicio Solicitada --}}
                <div class="form-group {{ $errors->has('SOLICITUD_FECHA_HORA_INICIO_SOLICITADA') ? 'has-error' : '' }}">
                    <label for="SOLICITUD_FECHA_HORA_INICIO_SOLICITADA"><i class="fa-solid fa-calendar-days"></i> Fecha y hora inicio solicitud</label>
                    <input type="datetime-local" class="form-control" id="SOLICITUD_FECHA_HORA_INICIO_SOLICITADA" name="SOLICITUD_FECHA_HORA_INICIO_SOLICITADA" value="{{ old('SOLICITUD_FECHA_HORA_INICIO_SOLICITADA') }}" required>
                    @error('SOLICITUD_FECHA_HORA_INICIO_SOLICITADA')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                {{-- Fecha y Hora de Término Solicitada --}}
                <div class="form-group {{ $errors->has('SOLICITUD_FECHA_HORA_TERMINO_SOLICITADA') ? 'has-error' : '' }}">
                    <label for="SOLICITUD_FECHA_HORA_TERMINO_SOLICITADA"><i class="fa-solid fa-calendar-xmark"></i> Fecha y hora término solicitud</label>
                    <input type="datetime-local" class="form-control" id="SOLICITUD_FECHA_HORA_TERMINO_SOLICITADA" name="SOLICITUD_FECHA_HORA_TERMINO_SOLICITADA" value="{{ old('SOLICITUD_FECHA_HORA_TERMINO_SOLICITADA') }}" required>
                    @error('SOLICITUD_FECHA_HORA_TERMINO_SOLICITADA')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>
        {{-- Boton de envio y volver --}}

        <a href="{{ route('solicitudes.equipos.index') }}" class="btn btn-secondary"><i class="fa-solid fa-hand-point-left"></i> Cancelar</a>
        <button type="submit" class="btn agregar"><i class="fa-solid fa-clipboard-check"></i> Crear Solicitud</button>
    </form>
@stop

@section('css')
    <style>/* Estilos personalizados si es necesario */
        .centrar{
            text-align: center;
        }
        .tablacolor {
            background-color: #723E72; /* Color de fondo personalizado */
            color: #fff; /* Color de texto personalizado */
        }
        .tablacarrito {
            background-color: #956E95;
            color: #fff;
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
    {{-- Llamar a componente configuracion fechas SOLICITADAS --}}
    <script src="{{ asset('js/Components/fechasSolicitadas.js') }}"></script>
    {{-- Script cooldown envio formulario (evita entradas repetidas) --}}
    <script src="{{ asset('js/Components/cooldownSendForm.js') }}"></script>
    {{-- Cooldown borrar del carrito (evita llamados multiples) --}}
    <script src="{{ asset('js/Components/cooldownEraseFromCart.js') }}"></script>

    {{-- Componentes dataTables --}}
    <script>
        $(document).ready(function () {
            $('#equipos').DataTable({
                "lengthMenu": [[5, 10, 50, -1], [5, 10, 50, "All"]],
                "columnDefs": [
                    { "orderable": false, "targets": 1 }
                ],
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/es_es.json"
                },
            });
            $('#carrito').DataTable({
                "lengthMenu": [[5, 10, 50, -1], [5, 10, 50, "All"]],
                "columnDefs": [
                    { "orderable": false, "targets": 2 }
                ],
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/es_es.json"
                },
            });
        });
    </script>

    <script>
        document.querySelectorAll('.botoneditar').forEach(button => {
            button.addEventListener('click', function(event) {
                event.preventDefault();
                const tipoEquipoId = this.getAttribute('data-tipo-equipo-id');
                document.getElementById('tipo_equipo_id').value = tipoEquipoId;
                const formAction = this.getAttribute('data-form-action');
                document.getElementById('formAgregarEquipoCarrito').action = formAction;
                new bootstrap.Modal(document.getElementById('cantidadEquipoModal')).show();
            });
        });
    </script>
@stop
