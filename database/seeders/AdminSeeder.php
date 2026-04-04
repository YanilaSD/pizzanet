<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $userId = DB::table('users')->insertGetId([
            'name'       => 'Administrador',
            'email'      => 'admin@pizzeria.com',
            'password'   => Hash::make('admin1234'),
            'estado'     => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('usuario_rol')->insert([
            'usuario_id' => $userId,
            'rol_id'     => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}