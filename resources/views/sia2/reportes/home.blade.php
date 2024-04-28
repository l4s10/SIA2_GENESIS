@extends('adminlte::page')

@section('title', 'Estadísticas Generales')

@section('content_header')
    <h1>Estadística y Reportes Generales del Sistema</h1>
@stop

@section('content')
    {{-- Mensajes de alerta --}}
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

    {{-- Solo rol administrador o servicios --}}
    @role('ADMINISTRADOR|SERVICIOS')
        {{-- Reportes vehiculos --}}
        <div class="card text-bg-primary mb-3">
            <div class="card-header">Módulo de Informes de Vehículos</div>
            <div class="card-body">
                <p class="card-text">Este módulo permite ver los reportes de <strong>Vehiculos</strong>. Estadísticas de uso de vehículos, georreferenciación, este módulo permite acceder a la información relacionada con las solicitudes de salida de vehículos.</p>
            </div>
            <div class="card-footer">
                <a class="btn btn-primary" href="{{route('reportes.vehiculos.index')}}"><i class="fa-solid fa-chart-pie"></i> Graficos</a>
            </div>
        </div>

        {{-- Reportes materiales --}}
        <div class="card text-bg-primary mb-3">
            <div class="card-header">Módulo de Informes sobre Materiales</div>
            <div class="card-body">
                <p class="card-text">Este módulo permite ver los reportes de <strong>Materiales</strong>. Para saber las cantidades de solicitudes de <strong>Materiales</strong> del sistema completo.</p>
            </div>
            <div class="card-footer">
                <a class="btn btn-primary" href="{{route('reportes.materiales.index')}}"><i class="fa-solid fa-chart-pie"></i> Graficos</a>
            </div>
        </div>

        {{-- Reportes reparaciones --}}
        <div class="card text-bg-primary mb-3">
            <div class="card-header">Módulo de Informes de Reparaciones y Mantenimientos</div>
            <div class="card-body">
                <p class="card-text">Este módulo permite ver los reportes de <strong>Reparaciones y Mantenciones</strong>. Para saber las cantidades de solicitudes de <strong>reparaciones y mantenciones</strong> del sistema completo.</p>
            </div>
            <div class="card-footer">
                <a class="btn btn-primary" href="{{route('reportes.reparaciones.index')}}"><i class="fa-solid fa-chart-pie"></i> Graficos</a>
            </div>
        </div>

        {{-- Reportes bodegas --}}
        <div class="card text-bg-primary mb-3">
            <div class="card-header">Módulo de Informes sobre Reservas de Bodegas</div>
            <div class="card-body">
                <p class="card-text">Este módulo permite ver los reportes de <strong>Reservas de Bodegas</strong>. Para saber las cantidades de solicitudes de <strong>Reservas de bodegas</strong> del sistema completo.</p>
            </div>
            <div class="card-footer">
                <a class="btn btn-primary" href="{{route('reportes.bodegas.index')}}"><i class="fa-solid fa-chart-pie"></i> Graficos</a>
            </div>
        </div>
    @endrole

    {{-- Solo rol ADMINISTRADOR|INFORMATICA --}}
    @role('ADMINISTRADOR|INFORMATICA')
        {{-- Reportes equipos --}}
        <div class="card text-bg-primary mb-3">
            <div class="card-header">Módulo de Informes sobre Equipos</div>
            <div class="card-body">
                <p class="card-text">Este módulo permite ver los reportes de <strong>Equipos</strong>. Para saber las cantidades de solicitudes de <strong>Equipos</strong> del sistema completo.</p>
            </div>
            <div class="card-footer">
                <a class="btn btn-primary" href="{{ route('reportes.equipos.index') }}"><i class="fa-solid fa-chart-pie"></i> Graficos</a>
            </div>
        </div>
        {{-- Reportes salas --}}
        <div class="card text-bg-primary mb-3">
            <div class="card-header">Módulo de Informes sobre Reservas de Salas</div>
            <div class="card-body">
                <p class="card-text">Este módulo permite ver los reportes de <strong>Reservas de Salas</strong>. Para saber las cantidades de solicitudes de <strong>Reservas de salas</strong> del sistema completo.</p>
            </div>
            <div class="card-footer">
                <a class="btn btn-primary" href="{{route('reportes.salas.index')}}"><i class="fa-solid fa-chart-pie"></i> Graficos</a>
            </div>
        </div>
    @endrole

    {{-- Solo rol ADMINISTRADOR --}}
    @role('ADMINISTRADOR')
        {{-- Reportes sistema --}}
        <div class="card text-bg-primary mb-3">
            <div class="card-header">Módulo Informes del Sistema</div>
            <div class="card-body">
                <p class="card-text">Este módulo permite ver los reportes del <strong>Sistema</strong>. Para saber las cantidades de solicitudes del <strong>Sistema</strong> del sistema completo.</p>
            </div>
            <div class="card-footer">
                <a class="btn btn-primary" href="{{route('reportes.sistema.index')}}"><i class="fa-solid fa-chart-pie"></i> Graficos</a>
            </div>
        </div>
    @endrole
@stop

@section('css')
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

@stop
