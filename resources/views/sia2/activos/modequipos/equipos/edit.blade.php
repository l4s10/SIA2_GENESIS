@extends('adminlte::page')

@section('title', 'Editar equipo')

@section('content_header')
    <h1>Editar Material</h1>
@stop

@section('content')
    <div class="container">
        <form action="{{ route('equipos.update', $equipo->EQUIPO_ID) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="EQUIPO_MODELO" class="form-label"><i class="fa-solid fa-book-bookmark"></i> Modelo Equipo</label>
                <input id="EQUIPO_MODELO" name="EQUIPO_MODELO" type="text" class="form-control{{ $errors->has('EQUIPO_MODELO') ? ' is-invalid' : '' }}" value="{{ old('EQUIPO_MODELO', $equipo->EQUIPO_MODELO) }}">
                @if($errors->has('EQUIPO_MODELO'))
                    <div class="invalid-feedback">
                        {{ $errors->first('EQUIPO_MODELO') }}
                    </div>
                @endif
            </div>

            <div class="mb-3">
                <label for="EQUIPO_MARCA" class="form-label"><i class="fa-solid fa-book-bookmark"></i> Marca Equipo</label>
                <input id="EQUIPO_MARCA" name="EQUIPO_MARCA" type="text" class="form-control{{ $errors->has('EQUIPO_MARCA') ? ' is-invalid' : '' }}" value="{{ old('EQUIPO_MARCA', $equipo->EQUIPO_MARCA) }}">
                @if($errors->has('EQUIPO_MARCA'))
                    <div class="invalid-feedback">
                        {{ $errors->first('EQUIPO_MARCA') }}
                    </div>
                @endif
            </div>

            <div class="mb-3">
                <div class="row">

                    <div class="col-md-6">
                        <label for="TIPO_EQUIPO_ID" class="form-label"><i class="fa-solid fa-pen-to-square"></i> Tipo de equipo</label>
                        <select id="TIPO_EQUIPO_ID" name="TIPO_EQUIPO_ID" class="form-control @error('TIPO_EQUIPO_ID') is-invalid @enderror">
                            @foreach($tiposEquipos as $tipoEquipo)
                                @if($equipo->TIPO_EQUIPO_ID == $tipoEquipo->TIPO_EQUIPO_ID)
                                    <option value="{{$tipoEquipo->TIPO_EQUIPO_ID}}" selected>{{$tipoEquipo->TIPO_EQUIPO_NOMBRE}}</option>
                                @else
                                    <option value="{{$tipoEquipo->TIPO_EQUIPO_ID}}">{{$tipoEquipo->TIPO_EQUIPO_NOMBRE}}</option>
                                @endif
                            @endforeach
                        </select>
                        @error('TIPO_EQUIPO_ID')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="TIPO_MOVIMIENTO" class="form-label"><i class="fa-solid fa-pen-to-square"></i> Tipo de movimiento: </label>
                        <select name="TIPO_MOVIMIENTO" id="TIPO_MOVIMIENTO" class="form-control @error('TIPO_MOVIMIENTO') is-invalid @enderror" required>
                            <option value="INGRESO" {{ old('TIPO_MOVIMIENTO', $equipo->TIPO_MOVIMIENTO) == 'INGRESO' ? 'selected' : '' }}>INGRESO</option>
                            <option value="TRASLADO" {{ old('TIPO_MOVIMIENTO', $equipo->TIPO_MOVIMIENTO) == 'TRASLADO' ? 'selected' : '' }}>TRASLADO</option>
                            <option value="MERMA" {{ old('TIPO_MOVIMIENTO', $equipo->TIPO_MOVIMIENTO) == 'MERMA' ? 'selected' : '' }}>MERMA</option>
                            <option value="OTRO" {{ old('TIPO_MOVIMIENTO', $equipo->TIPO_MOVIMIENTO) == 'OTRO' ? 'selected' : '' }}>OTRO</option>
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
                        <label for="EQUIPO_STOCK"><i class="fa-solid fa-list-ol"></i> Stock actual:</label>
                        <input type="number" class="form-control" id="EQUIPO_STOCK" name="EQUIPO_STOCK" value="{{ old('EQUIPO_STOCK', $equipo->EQUIPO_STOCK) }}" min="0" readonly>
                        @error('EQUIPO_STOCK')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="STOCK_NUEVO"><i class="fa-solid fa-list-ol"></i> Cantidad a modificar:</label>
                        <input type="number" class="form-control{{ $errors->has('STOCK_NUEVO') ? ' is-invalid' : '' }}" id="STOCK_NUEVO" name="STOCK_NUEVO" value="{{ old('STOCK_NUEVO', $equipo->STOCK_NUEVO ?? 0) }}" min="0" max="1000" placeholder="Indicar la cantidad" required>
                        @error('STOCK_NUEVO')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
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

            {{--<div class="mb-3">
                <label for="ID_DIRECCION" class="form-label"><i class="fa-solid fa-person-chalkboard"></i> Dirección Regional:</label>
                <select id="ID_DIRECCION" name="ID_DIRECCION" class="form-control" disabled>
                    <option value="{{ $material->ID_DIRECCION }}" selected>{{ $material->direccionRegional->DIRECCION }}</option>
                </select>
            </div>--}}

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

