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

        // Definir permisos
        $permisos = [
            'crear_solicitud',
            'ver_solicitudes', // Permiso único para ver el detalle y la lista de solicitudes
            'editar_solicitud',
            'actualizar_solicitud',
            'eliminar_solicitud',
        ];

        // Crear permisos
        foreach ($permisos as $permiso) {
            Permission::create(['name' => $permiso]);
        }

        // Asignación de permisos simplificada
        $roles['FUNCIONARIO']->givePermissionTo(['crear_solicitud', 'ver_solicitudes']);
        $roles['SERVICIOS']->givePermissionTo(['crear_solicitud', 'ver_solicitudes', 'editar_solicitud', 'actualizar_solicitud']);
        $roles['INFORMATICA']->givePermissionTo(['crear_solicitud', 'ver_solicitudes', 'editar_solicitud', 'actualizar_solicitud']);
        $roles['ADMINISTRADOR']->givePermissionTo(Permission::all());
    }
}
