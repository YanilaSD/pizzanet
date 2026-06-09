<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $usuarios = [
            [
                'name'      => 'Administrador',
                'email'     => 'admin@pizzeria.com',
                'password'  => Hash::make('admin1234'),
                'rol_nombre' => 'Administrador',
            ],
            [
                'name'      => 'Cajero',
                'email'     => 'cajero@pizzeria.com',
                'password'  => Hash::make('cajero1234'),
                'rol_nombre' => 'Cajero',
            ],
            [
                'name'      => 'Supervisor',
                'email'     => 'supervisor@pizzeria.com',
                'password'  => Hash::make('supervisor1234'),
                'rol_nombre' => 'Supervisor',
            ],
        ];

        foreach ($usuarios as $usuario) {
            $userId = DB::table('users')->insertGetId([
                'name'       => $usuario['name'],
                'email'      => $usuario['email'],
                'password'   => $usuario['password'],
                'estado'     => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $rolId = DB::table('roles')->where('nombre', $usuario['rol_nombre'])->value('id');

            DB::table('usuario_rol')->insert([
                'usuario_id' => $userId,
                'rol_id'     => $rolId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}