
@extends('adminlte::page')

@section('title', 'Solicitar Bodega')

@section('content_header')
    <h1>Crear Solicitud</h1>
    @role('ADMINISTRADOR')
    <div class="alert alert-info alert1" role="alert">
        <div>Bienvenido al módulo de <strong>solicitar visita a bodega</strong>. En el presente módulo usted podrá solicitar visitas a las distintas bodegas, según sea el caso el Departamento de Administración analizará la disponibilidad de auxiliares y coordinará según sea el caso.</div>
    </div>
    @else
    <div class="alert alert-info" role="alert">
        <div>Bienvenido al módulo de <strong>solicitar visita a bodega</strong>. En el presente módulo usted podrá solicitar visitas a las distintas bodegas, según sea el caso el Departamento de Administración analizará la disponibilidad de auxiliares y coordinará según sea el caso.</div>
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

    {{-- Formulario de Solicitud --}}
    <form action="{{ route('solicitudes.bodegas.store') }}" method="POST">
        @csrf

        {{-- Bodega a pedir --}}
        <div class="form-group {{ $errors->has('BODEGA_ID') ? 'has-error' : '' }}">
            <label for="BODEGA_ID"><i class="fa-solid fa-door-open"></i> Bodega</label>
            <select name="BODEGA_ID" id="BODEGA_ID" class="form-control" required>
                <option value="">Seleccione una bodega</option>
                @foreach($bodegas as $bodega)
                    <option value="{{ $bodega->BODEGA_ID }}">{{ $bodega->BODEGA_NOMBRE }}</option>
                @endforeach
            </select>
            @error('BODEGA_ID')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        {{-- Motivo de la solicitud --}}
        <div class="form-group {{ $errors->has('SOLICITUD_MOTIVO') ? 'has-error' : '' }}">
            <label for="SOLICITUD_MOTIVO"><i class="fa-solid fa-pen-to-square"></i> Motivo de la Solicitud</label>
            <textarea class="form-control" id="SOLICITUD_MOTIVO" name="SOLICITUD_MOTIVO" required></textarea>
            @error('SOLICITUD_MOTIVO')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        {{-- Estado de la Solicitud --}}
        <div class="form-group">
            <label for="SOLICITUD_ESTADO"><i class="fa-solid fa-file-circle-check"></i> Estado de la Solicitud</label>
            <input type="text" class="form-control" id="SOLICITUD_ESTADO" name="SOLICITUD_ESTADO" value="🟠INGRESADO" readonly>
        </div>

        {{-- row para fechas --}}
        <div class="row">
            <div class="col-md-6">
                {{-- Fecha inicio solicitada --}}
                <div class="form-group {{ $errors->has('SOLICITUD_FECHA_HORA_INICIO_SOLICITADA') ? 'has-error' : '' }}">
                    <label for="SOLICITUD_FECHA_HORA_INICIO_SOLICITADA"><i class="fa-solid fa-calendar-days"></i> Fecha y Hora de Inicio Solicitada</label>
                    <input type="datetime-local" class="form-control" id="SOLICITUD_FECHA_HORA_INICIO_SOLICITADA" name="SOLICITUD_FECHA_HORA_INICIO_SOLICITADA" required>
                    @error('SOLICITUD_FECHA_HORA_INICIO_SOLICITADA')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="col-md-6">
                {{-- Fecha termino solicitud --}}
                <div class="form-group {{ $errors->has('SOLICITUD_FECHA_HORA_TERMINO_SOLICITADA') ? 'has-error' : '' }}">
                    <label for="SOLICITUD_FECHA_HORA_TERMINO_SOLICITADA"><i class="fa-solid fa-calendar-xmark"></i> Fecha y Hora de Término Solicitada</label>
                    <input type="datetime-local" class="form-control" id="SOLICITUD_FECHA_HORA_TERMINO_SOLICITADA" name="SOLICITUD_FECHA_HORA_TERMINO_SOLICITADA" required>
                    @error('SOLICITUD_FECHA_HORA_TERMINO_SOLICITADA')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>
        {{-- Boton para enviar y volver --}}
        <a href="{{ route('solicitudes.bodegas.index') }}" class="btn btn-secondary"><i class="fa-solid fa-hand-point-left"></i> Cancelar</a>
        <button type="submit" class="btn agregar"><i class="fa-solid fa-clipboard-check"></i> Crear Solicitud</button>
    </form>
@stop

@section('css')
    <style>
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
@stop
