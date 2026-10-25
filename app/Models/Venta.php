<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    protected $fillable = [
        'usuario_id',
        'promocion_id',
        'tipo_pago_id',
        'cliente_id',
        'puntos',
        'subtotal',
        'descuento',
        'total',
        'estado',
    ];

    protected $table = 'ventas';

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function tipoPago()
    {
        return $this->belongsTo(TipoPago::class, 'tipo_pago_id');
    }

    public function promocion()
    {
        return $this->belongsTo(Promocion::class);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function detalleVentas()
    {
        return $this->hasMany(DetalleVenta::class);
    }

    public function historialCanjes()
    {
        return $this->hasMany(HistorialCanje::class);
    }
}
