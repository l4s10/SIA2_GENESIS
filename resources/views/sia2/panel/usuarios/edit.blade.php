@extends('adminlte::page')

<!-- TITULO DE LA PESTAÑA -->
@section('title', 'Editar Funcionario')

<!-- CABECERA DE LA PAGINA -->
@section('content_header')
    <h1>Editar Funcionario</h1>
@stop

@section('content')
    <div class="container">
        <form action="{{ route('panel.usuarios.update', $usuario->id) }}" method="post">
            @csrf
            @method('PUT')

            {{-- Datos de la persona --}}
            <br>
            <h4>Datos de Personales</h4>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="USUARIO_NOMBRES"><i class="fa-solid fa-id-card"></i> Nombres:</label>
                        <input type="text" name="USUARIO_NOMBRES" id="USUARIO_NOMBRES" class="form-control @error('USUARIO_NOMBRES') is-invalid @enderror" placeholder="Ej: Primer nombre Segundo nombre" value="{{ $usuario->USUARIO_NOMBRES }}" required autofocus>
                        @error('USUARIO_NOMBRES')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="USUARIO_APELLIDOS"><i class="fa-solid fa-id-card"></i> Apellidos:</label>
                        <input type="text" name="USUARIO_APELLIDOS" id="USUARIO_APELLIDOS" class="form-control @error('USUARIO_APELLIDOS') is-invalid @enderror" placeholder="Ej: Apellido Paterno Apellido Materno" value="{{ $usuario->USUARIO_APELLIDOS }}" required autofocus>
                        @error('USUARIO_APELLIDOS')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="USUARIO_RUT"><i class="fa-solid fa-id-card"></i> Rut:</label>
                        <input type="text" name="USUARIO_RUT" id="USUARIO_RUT" class="form-control @error('USUARIO_RUT') is-invalid @enderror" placeholder="Ej: 12345678-9" value="{{ $usuario->USUARIO_RUT }}" required>
                        @error('USUARIO_RUT')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Correo electrónico --}}
            <div class="row">
                <div class="col-md-3">
                    {{-- Fecha de nacimiento field --}}
                    <div class="form-group">
                        <label for="USUARIO_FECHA_NAC"><i class="fa-solid fa-calendar-days"></i> Fecha de nacimiento:</label>
                        <input type="date" id="USUARIO_FECHA_NAC" name="USUARIO_FECHA_NAC" class="form-control @error('USUARIO_FECHA_NAC') is-invalid @enderror" value="{{ $usuario->USUARIO_FECHA_NAC }}" required style="text-align: center;">
                        @error('USUARIO_FECHA_NAC')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="USUARIO_SEXO"><i class="fa-solid fa-person-half-dress"></i> Sexo:</label>
                        <select name="USUARIO_SEXO" class="form-control @error('USUARIO_SEXO') is-invalid @enderror" required>
                            <option value="" style="text-align: center;" selected disabled>{{ __('-- Seleccione una opción --') }}</option>
                            <option value="FEMENINO" {{ $usuario->USUARIO_SEXO == 'FEMENINO' ? 'selected' : '' }}>{{ __('FEMENINO') }}</option>
                            <option value="MASCULINO" {{ $usuario->USUARIO_SEXO == 'MASCULINO' ? 'selected' : '' }}>{{ __('MASCULINO') }}</option>
                            <option value="OTRO" {{ $usuario->USUARIO_SEXO == 'OTRO' ? 'selected' : '' }}>{{ __('OTRO') }}</option>
                        </select>
                        @error('USUARIO_SEXO')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="email"><i class="fa-solid fa-envelope"></i> Email:</label>
                        <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ $usuario->email }}" required>
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-3">
                    {{-- Fecha de ingreso a la empresa field --}}
                    <div class="form-group">
                        <label for="USUARIO_FECHA_INGRESO"><i class="fa-solid fa-calendar-days"></i> Fecha de ingreso al SII:</label>
                        <input type="date" id="USUARIO_FECHA_INGRESO" name="USUARIO_FECHA_INGRESO" class="form-control @error('USUARIO_FECHA_INGRESO') is-invalid @enderror" value="{{ $usuario->USUARIO_FECHA_INGRESO }}" required style="text-align: center;">
                        @error('USUARIO_FECHA_INGRESO')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
            </div>


            <br>
            {{-- Contraseña y confirmación de contraseña --}}
            <h4>Contraseña</h4>
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label for="password"><i class="fa-solid fa-key"></i> Nueva Contraseña:</label>
                        <input type="password" name="password" id="password" class="form-control" placeholder="Nueva Contraseña">
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label for="password_confirmation"><i class="fa-solid fa-key"></i> Confirmar nueva contraseña:</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Confirmar nueva contraseña">
                    </div>
                </div>
            </div>


            {{-- !!REGION Y DIRECCION REGIONAL --}}
            <br>
            <h4>Dependencia Regional</h4>
            <div class="row">
                <div class="form-group col-md-6">
                    <label for="oficina"><i class="fa-solid fa-street-view"></i> Dirección regional:</label>
                    <select id="oficina" name="oficina" class="form-control oficina" required>
                        <option style="text-align: center;" value="">-- Seleccione una opción --</option>
                        @foreach($oficinas as $oficina)
                            <option value="{{ $oficina->OFICINA_ID }}" data-oficina="{{ $oficina->OFICINA_ID }}" {{ isset($usuario->oficina) && $usuario->oficina->OFICINA_ID == $oficina->OFICINA_ID ? 'selected' : '' }}>
                                {{ $oficina->OFICINA_NOMBRE }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label for="dependencia"><i class="fa-solid fa-building-user"></i> Ubicación o departamento:</label>
                    <select id="dependencia" name="dependencia" class="form-control dependencia" required>
                        <option style="text-align: center;" value="">-- Seleccione una opción --</option>
                        <optgroup label="Ubicaciones">
                            @foreach($ubicaciones as $ubicacion)
                                <option value="{{ $ubicacion->UBICACION_ID }}" data-oficina="{{ $ubicacion->OFICINA_ID }}" {{ isset($usuario->ubicacion) && $usuario->ubicacion->UBICACION_ID == $ubicacion->UBICACION_ID ? 'selected' : '' }}>
                                    {{ $ubicacion->UBICACION_NOMBRE }}
                                </option>
                            @endforeach
                        </optgroup>
                        <optgroup label="Departamentos">
                            @foreach($departamentos as $departamento)
                                <option value="{{ $departamento->DEPARTAMENTO_ID }}" data-oficina="{{ $departamento->OFICINA_ID }}" {{ isset($usuario->departamento) && $usuario->departamento->DEPARTAMENTO_ID == $departamento->DEPARTAMENTO_ID ? 'selected' : '' }}>
                                    {{ $departamento->DEPARTAMENTO_NOMBRE }}
                                </option>
                            @endforeach
                        </optgroup>
                    </select>
                </div>
                <input type="hidden" name="tipo_dependencia" id="tipo_dependencia" value="">

            </div>

            {{-- Datos de asociación --}}
            <br>
            <h4>Datos de Asociación</h4>
            <div class="row">
                <div class="col-md-3">
                    {{-- Grupo field --}}
                    <div class="form-group">
                        <label for="GRUPO_ID"><i class="fa-solid fa-user-group"></i> Grupo:</label>
                        <select name="GRUPO_ID" id="GRUPO_ID" class="form-control @error('GRUPO_ID') is-invalid @enderror" required autofocus>
                            <option value="" style="text-align: center;" disabled selected>-- Seleccione un grupo --</option>
                            @foreach ($grupos as $grupo)
                                <option value="{{ $grupo->GRUPO_ID }}" data-oficina="{{ $grupo->OFICINA_ID }}" {{ $usuario->grupo->GRUPO_ID == $grupo->GRUPO_ID ? 'selected' : '' }}>{{ $grupo->GRUPO_NOMBRE }}</option>
                            @endforeach
                        </select>
                        @error('GRUPO_ID')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-3">
                    {{-- Calidad Jurídica --}}
                    <div class="form-group">
                        <label for="USUARIO_CALIDAD_JURIDICA"><i class="fa-solid fa-pen-to-square"></i> Calidad Jurídica:</label>
                        <select name="USUARIO_CALIDAD_JURIDICA" id="USUARIO_CALIDAD_JURIDICA" class="form-control @error('USUARIO_CALIDAD_JURIDICA') is-invalid @enderror" required>
                            <option value="" style="text-align: center;" selected disabled>{{ __('-- Seleccione una opción --') }}</option>
                            <option value="PLANTA" {{ $usuario->USUARIO_CALIDAD_JURIDICA == 'PLANTA' ? 'selected' : '' }}>{{ __('PLANTA') }}</option>
                            <option value="CONTRATA" {{ $usuario->USUARIO_CALIDAD_JURIDICA == 'CONTRATA' ? 'selected' : '' }}>{{ __('CONTRATA') }}</option>
                        </select>
                        @error('USUARIO_CALIDAD_JURIDICA')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-3">
                    {{-- Grado field --}}
                    <div class="form-group">
                        <label for="GRADO_ID"><i class="fa-solid fa-layer-group"></i> Grado:</label>
                        <select name="GRADO_ID" id="GRADO_ID" class="form-control @error('GRADO_ID') is-invalid @enderror" required>
                            <option value="" style="text-align: center;" selected disabled>-- Seleccione un grado --</option>
                            @foreach($grados as $grado)
                                <option value="{{ $grado->GRADO_ID }}" data-oficina="{{ $grado->OFICINA_ID }}" {{ $usuario->grado->GRADO_ID == $grado->GRADO_ID ? 'selected' : '' }}>{{ $grado->GRADO_NUMERO }}</option>
                            @endforeach
                        </select>
                        @error('GRADO_ID')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    {{-- Escalafon field --}}
                    <div class="form-group">
                        <label for="ESCALAFON_ID"><i class="fa-solid fa-layer-group"></i> Escalafón:</label>
                        <select name="ESCALAFON_ID" id="ESCALAFON_ID" class="form-control @error('ESCALAFON_ID') is-invalid @enderror" required>
                            <option value="" style="text-align: center;" selected disabled>-- Seleccione un escalafón --</option>
                            @foreach($escalafones as $escalafon)
                                <option value="{{ $escalafon->ESCALAFON_ID }}" data-oficina="{{ $escalafon->OFICINA_ID }}" {{ $usuario->ESCALAFON_ID == $escalafon->ESCALAFON_ID ? 'selected' : '' }}>{{ $escalafon->ESCALAFON_NOMBRE }}</option>
                            @endforeach
                        </select>
                        @error('ESCALAFON_ID')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="CARGO_ID"><i class="fa-solid fa-person-circle-check"></i> Cargo:</label>
                        <select name="CARGO_ID" id="CARGO_ID" class="form-control @error('CARGO_ID') is-invalid @enderror" required>
                            <option value="" selected>Seleccione un cargo</option>
                            @foreach ($cargos as $cargo)
                                <option value="{{ $cargo->CARGO_ID }}" data-oficina="{{ $cargo->OFICINA_ID }}" {{ old('CARGO_ID', $usuario->CARGO_ID) == $cargo->CARGO_ID ? 'selected' : '' }}>{{ $cargo->CARGO_NOMBRE }}</option>
                            @endforeach
                        </select>
                        @error('CARGO_ID')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    {{-- Anexo field --}}
                    <div class="form-group">
                        <label for="USUARIO_ANEXO"><i class="fa-regular fa-id-badge"></i> Anexo:</label>
                        <input type="text" name="USUARIO_ANEXO" class="form-control @error('USUARIO_ANEXO') is-invalid @enderror"
                            value="{{ old('USUARIO_ANEXO', $usuario->USUARIO_ANEXO) }}" placeholder="{{ __('Ej: 9999') }}" required autofocus>
            
                        @error('USUARIO_ANEXO')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    {{-- Fono field --}}
                    <div class="form-group">
                        <label for="USUARIO_FONO"><i class="fa-solid fa-phone"></i> Fono:</label>
                        <input type="text" name="USUARIO_FONO" class="form-control @error('USUARIO_FONO') is-invalid @enderror"
                            value="{{ old('USUARIO_FONO', $usuario->USUARIO_FONO) }}" placeholder="{{ __('Ej: 41 123 456') }}" required autofocus>
            
                        @error('USUARIO_FONO')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
            </div>

            
            <br>

            {{-- Niveles --}}
            <h4>Privilegios</h4>

            {{-- !!ROL --}}
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="role"><i class="fa-solid fa-address-book"></i> Rol en sistema:</label>
                        <select name="role" id="role" class="form-control" required>
                            <option value="" style="text-align: center;" disabled selected>-- Seleccione un rol --</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}" {{ $usuario->roles->pluck('id')->contains($role->id) ? 'selected' : '' }}>{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
            
            </div>
            <br>
            <br>

            <div class="form-group">
                <a href="{{route('panel.usuarios.index')}}" class="btn btn-secondary"><i class="fa-solid fa-hand-point-left"></i> Cancelar</a>
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> Guardar cambios</button>
            </div>
        </form>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    {{-- Probando colores personalizados --}}
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/custom.css') }}">
@endsection

