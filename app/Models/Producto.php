<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $tables = 'productos';
     protected $fillable = [
        'categoria_id',
        'nombre',
        'descripcion',
        'precio',
        'imagen',
        'estado',
    ];

    // Relación con la tabla Categorias
    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    // Accesor para obtener la URL de la imagen
    public function getImagenUrlAttribute()
    {
        return $this->imagen ? asset('storage/' . $this->imagen) : null;
    }
}
