<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventario extends Model
{
    protected $table = 'inventarios';
    protected $fillable = [
        'producto_id',
        'cantidad',
        'estado',
    ];

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    public function decrementarCantidad(int $cantidad): void
    {
        if ($cantidad > $this->cantidad || $cantidad < 0) {
            throw new \Exception('Stock insuficiente.');
        }

        $this->cantidad -= $cantidad;
        $this->save();
    }
}
