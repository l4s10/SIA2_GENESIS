
@extends('adminlte::page')

@section('title', 'Solicitar Bodega')

@section('content_header')
    <h1>Crear Solicitud</h1>
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
            <input type="text" class="form-control" id="SOLICITUD_MOTIVO" name="SOLICITUD_MOTIVO" required>
            @error('SOLICITUD_MOTIVO')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        {{-- Estado de la Solicitud --}}
        <div class="form-group">
            <label for="SOLICITUD_ESTADO"><i class="fa-solid fa-file-circle-check"></i> Estado de la Solicitud</label>
            <input type="text" class="form-control" id="SOLICITUD_ESTADO" name="SOLICITUD_ESTADO" value="🟠INGRESADO" readonly>
        </div>

        {{-- Fecha inicio solicitada --}}
        <div class="form-group {{ $errors->has('SOLICITUD_FECHA_HORA_INICIO_SOLICITADA') ? 'has-error' : '' }}">
            <label for="SOLICITUD_FECHA_HORA_INICIO_SOLICITADA"><i class="fa-solid fa-calendar-days"></i> Fecha y Hora de Inicio Solicitada</label>
            <input type="datetime-local" class="form-control" id="SOLICITUD_FECHA_HORA_INICIO_SOLICITADA" name="SOLICITUD_FECHA_HORA_INICIO_SOLICITADA" required>
            @error('SOLICITUD_FECHA_HORA_INICIO_SOLICITADA')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        {{-- Fecha termino solicitud --}}
        <div class="form-group {{ $errors->has('SOLICITUD_FECHA_HORA_TERMINO_SOLICITADA') ? 'has-error' : '' }}">
            <label for="SOLICITUD_FECHA_HORA_TERMINO_SOLICITADA"><i class="fa-solid fa-calendar-xmark"></i> Fecha y Hora de Término Solicitada</label>
            <input type="datetime-local" class="form-control" id="SOLICITUD_FECHA_HORA_TERMINO_SOLICITADA" name="SOLICITUD_FECHA_HORA_TERMINO_SOLICITADA" required>
            @error('SOLICITUD_FECHA_HORA_TERMINO_SOLICITADA')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <button type="submit" class="btn agregar"><i class="fa-solid fa-plus"></i> Crear Solicitud</button>
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
@stop

@section('js')

@stop
