<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductoSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('productos')->insert([
            [
                'categoria_id'  => 1,
                'nombre'        => 'Pizza Familiar',
                'descripcion'   => 'Pizza Familiar con mozzarella, jamón y champiñones',
                'precio'        => 40,
                'imagen'        => 'productos/pizza_familiar.png',
                'estado'        => 1,
                'created_at'    => now(),
                'updated_at'    => now()
            ],
        ]);
    }
}
