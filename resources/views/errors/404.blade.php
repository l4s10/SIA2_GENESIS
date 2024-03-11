@extends('adminlte::page')

@section('title', 'Página no encontrada')

@section('content_header')
    <h1>404 - Página no encontrada</h1>
@endsection

@section('content')
<div class="error-page">
    <h2 class="headline text-danger">404</h2>
    <div class="error-content">
        <h3><i class="fas fa-exclamation-circle text-danger"></i> ¡Oops! Página no encontrada.</h3>
        <p>
            Lo sentimos, la página que estás buscando no existe.
            Por favor, verifica la URL o regresa a la página de inicio.
            <a href="javascript:history.back()" class="btn btn-primary">volver atrás</a>
        </p>
    </div>
</div>
@endsection

@section('css')
    <style>
        /* Estilos personalizados según la guía de usabilidad de tu empresa */
    </style>
@endsection
