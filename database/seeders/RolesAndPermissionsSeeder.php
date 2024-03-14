<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Limpiar caché de permisos
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Crear roles
        $roles = [
            'ADMINISTRADOR' => Role::create(['name' => 'ADMINISTRADOR']),
            'SERVICIOS' => Role::create(['name' => 'SERVICIOS']),
            'INFORMATICA' => Role::create(['name' => 'INFORMATICA']),
            'FUNCIONARIO' => Role::create(['name' => 'FUNCIONARIO']),
            'JURIDICO' => Role::create(['name' => 'JURIDICO']),
        ];

        // Definir permisos (SOLICITUDES)
        $permisos = [
            'crear_solicitud',
            'ver_solicitudes', // Permiso único para ver el detalle y la lista de solicitudes
            'editar_solicitud',
            'actualizar_solicitud',
            'eliminar_solicitud',
        ];

        // Definir permisos (ACTIVOS - INVENTARIO)
        $permisos = array_merge($permisos, [
            'crear_activo',
            'ver_activos',
            'editar_activo',
            'actualizar_activo',
            'eliminar_activo',
        ]);

        // Definir permisos (PANEL DE CONTROL)
        $permisos = array_merge($permisos, [
            'ver_panel_control',
            'editar_panel_control',
            'actualizar_panel_control',
        ]);

        // Definir permisos para (REPOSITORIO)
        $permisos = array_merge($permisos, [
            'ver_repositorio',
            'crear_repositorio',
            'editar_repositorio',
            'actualizar_repositorio',
            'eliminar_repositorio',
        ]);

        // Definir permisos para (BUSQUEDA RESOLUCIONES)
        $permisos = array_merge($permisos, [
            'buscar_resoluciones',
        ]);

        // Crear permisos
        foreach ($permisos as $permiso) {
            Permission::create(['name' => $permiso]);
        }

        // Asignación de permisos (ADMINISTRADOR)
        $roles['ADMINISTRADOR']->givePermissionTo(Permission::all());

        // Asignación de permisos (SOLICITUDES)
        $roles['FUNCIONARIO']->givePermissionTo(['crear_solicitud', 'ver_solicitudes']);

        $roles['JURIDICO']->givePermissionTo(['crear_solicitud', 'ver_solicitudes']);

        $roles['SERVICIOS']->givePermissionTo(['crear_solicitud', 'ver_solicitudes', 'editar_solicitud', 'actualizar_solicitud']);

        $roles['INFORMATICA']->givePermissionTo(['crear_solicitud', 'ver_solicitudes', 'editar_solicitud', 'actualizar_solicitud']);

        // Asignación de permisos (ACTIVOS - INVENTARIO)
        $roles['SERVICIOS']->givePermissionTo(['crear_activo', 'ver_activos', 'editar_activo', 'actualizar_activo']);
        $roles['INFORMATICA']->givePermissionTo(['crear_activo', 'ver_activos', 'editar_activo', 'actualizar_activo']);

        // Asignación de permisos (PANEL DE CONTROL)
        $roles['SERVICIOS']->givePermissionTo(['ver_panel_control', 'editar_panel_control', 'actualizar_panel_control']);
        $roles['INFORMATICA']->givePermissionTo(['ver_panel_control', 'editar_panel_control', 'actualizar_panel_control']);

        // Asignación de permisos (REPOSITORIO - JURIDICO)
        $roles['JURIDICO']->givePermissionTo(['ver_repositorio', 'crear_repositorio', 'editar_repositorio', 'actualizar_repositorio', 'eliminar_repositorio']);

        // Asignación de permisos (BUSQUEDA RESOLUCIONES)
        $roles['JURIDICO']->givePermissionTo(['buscar_resoluciones']);
        $roles['SERVICIOS']->givePermissionTo(['buscar_resoluciones']);
        $roles['INFORMATICA']->givePermissionTo(['buscar_resoluciones']);
    }
}
