<?php
    // Obtener el usuario actual
    $user = auth()->user();

    // Obtener los roles del usuario actual
    $roles = $user ? $user->getRoleNames() : [];

    // Definir los colores por defecto para cada tipo de usuario
    $colors = [
        'ADMINISTRADOR' => 'navbar-primary',
        'FUNCIONARIO' => 'navbar-warning',
        'SERVICIOS' => 'navbar-success', // Nuevo rol "SERVICIOS" con color verde
        'INFORMATICA' => 'navbar-danger', // Nuevo rol "INFORMATICA" con color rojo
        'JURIDICO' => 'navbar-danger', // Nuevo rol "JURIDICO" con color rojo
    ];
    // Establecer el color en función de los roles del usuario actual
    $color = null;
    foreach ($roles as $role) {
        if (isset($colors[$role])) {
            $color = $colors[$role];
            break;
        }
    };
?>
<nav class="main-header navbar
    {{ config('adminlte.classes_topnav_nav', 'navbar-expand') }}
    @role('ADMINISTRADOR')
    navbar-primary
    @elseif(request()->user() && request()->user()->hasRole('SERVICIOS'))
    navbar-success
    @elseif(request()->user() && request()->user()->hasAnyRole(['INFORMATICA', 'JURIDICO']))
    navbar-danger
    @else
    navbar-warning
    @endrole
    {{ config('adminlte.classes_topnav', 'navbar-white navbar-light') }}">

    {{-- Navbar left links --}}
    <ul class="navbar-nav">
        {{-- Left sidebar toggler link --}}
        @include('adminlte::partials.navbar.menu-item-left-sidebar-toggler')

        {{-- Configured left links --}}
        @each('adminlte::partials.navbar.menu-item', $adminlte->menu('navbar-left'), 'item')

        {{-- Custom left links --}}
        @yield('content_top_nav_left')
    </ul>

    {{-- Navbar right links --}}
    <ul class="navbar-nav ml-auto">
        {{-- Custom right links --}}
        @yield('content_top_nav_right')

        {{-- Configured right links --}}
        @each('adminlte::partials.navbar.menu-item', $adminlte->menu('navbar-right'), 'item')

        {{-- User menu link --}}
        @if(Auth::user())
            @if(config('adminlte.usermenu_enabled'))
                @include('adminlte::partials.navbar.menu-item-dropdown-user-menu')
            @else
                @include('adminlte::partials.navbar.menu-item-logout-link')
            @endif
        @endif

        {{-- Right sidebar toggler link --}}
        @if(config('adminlte.right_sidebar'))
            @include('adminlte::partials.navbar.menu-item-right-sidebar-toggler')
        @endif
    </ul>
    <style>
        /*Color administrador */
        .navbar-primary {
            background-color: #0064A0; /* Cambia el color a azul */
        }
        /*Color Servicios */
        .navbar-success {
            background-color: #00B050;
        }
        /*Color Informatica/juridico */
        .navbar-danger {
            background-color: #E22C2C;
        }
        /*Color Funcionario */
        .navbar-warning {
            background-color: #E6500A; /* Cambia el color a naranja */
        }
    </style>
</nav>
