@extends('adminlte::page')

<!-- TITULO DE LA PESTAÑA -->
@section('title', 'Modificar dirección regional')

<!-- CABECERA DE LA PAGINA -->
@section('content_header')
    <h1>Editar Dirección Regional</h1>
@stop

@section('content')
<div class="container">
    <form action="{{ route('panel.oficinas.update', $oficina->OFICINA_ID) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="OFICINA_NOMBRE" class="form-label"><i class="fa-solid fa-book-bookmark"></i> Nombre de la dirección regional:</label>
            <input type="text" name="OFICINA_NOMBRE" id="OFICINA_NOMBRE" class="form-control{{ $errors->has('OFICINA_NOMBRE') ? ' is-invalid' : '' }}" placeholder="Nombre de la dirección regional" value="{{ $oficina->OFICINA_NOMBRE }}" required autofocus>
        
            @if($errors->has('OFICINA_NOMBRE'))
                <div class="invalid-feedback">
                    {{ $errors->first('OFICINA_NOMBRE') }}
                </div>
            @endif
        </div>
        
        <div class="row">
            <div class="col">
                <div class="mb-3">
                    <label for="REGION_ID" class="form-label"><i class="fa-solid fa-book-bookmark"></i> Región asociada:</label>
                    <select id="REGION_ID" name="REGION_ID" class="form-control{{$errors->has('REGION_ID') ? ' is-invalid' : '' }}" required>
                        <option value=""  style="text-align: center;">-- Seleccione una región --</option>
                        @foreach($regiones as $region)
                            <option value="{{ $region->REGION_ID }}" {{ $oficina->comuna->REGION_ID == $region->REGION_ID ? 'selected' : '' }}>{{ $region->REGION_NOMBRE }}</option>
                        @endforeach
                    </select>
                    @if($errors->has('REGION_ID'))
                    <div class="invalid-feedback">
                        {{$errors->first('REGION_ID')}}
                    </div>
                    @endif

                </div>
            </div>
            <div class="col">
                <div class="mb-3">
                    <label for="COMUNA_ID" class="form-label"><i class="fa-solid fa-book-bookmark"></i> Comuna:</label>
                    <select name="COMUNA_ID" id="COMUNA_ID" class="form-control{{$errors->has('COMUNA_ID') ? ' is-invalid' : '' }}" required>
                        <option value="" style="text-align: center;">-- Seleccione una comuna --</option>
                        @foreach($comunas as $comuna)
                            <option value="{{ $comuna->COMUNA_ID }}" data-region="{{ $comuna->REGION_ID }}" {{ $oficina->COMUNA_ID == $comuna->COMUNA_ID ? 'selected' : '' }}>{{ $comuna->COMUNA_NOMBRE }}</option>
                        @endforeach
                    </select>
                    @if($errors->has('COMUNA_ID'))
                    <div class="invalid-feedback">
                        {{$errors->first('COMUNA_ID')}}
                    </div>
                    @endif

                </div>
            </div>
        </div>
        <div class="form-group">
            <a href="{{ route('panel.oficinas.index') }}" class="btn btn-secondary" tabindex="5"><i class="fa-solid fa-hand-point-left"></i> Cancelar</a>
            <button type="submit" class="btn btn-primary" tabindex="4"><i class="fa-solid fa-floppy-disk"></i> Guardar</button>
        </div>
    </form>
</div>
@endsection

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@endsection


@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Obtener los elementos select
            var regionSelect = document.getElementById('REGION_ID');
            var comunaSelect = document.getElementById('COMUNA_ID');

            // Almacenar el valor seleccionado para la comuna antes de limpiar las opciones del selector de comunas
            var selectedComunaId = comunaSelect.value;

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

                // Restaurar el valor seleccionado para la comuna después de agregar las opciones
                comunaSelect.value = selectedComunaId;
            });

            // Al cargar la página, si hay una región seleccionada, cargar las comunas correspondientes
            if (regionSelect.value) {
                // Simular el evento de cambio de región para cargar las comunas al inicio
                regionSelect.dispatchEvent(new Event('change'));
            }
        });
    </script>
@endsection