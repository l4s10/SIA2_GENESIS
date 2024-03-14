@extends('adminlte::page')

@section('title', 'Ingreso de Dirección Regional')

@section('content_header')
    <h1>Registrar Dirección Regional</h1>
@stop

@section('content')
<div class="container">
    <form action="{{ route('panel.oficinas.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="OFICINA_NOMBRE" class="form-label"><i class="fa-solid fa-book-bookmark"></i> Nombre de la dirección regional:</label>
            <input type="text" class="form-control{{ $errors->has('OFICINA_NOMBRE') ? ' is-invalid' : '' }}" id="OFICINA_NOMBRE" name="OFICINA_NOMBRE" value="{{ old('OFICINA_NOMBRE') }}" placeholder="Ej: DIRECCIÓN REGIONAL DE ARICA" required>
            @if ($errors->has('OFICINA_NOMBRE'))
                <div class="invalid-feedback">
                    {{ $errors->first('OFICINA_NOMBRE') }}
                </div>
            @endif
        </div>

        <div class="row">
            <div class="col-6">
                <label for="REGION_ID"><i class="fa-solid fa-book-bookmark"></i> Región:</label>
                <select name="REGION_ID" id="REGION_ID" class="form-control @error('REGION_ID') is-invalid @enderror" required>
                    <option value=""  selected style="text-align: center;">-- Seleccione una región --</option>
                    @foreach($regiones as $region)
                        <option value="{{ $region->REGION_ID }}" data-region="{{ $region->REGION_ID }}" {{ old('REGION_ID') == $region->REGION_ID ? 'selected' : '' }}>{{ $region->REGION_NOMBRE }}</option>
                    @endforeach
                </select>
                @error('REGION_ID')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            
            <div class="col-6">
                <label for="COMUNA_ID"><i class="fa-solid fa-book-bookmark"></i> Comuna:</label>
                <select name="COMUNA_ID" id="COMUNA_ID" class="form-control @error('COMUNA_ID') is-invalid @enderror" required>
                    <option value="" disabled selected style="text-align: center;">-- Seleccione una comuna --</option>
                    @foreach($comunas as $comuna)
                        <option value="{{ $comuna->COMUNA_ID }}" data-region="{{ $comuna->REGION_ID }}">{{ $comuna->COMUNA_NOMBRE }}</option>
                    @endforeach
                </select>
                
                @error('COMUNA_ID')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>

        <br>
        <a href="{{route('panel.oficinas.index')}}" class="btn btn-secondary" tabindex="5"><i class="fa-solid fa-hand-point-left"></i> Cancelar</a>
        <button type="submit" class="btn btn-primary" tabindex="4"><i class="fa-solid fa-floppy-disk"></i> Guardar</button>
    </form>
</div>
@stop


@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Obtener los elementos select
            var regionSelect = document.getElementById('REGION_ID');
            var comunaSelect = document.getElementById('COMUNA_ID');

            // Desactivar el selector de comunas al principio
            comunaSelect.disabled = true;

            // Cuando cambia la selección de región
            regionSelect.addEventListener('change', function () {
                var selectedRegionId = this.value;

                // Desactivar el selector de comunas si no hay una región seleccionada
                comunaSelect.disabled = !selectedRegionId;

                // Limpiar opciones anteriores en el selector de comunas
                comunaSelect.innerHTML = '<option value="" style="text-align: center;" selected>-- Seleccione una comuna --</option>';

                // Mostrar solo las comunas que pertenecen a la región seleccionada
                var comunas = {!! json_encode($comunas) !!};
                comunas.forEach(function (comuna) {
                    if (comuna.REGION_ID == selectedRegionId) {
                        var option = document.createElement('option');
                        option.value = comuna.COMUNA_ID;
                        option.textContent = comuna.COMUNA_NOMBRE;
                        comunaSelect.appendChild(option);
                    }
                });
            });
        });
    </script>
@stop
