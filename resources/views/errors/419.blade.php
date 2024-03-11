@extends('adminlte::page')

@section('title', 'Sesión Expirada')

@section('content_header')
    <h1>419 - Sesión Expirada</h1>
@endsection

@section('content')
<div class="error-page">
    <h2 class="headline text-warning">419</h2>
    <div class="error-content">
        <h3><i class="fas fa-exclamation-triangle text-warning"></i> ¡Oops! Sesión Expirada.</h3>
        <p>
            Lo sentimos, tu sesión ha expirado debido a inactividad o problemas con el token de seguridad.
            Por favor, intenta regresar a la página anterior o recargar la página.
            <a href="javascript:history.back()" class="btn btn-primary">Volver atrás</a>
        </p>
    </div>
</div>
@endsection

@section('css')
    <style>
        /* estilos personalizados específicos para esta página. */
    </style>
@endsection
