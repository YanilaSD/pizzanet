<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Cliente;
use App\Models\TipoPago;
use App\Models\Promocion;
use App\Models\Usuario; // si tienes modelo Usuario

class Venta extends Model
{
    protected $fillable = [
        'usuario_id',
        'promocion_id',
        'tipo_pago_id',
        'cliente_id',
        'puntos',
        'fecha',
        'subtotal',
        'descuento',
        'total',
        'estado',
    ];
    protected $casts = [
        'fecha' => 'datetime',
    ];

    protected $table = 'ventas';

    // Relación con Cliente
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    // Relación con Tipo de Pago
    public function tipoPago()
    {
        return $this->belongsTo(TipoPago::class, 'tipo_pago_id');
    }

    // Relación con Promoción
    public function promocion()
    {
        return $this->belongsTo(Promocion::class);
    }

    // Relación con Usuario
    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }

    // Relación con detalle de venta (si tienes detalle_ventas)
    public function detalleVentas()
    {
        return $this->hasMany(DetalleVenta::class);
    }
}
