
@extends('adminlte::page')

@section('title', 'Solicitar Formularios')

@section('content_header')
    <h1>Crear Solicitud</h1>
    @role('ADMINISTRADOR')
    <div class="alert alert-info alert1" role="alert">
    <div><strong>Bienvenido Administrador:</strong> Acceso total al modulo.<div>
    </div>
    @endrole
    @role('SERVICIOS')
    <div class="alert alert-info" role="alert">
    <div><strong>Bienvenido Servicio:</strong> En el presente módulo usted podrá solicitar formularios.<div>
    </div>
    @endrole
    @role('INFORMATICA')
    <div class="alert alert-info" role="alert">
    <div><strong>Bienvenido Informatica:</strong> En el presente módulo usted podrá solicitar formularios.<div>
    </div>
    @endrole
    @role('JURIDICO')
    <div class="alert alert-info" role="alert">
    <div><strong>Bienvenido Juridico:</strong> En el presente módulo usted podrá solicitar formularios.<div>
    </div>
    @endrole
    @role('FUNCIONARIO')
    <div class="alert alert-info" role="alert">
    <div><strong>Bienvenido Funcionario:</strong> En el presente módulo usted podrá solicitar formularios, según sea el caso el Departamento de Administración analizará los antecedentes, y podrá aceptar o rechazar la solicitud.<div>
    </div>
    @endrole
@stop

@section('content')
<div class="verde">
    <div><i class="fas fa-seedling"></i>Cuidemos el medio ambiente <i class="fas fa-seedling"></i>. Recuerde que se debe priorizar los formularios con uso cero papel.</div>
</div>
    <br>
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

    {{-- Tabla de Formularios --}}
    <h3 class="centrar">Formularios Disponibles</h3>
    <table class="table table-bordered" id="formularios">
        <thead class="tablacolor">
            <tr>
                <th>Tipo formulario</th>
                <th>Nombre formulario</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($formularios as $formulario)
                <tr>
                    <td>{{ $formulario->FORMULARIO_TIPO}}</td>
                    <td>{{ $formulario->FORMULARIO_NOMBRE }}</td>
                    <td>
                        <button type="button" class="btn botoneditar" data-formulario-id="{{ $formulario->FORMULARIO_ID }}" data-form-action="{{ route('formularios.addToCart', $formulario->FORMULARIO_ID) }}">
                            <i class="fa-solid fa-plus"></i> Agregar al Carrito
                        </button>
                        {{-- <form action="{{ route('formularios.addToCart', $formulario->FORMULARIO_ID) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn botoneditar">
                                <i class="fa-solid fa-plus"></i> Agregar al Carrito
                            </button>
                        </form> --}}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Carrito --}}
    <h3 class="centrar">Carrito</h3>
    <table class="table table-bordered" id="carrito">
        <thead class="tablacarrito">
            <tr>
                <th>Formulario</th>
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
                        <form action="{{ route('formularios.removeItem', $cartItem->rowId) }}" method="POST">
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

    {{-- Modal para cantidad personalizada --}}
    <div class="modal fade" id="cantidadModal" tabindex="-1" data-bs-backdrop="static" aria-labelledby="cantidadModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cantidadModalLabel">Agregar formulario</h5>
                </div>
                <form id="formAgregarCarrito" method="POST">
                    @csrf
                    <div class="modal-body">
                        <input type="number" name="cantidad" class="form-control" required min="1" value="1">
                        <input type="hidden" name="formulario_id" id="formulario_id">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dissmiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Agregar al carrito</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    {{-- Formulario de Solicitud --}}
    <form action="{{ route('solicitudes.formularios.store') }}" method="POST">
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
            <div class="col-md-12">
                {{-- Fecha y Hora de Inicio Solicitada --}}
                <div class="form-group {{ $errors->has('SOLICITUD_FECHA_HORA_INICIO_SOLICITADA') ? 'has-error' : '' }}">
                    <label for="SOLICITUD_FECHA_HORA_INICIO_SOLICITADA"><i class="fa-solid fa-calendar-days"></i> Fecha y Hora de Inicio Solicitada</label>
                    <input type="datetime-local" class="form-control" id="SOLICITUD_FECHA_HORA_INICIO_SOLICITADA" name="SOLICITUD_FECHA_HORA_INICIO_SOLICITADA" required>
                    @error('SOLICITUD_FECHA_HORA_INICIO_SOLICITADA')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            {{-- Fecha y Hora de Término Solicitada (N/A) --}}
            {{-- <div class="col-md-6">
                <div class="form-group {{ $errors->has('SOLICITUD_FECHA_HORA_TERMINO_SOLICITADA') ? 'has-error' : '' }}">
                    <label for="SOLICITUD_FECHA_HORA_TERMINO_SOLICITADA"><i class="fa-solid fa-calendar-xmark"></i> Fecha y Hora de Término Solicitada</label>
                    <input type="datetime-local" class="form-control" id="SOLICITUD_FECHA_HORA_TERMINO_SOLICITADA" name="SOLICITUD_FECHA_HORA_TERMINO_SOLICITADA" required>
                    @error('SOLICITUD_FECHA_HORA_TERMINO_SOLICITADA')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div> --}}
        </div>
        {{-- Botones de envio y volver --}}
        <a href="{{ route('solicitudes.formularios.index') }}" class="btn btn-secondary"><i class="fa-solid fa-hand-point-left"></i> Cancelar</a>
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

        .verde {
            display: flex;
            justify-content: center;
            height: 6vh; /* Ajusta la altura como desees */
            align-items: center;
            padding: 10px;
            background-color: #40C47C;
            color: #FFFFFF;
            border-radius: 10px;
            font-size: 16px; /* Tamaño de fuente */
            text-align: center; /* Alineación del texto */
            overflow: hidden;
        }

        .verde i {
            margin: 0 5px; /* Espacio entre los íconos y el texto */
            margin-right: 10px;
        }
    </style>
