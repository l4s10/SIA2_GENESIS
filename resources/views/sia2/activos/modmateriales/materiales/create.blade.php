@extends('adminlte::page')

@section('title', 'Ingreso de Materiales')

@section('content_header')
    <h1>Ingresar Material</h1>
@stop

@section('content')
<div class="container">
    <form action="{{ route('materiales.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="MATERIAL_NOMBRE" class="form-label"><i class="fa-solid fa-pen-to-square"></i> Nombre del material:</label>
            <input type="text" class="form-control{{ $errors->has('MATERIAL_NOMBRE') ? ' is-invalid' : '' }}" id="MATERIAL_NOMBRE" name="MATERIAL_NOMBRE" value="{{ old('MATERIAL_NOMBRE') }}" placeholder="Ej: LÁPIZ N°2" maxlength="40" required>
            @if ($errors->has('MATERIAL_NOMBRE'))
                <div class="invalid-feedback">
                    {{ $errors->first('MATERIAL_NOMBRE') }}
                </div>
            @endif
        </div>

        <div class="mb-3">
            <label for="TIPO_MATERIAL_ID" class="form-label"><i class="fa-solid fa-pen-to-square"></i> Tipo de material:</label>
            <select class="form-control{{ $errors->has('TIPO_MATERIAL_ID') ? ' is-invalid' : '' }}" aria-label="Seleccione un tipo de material" id="TIPO_MATERIAL_ID" name="TIPO_MATERIAL_ID" required>
                <option value="">--Seleccione un tipo de material--</option>
                @foreach($tiposMaterial as $tipo)
                    <option value="{{ $tipo->TIPO_MATERIAL_ID }}" {{ old('TIPO_MATERIAL_ID') == $tipo->TIPO_MATERIAL_ID ? 'selected' : '' }}>
                        {{ $tipo->TIPO_MATERIAL_NOMBRE }}
                    </option>
                @endforeach
            </select>
            @if ($errors->has('TIPO_MATERIAL_ID'))
                <div class="invalid-feedback">
                    {{ $errors->first('TIPO_MATERIAL_ID') }}
                </div>
            @endif
        </div>

        <div class="mb-3">
            <label for="MATERIAL_STOCK" class="form-label"><i class="fa-solid fa-list-ol"></i> Stock:</label>
            <input type="number" class="form-control{{ $errors->has('MATERIAL_STOCK') ? ' is-invalid' : '' }}" id="MATERIAL_STOCK" name="MATERIAL_STOCK" value="{{ old('MATERIAL_STOCK') }}" placeholder="Ingrese la cantidad disponible del material" min="0" max="1000" required>
            @if ($errors->has('MATERIAL_STOCK'))
                <div class="invalid-feedback">
                    {{ $errors->first('MATERIAL_STOCK') }}
                </div>
            @endif
        </div>

        {{-- <div class="mb-3">
            <label for="DETALLE_MOVIMIENTO" class="form-label"><i class="fa-solid fa-person-chalkboard"></i> Detalle del Movimiento:</label>
            <textarea required class="form-control{{ $errors->has('DETALLE_MOVIMIENTO') ? ' is-invalid' : '' }}" name="DETALLE_MOVIMIENTO" id="DETALLE_MOVIMIENTO" cols="30" rows="5" placeholder="Especifique: N° factura, Código libro adquisiciones, Nombre y rut proveedor, N° res. exenta de compra y de orden de compra. (MAX 1000 CARACTERES)">{{ old('DETALLE_MOVIMIENTO') }}</textarea>
            @if ($errors->has('DETALLE_MOVIMIENTO'))
                <div class="invalid-feedback">
                    {{ $errors->first('DETALLE_MOVIMIENTO') }}
                </div>
            @endif
        </div> --}}

        <div class="mb-3">
            <label for="PROVEEDOR" class="form-label"><i class="fa-solid fa-building"></i> Proveedor:</label>
            <input type="text" class="form-control{{ $errors->has('PROVEEDOR') ? ' is-invalid' : '' }}" id="PROVEEDOR" name="PROVEEDOR" value="{{ old('PROVEEDOR') }}" placeholder="Nombre del proveedor" maxlength="255" required>
            @if ($errors->has('PROVEEDOR'))
                <div class="invalid-feedback">
                    {{ $errors->first('PROVEEDOR') }}
                </div>
            @endif
        </div>

        <div class="mb-3">
            <label for="NUMERO_FACTURA" class="form-label"><i class="fa-solid fa-file-invoice-dollar"></i> Número de Factura:</label>
            <input type="number" class="form-control{{ $errors->has('NUMERO_FACTURA') ? ' is-invalid' : '' }}" id="NUMERO_FACTURA" name="NUMERO_FACTURA" value="{{ old('NUMERO_FACTURA') }}" placeholder="Número de factura" min="0" max="999999" required>
            @if ($errors->has('NUMERO_FACTURA'))
                <div class="invalid-feedback">
                    {{ $errors->first('NUMERO_FACTURA') }}
                </div>
            @endif
        </div>

        <div class="mb-3">
            <label for="COD_LIBRO_ADQUISICIONES" class="form-label"><i class="fa-solid fa-book"></i> Código Libro de Adquisiciones:</label>
            <input type="text" class="form-control{{ $errors->has('COD_LIBRO_ADQUISICIONES') ? ' is-invalid' : '' }}" id="COD_LIBRO_ADQUISICIONES" name="COD_LIBRO_ADQUISICIONES" value="{{ old('COD_LIBRO_ADQUISICIONES') }}" placeholder="Código libro adquisiciones" maxlength="255" required>
            @if ($errors->has('COD_LIBRO_ADQUISICIONES'))
                <div class="invalid-feedback">
                    {{ $errors->first('COD_LIBRO_ADQUISICIONES') }}
                </div>
            @endif
        </div>

        <div class="mb-3">
            <label for="NUM_RES_EXCENTO_COMPRA" class="form-label"><i class="fa-solid fa-file-signature"></i> Número Resolución Exenta de Compra:</label>
            <input type="number" class="form-control{{ $errors->has('NUM_RES_EXCENTO_COMPRA') ? ' is-invalid' : '' }}" id="NUM_RES_EXCENTO_COMPRA" name="NUM_RES_EXCENTO_COMPRA" value="{{ old('NUM_RES_EXCENTO_COMPRA') }}" placeholder="Número res. exenta de compra" min="0" max="999999" required>
            @if ($errors->has('NUM_RES_EXCENTO_COMPRA'))
                <div class="invalid-feedback">
                    {{ $errors->first('NUM_RES_EXCENTO_COMPRA') }}
                </div>
            @endif
        </div>

        <div class="mb-3">
            <label for="NUM_ORDEN_COMPRA" class="form-label"><i class="fa-solid fa-file-contract"></i> Número Orden de Compra:</label>
            <input type="number" class="form-control{{ $errors->has('NUM_ORDEN_COMPRA') ? ' is-invalid' : '' }}" id="NUM_ORDEN_COMPRA" name="NUM_ORDEN_COMPRA" value="{{ old('NUM_ORDEN_COMPRA') }}" placeholder="Número de orden de compra" min="0" max="999999" required>
            @if ($errors->has('NUM_ORDEN_COMPRA'))
                <div class="invalid-feedback">
                    {{ $errors->first('NUM_ORDEN_COMPRA') }}
                </div>
            @endif
        </div>

        <div class="mb-3">
            <label for="OFICINA_ID" class="form-label"><i class="fa-solid fa-person-chalkboard"></i> Dirección Regional Asociada:</label>
            <input type="text" id="OFICINA_ID" name="OFICINA_ID" class="form-control" value="{{ $oficina->OFICINA_NOMBRE }}" readonly>
            <input type="hidden" name="OFICINA_ID" value="{{ $oficina->OFICINA_ID }}">
        </div>

        <a href="{{route('materiales.index')}}" class="btn btn-secondary" tabindex="5"><i class="fa-solid fa-hand-point-left"></i> Cancelar</a>
        <button type="submit" class="btn guardar" tabindex="4"><i class="fa-solid fa-floppy-disk"></i> Guardar material</button>
    </form>
</div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
    <style>/* Estilos personalizados si es necesario */
        .guardar {
            background-color: #e6500a;
            color: #fff;
        }
    </style>
@stop

@section('js')
    <!-- CONEXION FONT-AWESOME CON TOOLKIT -->
    <script src="https://kit.fontawesome.com/742a59c628.js" crossorigin="anonymous"></script>
@stop
