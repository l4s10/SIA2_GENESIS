@extends('adminlte::page')

@section('title', 'Ingresar Vehiculo')

@section('content_header')
    <h1>Ingresar Vehículo</h1>
    <br>
@stop

@section('content')
    <div class="container">
        <form action="{{ route('vehiculos.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="VEHICULO_PATENTE" class="form-label"><i class="fa-solid fa-credit-card"></i> Patente</label>
                        <input id="VEHICULO_PATENTE" name="VEHICULO_PATENTE" type="text" class="form-control" tabindex="1" placeholder="Ej: AB12-34" maxlength="7" tabindex="1" required value="{{ old('VEHICULO_PATENTE') }}">
                        @error('VEHICULO_PATENTE')
                            <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="TIPO_VEHICULO_ID" class="form-label"><i class="fa-solid fa-car-side"></i> Tipo de Vehículo</label>
                        <select name="TIPO_VEHICULO_ID" id="TIPO_VEHICULO_ID" class="form-control" tabindex="2" required>
                            <option value="">-- Seleccione un tipo de vehiculo --</option>
                            @foreach ($tiposVehiculos as $tipoVehiculo)
                                <option value="{{ $tipoVehiculo->TIPO_VEHICULO_ID }}" {{ old('TIPO_VEHICULO_ID') == $tipoVehiculo->TIPO_VEHICULO_ID ? 'selected' : '' }}>
                                    {{ $tipoVehiculo->TIPO_VEHICULO_NOMBRE }}
                                </option>
                            @endforeach
                        </select>
                        @error('TIPO_VEHICULO_ID')
                            <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="VEHICULO_MARCA" class="form-label"><i class="fa-solid fa-car-rear"></i> Marca</label>
                        <input id="VEHICULO_MARCA" name="VEHICULO_MARCA" type="text" class="form-control" tabindex="3" placeholder="Toyota" maxlength="20" required value="{{ old('VEHICULO_MARCA') }}">
                        @error('VEHICULO_MARCA')
                            <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="VEHICULO_MODELO" class="form-label"><i class="fa-solid fa-car-on"></i> Modelo</label>
                        <input id="VEHICULO_MODELO" name="VEHICULO_MODELO" type="text" class="form-control" tabindex="4" placeholder="Corolla" maxlength="20" required value="{{ old('VEHICULO_MODELO') }}">
                        @error('VEHICULO_MODELO')
                            <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="VEHICULO_ANO" class="form-label"><i class="fa-regular fa-calendar-days"></i> Año</label>
                        <input type="number" min="2000" step="1" id="VEHICULO_ANO" name="VEHICULO_ANO" placeholder="(2000 - Año actual)" tabindex="5" required class="form-control" value="{{ old('VEHICULO_ANO') }}">
                        @error('VEHICULO_ANO')
                            <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="OFICINA" class="form-label"><i class="fa-solid fa-map-location-dot"></i> Dirección Regional </label>
                        <select id="OFICINA" class="form-control" name="OFICINA" tabindex="6" required disabled>
                            <option value="{{ $oficinaAsociada->OFICINA_ID }}">{{ $oficinaAsociada->OFICINA_NOMBRE }}</option>
                        </select>
                        @error('ID_REGION')
                            <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="DEPENDENCIA_ID" class="form-label"><i class="fa-solid fa-street-view"></i> Ubicación/Departamento </label>
                        <select id="DEPENDENCIA_ID" class="form-control" name="DEPENDENCIA_ID" tabindex="7" required>
                            <option value="" disabled selected>-- Seleccione una ubicación o departamento --</option>

                            <optgroup label="Ubicaciones">
                                @foreach ($ubicacionesLocales as $ubicacion)
                                    <option value="{{ $ubicacion->UBICACION_ID }}" {{ old('DEPENDENCIA_ID') == $ubicacion->UBICACION_ID ? 'selected' : '' }}>
                                        {{ $ubicacion->UBICACION_NOMBRE }}
                                    </option>
                                @endforeach
                            </optgroup>

                            <optgroup label="Departamentos">
                                @foreach ($departamentosLocales as $departamento)
                                    <option value="{{ $departamento->DEPARTAMENTO_ID }}" {{ old('DEPENDENCIA_ID') == $departamento->DEPARTAMENTO_ID ? 'selected' : '' }}>
                                        {{ $departamento->DEPARTAMENTO_NOMBRE }}
                                    </option>
                                @endforeach
                            </optgroup>
                        </select>
                        @error('ID_DIRECCION_UBICACION')
                            <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="VEHICULO_ESTADO" class="form-label"><i class="fa-solid fa-square-check"></i> Estado</label>
                        <select name="VEHICULO_ESTADO" id="VEHICULO_ESTADO" class="form-control" tabindex="10" required>
                            <option value="">-- Seleccione un estado --</option>
                            <option value="DISPONIBLE" {{ old('VEHICULO_ESTADO') == 'DISPONIBLE' ? 'selected' : '' }}>🟢 DISPONIBLE</option>
                            <option value="NO DISPONIBLE" {{ old('VEHICULO_ESTADO') == 'NO DISPONIBLE' ? 'selected' : '' }}>🔴 NO DISPONIBLE</option>
                        </select>
                        @error('VEHICULO_ESTADO')
                            <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="VEHICULO_NIVEL_ESTANQUE" class="form-label"><i class="fa-solid fa-gas-pump"></i> Nivel de Estanque</label>
                        <select id="VEHICULO_NIVEL_ESTANQUE" name="VEHICULO_NIVEL_ESTANQUE" class="form-control" tabindex="9" required>
                            <option value="">-- Seleccione un nivel de estanque --</option>
                            <option value="VACÍO" {{ old('VEHICULO_NIVEL_ESTANQUE') == 'VACÍO' ? 'selected' : '' }}>VACÍO</option>
                            <option value="BAJO" {{ old('VEHICULO_NIVEL_ESTANQUE') == 'BAJO' ? 'selected' : '' }}>BAJO</option>
                            <option value="MEDIO BAJO" {{ old('VEHICULO_NIVEL_ESTANQUE') == 'MEDIO BAJO' ? 'selected' : '' }}>MEDIO BAJO</option>
                            <option value="MEDIO" {{ old('VEHICULO_NIVEL_ESTANQUE') == 'MEDIO' ? 'selected' : '' }}>MEDIO</option>
                            <option value="MEDIO LLENO" {{ old('VEHICULO_NIVEL_ESTANQUE') == 'MEDIO LLENO' ? 'selected' : '' }}>MEDIO LLENO</option>
                            <option value="LLENO" {{ old('VEHICULO_NIVEL_ESTANQUE') == 'LLENO' ? 'selected' : '' }}>LLENO</option>
                        </select>
                        @error('VEHICULO_NIVEL_ESTANQUE')
                            <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            <br>
           
            <br>
            <div class="row">
                <div class="col-md-12">
                    <a href="{{ route('vehiculos.index') }}" class="btn btn-secondary" tabindex="11"><i class="fa-solid fa-hand-point-left"></i> Cancelar</a>
                    <button type="submit" class="btn agregar" tabindex="12"><i class="fa-solid fa-floppy-disk"></i> Guardar Vehículo</button>
                </div>
            </div>
        </form>
    </div>
@stop


@section('css')
    <style>
        .agregar{
            background-color: #e6500a;
            color: #fff;
        }
    </style>
@stop

@section('js')
    {{-- Script cooldown envio formulario (evita entradas repetidas) --}}
    <script src="{{ asset('js/Components/cooldownSendForm.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    {{-- Establecer el año máximo como el año actual, para el input VEHICULO_ANO --}}
    <script>
        // Obtener el elemento de entrada del año
        let yearInput = document.getElementById('VEHICULO_ANO');

        // Obtener el año actual
        let currentYear = new Date().getFullYear();

        // Establecer el atributo max al año actual
        yearInput.setAttribute('max', currentYear);
    </script>
@stop
