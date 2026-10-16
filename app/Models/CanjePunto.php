<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CanjePunto extends Model
{
    protected $fillable = [
        'nombre',
        'descripcion',
        'puntos',
        'descuento',
        'estado',
    ];

    protected $casts = [
        'puntos' => 'integer',
        'descuento' => 'decimal:2',
        'estado' => 'integer',
    ];
}
