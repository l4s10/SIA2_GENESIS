@extends('adminlte::page')

@section('title', 'Ingreso de Equipos')

@section('content_header')
    <h1>Ingresar Equipo</h1>
@stop

@section('content')
<div class="container">
    <form action="{{ route('equipos.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="EQUIPO_MODELO" class="form-label"><i class="fa-solid fa-computer"></i> Modelo del equipo:</label>
            <input type="text" class="form-control{{ $errors->has('EQUIPO_MODELO') ? ' is-invalid' : '' }}" id="EQUIPO_MODELO" name="EQUIPO_MODELO" value="{{ old('EQUIPO_MODELO') }}" placeholder="Ej: LEGION Y-530" required>
            @if ($errors->has('EQUIPO_MODELO'))
                <div class="invalid-feedback">
                    {{ $errors->first('EQUIPO_MODELO') }}
                </div>
            @endif
        </div>

        <div class="mb-3">
            <label for="EQUIPO_MARCA" class="form-label"><i class="fa-solid fa-pen-to-square"></i> Marca del equipo:</label>
            <input type="text" class="form-control{{ $errors->has('EQUIPO_MARCA') ? ' is-invalid' : '' }}" id="EQUIPO_MARCA" name="EQUIPO_MARCA" value="{{ old('EQUIPO_MARCA') }}" placeholder="Ej: LENOVO" required>
            @if ($errors->has('EQUIPO_MARCA'))
                <div class="invalid-feedback">
                    {{ $errors->first('EQUIPO_MARCA') }}
                </div>
            @endif
        </div>

        <div class="mb-3">
            <label for="TIPO_EQUIPO_ID" class="form-label"><i class="fa-solid fa-pen-to-square"></i> Tipo de equipo:</label>
            <select class="form-control{{ $errors->has('TIPO_EQUIPO_ID') ? ' is-invalid' : '' }}" aria-label="Seleccione un tipo de equipo" id="TIPO_EQUIPO_ID" name="TIPO_EQUIPO_ID" required>
                <option value="">--Seleccione un tipo de equipo--</option>
                @foreach($tiposEquipos as $tipo)
                    <option value="{{ $tipo->TIPO_EQUIPO_ID }}" {{ old('TIPO_EQUIPO_ID') == $tipo->TIPO_EQUIPO_ID ? 'selected' : '' }}>
                        {{ $tipo->TIPO_EQUIPO_NOMBRE }}
                    </option>
                @endforeach
            </select>
            @if ($errors->has('TIPO_EQUIPO_ID'))
                <div class="invalid-feedback">
                    {{ $errors->first('TIPO_EQUIPO_ID') }}
                </div>
            @endif
        </div>

        <div class="mb-3">
            <label for="EQUIPO_ESTADO" class="form-label"><i class="fa-solid fa-notes-medical"></i> Estado del equipo:</label>
            <select class="form-control{{ $errors->has('EQUIPO_ESTADO') ? ' is-invalid' : '' }}" aria-label="Seleccione un tipo de equipo" id="EQUIPO_ESTADO" name="EQUIPO_ESTADO" required>
                <option value="">--Seleccione un estado--</option>
                <option value="DISPONIBLE">DISPONIBLE</option>
                <option value="PRESTADO">PRESTADO</option>
                <option value="NO DISPONIBLE">NO DISPONIBLE</option>
            </select>
            @if ($errors->has('EQUIPO_ESTADO'))
                <div class="invalid-feedback">
                    {{ $errors->first('EQUIPO_ESTADO') }}
                </div>
            @endif
        </div>

        <div class="mb-3">
            <label for="EQUIPO_STOCK" class="form-label"><i class="fa-solid fa-list-ol"></i> Stock:</label>
            <input type="number" class="form-control{{ $errors->has('EQUIPO_STOCK') ? ' is-invalid' : '' }}" id="EQUIPO_STOCK" name="EQUIPO_STOCK" value="{{ old('EQUIPO_STOCK') }}" placeholder="Ingrese la cantidad disponible del equipo" min="0" max="1000" required>
            @if ($errors->has('EQUIPO_STOCK'))
                <div class="invalid-feedback">
                    {{ $errors->first('EQUIPO_STOCK') }}
                </div>
            @endif
        </div>

        <div class="mb-3">
            <label for="DETALLE_MOVIMIENTO" class="form-label"><i class="fa-solid fa-person-chalkboard"></i> Detalle del Movimiento:</label>
            <textarea required class="form-control{{ $errors->has('DETALLE_MOVIMIENTO') ? ' is-invalid' : '' }}" name="DETALLE_MOVIMIENTO" id="DETALLE_MOVIMIENTO" cols="30" rows="5" placeholder="Especifique: N° factura, Código libro adquisiciones, Nombre y rut proveedor, N° res. exenta de compra y de orden de compra. (MAX 1000 CARACTERES)">{{ old('DETALLE_MOVIMIENTO') }}</textarea>
            @if ($errors->has('DETALLE_MOVIMIENTO'))
                <div class="invalid-feedback">
                    {{ $errors->first('DETALLE_MOVIMIENTO') }}
                </div>
            @endif
        </div>

        <div class="mb-3">
            <label for="OFICINA_ID" class="form-label"><i class="fa-solid fa-person-chalkboard"></i> Dirección Regional Asociada:</label>
            <input type="text" id="OFICINA_ID" name="OFICINA_ID" class="form-control" value="{{ $oficina->OFICINA_NOMBRE }}" readonly>
            <input type="hidden" name="OFICINA_ID" value="{{ $oficina->OFICINA_ID }}">
        </div>

        <a href="{{route('equipos.index')}}" class="btn btn-secondary" tabindex="5"><i class="fa-solid fa-hand-point-left"></i> Cancelar</a>
        <button type="submit" class="btn guardar" tabindex="4"><i class="fa-solid fa-floppy-disk"></i> Guardar equipo</button>
    </form>
</div>
@stop

@section('css')
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
    <style>/* Estilos personalizados si es necesario */
        .guardar {
            background-color: #e6500a;
            color: #fff;
        }
    </style>
@stop

@section('js')
    <!-- CONEXION FONT-AWESOME CON TOOLKIT -->
    {{-- <script src="https://kit.fontawesome.com/742a59c628.js" crossorigin="anonymous"></script> --}}
@stop
