
@extends('adminlte::page')

@section('title', 'Solicitar reparación o mantención')

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
        <form action="{{ route('solicitudes.reparaciones.store') }}" method="POST">
            @csrf

            {{-- Tipo de Solicitud --}}
            <div class="form-group {{ $errors->has('SOLICITUD_REPARACION_TIPO') ? 'has-error' : '' }}">
                <label for="SOLICITUD_REPARACION_TIPO"><i class="fa-solid fa-file-pen"></i> Tipo de solicitud</label>
                <select name="SOLICITUD_REPARACION_TIPO" id="SOLICITUD_REPARACION_TIPO" class="form-control" required>
                    <option value="">Seleccione un tipo</option>
                    <option value="REPARACION">Reparación</option>
                    <option value="MANTENCION">Mantención</option>
                </select>
                @error('SOLICITUD_REPARACION_TIPO')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            {{-- Categoría de Solicitud --}}
            <div class="form-group {{ $errors->has('CATEGORIA_REPARACION_ID') ? 'has-error' : '' }}">
                <label for="CATEGORIA_REPARACION_ID"><i class="fa-solid fa-car-on"></i> Categoría de solicitud</label>
                <select name="CATEGORIA_REPARACION_ID" id="CATEGORIA_REPARACION_ID" class="form-control" required>
                    <option value="">Seleccione una categoría</option>
                    @foreach ($categorias as $categoria)
                        <option value="{{ $categoria->CATEGORIA_REPARACION_ID }}">{{ $categoria->CATEGORIA_REPARACION_NOMBRE }}</option>
                    @endforeach
                </select>
                @error('CATEGORIA_REPARACION_ID')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            {{-- Vehículo con Problemas --}}
            <div class="form-group {{ $errors->has('VEHICULO_ID') ? 'has-error' : '' }}">
                <label for="VEHICULO_ID"><i class="fa-solid fa-car-burst"></i> Vehículo con problemas</label>
                <select name="VEHICULO_ID" id="VEHICULO_ID" class="form-control">
                    <option value="">Seleccione un vehículo</option>
                    @foreach ($vehiculos as $vehiculo)
                        <option value="{{ $vehiculo->VEHICULO_ID }}">{{ $vehiculo->VEHICULO_PATENTE }}</option>
                    @endforeach
                </select>
                @error('VEHICULO_ID')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            {{-- Motivo de la Solicitud --}}
            <div class="form-group {{ $errors->has('SOLICITUD_REPARACION_MOTIVO') ? 'has-error' : '' }}">
                <label for="SOLICITUD_REPARACION_MOTIVO"><i class="fa-solid fa-pen-to-square"></i> Motivo de la Solicitud</label>
                <input type="text" class="form-control" id="SOLICITUD_REPARACION_MOTIVO" name="SOLICITUD_REPARACION_MOTIVO" required>
                @error('SOLICITUD_REPARACION_MOTIVO')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            {{-- Estado de la Solicitud --}}
            <div class="form-group">
                <label for="SOLICITUD_ESTADO"><i class="fa-solid fa-file-circle-check"></i> Estado de la Solicitud</label>
                <input type="text" class="form-control" id="SOLICITUD_ESTADO" name="SOLICITUD_ESTADO" value="🟠INGRESADO" readonly>
            </div>

            {{-- Botón de envío --}}
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
