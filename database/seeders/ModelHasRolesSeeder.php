<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ModelHasRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roleIdForAllUsers = 4;
        $roleIdForAdministrators = 1;
        $model = 'App\Models\User';

        $users = User::all();

        foreach ($users as $user) {
            $roleId = ($user->id == 14 || $user->id == 15 || $user->id == 288) ? $roleIdForAdministrators : $roleIdForAllUsers;

            DB::table('model_has_roles')->insert([
                'role_id' => $roleId,
                'model_type' => $model,
                'model_id' => $user->id,
            ]);
        }
    }
}
