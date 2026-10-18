<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Festividad extends Model
{
    protected $table = 'festividades';
    protected $fillable = ['nombre', 'descripcion', 'estado'];
}