@stop

@section('js')
    {{-- Llamar a componente configuracion fechas SOLICITADAS --}}
    {{-- <script src="{{ asset('js/Components/fechasSolicitadas.js') }}"></script> --}}
    {{-- Inicializa flatpickers --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Obtener la fecha y hora actual
            let today = new Date();

            // Inicializar Flatpickr para el campo de fecha y hora de inicio autorizada
            flatpickr("#SOLICITUD_FECHA_HORA_INICIO_SOLICITADA", {
                enableTime: true,
                dateFormat: "Y-m-d H:i:s",
                altFormat: "d-m-Y H:i",
                altInput: true,
                locale: "es",
                // Establecer el valor mínimo solo si hay un valor capturado por la base de datos
                minDate: today,
                maxDate: new Date(today.getFullYear(), 11, 31), // Permitir fechas hasta fin de año
                minTime: "08:00", // Hora mínima permitida
                maxTime: "19:00", // Hora máxima permitida
                placeholder: 'Seleccione la fecha y hora de inicio' // Añadido placeholder
            });

            // Inicializar Flatpickr para el campo de fecha y hora de término autorizada
            // flatpickr("#SOLICITUD_FECHA_HORA_TERMINO_SOLICITADA", {
            //     enableTime: true,
            //     dateFormat: "Y-m-d H:i:s",
            //     altFormat: "d-m-Y H:i",
            //     altInput: true,
            //     locale: "es",
            //     minDate: today, // Establecer la fecha mínima como la fecha actual
            //     maxDate: new Date(today.getFullYear() + 1, 1, 28), // Permitir fechas hasta febrero del siguiente año
            //     minTime: "08:00", // Hora mínima permitida
            //     maxTime: "19:00", // Hora máxima permitida
            //     placeholder: 'Seleccione la fecha y hora de término' // Añadido placeholder
            // });
        });
    </script>
    {{-- Script cooldown envio formulario (evita entradas repetidas) --}}
    <script src="{{ asset('js/Components/cooldownSendForm.js') }}"></script>
    {{-- Cooldown borrar del carrito (evita llamados multiples) --}}
    <script src="{{ asset('js/Components/cooldownEraseFromCart.js') }}"></script>

    {{-- Componentes dataTables --}}
    <script>
        $(document).ready(function () {
            $('#formularios').DataTable({
                "lengthMenu": [[5, 10, 50, -1], [5, 10, 50, "All"]],
                "columnDefs": [
                    { "orderable": false, "targets": 2 }
                ],
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/es_es.json"
                },
            });
            $('#carrito').DataTable({
                "lengthMenu": [[5, 10, 50, -1], [5, 10, 50, "All"]],
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
                const formularioId = this.getAttribute('data-formulario-id');
                document.getElementById('formulario_id').value = formularioId;
                const formAction = this.getAttribute('data-form-action');
                document.getElementById('formAgregarCarrito').action = formAction;
                new bootstrap.Modal(document.getElementById('cantidadModal')).show();
            });
        });
    </script>
@stop
