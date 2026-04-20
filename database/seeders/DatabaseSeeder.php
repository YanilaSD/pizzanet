<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            TipoPagoSeeder::class,
            CategoriaSeeder::class,
            RolPrivilegioSeeder::class,
            ClienteSeeder::class,
            AdminSeeder::class,
            DescuentoSeeder::class
        ]);
    }
}