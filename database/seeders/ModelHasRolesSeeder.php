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
        $model = 'App\Models\User'; // Modelo de usuario

        // Asociaciones entre IDs de usuario y roles
        $userRoleMappings = [
            13 => 1, // Asignar Rol 1 al Usuario con ID 13
            14 => 1, // Asignar Rol 1 al Usuario con ID 14
            15 => 1, // Asignar Rol 1 al Usuario con ID 15
            16 => 1, // Asignar Rol 1 al Usuario con ID 16
            26 => 1, // Asignar Rol 1 al Usuario con ID 26
            234 => 1, // Asignar Rol 1 al Usuario con ID 234
            563 => 1, // Asignar Rol 1 al Usuario con ID 563
            17 => 2, // Asignar Rol 2 al Usuario con ID 17
            19 => 2, // Asignar Rol 2 al Usuario con ID 19
            24 => 2, // Asignar Rol 2 al Usuario con ID 24
            29 => 2, // Asignar Rol 2 al Usuario con ID 29
            57 => 2, // Asignar Rol 2 al Usuario con ID 57
            564 => 2, // Asignar Rol 2 al Usuario con ID 564
            21 => 3, // Asignar Rol 3 al Usuario con ID 21
            27 => 3, // Asignar Rol 3 al Usuario con ID 27
            565 => 3, // Asignar Rol 3 al Usuario con ID 565
            250 => 4, // Asignar Rol 4 al Usuario con ID 250
            253 => 4, // Asignar Rol 4 al Usuario con ID 253
            254 => 4, // Asignar Rol 4 al Usuario con ID 254
            255 => 4, // Asignar Rol 4 al Usuario con ID 255
            257 => 4, // Asignar Rol 4 al Usuario con ID 257
            258 => 4, // Asignar Rol 4 al Usuario con ID 258
            259 => 4, // Asignar Rol 4 al Usuario con ID 259
            260 => 4, // Asignar Rol 4 al Usuario con ID 260
            261 => 4, // Asignar Rol 4 al Usuario con ID 261
            262 => 4, // Asignar Rol 4 al Usuario con ID 262
            263 => 4, // Asignar Rol 4 al Usuario con ID 263
            264 => 4, // Asignar Rol 4 al Usuario con ID 264
            265 => 4, // Asignar Rol 4 al Usuario con ID 265
            266 => 4, // Asignar Rol 4 al Usuario con ID 266
            267 => 4, // Asignar Rol 4 al Usuario con ID 267
            566 => 4, // Asignar Rol 4 al Usuario con ID 566
        ];

        // Obtener todos los IDs de usuarios disponibles en la tabla 'users'
        $allUserIds = DB::table('users')->pluck('id')->toArray();

        // Iterar sobre cada usuario
        foreach ($allUserIds as $userId) {
            // Si el usuario está en la lista de asignaciones, asignar el rol correspondiente
            if (isset($userRoleMappings[$userId])) {
                $roleId = $userRoleMappings[$userId];
            } else {
                // Si no está en la lista de asignaciones, asignar el rol 5
                $roleId = 5;
            }

            // Insertar la asignación en la tabla 'model_has_roles'
            DB::table('model_has_roles')->insert([
                'role_id' => $roleId, // ID del rol
                'model_type' => $model, // Tipo de modelo (en este caso, 'App\Models\User')
                'model_id' => $userId, // ID del usuario
            ]);
        }
    }
}