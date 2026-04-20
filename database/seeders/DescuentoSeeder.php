<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DescuentoSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('descuentos')->insert([
            [
                'nombre' => 'Descuento por innaguracion',
                'descripcion' => 'Canjea 100 puntos y obtiene Bs. 10 de descuento',
                'puntos' => 100,
                'descuento' => 10,
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }
}