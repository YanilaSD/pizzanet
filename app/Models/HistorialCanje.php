<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistorialCanje extends Model
{
    protected $table = 'historial_canjes';

    protected $fillable = [
        'cliente_id',
        'canje_punto_id',
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

    public function canjePunto()
    {
        return $this->belongsTo(CanjePunto::class);
    }

    public function descuento()
    {
        return $this->belongsTo(CanjePunto::class, 'canje_punto_id');
    }

    public function venta()
    {
        return $this->belongsTo(Venta::class);
    }

    public function esReversion(): bool
    {
        return (int) $this->puntos < 0;
    }

    public function registrarReversionPorBaja(): self
    {
        if ($this->esReversion()) {
            return $this;
        }

        $puntosRevertidos = -abs((int) $this->puntos);

        $existente = static::query()
            ->where('cliente_id', $this->cliente_id)
            ->where('canje_punto_id', $this->canje_punto_id)
            ->where('venta_id', $this->venta_id)
            ->where('puntos', $puntosRevertidos)
            ->first();

        if ($existente) {
            return $existente;
        }

        return static::create([
            'cliente_id' => $this->cliente_id,
            'canje_punto_id' => $this->canje_punto_id,
            'venta_id' => $this->venta_id,
            'puntos' => $puntosRevertidos,
            'fecha' => now()->toDateString(),
            'estado' => 1,
        ]);
    }

    public function montoDescuento(): float
    {
        if ($this->venta_id && $this->venta) {
            return round((float) $this->venta->descuento, 2);
        }

        $regla = $this->descuento;
        $basePuntos = (int) ($regla->puntos ?? 0);
        $baseMonto = (float) ($regla->descuento ?? 0);

        if ($basePuntos <= 0 || $baseMonto <= 0) {
            return 0.0;
        }

        return round(intdiv((int) $this->puntos, $basePuntos) * $baseMonto, 2);
    }
}