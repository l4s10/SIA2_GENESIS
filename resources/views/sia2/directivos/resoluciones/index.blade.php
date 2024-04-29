@extends('adminlte::page')

<!-- TITULO DE LA PESTAÑA -->
@section('title', 'Mostrar resoluciones')

<!-- CABECERA DE LA PAGINA -->
@section('content_header')
    <h1>Listado de Resoluciones</h1>
    @role('ADMINISTRADOR')
    <div class="alert alert-info alert1" role="alert">
    <div><strong>Bienvenido Administrador:</strong> Acceso total al modulo.<div>
    </div>
    @endrole
    @role('INFORMATICA')
    <div class="alert alert-info" role="alert">
    <div><strong>Bienvenido Informatica:</strong> Aqui iria el texto donde le corresponde el rol INFORMATICA.<div>
    </div>
    @endrole
@stop

@section('content')
        @if (session('success'))
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    Swal.fire({
                        icon: 'success',
                        title: '{{ session('success') }}',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#0064A0'
                    });
                });
            </script>
        @elseif (session('error'))
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    Swal.fire({
                        icon: 'error',
                        title: '{{ session('error') }}',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#0064A0'
                    });
                });
            </script>
        @endif
        
        <a class="btn agregar" href="{{ route('resoluciones.create') }}"><i class="fa-solid fa-plus"></i> Agregar Resolucion</a>
        {{--<a href="{{route('resolucion.create')}}" class="btn" style="background-color: #0099FF; color: white;">Ingresar nueva resolución</a>--}}
        <br><br>

        <div class="table custom-table-responsive">
            <table id="resoluciones" class="table table-bordered mt-4 custom-table">
                <thead class="tablacolor">
                    <tr>
                        <th scope="col">Resolución</th>
                        <th scope="col">Fecha</th>
                        <th scope="col">Tipo Resolucion</th>
                        <th scope="col">Firmante</th>
                        <th scope="col">Delegado</th>
                        <th scope="col">Facultades</th>
                        <th scope="col">Ley asociada</th>
                        <th scope="col">Glosa</th>
                        <th scope="col">Observación</th>
                        <th scope="col">Documento</th>
                        <th scope="col">Administrar</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($resoluciones as $resolucion)
                        <tr>
                            <td>
                                <div class="d-flex justify-content-center">
                                    {{ $resolucion->RESOLUCION_NUMERO }}
                                </div>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center">
                                    {{ date('d-m-Y', strtotime($resolucion->RESOLUCION_FECHA)) }}
                                </div>
                            </td>
                            <td>{{ $resolucion->tipoResolucion->TIPO_RESOLUCION_NOMBRE }}</td>
                            <td>
                                <div class="d-flex justify-content-center">
                                    {{ $resolucion->firmante->CARGO_NOMBRE }}
                                </div>
                            </td>
                            <!-- Encontrar la obediencia asociada a esta resolución -->
                            @php
                                $obedienciaResolucion = $obediencias->where('RESOLUCION_ID', $resolucion->RESOLUCION_ID)->first();
                            @endphp


                            @if($obedienciaResolucion)
                                <td>
                                    <div class="d-flex justify-content-center">
                                        {{ $obedienciaResolucion->cargo->CARGO_NOMBRE }}
                                    </div>
                                </td> 
                            @else
                                <td>No hay obediencia asociada</td>
                            @endif

                            <!-- Encontrar las delegaciones asociadas a esta resolución -->
                            @php
                                $delegacionesResolucion = $delegaciones->where('RESOLUCION_ID', $resolucion->RESOLUCION_ID);
                            @endphp

                
                            @if($delegacionesResolucion->isNotEmpty())
                                <td>
                                    @foreach($delegacionesResolucion as $delegacionResolucion)
                                        {!! '<strong>FAC ' . $delegacionResolucion->facultad->FACULTAD_NUMERO . ': </strong>' . $delegacionResolucion->facultad->FACULTAD_NOMBRE .'<br>'!!}<br>
                                    @endforeach
                                </td>
                                <td>
                                    @foreach($delegacionesResolucion as $delegacionResolucion)
                                        {!! '<strong>FAC ' . $delegacionResolucion->facultad->FACULTAD_NUMERO . ': </strong>' . $delegacionResolucion->facultad->FACULTAD_LEY_ASOCIADA .'<br>'!!}<br>
                                    @endforeach
                                </td>
                                <td>
                                    @foreach($delegacionesResolucion as $delegacionResolucion)
                                        <div>   
                                            <span class="glosa-abreviada">{{ substr($delegacionResolucion->facultad->FACULTAD_CONTENIDO, 0, 0) }}</span>
                                            <button class="btn btn-sia-primary btn-block btn-expand" data-glosa="{{ $delegacionResolucion->facultad->FACULTAD_CONTENIDO }}">
                                                <i class="fa-solid fa-square-plus"></i>
                                            </button>
                                            <button class="btn btn-sia-primary btn-block btn-collapse" style="display: none;">
                                                <i class="fa-solid fa-square-minus"></i>
                                            </button>
                                            
                                            <span class="glosa-completa" style="display: none;">
                                                <strong>FAC {{ $delegacionResolucion->facultad->FACULTAD_NUMERO }}: </strong>{{ $delegacionResolucion->facultad->FACULTAD_CONTENIDO }}
                                            </span>        
                                        </div>                           
                                    @endforeach

                                </td>
                            @else
                                <td>No hay facultad asociada</td>
                                <td>No hay ley de facultad asociada</td>
                                <td>No hay contenido de facultad asociado</td>
                             @endif
    
                            <td>
                                <span class="observaciones-abreviada">{{ substr($resolucion->OBSERVACIONES, 0, 0) }}</span>
                                <button class="btn btn-sia-primary btn-block btn-expand-obs" data-obs="{{ $resolucion->RESOLUCION_OBSERVACIONES }}">
                                    <i class="fa-solid fa-square-plus"></i>
                                </button>
                                <button class="btn btn-sia-primary btn-block btn-collapse-obs" style="display: none;">
                                    <i class="fa-solid fa-square-minus"></i>
                                </button>
                                
                                <span class="observaciones-completa" style="display: none;">{{ $resolucion->RESOLUCION_OBSERVACIONES }}</span>
                            </td>
                            <td>
                                @if ($resolucion->RESOLUCION_DOCUMENTO)
                                    <a href="{{ asset('resolucionesPdf/' . $resolucion->RESOLUCION_DOCUMENTO) }}" class="btn btn-sia-primary btn-block" target="_blank">
                                        <i class="fa-solid fa-file-pdf" style="color: green;"></i>
                                    </a>
                                @else
                                    Sin documento
                                @endif
                            </td>
                            <td>
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('resoluciones.edit', $resolucion->RESOLUCION_ID) }}" class="btn botoneditar"><i class="fa-solid fa-pencil"></i></a>
                                    <form action="{{ route('resoluciones.destroy', $resolucion->RESOLUCION_ID) }}" method="POST" style="display: inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger"><i class="fa-solid fa-trash"></i></button>
                                    </form>
                                </div>            
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
@endsection

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
    <style>
        .alert {
        opacity: 0.7; 
        background-color: #99CCFF;
        color:     #000000;
        }
    </style>
    <style>
        .alert1 {
            opacity: 0.7;
            /* Ajusta la opacidad a tu gusto */
            background-color: #FF8C40;
            /* Color naranjo claro (RGB: 255, 214, 153) */
            color: #000000;
        }
    </style>

    <style>/* Estilos personalizados si es necesario */
        .tablacolor {
            background-color: #0064a0; /* Color de fondo personalizado */
            color: #fff; /* Color de texto personalizado */
        }
        .agregar{
            background-color: #e6500a;
            color: #fff;
        }
        .botoneditar{
            background-color: #1aa16b;
            color: #fff;
        }
    </style>

