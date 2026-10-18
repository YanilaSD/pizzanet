<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TipoPagoSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('tipo_pagos')->insert([
            ['nombre' => 'Efectivo',      'estado' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'QR',            'estado' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Transferencia', 'estado' => 1, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}