<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistorialCanje extends Model
{
    protected $table = 'historial_canjes';

    protected $fillable = [
        'cliente_id',
        'descuento_id',
        'venta_id',
        'puntos',
        'fecha',
        'estado',
    ];

    protected $casts = [
        'fecha' => 'date',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function descuento()
    {
        return $this->belongsTo(Descuento::class);
    }

    public function venta()
    {
        return $this->belongsTo(Venta::class);
    }
}