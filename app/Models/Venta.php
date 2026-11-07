<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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

    public function estaAnulada(): bool
    {
        return (int) $this->estado === 0;
    }

    public function anular(): void
    {
        DB::transaction(function () {
            $this->refresh();

            if ($this->estaAnulada()) {
                return;
            }

            static::whereKey($this->id)->lockForUpdate()->first();

            foreach ($this->detalleVentas as $detalle) {
                $cantidad = (int) $detalle->cantidad;

                if ($cantidad <= 0) {
                    continue;
                }

                $inventario = Inventario::where('producto_id', $detalle->producto_id)
                    ->lockForUpdate()
                    ->first();

                if ($inventario) {
                    $inventario->incrementarCantidad($cantidad);
                }
            }

            $canjes = $this->historialCanjes()
                ->where('estado', 1)
                ->where('puntos', '>', 0)
                ->lockForUpdate()
                ->get();

            foreach ($canjes as $canje) {
                $canje->registrarReversionPorBaja();
            }

            $this->estado = 0;
            $this->save();
        });
    }
}
