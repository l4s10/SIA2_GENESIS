function agregarFila(numeroFila) {
    let pasajeros = document.getElementById('pasajeros');
    let nuevaFila = document.createElement('div');
    nuevaFila.classList.add('pasajero-row', 'border', 'p-3', 'mb-3');
    nuevaFila.id = 'fila_' + numeroFila;

    let contenidoHTML = `
        <h5>Pasajero N°${numeroFila - 1}</h5>
        <div class="form-row">
            <div class="form-group col-md-4">
                <label for="oficina_${numeroFila}">Oficina</label>
                <select id="oficina_${numeroFila}" class="form-control oficina" data-row="${numeroFila}" required>
                    <option style="text-align: center;" value="">-- Seleccione una opción --</option>
                    @foreach($oficinas as $oficina)
                        <option value="{{ $oficina->OFICINA_ID }}">{{ $oficina->OFICINA_NOMBRE }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-md-4">
                <label for="dependencia_${numeroFila}">Ubicación o Departamento</label>
                <select id="dependencia_${numeroFila}" class="form-control dependencia" data-row="${numeroFila}" disabled required>
                    <option style="text-align: center;" value="">-- Seleccione una opción --</option>
                    <optgroup label="Ubicaciones">
                        @foreach($ubicaciones as $ubicacion)
                            <option value="{{ $ubicacion->UBICACION_ID }}" data-office-id="{{ $ubicacion->OFICINA_ID }}">{{ $ubicacion->UBICACION_NOMBRE }}</option>
                        @endforeach
                    </optgroup>
                    <optgroup label="Departamentos">
                        @foreach($departamentos as $departamento)
                            <option value="{{ $departamento->DEPARTAMENTO_ID }}" data-office-id="{{ $departamento->OFICINA_ID }}">{{ $departamento->DEPARTAMENTO_NOMBRE }}</option>
                        @endforeach
                    </optgroup>
                </select>
            </div>
            <div class="form-group col-md-4">
                <label for="pasajero${numeroFila}">Funcionario</label>
                <select id="pasajero${numeroFila}" class="form-control pasajero" name="PASAJERO_${numeroFila}" data-row="${numeroFila}" disabled required>
                    <option style="text-align: center;" value="">-- Seleccione al pasajero N°${numeroFila - 1} --</option>
                    <optgroup label="Funcionarios Asociados">
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" data-ubicacion-id="{{ $user->UBICACION_ID }}" data-departamento-id="{{ $user->DEPARTAMENTO_ID }}">{{ $user->USUARIO_NOMBRES }} {{ $user->USUARIO_APELLIDOS }}</option>
                        @endforeach
                    </optgroup>
                </select>
            </div>
        </div>
    `;

    nuevaFila.innerHTML = contenidoHTML;
    pasajeros.appendChild(nuevaFila);