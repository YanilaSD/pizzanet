<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CanjePuntoSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('canje_puntos')->insert([
            [
                'nombre' => 'Descuento por fidelización',
                'descripcion' => 'Canjea 100 puntos y obtiene Bs. 10 de descuento en tu próxima compra',
                'puntos' => 100,
                'descuento' => 10,
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }
}