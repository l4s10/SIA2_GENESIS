@extends('adminlte::page')

@section('title', 'Gestión de Inventarios')

@section('content_header')
    <h1>Menú de Inventarios</h1>
    {{-- Aquí se mantiene la lógica de roles si es necesario --}}
    {{-- ... --}}
@stop

@section('content')
    {{-- GESTION DE MATERIALES --}}
    <h4 class="card-subtitle mt-4 mb-4 text-muted" style="text-align: center;">Módulos Materiales</h4>
    <div class="row">
        {{-- MATERIALES --}}
        <div class="col-md-6">
            <div class="card text-bg-primary mb-3">
                <div class="card-header">Gestión de Materiales</div>
                <div class="card-body">
                    <p class="card-text">En este módulo puedes gestionar los <strong>Materiales</strong>. Agrega, edita o elimina materiales según sea necesario.</p>
                </div>
                <div class="card-footer ">
                    {{-- Ajusta las rutas según tu estructura --}}
                    <a class="btn btn-primary" href="{{ route('materiales.index') }}"><i class="fa-solid fa-eye"></i> Ver</a>
                    <a class="btn guardar" href="{{ route('materiales.create') }}"><i class="fa-solid fa-plus"></i> Agregar</a>
                </div>
            </div>
        </div>
        {{-- TIPOS DE MATERIALES --}}
        <div class="col-md-6">
            {{-- Gestión de categorías de materiales --}}
            <div class="card text-bg-primary mb-3">
                <div class="card-header">Gestión de Categorías de Materiales</div>
                <div class="card-body">
                    <p class="card-text">En este módulo puedes gestionar las <strong>Categorías de Materiales</strong>. Agrega, edita o elimina categorías de materiales según sea necesario.</p>
                </div>
                <div class="card-footer">
                    {{-- Ajusta las rutas según tu estructura --}}
                    <a class="btn btn-primary" href="{{ route('tiposmateriales.index') }}"><i class="fa-solid fa-eye"></i> Ver</a>
                    <a class="btn guardar" href="{{ route('tiposmateriales.create') }}"><i class="fa-solid fa-plus"></i> Agregar</a>
                </div>
            </div>
        </div>
    </div>

    {{-- GESTION DE EQUIPOS --}}
    <h4 class="card-subtitle mt-4 mb-4 text-muted" style="text-align: center;">Módulos Equipos</h4>
    <div class="row">
        {{-- Gestión de equipos --}}
        <div class="col-md-6">
            <div class="card text-bg-primary mb-3">
                <div class="card-header">Gestión de Equipos</div>
                <div class="card-body">
                    <p class="card-text">En este módulo puedes gestionar los <strong>Equipos</strong>. Agrega, edita o elimina Equipos según sea necesario.</p>
                </div>
                <div class="card-footer">
                    {{-- Ajusta las rutas según tu estructura --}}
                    <a class="btn btn-primary" href="{{ route('equipos.index') }}"><i class="fa-solid fa-eye"></i> Ver</a>
                    <a class="btn guardar" href="{{ route('equipos.create') }}"><i class="fa-solid fa-plus"></i> Agregar</a>
                </div>
            </div>
        </div>
        {{-- TIPOS DE EQUIPOS --}}
        <div class="col-md-6">
            {{-- Gestión de tipos de equipos --}}
            <div class="card text-bg-primary mb-3">
                <div class="card-header">Gestión de Tipos de Equipos</div>
                <div class="card-body">
                    <p class="card-text">En este módulo puedes gestionar los <strong>Tipos de Equipos</strong>. Agrega, edita o elimina tipos de equipos según sea necesario.</p>
                </div>
                <div class="card-footer">
                    {{-- Ajusta las rutas según tu estructura --}}
                    <a class="btn btn-primary" href="{{ route('tiposequipos.index') }}"><i class="fa-solid fa-eye"></i> Ver</a>
                    <a class="btn guardar" href="{{ route('tiposequipos.create') }}"><i class="fa-solid fa-plus"></i> Agregar</a>
                </div>
            </div>
        </div>
    </div>

    {{-- GESTION DE SALAS Y BODEGAS --}}
    <h4 class="card-subtitle mt-4 mb-4 text-muted" style="text-align: center;">Módulos Salas y Bodegas </h4>
    <div class="row">
        {{-- Gestión de salas --}}
        <div class="col-md-6">
            <div class="card text-bg-primary mb-3">
                <div class="card-header">Gestión de Salas </div>
                <div class="card-body">
                    <p class="card-text">En este módulo puedes gestionar las <strong>Salas</strong>. Agrega, edita o elimina salas de reuniones según sea necesario.</p>
                </div>
                <div class="card-footer">
                    {{-- Ajusta las rutas según tu estructura --}}
                    <a class="btn btn-primary" href="{{ route('salas.index') }}"><i class="fa-solid fa-eye"></i> Ver</a>
                    <a class="btn guardar" href="{{ route('salas.create') }}"><i class="fa-solid fa-plus"></i> Agregar</a>
                </div>
            </div>
        </div>
        {{-- Gestión de bodegas --}}
        <div class="col-md-6">
            {{-- Gestión de bodegas --}}
            <div class="card text-bg-primary mb-3">
                <div class="card-header">Gestión de Bodegas </div>
                <div class="card-body">
                    <p class="card-text">En este módulo puedes gestionar las <strong>Bodegas</strong>. Agrega, edita o elimina almacenes según sea necesario.</p>
                </div>
                <div class="card-footer">
                    {{-- Ajusta las rutas según tu estructura --}}
                    <a class="btn btn-primary" href="{{ route('bodegas.index') }}"><i class="fa-solid fa-eye"></i> Ver</a>
                    <a class="btn guardar" href="{{ route('bodegas.create') }}"><i class="fa-solid fa-plus"></i> Agregar</a>
                </div>
            </div>
        </div>
    </div>


    <h4 class="card-subtitle mb-4 mb-4 text-muted" style="text-align: center;">Módulos de Vehículos / Formularios</h4>
    <div class=row>
        {{-- Gestión de vehículos --}}
        <div class="col-md-6">
            <div class="card text-bg-primary mb-3">
                <div class="card-header">Gestión de Vehículos</div>
                <div class="card-body">
                    <p class="card-text">En este módulo puedes gestionar los <strong>Vehículos</strong>. Agrega, edita o elimina Vehículos según sea necesario.</p>
                </div>
                <div class="card-footer ">
                    {{-- Ajusta las rutas según tu estructura --}}
                    <a class="btn btn-primary" href="{{ route('vehiculos.index') }}"><i class="fa-solid fa-eye"></i> Ver</a>
                    <a class="btn guardar" href="{{ route('vehiculos.create') }}"><i class="fa-solid fa-plus"></i> Agregar</a>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            {{-- Gestión de formularios --}}
            <div class="card text-bg-primary mb-3">
                <div class="card-header">Gestión de Formularios</div>
                <div class="card-body">
                    <p class="card-text">En este módulo puedes gestionar los <strong>Formularios</strong>. Agrega, edita o elimina Formularios según sea necesario.</p>
                </div>
                <div class="card-footer">
                    {{-- Ajusta las rutas según tu estructura --}}
                    <a class="btn btn-primary" href="{{ route('formularios.index') }}"><i class="fa-solid fa-eye"></i> Ver</a>
                    <a class="btn guardar" href="{{ route('formularios.create') }}"><i class="fa-solid fa-plus"></i> Agregar</a>
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
