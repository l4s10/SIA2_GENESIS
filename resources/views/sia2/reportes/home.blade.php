@extends('adminlte::page')

@section('title', 'Estadísticas Generales')

@section('content_header')
    <h1>Estadística y Reportes Generales del Sistema</h1>
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
    <div class="card text-bg-primary mb-3">
        <div class="card-header">Módulo de Informes de vehículos</div>
        <div class="card-body">
            <p class="card-text">Este módulo permite ver los reportes de <strong>Vehiculos</strong>. Estadísticas de uso de vehículos, georreferenciación, este módulo permite acceder a la información relacionada con las solicitudes de salida de vehículos.</p>
        </div>
        <div class="card-footer">
            <a class="btn btn-primary" href=" "><i class="fa-solid fa-chart-pie"></i> Graficos</a>
        </div>
    </div>
    <div class="card text-bg-primary mb-3">
        <div class="card-header">Módulo de Informes sobre Materiales</div>
        <div class="card-body">
            <p class="card-text">Este módulo permite ver los reportes de <strong>Materiales</strong>. Para saber las cantidades de solicitudes de <strong>Materiales</strong> del sistema completo.</p>
        </div>
        <div class="card-footer">
            <a class="btn btn-primary" href=" "><i class="fa-solid fa-chart-pie"></i> Graficos</a>
        </div>
    </div>
    <div class="card text-bg-primary mb-3">
        <div class="card-header">Módulo de Informes de Reparaciones y Mantenimientos</div>
        <div class="card-body">
            <p class="card-text">Este módulo permite ver los reportes de <strong>reparaciones y mantenciones</strong>. Para saber las cantidades de solicitudes de <strong>reparaciones y mantenciones</strong> del sistema completo.</p>
        </div>
        <div class="card-footer">
            <a class="btn btn-primary" href=" "><i class="fa-solid fa-chart-pie"></i> Graficos</a>
        </div>
    </div>
    <div class="card text-bg-primary mb-3">
        <div class="card-header">Módulo de Informes sobre equipos</div>
        <div class="card-body">
            <p class="card-text">Este módulo permite ver los reportes de <strong>Equipos</strong>. Para saber las cantidades de solicitudes de <strong>Equipos</strong> del sistema completo.</p>
        </div>
        <div class="card-footer">
            <a class="btn btn-primary" href=" "><i class="fa-solid fa-chart-pie"></i> Graficos</a>
        </div>
    </div>
    <div class="card text-bg-primary mb-3">
        <div class="card-header">Módulo de Informes sobre reservas de salas</div>
        <div class="card-body">
            <p class="card-text">Este módulo permite ver los reportes de <strong>Reservas de salas</strong>. Para saber las cantidades de solicitudes de <strong>Reservas de salas</strong> del sistema completo.</p>
        </div>
        <div class="card-footer">
            <a class="btn btn-primary" href=" "><i class="fa-solid fa-chart-pie"></i> Graficos</a>
        </div>
    </div>

    <div class="card text-bg-primary mb-3" style="max-width: 100%; text-align: justify;">
        <div class="card-header">Módulo de Informes sobre inventario</div>
        <div class="card-body">
            <p class="card-text">Este módulo permite ver los reportes de <strong>Inventario</strong>. Para saber las cantidades de solicitudes de <strong>Inventario</strong> del sistema completo.</p>
        </div>
        <div class="card-footer">
            <a class="btn btn-primary" href=" "><i class="fa-solid fa-chart-pie"></i> Graficos</a>
        </div>
    </div>

    <div class="card text-bg-primary mb-3">
        <div class="card-header">Módulo Informes del sistema</div>
        <div class="card-body">
            <p class="card-text">Este módulo permite ver los reportes del <strong>Sistema</strong>. Para saber las cantidades de solicitudes del <strong>Sistema</strong> del sistema completo.</p>
        </div>
        <div class="card-footer">
            <a class="btn btn-primary" href=" "><i class="fa-solid fa-chart-pie"></i> Graficos</a>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.2/css/dataTables.bootstrap5.min.css">
    <style>
    .alert {
    opacity: 0.7; /* Ajusta la opacidad a tu gusto */
    background-color: #99CCFF; /* Color de fondo del aviso */
    color: 	#000000;
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
@stop

@section('js')
    <!-- CONEXION FONT-AWESOME CON TOOLKIT -->
    <script src="https://kit.fontawesome.com/742a59c628.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
    <!-- Agregando funciones de paginacion, busqueda, etc -->
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
        <script src="https://cdn.datatables.net/1.13.2/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.2/js/dataTables.bootstrap5.min.js"></script>

        <!-- Para inicializar -->
        <script>
            $(document).ready(function () {
                $('#materiales').DataTable({
                    "lengthMenu": [[5,10, 50, -1], [5, 10, 50, "All"]]
                });
            });
        </script>

@stop
