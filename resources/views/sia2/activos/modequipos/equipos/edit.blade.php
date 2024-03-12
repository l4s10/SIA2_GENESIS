@extends('adminlte::page')

@section('title', 'Editar equipo')

@section('content_header')
    <h1>Editar Equipo</h1>
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
                <label for="EQUIPO_ESTADO" class="form-label"><i class="fa-solid fa-notes-medical"></i> Estado del equipo:</label>
                <select class="form-control{{ $errors->has('EQUIPO_ESTADO') ? ' is-invalid' : '' }}" aria-label="Seleccione un tipo de equipo" id="EQUIPO_ESTADO" name="EQUIPO_ESTADO" required>
                    <option value="">--Seleccione un estado--</option>
                    <option value="DISPONIBLE" {{ old('EQUIPO_ESTADO', $equipo->EQUIPO_ESTADO) == 'DISPONIBLE' ? 'selected' : '' }}>DISPONIBLE</option>
                    <option value="PRESTADO" {{ old('EQUIPO_ESTADO', $equipo->EQUIPO_ESTADO) == 'PRESTADO' ? 'selected' : '' }}>PRESTADO</option>
                    <option value="NO DISPONIBLE" {{ old('EQUIPO_ESTADO', $equipo->EQUIPO_ESTADO) == 'NO DISPONIBLE' ? 'selected' : '' }}>NO DISPONIBLE</option>
                </select>
                @if ($errors->has('EQUIPO_ESTADO'))
                    <div class="invalid-feedback">
                        {{ $errors->first('EQUIPO_ESTADO') }}
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
                        <label for="TIPO_MOVIMIENTO" class="form-label"><i class="fa-solid fa-pen-to-square"></i> Tipo de movimiento:</label>
                        <select name="TIPO_MOVIMIENTO" id="TIPO_MOVIMIENTO" class="form-control @error('TIPO_MOVIMIENTO') is-invalid @enderror" required>
                            <option value="" disabled selected>Seleccione un tipo de movimiento</option>
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
                        <input type="number" class="form-control{{ $errors->has('STOCK_NUEVO') ? ' is-invalid' : '' }}" id="STOCK_NUEVO" name="STOCK_NUEVO" value="{{ old('STOCK_NUEVO', isset($material) ? $material->STOCK_NUEVO : null) }}" min="0" max="1000" placeholder="Indicar la cantidad" required>
                        @if ($errors->has('STOCK_NUEVO'))
                            <div class="text-danger">{{ $errors->first('STOCK_NUEVO') }}</div>
                        @endif
                    </div>

                </div>
            </div>

            <div id="ingresoFields" style="display: none;">
                {{-- Campos especificos para INGRESO --}}
                <div class="mb-3">
                    <label for="PROVEEDOR" class="form-label"><i class="fa-solid fa-building"></i> Proveedor:</label>
                    <input type="text" class="form-control{{ $errors->has('PROVEEDOR') ? ' is-invalid' : '' }}" id="PROVEEDOR" name="PROVEEDOR" value="{{ old('PROVEEDOR') }}" placeholder="Nombre del proveedor" maxlength="255" >
                    @if ($errors->has('PROVEEDOR'))
                        <div class="invalid-feedback">
                            {{ $errors->first('PROVEEDOR') }}
                        </div>
                    @endif
                </div>

                <div class="mb-3">
                    <label for="NUMERO_FACTURA" class="form-label"><i class="fa-solid fa-file-invoice-dollar"></i> Número de Factura:</label>
                    <input type="number" class="form-control{{ $errors->has('NUMERO_FACTURA') ? ' is-invalid' : '' }}" id="NUMERO_FACTURA" name="NUMERO_FACTURA" value="{{ old('NUMERO_FACTURA') }}" placeholder="Número de factura" min="0" max="999999" >
                    @if ($errors->has('NUMERO_FACTURA'))
                        <div class="invalid-feedback">
                            {{ $errors->first('NUMERO_FACTURA') }}
                        </div>
                    @endif
                </div>

                <div class="mb-3">
                    <label for="COD_LIBRO_ADQUISICIONES" class="form-label"><i class="fa-solid fa-book"></i> Código Libro de Adquisiciones:</label>
                    <input type="text" class="form-control{{ $errors->has('COD_LIBRO_ADQUISICIONES') ? ' is-invalid' : '' }}" id="COD_LIBRO_ADQUISICIONES" name="COD_LIBRO_ADQUISICIONES" value="{{ old('COD_LIBRO_ADQUISICIONES') }}" placeholder="Código libro adquisiciones" maxlength="255" >
                    @if ($errors->has('COD_LIBRO_ADQUISICIONES'))
                        <div class="invalid-feedback">
                            {{ $errors->first('COD_LIBRO_ADQUISICIONES') }}
                        </div>
                    @endif
                </div>

                <div class="mb-3">
                    <label for="NUM_RES_EXCENTO_COMPRA" class="form-label"><i class="fa-solid fa-file-signature"></i> Número Resolución Exenta de Compra:</label>
                    <input type="number" class="form-control{{ $errors->has('NUM_RES_EXCENTO_COMPRA') ? ' is-invalid' : '' }}" id="NUM_RES_EXCENTO_COMPRA" name="NUM_RES_EXCENTO_COMPRA" value="{{ old('NUM_RES_EXCENTO_COMPRA') }}" placeholder="Número res. exenta de compra" min="0" max="999999" >
                    @if ($errors->has('NUM_RES_EXCENTO_COMPRA'))
                        <div class="invalid-feedback">
                            {{ $errors->first('NUM_RES_EXCENTO_COMPRA') }}
                        </div>
                    @endif
                </div>

                <div class="mb-3">
                    <label for="NUM_ORDEN_COMPRA" class="form-label"><i class="fa-solid fa-file-contract"></i> Número Orden de Compra:</label>
                    <input type="number" class="form-control{{ $errors->has('NUM_ORDEN_COMPRA') ? ' is-invalid' : '' }}" id="NUM_ORDEN_COMPRA" name="NUM_ORDEN_COMPRA" value="{{ old('NUM_ORDEN_COMPRA') }}" placeholder="Número de orden de compra" min="0" max="999999" >
                    @if ($errors->has('NUM_ORDEN_COMPRA'))
                        <div class="invalid-feedback">
                            {{ $errors->first('NUM_ORDEN_COMPRA') }}
                        </div>
                    @endif
                </div>
            </div>

            <div id="trasladoFields" style="display: none;">
                <!-- Campos específicos para TRASLADO -->
                {{-- Ubicacion ID -> lugar de destino --}}
                <div class="mb-3">
                    <label for="UBICACION_ID" class="form-label mb-0"><i class="fa-solid fa-building"></i> Lugar de destino:</label>
                    <select name="UBICACION_ID" id="UBICACION_ID" class="form-control @error('UBICACION_ID') is-invalid @enderror" >
                        <option value="" disabled selected>-- Seleccione un lugar de destino --</option>
                        @foreach($ubicaciones as $ubicacion)
                            @if($equipo->UBICACION_ID == $ubicacion->UBICACION_ID)
                                <option value="{{$ubicacion->UBICACION_ID}}" selected>{{$ubicacion->UBICACION_NOMBRE}}</option>
                            @else
                                <option value="{{$ubicacion->UBICACION_ID}}">{{$ubicacion->UBICACION_NOMBRE}}</option>
                            @endif
                        @endforeach
                    </select>
                    @error('UBICACION_ID')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                </div>

                {{-- Fecha memo conductor --}}
                <div class="mb-3">
                    <label for="FECHA_MEMO_CONDUCTOR" class="form-label"><i class="fa-solid fa-calendar"></i> Fecha memo conductor:</label>
                    <input type="date" class="form-control{{ $errors->has('FECHA_MEMO_CONDUCTOR') ? ' is-invalid' : '' }}" id="FECHA_MEMO_CONDUCTOR" name="FECHA_MEMO_CONDUCTOR" value="{{ old('FECHA_MEMO_CONDUCTOR') }}" >
                    @if ($errors->has('FECHA_MEMO_CONDUCTOR'))
                        <div class="invalid-feedback">
                            {{ $errors->first('FECHA_MEMO_CONDUCTOR') }}
                        </div>
                    @enderror
                </div>

                {{-- CORREO_ELECTRONICO_SOLICITANTE --}}
                <div class="mb-3">
                    <label for="CORREO_ELECTRONICO_SOLICITANTE" class="form-label"><i class="fa-solid fa-envelope"></i> Correo electrónico del solicitante:</label>
                    <select name="CORREO_ELECTRONICO_SOLICITANTE" id="CORREO_ELECTRONICO_SOLICITANTE" class="form-control @error('CORREO_ELECTRONICO_SOLICITANTE') is-invalid @enderror">
                        <option value="" disabled selected>-- Seleccione un correo --</option>
                    </select>
                    @error('CORREO_ELECTRONICO_SOLICITANTE')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div id="mermaFields" style="display: none;">
                <!-- Campos específicos para MERMA -->
                {{-- Fecha de autorización --}}
                <div class="mb-3">
                    <label for="FECHA_AUTORIZACION" class="form-label"><i class="fa-solid fa-calendar"></i> Fecha de autorización:</label>
                    <input type="date" class="form-control{{ $errors->has('FECHA_AUTORIZACION') ? ' is-invalid' : '' }}" id="FECHA_AUTORIZACION" name="FECHA_AUTORIZACION" value="{{ old('FECHA_AUTORIZACION') }}">
                    @if ($errors->has('FECHA_AUTORIZACION'))
                        <div class="invalid-feedback">
                            {{ $errors->first('FECHA_AUTORIZACION') }}
                        </div>
                    @enderror
                </div>

                {{-- Nombre jefe que autoriza --}}
                <div class="mb-3">
                    <label for="NOMBRE_JEFE_AUTORIZA" class="form-label"><i class="fa-solid fa-user"></i> Nombre del jefe que autoriza:</label>
                    <input type="text" class="form-control{{ $errors->has('NOMBRE_JEFE_AUTORIZA') ? ' is-invalid' : '' }}" id="NOMBRE_JEFE_AUTORIZA" name="NOMBRE_JEFE_AUTORIZA" value="{{ old('NOMBRE_JEFE_AUTORIZA') }}" placeholder="Nombre del jefe que autoriza" maxlength="255">
                    @error('NOMBRE_JEFE_AUTORIZA')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div id="otroFields" style="display: none;">
                <!-- Campos específicos para OTRO -->
                {{-- Detalle movimiento --}}
                <div class="mb-3">
                    <div class="form-group">
                        <label for="DETALLE_MOVIMIENTO"><i class="fa-solid fa-person-chalkboard"></i> Detalle del Movimiento</label>
                        <textarea class="form-control{{ $errors->has('DETALLE_MOVIMIENTO') ? ' is-invalid' : '' }}" name="DETALLE_MOVIMIENTO" id="DETALLE_MOVIMIENTO" cols="30" rows="5" placeholder="Especificar según caso:
                            - OTRO: Descripción de parámetro modificado y justificar el cambio.
                            (MAX 1000 CARACTERES)">{{ old('DETALLE_MOVIMIENTO') }}</textarea>
                        @if ($errors->has('DETALLE_MOVIMIENTO'))
                            <div class="invalid-feedback">
                                {{ $errors->first('DETALLE_MOVIMIENTO') }}
                            </div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label for="OFICINA_ID" class="form-label"><i class="fa-solid fa-person-chalkboard"></i> Dirección Regional Asociada:</label>
                <input type="text" id="OFICINA_ID" name="OFICINA_ID" class="form-control" value="{{ $oficina->OFICINA_NOMBRE }}" readonly>
                <input type="hidden" name="OFICINA_ID" value="{{ $oficina->OFICINA_ID }}">
            </div>

            <a href="{{ route('equipos.index') }}" class="btn btn-secondary" tabindex="5"><i class="fa-solid fa-hand-point-left"></i> Cancelar</a>
            <button type="submit" class="btn guardar" tabindex="4"><i class="fa-solid fa-floppy-disk"></i> Guardar </button>
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
    <!-- JavaScript para manejar la lógica de mostrar/ocultar campos -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const tipoMovimientoSelect = document.getElementById('TIPO_MOVIMIENTO');
            const ingresoFields = document.getElementById('ingresoFields');
            const trasladoFields = document.getElementById('trasladoFields');
            const mermaFields = document.getElementById('mermaFields');
            const otroFields = document.getElementById('otroFields');

            const setRequiredAndClear = (fields, status) => {
                fields.forEach(field => {
                    const element = document.getElementById(field);
                    if(element) {
                        element.required = status;
                        if (!status) element.value = ''; // Limpia el valor si el campo no es requerido
                    }
                });
            };

            function toggleFields() {
                const selectedValue = tipoMovimientoSelect.value;

                // Lista de todos los campos para hacerlos opcionales y limpiarlos inicialmente
                const allFields = ['PROVEEDOR', 'NUMERO_FACTURA', 'COD_LIBRO_ADQUISICIONES', 'NUM_RES_EXCENTO_COMPRA', 'NUM_ORDEN_COMPRA', 'UBICACION_ID', 'FECHA_MEMO_CONDUCTOR', 'CORREO_ELECTRONICO_SOLICITANTE', 'FECHA_AUTORIZACION', 'NOMBRE_JEFE_AUTORIZA'];
                setRequiredAndClear(allFields, false);

                // Ocultar todos los contenedores de campos específicos inicialmente
                ingresoFields.style.display = 'none';
                trasladoFields.style.display = 'none';
                mermaFields.style.display = 'none';
                otroFields.style.display = 'none';

                // Lógica para manejar la visualización de campos específicos y hacerlos requeridos según el tipo de movimiento seleccionado
                switch (selectedValue) {
                    case 'INGRESO':
                        ingresoFields.style.display = 'block';
                        setRequiredAndClear(['PROVEEDOR', 'NUMERO_FACTURA', 'COD_LIBRO_ADQUISICIONES', 'NUM_RES_EXCENTO_COMPRA', 'NUM_ORDEN_COMPRA'], true);
                        break;
                    case 'TRASLADO':
                        trasladoFields.style.display = 'block';
                        setRequiredAndClear(['UBICACION_ID', 'FECHA_MEMO_CONDUCTOR', 'CORREO_ELECTRONICO_SOLICITANTE'], true);
                        break;
                    case 'MERMA':
                        mermaFields.style.display = 'block';
                        setRequiredAndClear(['FECHA_AUTORIZACION', 'NOMBRE_JEFE_AUTORIZA'], true);
                        break;
                    case 'OTRO':
                        otroFields.style.display = 'block';
                        // Aquí puedes establecer campos específicos como requeridos si es necesario para "OTRO"
                        break;
                }
            }

            tipoMovimientoSelect.addEventListener('change', toggleFields);
            toggleFields(); // Para manejar el valor inicial o cuando se recargue con datos antiguos
        });
        </script>

        <script>
                /**
                 * This script handles the dynamic updating of the correoSelect dropdown based on the selected ubicacionSelect value.
                 * It listens for changes in the ubicacionSelect dropdown and updates the correoSelect dropdown accordingly.
                 * The usuarios variable is a JSON representation of the Laravel collection of users.
                 */
                 document.addEventListener('DOMContentLoaded', function() {
                    const ubicacionSelect = document.getElementById('UBICACION_ID');
                    const correoSelect = document.getElementById('CORREO_ELECTRONICO_SOLICITANTE');
                    const usuarios = @json($usuarios); // Convierte la colección de usuarios de Laravel a JSON

                    // Escuchar cambios en el selector de ubicación
                    ubicacionSelect.addEventListener('change', function() {
                        const ubicacionIdSeleccionada = this.value;
                        actualizarCorreos(ubicacionIdSeleccionada);
                    });

                    function actualizarCorreos(ubicacionId) {
                        // Limpiar opciones existentes
                        correoSelect.innerHTML = '<option value="" disabled selected>-- Seleccione un correo --</option>';

                        // Filtrar usuarios por la ubicación seleccionada y actualizar el selector
                        usuarios.forEach(usuario => {
                            if (usuario.UBICACION_ID == ubicacionId) {
                                const opcion = new Option(`${usuario.USUARIO_NOMBRES} ${usuario.USUARIO_APELLIDOS} (${usuario.email})`, usuario.email);
                                correoSelect.add(opcion);
                            }
                        });
                    }
                });
        </script>
@stop


