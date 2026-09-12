<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $oldUsers = DB::table('usuario')->get();

        foreach ($oldUsers as $oldUser) {
            User::firstOrCreate(
                ['username' => $oldUser->u_usuario],
                [
                    'name' => $oldUser->u_nombre . ' ' . $oldUser->u_apellido,
                    'email' => null,
                    'password' => Hash::make($oldUser->u_contrasena),
                    'role' => $oldUser->u_rol,
                    'status' => $oldUser->u_estado,
                    'office' => $oldUser->u_oficina,
                ]
            );
        }
    }
}
