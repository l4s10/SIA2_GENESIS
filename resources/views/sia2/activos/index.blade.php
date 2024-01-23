@extends('adminlte::page')

@section('title', 'Gestión de Inventarios')

@section('content_header')
    <h1>Menú de Inventarios</h1>
    {{-- Aquí se mantiene la lógica de roles si es necesario --}}
    {{-- ... --}}
@stop

@section('content')
<div class="container">
    {{-- Aquí se agrega la lógica para mostrar mensajes de éxito o error --}}
    {{-- ... --}}
    <div class="container-fluid d-flex justify-content-center align-items-center flex-column" >
    <h4 class="card-subtitle mb-2 text-muted" style="text-align: justify;">Módulos Materiales</h4>
        <div class="row">
        <div class="col-sm-6">
        {{-- Gestión de materiales --}}
        <div class="card text-bg-primary mb-3 mx-auto col-sm-12 col-md-6" style="max-width: 100%; text-align: justify;">
            <div class="card-header">Gestión de Materiales</div>
            <div class="card-body">
                <p class="card-text">En este módulo puedes gestionar los <strong>Materiales</strong>. Agrega, edita o elimina materiales según sea necesario.</p>
            </div>
            <div class="card-footer ">
                {{-- Ajusta las rutas según tu estructura --}}
                <a class="btn btn-primary" href="{{ route('materiales.index') }}"><i class="fa-solid fa-box"></i> Ver Materiales</a>
                <a class="btn guardar" href="{{ route('materiales.create') }}"><i class="fa-solid fa-plus"></i> Agregar Material</a>
            </div>
        </div>
        </div>

        {{-- Gestión de tipos de materiales --}}
        <div class="card text-bg-primary mb-3 mx-auto col-sm-12 col-md-6" style="max-width: 100%; text-align: justify;">
                <div class="card-header">Gestión de Tipos de Materiales</div>
                <div class="card-body">
                    <p class="card-text">En este módulo puedes gestionar los <strong>Tipos de Materiales</strong>. Agrega, edita o elimina tipos de materiales según sea necesario.</p>
                </div>
                <div class="card-footer">
                    {{-- Ajusta las rutas según tu estructura --}}
                    <a class="btn btn-primary" href="{{ route('tiposmateriales.index') }}"><i class="fa-solid fa-tag"></i> Ver Tipos de Materiales</a>
                    <a class="btn guardar" href="{{ route('tiposmateriales.create') }}"><i class="fa-solid fa-plus"></i> Agregar Tipo de Material</a>
                </div>
            </div>
        </div>
        <h4 class="card-subtitle mb-2 text-muted">Módulos Equipos</h4>
        <div class="row">
            <div class="col-sm-6">
        {{-- Gestión de equipos --}}
        <div class="card text-bg-primary mb-3 mx-auto col-sm-12 col-md-6" style="max-width: 100%; text-align: justify;">
            <div class="card-header">Gestión de Equipos</div>
            <div class="card-body">
                <p class="card-text">En este módulo puedes gestionar los <strong>Equipos</strong>. Agrega, edita o elimina materiales según sea necesario.</p>
            </div>
            <div class="card-footer">
                {{-- Ajusta las rutas según tu estructura --}}
                <a class="btn btn-primary" href="{{ route('equipos.index') }}"><i class="fa-solid fa-box"></i> Ver Equipos</a>
                <a class="btn guardar" href="{{ route('equipos.create') }}"><i class="fa-solid fa-plus"></i> Agregar Equipo</a>
            </div>
            </div>
        </div>

        {{-- Gestión de tipos de equipos --}}
        <div class="card text-bg-primary mb-3 mx-auto col-sm-12 col-md-6" style="max-width: 100%; text-align: justify;">
            <div class="card-header">Gestión de Tipos de Equipos</div>
            <div class="card-body">
                <p class="card-text">En este módulo puedes gestionar los <strong>Tipos de Equipos</strong>. Agrega, edita o elimina tipos de equipos según sea necesario.</p>
            </div>
                <div class="card-footer">
                    {{-- Ajusta las rutas según tu estructura --}}
                    <a class="btn btn-primary" href="{{ route('tiposequipos.index') }}"><i class="fa-solid fa-tag"></i> Ver Tipos de Equipos</a>
                    <a class="btn guardar" href="{{ route('tiposequipos.create') }}"><i class="fa-solid fa-plus"></i> Agregar Tipo de Equipos</a>
                </div>
            </div>
        </div>
        <h4 class="card-subtitle mb-2 text-muted">Módulos de Salas y Bodegas / Formularios</h4>
        <div class="row">
            <div class="col-sm-6">
        {{-- Gestión de salas o bodegas --}}
        <div class="card text-bg-primary mb-3 mx-auto col-sm-12 col-md-6" style="max-width: 100%; text-align: justify;">
            <div class="card-header">Gestión de Salas y Bodegas</div>
            <div class="card-body">
                <p class="card-text">En este módulo puedes gestionar las <strong>Salas y Bodegas</strong>. Agrega, edita o elimina salas de reuniones y almacenes según sea necesario.</p>
            </div>
            <div class="card-footer">
                {{-- Ajusta las rutas según tu estructura --}}
                <a class="btn btn-primary" href="{{ route('salasobodegas.index') }}"><i class="fa-solid fa-box"></i> Ver Salas y Bodegas</a>
                <a class="btn guardar" href="{{ route('salasobodegas.create') }}"><i class="fa-solid fa-plus"></i> Agregar Sala o Bodega</a>
            </div>
            </div>
        </div>
            {{-- Gestión de formularios --}}
            <div class="card text-bg-primary mb-3 mx-auto col-sm-12 col-md-6" style="max-width: 100%; text-align: justify;">
                <div class="card-header">Gestión de Formularios</div>
                <div class="card-body">
                    <p class="card-text">En este módulo puedes gestionar los <strong>Formularios</strong>. Agrega, edita o elimina Formularios según sea necesario.</p>
                </div>
                <div class="card-footer">
                    {{-- Ajusta las rutas según tu estructura --}}
                    <a class="btn btn-primary" href="{{ route('formularios.index') }}"><i class="fa-solid fa-box"></i> Ver Formularios</a>
                    <a class="btn guardar" href="{{ route('formularios.create') }}"><i class="fa-solid fa-plus"></i> Agregar Formularios</a>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
    {{-- Puedes mantener tus estilos personalizados aquí --}}
    {{-- ... --}}
    <style>/* Estilos personalizados si es necesario */
        .guardar {
            background-color: #e6500a;
            color: #fff;
        }
    </style>
@stop

@section('js')
    {{-- Puedes mantener tus scripts JavaScript aquí --}}
    {{-- ... --}}
@stop
