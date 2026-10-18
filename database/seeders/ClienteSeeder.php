<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClienteSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('clientes')->insert([
            [
                'nombre' => 'Sin nombre',
                'correo' => null,
                'ci' => config('app.ci_anonimo'),
                'celular' => config('app.ci_anonimo'),
                'estado' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }
}
