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
            <input type="text" class="form-control{{ $errors->has('MATERIAL_NOMBRE') ? ' is-invalid' : '' }}" id="MATERIAL_NOMBRE" name="MATERIAL_NOMBRE" value="{{ old('MATERIAL_NOMBRE') }}" placeholder="Ej: LÁPIZ N°2" required>
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
            <small id="stockHelp" class="form-text text-muted">
                El stock debe ser mayor o igual que 0.-
            </small>
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

        <a href="{{route('materiales.index')}}" class="btn btn-secondary" tabindex="5"><i class="fa-solid fa-hand-point-left"></i> Cancelar</a>
        <button type="submit" class="btn btn-primary" tabindex="4"><i class="fa-solid fa-floppy-disk"></i> Guardar material</button>
    </form>
</div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <!-- CONEXION FONT-AWESOME CON TOOLKIT -->
    <script src="https://kit.fontawesome.com/742a59c628.js" crossorigin="anonymous"></script>
@stop