@endsection

@section('js')
    <!-- Para inicializar -->
    <script>
        $(document).ready(function () {
            $('#resoluciones').DataTable({
                "lengthMenu": [[5,10, 50, -1], [5, 10, 50, "All"]],
                "columnDefs": [
                    { "orderable": false, "targets": 10 } // La séptima columna no es ordenable
                ],
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/es_es.json"
                },
            });
        });

        // Agrega evento de clic al botón de expansión
        $('.btn-expand').on('click', function() {
            var glosaAbreviada = $(this).siblings('.glosa-abreviada');
            var glosaCompleta = $(this).siblings('.glosa-completa');
            var btnExpand = $(this);
            var btnCollapse = $(this).siblings('.btn-collapse');
    
            glosaAbreviada.hide();
            glosaCompleta.show();
            btnExpand.hide();
            btnCollapse.show();
        });
        
        // Agrega evento de clic al botón de colapso
        $('.btn-collapse').on('click', function() {
            var glosaAbreviada = $(this).siblings('.glosa-abreviada');
            var glosaCompleta = $(this).siblings('.glosa-completa');
            var btnExpand = $(this).siblings('.btn-expand');
            var btnCollapse = $(this);
    
            glosaAbreviada.show();
            glosaCompleta.hide();
            btnExpand.show();
            btnCollapse.hide();
        });


        // Código para expandir y colapsar las observaciones
        $('.btn-expand-obs').click(function() {
            $(this).hide();
            $(this).siblings('.btn-collapse-obs').show();
            $(this).siblings('.observaciones-abreviada').hide();
            $(this).siblings('.observaciones-completa').show();
        });

        $('.btn-collapse-obs').click(function() {
            $(this).hide();
            $(this).siblings('.btn-expand-obs').show();
            $(this).siblings('.observaciones-abreviada').show();
            $(this).siblings('.observaciones-completa').hide();
        });

    </script>
@endsection