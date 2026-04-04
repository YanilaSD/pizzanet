<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolPrivilegioSeeder extends Seeder
{
    public function run(): void
    {
        // Roles
        DB::table('roles')->insert([
            ['nombre' => 'Administrador', 'descripcion' => 'Acceso total al sistema', 'estado' => 1, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Privilegios
        $privilegios = [
            ['nombre' => 'Ver ventas',      'slug' => 'ventas.index',    'descripcion' => 'Ver listado de ventas'],
            ['nombre' => 'Crear ventas',    'slug' => 'ventas.create',   'descripcion' => 'Registrar nuevas ventas'],
            ['nombre' => 'Ver productos',   'slug' => 'productos.index', 'descripcion' => 'Ver listado de productos'],
            ['nombre' => 'Crear productos', 'slug' => 'productos.create','descripcion' => 'Registrar nuevos productos'],
            ['nombre' => 'Ver clientes',    'slug' => 'clientes.index',  'descripcion' => 'Ver listado de clientes'],
            ['nombre' => 'Crear clientes',  'slug' => 'clientes.create', 'descripcion' => 'Registrar nuevos clientes'],
            ['nombre' => 'Ver inventario',  'slug' => 'inventario.index','descripcion' => 'Ver movimientos de inventario'],
            ['nombre' => 'Ver reportes',    'slug' => 'reportes.index',  'descripcion' => 'Acceso a reportes'],
        ];

        foreach ($privilegios as $privilegio) {
            DB::table('privilegios')->insert([
                ...$privilegio,
                'estado' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Asignar todos los privilegios al rol Administrador (id: 1)
        $totalPrivilegios = DB::table('privilegios')->count();
        for ($i = 1; $i <= $totalPrivilegios; $i++) {
            DB::table('rol_privilegio')->insert([
                'rol_id'       => 1,
                'privilegio_id' => $i,
                'created_at'   => now(),
                'updated_at'   => now(),
            ]);
        }
    }
}