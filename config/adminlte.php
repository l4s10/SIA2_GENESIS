<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Title
    |--------------------------------------------------------------------------
    |
    | Here you can change the default title of your admin panel.
    |
    | For detailed instructions you can look the title section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'title' => 'SIA2',
    'title_prefix' => '',
    'title_postfix' => '',

    /*
    |--------------------------------------------------------------------------
    | Favicon
    |--------------------------------------------------------------------------
    |
    | Here you can activate the favicon.
    |
    | For detailed instructions you can look the favicon section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'use_ico_only' => false,
    'use_full_favicon' => false,

    /*
    |--------------------------------------------------------------------------
    | Google Fonts
    |--------------------------------------------------------------------------
    |
    | Here you can allow or not the use of external google fonts. Disabling the
    | google fonts may be useful if your admin panel internet access is
    | restricted somehow.
    |
    | For detailed instructions you can look the google fonts section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'google_fonts' => [
        'allowed' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Admin Panel Logo
    |--------------------------------------------------------------------------
    |
    | Here you can change the logo of your admin panel.
    |
    | For detailed instructions you can look the logo section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'logo' => '<b>SIA2</b>WebApp',
    'logo_img' => 'vendor/adminlte/dist/img/logosii.png',
    'logo_img_class' => 'brand-image',
    'logo_img_xl' => null,
    'logo_img_xl_class' => 'brand-image-xs',
    'logo_img_alt' => 'SII Logo',

    /*
    |--------------------------------------------------------------------------
    | Authentication Logo
    |--------------------------------------------------------------------------
    |
    | Here you can setup an alternative logo to use on your login and register
    | screens. When disabled, the admin panel logo will be used instead.
    |
    | For detailed instructions you can look the auth logo section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'auth_logo' => [
        'enabled' => false,
        'img' => [
            'path' => 'vendor/adminlte/dist/img/AdminLTELogo.png',
            'alt' => 'Auth Logo',
            'class' => '',
            'width' => 50,
            'height' => 50,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Preloader Animation
    |--------------------------------------------------------------------------
    |
    | Here you can change the preloader animation configuration.
    |
    | For detailed instructions you can look the preloader section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'preloader' => [
        'enabled' => true,
        'img' => [
            'path' => 'vendor/adminlte/dist/img/logosii.png',
            'alt' => 'SIA2 Preloader Image',
            'effect' => 'animation__shake',
            'width' => 200,
            'height' => 160,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Menu
    |--------------------------------------------------------------------------
    |
    | Here you can activate and change the user menu.
    |
    | For detailed instructions you can look the user menu section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'usermenu_enabled' => true,
    'usermenu_header' => false,
    'usermenu_header_class' => 'bg-primary',
    'usermenu_image' => false,
    'usermenu_desc' => false,
    'usermenu_profile_url' => false,

    /*
    |--------------------------------------------------------------------------
    | Layout
    |--------------------------------------------------------------------------
    |
    | Here we change the layout of your admin panel.
    |
    | For detailed instructions you can look the layout section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'layout_topnav' => null,
    'layout_boxed' => null,
    'layout_fixed_sidebar' => true,
    'layout_fixed_navbar' => null,
    'layout_fixed_footer' => null,
    'layout_dark_mode' => null,

    /*
    |--------------------------------------------------------------------------
    | Authentication Views Classes
    |--------------------------------------------------------------------------
    |
    | Here you can change the look and behavior of the authentication views.
    |
    | For detailed instructions you can look the auth classes section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'classes_auth_card' => 'card-outline card-primary',
    'classes_auth_header' => '',
    'classes_auth_body' => '',
    'classes_auth_footer' => '',
    'classes_auth_icon' => '',
    'classes_auth_btn' => 'btn-flat btn-primary',

    /*
    |--------------------------------------------------------------------------
    | Admin Panel Classes
    |--------------------------------------------------------------------------
    |
    | Here you can change the look and behavior of the admin panel.
    |
    | For detailed instructions you can look the admin panel classes here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'classes_body' => '',
    'classes_brand' => '',
    'classes_brand_text' => '',
    'classes_content_wrapper' => '',
    'classes_content_header' => '',
    'classes_content' => '',
    'classes_sidebar' => 'sidebar-dark-primary elevation-4',
    'classes_sidebar_nav' => '',
    'classes_topnav' => 'navbar-white navbar-light',
    'classes_topnav_nav' => 'navbar-expand',
    'classes_topnav_container' => 'container',

    /*
    |--------------------------------------------------------------------------
    | Sidebar
    |--------------------------------------------------------------------------
    |
    | Here we can modify the sidebar of the admin panel.
    |
    | For detailed instructions you can look the sidebar section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'sidebar_mini' => 'lg',
    'sidebar_collapse' => false,
    'sidebar_collapse_auto_size' => false,
    'sidebar_collapse_remember' => false,
    'sidebar_collapse_remember_no_transition' => true,
    'sidebar_scrollbar_theme' => 'os-theme-light',
    'sidebar_scrollbar_auto_hide' => 'l',
    'sidebar_nav_accordion' => true,
    'sidebar_nav_animation_speed' => 300,

    /*
    |--------------------------------------------------------------------------
    | Control Sidebar (Right Sidebar)
    |--------------------------------------------------------------------------
    |
    | Here we can modify the right sidebar aka control sidebar of the admin panel.
    |
    | For detailed instructions you can look the right sidebar section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'right_sidebar' => false,
    'right_sidebar_icon' => 'fas fa-cogs',
    'right_sidebar_theme' => 'dark',
    'right_sidebar_slide' => true,
    'right_sidebar_push' => true,
    'right_sidebar_scrollbar_theme' => 'os-theme-light',
    'right_sidebar_scrollbar_auto_hide' => 'l',

    /*
    |--------------------------------------------------------------------------
    | URLs
    |--------------------------------------------------------------------------
    |
    | Here we can modify the url settings of the admin panel.
    |
    | For detailed instructions you can look the urls section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'use_route_url' => false,
    'dashboard_url' => 'home',
    'logout_url' => 'logout',
    'login_url' => 'login',
    'register_url' => 'register',
    'password_reset_url' => 'password/reset',
    'password_email_url' => 'password/email',
    'profile_url' => false,

    /*
    |--------------------------------------------------------------------------
    | Laravel Mix
    |--------------------------------------------------------------------------
    |
    | Here we can enable the Laravel Mix option for the admin panel.
    |
    | For detailed instructions you can look the laravel mix section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Other-Configuration
    |
    */

    'enabled_laravel_mix' => false,
    'laravel_mix_css_path' => 'css/app.css',
    'laravel_mix_js_path' => 'js/app.js',

    /*
    |--------------------------------------------------------------------------
    | Menu Items
    |--------------------------------------------------------------------------
    |
    | Here we can modify the sidebar/top navigation of the admin panel.
    |
    | For detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Menu-Configuration
    |
    */

    'menu' => [
        // Navbar items:
        [
            'type'         => 'navbar-search',
            'text'         => 'search',
            'topnav_right' => false,
        ],
        [
            'type'         => 'fullscreen-widget',
            'topnav_right' => false,
        ],

        // Sidebar items:
        [
            'type' => 'sidebar-menu-search',
            'text' => 'Buscar...',
        ],
        /*ELEMENTO CON LARAVEL PERMISSIONS (PROGRAMAR PERMISOS)*/
        // [
        //     'text' => 'blog',
        //     'url'  => 'admin/blog',
        //     'can'  => 'manage-blog',
        // ],
        ['header' => 'MENÚ PRINCIPAL'],
        [
            'text'        => 'Inicio',
            'route'         => 'home',
            'icon'        => 'far fa-fw fa-solid fa-home',
        ],
        ['header' => 'MODULOS DE SOLICITUDES'],
        [
            'text'        => 'Vehículos',
            'icon'        => 'far fa-fw fa-solid fa-car-side',
            'can'         => 'crear_solicitud',
            'submenu' => [
                [
                    'text' => 'Solicitar',
                    'route'  => 'solicitudesvehiculos.create',
                    'can'   => 'crear_solicitud',
                ],
                [
                    'text' => 'Ver solicitudes',
                    'route'  => 'solicitudesvehiculos.index',
                    'can'   => 'ver_solicitudes',
                ],
                [
                    'text' => 'Aprobar solicitudes',
                    'route'  => 'solicitudesvehiculos.indexPorAprobar',
                    'can'   => 'ver_solicitudes',
                ],
                [
                    'text' => 'Rendir solicitudes',
                    'route'  => 'solicitudesvehiculos.indexPorRendir',
                    'can'   => 'ver_solicitudes',
                ],

            ],
        ],[
            'text'        => 'Materiales',
            'icon'        => 'far fa-fw fa-solid fa-boxes',
            'can'         => 'crear_solicitud',
            'submenu' => [
                [
                    'text' => 'Solicitar',
                    'route'  => 'solicitudes.materiales.create',
                    'can'   => 'crear_solicitud',
                ],
                [
                    'text' => 'Mis solicitudes',
                    'route'  => 'solicitudes.materiales.index',
                    'can'   => 'ver_solicitudes',
                ],
            ],
        ],
        [
            'text'        => 'Equipos',
            'url'         => 'admin/pages',
            'icon'        => 'far fa-fw fa-solid fa-desktop',
            'can'         => 'crear_solicitud',
            'submenu' => [
                [
                    'text' => 'Solicitar',
                    'route'  => 'solicitudes.equipos.create',
                    'can'   => 'crear_solicitud',
                ],
                [
                    'text' => 'Mis solicitudes',
                    'route'  => 'solicitudes.equipos.index',
                    'can'   => 'ver_solicitudes',
                ],
            ],
        ],
        [
            'text'        => 'Formularios',
            'icon'        => 'far fa-fw fa-solid fa-file',
            'can'         => 'crear_solicitud',
            'submenu' => [
                [
                    'text' => 'Solicitar',
                    'route'  => 'solicitudes.formularios.create',
                    'can'   => 'crear_solicitud',
                ],
                [
                    'text' => 'Mis solicitudes',
                    'route'  => 'solicitudes.formularios.index',
                    'can'   => 'ver_solicitudes',
                ],
            ],
        ],
        [
            'text'        => 'Salas',
            'icon'        => 'far fa-fw fa-solid fa-building',
            'can'         => 'crear_solicitud',
            'submenu' => [
                [
                    'text' => 'Solicitar',
                    'route'  => 'solicitudes.salas.create',
                    'can'   => 'crear_solicitud',
                ],
                [
                    'text' => 'Mis solicitudes',
                    'route'  => 'solicitudes.salas.index',
                    'can'   => 'ver_solicitudes',
                ],
            ],
        ],
        [
            'text'        => 'Bodegas',
            'icon'        => 'far fa-fw fa-solid fa-building',
            'can'         => 'crear_solicitud',
            'submenu' => [
                [
                    'text' => 'Solicitar',
                    'route'  => 'solicitudes.bodegas.create',
                    'can'   => 'crear_solicitud',
                ],
                [
                    'text' => 'Mis solicitudes',
                    'route'  => 'solicitudes.bodegas.index',
                    'can'   => 'ver_solicitudes',
                ],
            ],
        ],
        [
            'text'        => 'Reparaciones y mantenciones',
            'icon'        => 'far fa-fw fa-solid fa-hammer',
            'can'         => 'crear_solicitud',
            'submenu' => [
                [
                    'text' => 'Solicitar',
                    'route'  => 'solicitudes.reparaciones.create',
                    'can'   => 'crear_solicitud',
                ],
                [
                    'text' => 'Mis solicitudes',
                    'route'  => 'solicitudes.reparaciones.index',
                    'can'   => 'ver_solicitudes',
                ],
            ],
        ],
        ['header' => 'MÓDULOS DIRECTIVOS'],
        [
            'text' => 'Buscar resoluciones',
            'icon' => 'fas fa-fw fa-solid fa-search',
            'can'  => 'buscar_resoluciones',
            'submenu' => [
                [
                    'text' => 'Búsqueda avanzada',
                    'route'  => 'directivos.indexBusquedaFuncionarios',
                    'icon' => 'fas fa-fw fa-solid fa-search-plus',
                    'can'  => 'buscar_resoluciones',
                ],
                [
                    'text' => 'Búsqueda básica',
                    'route'  => 'directivos.indexBusquedaBasica',
                    'icon' => 'fas fa-fw fa-solid fa-search-minus',
                    'can'  => 'buscar_resoluciones',
                ],
            ],
        ],
        [
            'text' => 'Repositorio',
            'icon' => 'fas fa-fw fa-solid fa-archive',
            'can'  => 'ver_repositorio',
            'submenu' => [
                [
                    'text' => 'Resoluciones',
                    'route'  => 'resoluciones.index',
                    'icon' => 'fas fa-fw fa-solid fa-file-alt',
                    'can'  => 'ver_repositorio',
                ],
                [
                    'text' => 'Pólizas',
                    'route'  => 'polizas.index',
                    'icon' => 'fas fa-fw fa-solid fa-file-contract',
                    'can'  => 'ver_repositorio',
                ],
                [
                    'text' => 'Facultades',
                    'route'  => 'facultades.index',
                    'icon' => 'fas fa-fw fa-solid fa-file-signature',
                    'can'  => 'ver_repositorio',
                ],
                [
                    'text' => 'Cargos',
                    'route'  => 'cargos.index',
                    'icon' => 'fas fa-fw fa-solid fa-file-invoice',
                    'can'  => 'ver_repositorio',
                ],
            ],
        ],
        ['header' => 'GESTIONAR ACTIVOS', 'can' => 'ver_activos'],
        [
            'text'  =>  'Inventario',
            'route' =>  'inventarios.index',
            'can'   =>  'ver_activos', // Se visualiza a traves de permisos
            'icon'  =>  'fas fa-fw fa-solid fa-paste',
        ],
        [
            'text' => 'Reportes',
            'route'  => 'reportes.home.index',
            'icon' => 'fas fa-fw fa-solid fa-chart-pie',
            'can'  => 'ver_graficos',
        ],
        [
            'text' => 'Auditorías',
            'url'  => '#',
            'icon' => 'fas fa-fw fa-solid fa-tablet',
            'can'  => 'ver_auditoria',
            'submenu' => [
                [
                    'text' => 'Materiales',
                    'icon' => 'fas fa-fw fa-solid fa-boxes',
                    'route'  => 'movimientos.materiales',
                    'can'  => 'ver_auditoria'
                ],
                [
                    'text' => 'Equipos',
                    'icon' => 'fas fa-fw fa-solid fa-desktop',
                    'route'  => 'movimientos.equipos',
                    'can'  => 'ver_auditoria'
                ],
            ],
        ],

        ['header' => 'AJUSTES DEL SISTEMA SIAV2.5', 'can' => 'ver_panel_control'],
        [
            'text' => 'Administrar usuarios',
            'route'  => 'panel.usuarios.index',
            'icon' => 'fas fa-fw fa-solid fa-users-cog',
            'can'  => 'ver_panel_control',
        ],
        [
            'text' => 'Administrar regiones',
            'route'  => 'panel.regiones.index',
            'icon' => 'fas fa-fw fa-solid fa-globe-americas',
            'can'  => 'ver_panel_control',
        ],
        [
            'text' => 'Administrar comunas',
            'route'  => 'panel.comunas.index',
            'icon' => 'fas fa-fw fa-solid fa-map-marked-alt',
            'can'  => 'ver_panel_control',
        ],
        [
            'text' => 'Administrar direcciones regionales',
            'route'  => 'panel.oficinas.index',
            'icon' => 'fas fa-fw fa-solid fa-building',
            'can'  => 'ver_panel_control',
        ],
        [
            'text' => 'Administrar departamentos',
            'route'  => 'panel.departamentos.index',
            'icon' => 'fas fa-fw fa-solid fa-building-un',
            'can'  => 'ver_panel_control',
        ],
        [
            'text' => 'Administrar ubicaciones',
            'route'  => 'panel.ubicaciones.index',
            'icon' => 'fas fa-fw fa-solid fa-building-un',
            'can'  => 'ver_panel_control',
        ],
        // HEADER PARA EL ACCOUNT_SETTINGS
        // ['header' => 'account_settings'],
        // [
        //     'text' => 'profile',
        //     'url'  => 'admin/settings',
        //     'icon' => 'fas fa-fw fa-user',
        // ],
        // SE OCULTA EL CAMBIO DE CONTRASEÑA (FALTA IMPLEMENTAR "CORREO MENSAJERO")
        // [
        //     'text' => 'change_password',
        //     'url'  => 'password/reset',
        //     'icon' => 'fas fa-fw fa-lock',
        // ],
        // [
        //     'text'    => 'multilevel',
        //     'icon'    => 'fas fa-fw fa-share',
        //     'submenu' => [
        //         [
        //             'text' => 'level_one',
        //             'url'  => '#',
        //         ],
        //         [
        //             'text'    => 'level_one',
        //             'url'     => '#',
        //             'submenu' => [
        //                 [
        //                     'text' => 'level_two',
        //                     'url'  => '#',
        //                 ],
        //                 [
        //                     'text'    => 'level_two',
        //                     'url'     => '#',
        //                     'submenu' => [
        //                         [
        //                             'text' => 'level_three',
        //                             'url'  => '#',
        //                         ],
        //                         [
        //                             'text' => 'level_three',
        //                             'url'  => '#',
        //                         ],
        //                     ],
        //                 ],
        //             ],
        //         ],
        //         [
        //             'text' => 'level_one',
        //             'url'  => '#',
        //         ],
        //     ],
        // ],
        // ['header' => 'labels'],
        // [
        //     'text'       => 'important',
        //     'icon_color' => 'red',
        //     'url'        => '#',
        // ],
        // [
        //     'text'       => 'warning',
        //     'icon_color' => 'yellow',
        //     'url'        => '#',
        // ],
        // [
        //     'text'       => 'information',
        //     'icon_color' => 'cyan',
        //     'url'        => '#',
        // ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Menu Filters
    |--------------------------------------------------------------------------
    |
    | Here we can modify the menu filters of the admin panel.
    |
    | For detailed instructions you can look the menu filters section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Menu-Configuration
    |
    */

    'filters' => [
        JeroenNoten\LaravelAdminLte\Menu\Filters\GateFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\HrefFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\SearchFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ActiveFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ClassesFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\LangFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\DataFilter::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Plugins Initialization
    |--------------------------------------------------------------------------
    |
    | Here we can modify the plugins used inside the admin panel.
    |
    | For detailed instructions you can look the plugins section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Plugins-Configuration
    |
    */

    'plugins' => [
        'Datatables' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css',
                ],
            ],
        ],
        'Select2' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.css',
                ],
            ],
        ],
        'Chartjs' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.0/Chart.bundle.min.js',
                ],
            ],
        ],
        'Sweetalert2' => [
            'active' => true,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => '//cdn.jsdelivr.net/npm/sweetalert2@8',
                ],
            ],
        ],
        'Pace' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/themes/blue/pace-theme-center-radar.min.css',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/pace.min.js',
                ],
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | IFrame
    |--------------------------------------------------------------------------
    |
    | Here we change the IFrame mode configuration. Note these changes will
    | only apply to the view that extends and enable the IFrame mode.
    |
    | For detailed instructions you can look the iframe mode section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/IFrame-Mode-Configuration
    |
    */

    'iframe' => [
        'default_tab' => [
            'url' => null,
            'title' => null,
        ],
        'buttons' => [
            'close' => true,
            'close_all' => true,
            'close_all_other' => true,
            'scroll_left' => true,
            'scroll_right' => true,
            'fullscreen' => true,
        ],
        'options' => [
            'loading_screen' => 1000,
            'auto_show_new_tab' => true,
            'use_navbar_items' => true,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Livewire
    |--------------------------------------------------------------------------
    |
    | Here we can enable the Livewire support.
    |
    | For detailed instructions you can look the livewire here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Other-Configuration
    |
    */

    'livewire' => false,
];
