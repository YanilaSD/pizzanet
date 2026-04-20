<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $fillable = [
        'nombre',
        'ci',
        'celular',
        'correo',
        'puntos',
        'descuento',
        'estado'
    ];

    protected $table = 'clientes';

    public function ventas()
    {
        return $this->hasMany(Venta::class);
    }

    public function historialCanjes()
    {
        return $this->hasMany(HistorialCanje::class);
    }

    public function getSaldoPuntosAttribute(): int
    {
        $ganados  = $this->ventas()->where('estado', 1)->sum('puntos');
        $canjeados = $this->historialCanjes()->sum('puntos');
        return $ganados - $canjeados;
    }

    public static function calcularPuntos(float $total): int
    {
        return (int) floor($total / 10);
    }

    public static function validateClienteAnonimo($cliente): bool
    {
        return $cliente->ci === config('app.ci_anonimo');
    }
}