@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>
{{-- !!SCRIPT DE FILTROS (SE OBTENDRA DIRECCION REGIONAL SEGUN LA REGION SELECCIONADA) --}}
<script>
    $(document).ready(function () {
        // Configuración de Flatpickr para las fechas
        let flatpickrConfig = {
            locale: 'es',
            maxDate: "today",
            dateFormat: "Y-m-d",
            altFormat: "d-m-Y",
            altInput: true,
            allowInput: true,
            minDate: "1940-01-01",
            maxDate: new Date(new Date().getFullYear() - 18, 11, 31).toISOString().split('T')[0],
        };

        // Validar fecha de ingreso a partir del día actual. 31 días antes para ingresos tardíos y 31 días después para ingresos futuros.
        let currentDate = new Date();
        let fechaInicio = new Date(currentDate.getFullYear(), currentDate.getMonth(), currentDate.getDate() - 31);
        let fechaFin = new Date(currentDate.getFullYear(), currentDate.getMonth(), currentDate.getDate() + 31);

        let flatpickrConfig2 = {
            locale: 'es',
            minDate: fechaInicio.toISOString().split('T')[0],
            maxDate: fechaFin.toISOString().split('T')[0],
            dateFormat: "Y-m-d",
            altFormat: "d-m-Y",
            altInput: true,
            allowInput: true,
        };


        // Inicializar Flatpickr en los campos de fecha
        $('#USUARIO_FECHA_NAC').flatpickr(flatpickrConfig);
        $('#USUARIO_FECHA_INGRESO').flatpickr(flatpickrConfig2);
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectores = document.querySelectorAll('#dependencia, #GRUPO_ID, #GRADO_ID, #ESCALAFON_ID, #CARGO_ID');
        const opcionesOriginales = {};
        
        // Almacenar las opciones originales de cada selector
        selectores.forEach(selector => {
            opcionesOriginales[selector.id] = Array.from(selector.querySelectorAll('optgroup, option'));
            selector.disabled = true;
        });
        
        const selectorOficina = document.getElementById('oficina');
        selectorOficina.addEventListener('change', function() {
            const oficinaSeleccionada = this.value;
            if (oficinaSeleccionada !== '') {
                // Habilitar todos los selectores
                selectores.forEach(selector => {
                    selector.disabled = false;
                });

                // Filtrar las opciones de cada selector según la oficina seleccionada
                selectores.forEach(selector => {
                    const opcionesFiltradas = [];
                    const gruposAñadidos = new Set();
                    const opcionesÚnicas = new Set(); // Conjunto global para evitar duplicados en todo el selector
                    opcionesOriginales[selector.id].forEach(opcion => {
                        if (opcion.tagName.toLowerCase() === 'optgroup') {
                            // Clonar grupos de opciones
                            const grupoClonado = opcion.cloneNode(false);
                            // Filtrar opciones dentro del grupo según la oficina seleccionada
                            const opcionesGrupoFiltradas = Array.from(opcion.querySelectorAll('option')).filter(opt => opt.dataset.oficina === oficinaSeleccionada || opt.dataset.oficina === undefined);
                            if (opcionesGrupoFiltradas.length > 0 && !gruposAñadidos.has(grupoClonado.label)) {
                                opcionesGrupoFiltradas.forEach(opcionFiltrada => {
                                    if (!opcionesÚnicas.has(opcionFiltrada.value)) {
                                        grupoClonado.appendChild(opcionFiltrada.cloneNode(true));
                                        opcionesÚnicas.add(opcionFiltrada.value);
                                    }
                                });
                                opcionesFiltradas.push(grupoClonado);
                                gruposAñadidos.add(grupoClonado.label);
                            }
                        } else {
                            // Filtrar opciones individuales según la oficina seleccionada
                            if (opcion.dataset.oficina === oficinaSeleccionada || opcion.dataset.oficina === undefined) {
                                if (!opcionesÚnicas.has(opcion.value)) {
                                    opcionesFiltradas.push(opcion.cloneNode(true));
                                    opcionesÚnicas.add(opcion.value);
                                }
                            }
                        }
                    });
                    // Actualizar las opciones del selector
                    actualizarOpciones(selector, opcionesFiltradas);
                });
            } else {
                // Si no se selecciona ninguna oficina, deshabilitar todos los selectores y restaurar las opciones originales
                selectores.forEach(selector => {
                    selector.disabled = true;
                    actualizarOpciones(selector, opcionesOriginales[selector.id]);
                });
            }
        });
    });

    // Función para actualizar las opciones de un selector
    function actualizarOpciones(selector, opciones) {
        selector.innerHTML = '';
        opciones.forEach(opcion => {
            selector.appendChild(opcion);
        });
    }
</script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        var selectDependencia = document.getElementById("dependencia");
        var hiddenTipoDependencia = document.getElementById("tipo_dependencia");

        selectDependencia.addEventListener("change", function() {
            var tipoDependencia = selectDependencia.options[selectDependencia.selectedIndex].parentNode.label;
            hiddenTipoDependencia.value = tipoDependencia;
        });
    });
</script>

<script>
    // Esperar a que el documento esté completamente cargado
document.addEventListener("DOMContentLoaded", function() {
    // Obtener el botón "Guardar cambios"
    var guardarCambiosBtn = document.querySelector('.btn-primary');

    // Agregar un event listener para el evento click en el botón
    guardarCambiosBtn.addEventListener('click', function() {
        // Activar los selectores cuando se hace clic en el botón "Guardar cambios"
        var selectores = document.querySelectorAll('select');
        selectores.forEach(function(selector) {
            selector.disabled = false; // Activar el selector
        });
    });
});

</script>



@endsection
