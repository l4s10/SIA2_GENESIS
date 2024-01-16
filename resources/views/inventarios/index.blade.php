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

    <div class="container-fluid d-flex justify-content-center align-items-center flex-column">

        {{-- Gestión de materiales --}}
        <div class="card text-bg-primary mb-3 mx-auto col-sm-12 col-md-6" style="max-width: 100%; text-align: justify;">
            <div class="card-header">Gestión de Materiales</div>
            <div class="card-body">
                <p class="card-text">En este módulo puedes gestionar los <strong>Materiales</strong>. Agrega, edita o elimina materiales según sea necesario.</p>
            </div>
            <div class="card-footer">
                {{-- Ajusta las rutas según tu estructura --}}
                <a class="btn btn-primary" href="{{ route('materiales.index') }}"><i class="fa-solid fa-box"></i> Ver Materiales</a>
                <a class="btn btn-primary" href="{{ route('materiales.create') }}"><i class="fa-solid fa-plus"></i> Agregar Material</a>
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
                <a class="btn btn-primary" href="{{ route('tiposmateriales.create') }}"><i class="fa-solid fa-plus"></i> Agregar Tipo de Material</a>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
    {{-- Puedes mantener tus estilos personalizados aquí --}}
    {{-- ... --}}
@stop

@section('js')
    {{-- Puedes mantener tus scripts JavaScript aquí --}}
    {{-- ... --}}
@stop
