<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ModelHasRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [1, 2, 3, 4, 5]; // IDs de los roles que quieres asignar
        $model = 'App\Models\User';

        // Asociaciones entre IDs de usuario y roles
        $userRoleMappings = [
            14 => 1, // Asignar Rol 1 al Usuario con ID 1
            13 => 2, // Asignar Rol 2 al Usuario con ID 2
            12 => 3, // Asignar Rol 3 al Usuario con ID 3
            21 => 4, // Asignar Rol 4 al Usuario con ID 4
            10 => 5, // Asignar Rol 5 al Usuario con ID 5
        ];

        foreach ($userRoleMappings as $userId => $roleId) {
            DB::table('model_has_roles')->insert([
                'role_id' => $roleId,
                'model_type' => $model,
                'model_id' => $userId,
            ]);
        }
    }
}
