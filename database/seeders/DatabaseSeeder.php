<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        DB::table('clientes')->updateOrInsert(
            ['ci' => '77777777'], // si ya existe, actualiza
            [
                'nombre' => 'Sin nombre',
                'correo' => 'sin_nombre@sistema.com',
                'celular' => '77777777',
                'puntos' => 0,
                'descuento' => 0.00,
                'estado' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}
