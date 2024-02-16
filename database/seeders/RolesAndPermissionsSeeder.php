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

        // Crear permisos
        foreach ($permisos as $permiso) {
            Permission::create(['name' => $permiso]);
        }

        // Asignación de permisos (ADMINISTRADOR)
        $roles['ADMINISTRADOR']->givePermissionTo(Permission::all());

        // Asignación de permisos (SOLICITUDES)
        $roles['FUNCIONARIO']->givePermissionTo(['crear_solicitud', 'ver_solicitudes']);
        $roles['SERVICIOS']->givePermissionTo(['crear_solicitud', 'ver_solicitudes', 'editar_solicitud', 'actualizar_solicitud']);
        $roles['INFORMATICA']->givePermissionTo(['crear_solicitud', 'ver_solicitudes', 'editar_solicitud', 'actualizar_solicitud']);

        // Asignación de permisos (ACTIVOS - INVENTARIO)
        $roles['FUNCIONARIO']->givePermissionTo(['crear_activo', 'ver_activos']);
        $roles['SERVICIOS']->givePermissionTo(['crear_activo', 'ver_activos', 'editar_activo', 'actualizar_activo']);
        $roles['INFORMATICA']->givePermissionTo(['crear_activo', 'ver_activos', 'editar_activo', 'actualizar_activo']);

        // Asignación de permisos (PANEL DE CONTROL)
        $roles['FUNCIONARIO']->givePermissionTo(['ver_panel_control']);
        $roles['SERVICIOS']->givePermissionTo(['ver_panel_control', 'editar_panel_control', 'actualizar_panel_control']);
        $roles['INFORMATICA']->givePermissionTo(['ver_panel_control', 'editar_panel_control', 'actualizar_panel_control']);
    }
}
