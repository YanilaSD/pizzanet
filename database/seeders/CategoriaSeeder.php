<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriaSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('categorias')->insert([
            ['nombre' => 'Pizzas', 'estado' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Gaseosas', 'estado' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Extras', 'estado' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Jugos', 'estado' => 1, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}