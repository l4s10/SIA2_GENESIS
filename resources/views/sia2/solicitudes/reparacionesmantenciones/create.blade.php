
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

    <div class="container">
        {{-- Formulario de Solicitud --}}
        <form action="{{ route('solicitudes.reparaciones.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="SOLICITUD_REPARACION_TIPO"><i class="fa-solid fa-door-open"></i> Tipo de solicitud</label>
                <select name="SOLICITUD_REPARACION_TIPO" id="SOLICITUD_REPARACION_TIPO" class="form-control" required>
                    <option value="">Seleccione un tipo</option>
                    <option value="1">Reparación</option>
                    <option value="2">Mantención</option>
                </select>
            </div>

            {{-- Cargamos las categorias --}}
            <div class="form-group">
                <label for="SOLICITUD_REPARACION_CATEGORIA"><i class="fa-solid fa-door-open"></i> Categoría de solicitud</label>
                <select name="SOLICITUD_REPARACION_CATEGORIA" id="SOLICITUD_REPARACION_CATEGORIA" class="form-control" required>
                    <option value="">Seleccione una categoría</option>
                    @foreach ($categorias as $categoria)
                        <option value="{{ $categoria->CATEGORIA_REPARACION_ID }}">{{ $categoria->CATEGORIA_REPARACION_NOMBRE }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="form-group">
                <label for="SOLICITUD_MOTIVO"><i class="fa-solid fa-pen-to-square"></i>Motivo de la Solicitud</label>
                <input type="text" class="form-control" id="SOLICITUD_MOTIVO" name="SOLICITUD_MOTIVO" required>
            </div>

            <div class="form-group">
                <label for="SOLICITUD_ESTADO"><i class="fa-solid fa-file-circle-check"></i>Estado de la Solicitud</label>
                <input type="text" class="form-control" id="SOLICITUD_ESTADO" name="SOLICITUD_ESTADO" value="🟠INGRESADO" readonly>
            </div>

            <button type="submit" class="btn agregar"><i class="fa-solid fa-plus"></i> Crear Solicitud</button>
        </form>
    </div>
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
