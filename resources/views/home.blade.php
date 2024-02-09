@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
    <p>Si este nuevo modelo es genial.</p>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script> console.log('Hi!'); </script>
    {{-- Dentro de tu vista Blade, por ejemplo, dashboard.blade.php --}}
    @if(session('api_token'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Almacenar el token en localStorage
                localStorage.setItem('api_token', '{{ session('api_token') }}');
                console.log(localStorage.getItem('api_token'));
            });
        </script>
    @endif
@stop
