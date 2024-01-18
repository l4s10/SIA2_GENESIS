@extends('adminlte::page')

@section('title', 'Editar material')

@section('content_header')
    <h1>Editar Material</h1>
@stop

@section('content')
    <div class="container">
        <form action="{{ route('materiales.update', $material->MATERIAL_ID) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="MATERIAL_NOMBRE" class="form-label"><i class="fa-solid fa-book-bookmark"></i> Nombre Material</label>
                <input id="MATERIAL_NOMBRE" name="MATERIAL_NOMBRE" type="text" class="form-control{{ $errors->has('MATERIAL_NOMBRE') ? ' is-invalid' : '' }}" value="{{ old('MATERIAL_NOMBRE', $material->MATERIAL_NOMBRE) }}">
                @if($errors->has('MATERIAL_NOMBRE'))
                    <div class="invalid-feedback">
                        {{ $errors->first('MATERIAL_NOMBRE') }}
                    </div>
                @endif
            </div>

            <div class="mb-3">
                <div class="row">
                  
                    <div class="col-md-6">
                        <label for="TIPO_MATERIAL_ID" class="form-label"><i class="fa-solid fa-pen-to-square"></i> Tipo de material</label>
                        <select id="TIPO_MATERIAL_ID" name="TIPO_MATERIAL_ID" class="form-control @error('TIPO_MATERIAL_ID') is-invalid @enderror">
                            @foreach($tiposMateriales as $tipoMaterial)
                                @if($material->TIPO_MATERIAL_ID == $tipoMaterial->TIPO_MATERIAL_ID)
                                    <option value="{{$tipoMaterial->TIPO_MATERIAL_ID}}" selected>{{$tipoMaterial->TIPO_MATERIAL_NOMBRE}}</option>
                                @else
                                    <option value="{{$tipoMaterial->TIPO_MATERIAL_ID}}">{{$tipoMaterial->TIPO_MATERIAL_NOMBRE}}</option>
                                @endif
                            @endforeach
                        </select>
                        @error('ID_TIPO_MAT')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="TIPO_MOVIMIENTO" class="form-label"><i class="fa-solid fa-pen-to-square"></i> Tipo de movimiento:</label>
                        <select name="TIPO_MOVIMIENTO" id="TIPO_MOVIMIENTO" class="form-control @error('TIPO_MOVIMIENTO') is-invalid @enderror" required>
                            <option value="" disabled selected>Seleccione un tipo de movimiento</option>
                            <option value="INGRESO" {{ old('TIPO_MOVIMIENTO', $material->TIPO_MOVIMIENTO) == 'INGRESO' ? 'selected' : '' }}>INGRESO</option>
                            <option value="TRASLADO" {{ old('TIPO_MOVIMIENTO', $material->TIPO_MOVIMIENTO) == 'TRASLADO' ? 'selected' : '' }}>TRASLADO</option>
                            <option value="MERMA" {{ old('TIPO_MOVIMIENTO', $material->TIPO_MOVIMIENTO) == 'MERMA' ? 'selected' : '' }}>MERMA</option>
                            <option value="OTRO" {{ old('TIPO_MOVIMIENTO', $material->TIPO_MOVIMIENTO) == 'OTRO' ? 'selected' : '' }}>OTRO</option>
                        </select>
                        @error('TIPO_MOVIMIENTO')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                </div>
            </div>

            <div class="mb-3">
                <div class="row">
                    <div class="col-md-6">
                        <label for="MATERIAL_STOCK"><i class="fa-solid fa-list-ol"></i> Stock actual:</label>
                        <input type="number" class="form-control" id="MATERIAL_STOCK" name="MATERIAL_STOCK" value="{{ old('MATERIAL_STOCK', $material->MATERIAL_STOCK) }}" min="0" max="1000" readonly>
                        @error('MATERIAL_STOCK')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="STOCK_NUEVO"><i class="fa-solid fa-list-ol"></i> Cantidad a modificar:</label>
                        <input type="number" class="form-control{{ $errors->has('STOCK_NUEVO') ? ' is-invalid' : '' }}" id="STOCK_NUEVO" name="STOCK_NUEVO" value="{{ old('STOCK_NUEVO', isset($material) ? $material->STOCK_NUEVO : null) }}" min="0" max="1000" placeholder="Indicar la cantidad" required>
                        @if ($errors->has('STOCK_NUEVO'))
                            <div class="text-danger">{{ $errors->first('STOCK_NUEVO') }}</div>
                        @endif
                    </div>
                                   
                    
                </div>
            </div>

            <div class="mb-3">
                <div class="form-group">
                    <label for="DETALLE_MOVIMIENTO">Detalle del Movimiento</label>
                    <textarea required class="form-control{{ $errors->has('DETALLE_MOVIMIENTO') ? ' is-invalid' : '' }}" name="DETALLE_MOVIMIENTO" id="DETALLE_MOVIMIENTO" cols="30" rows="5" placeholder="Especificar según caso:
                        - OTRO: Nombre completo del editor, descripción de parámetro modificado.
                        - INGRESO: N° factura, código libro adquisiciones, nombre y rut proveedor, N° res. exenta de compra y de orden de compra.
                        - TRASLADO: Si es hacia o desde unidades, cantidad trasladada, fecha memo conductor y correo electrónico del solicitante.
                        - MERMA: Fecha de autorización y vía de autorización, nombre del jefe de dpto que autoriza.
                        (MAX 1000 CARACTERES)">{{ old('DETALLE_MOVIMIENTO') }}</textarea>
                    @if ($errors->has('DETALLE_MOVIMIENTO'))
                        <div class="invalid-feedback">
                            {{ $errors->first('DETALLE_MOVIMIENTO') }}
                        </div>
                    @enderror
                </div>
            </div>
            <a href="{{ route('materiales.index') }}" class="btn btn-secondary" tabindex="5"><i class="fa-solid fa-hand-point-left"></i> Cancelar</a>
            <button type="submit" class="btn btn-primary" tabindex="4"><i class="fa-solid fa-floppy-disk"></i> Guardar cambios</button>
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


