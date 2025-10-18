<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Privilegio extends Model
{
    protected $fillable = [
        'nombre',
        'descripcion',
        'estado',
    ];
}
