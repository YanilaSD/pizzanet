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
}
