@extends('adminlte::page')

@section('title', 'Acceso Prohibido')

@section('content_header')
    <h1>403 - Acceso Prohibido</h1>
@endsection

@section('content')
<div class="error-page">
    <h2 class="headline text-warning"> 403</h2>
    <div class="error-content">
        <h3><i class="fas fa-exclamation-triangle text-warning"></i> ¡Oops! Página no accesible.</h3>
        <p>
            No tienes permiso para acceder a esta página.
            Mientras tanto, puedes <a href="javascript:history.back()" class="btn btn-primary">volver atrás</a> o usar el formulario de búsqueda.
        </p>
    </div>
</div>
@endsection

@section('css')
    <style>
        /* Estilos personalizados según la guía de usabilidad de tu empresa */
    </style>
@endsection
