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

    public function historialCanjes()
    {
        return $this->hasMany(HistorialCanje::class);
    }

    /**
     * Bloques completos que alcanza el saldo.
     * 100 pts = Bs 10 y 234 pts permiten 2 bloques: 200 pts = Bs 20.
     *
     * @return array{bloques: int, puntos: int, descuento: float}
     */
    public function canjeDisponible(int $saldoPuntos): array
    {
        $puntosBase = (int) $this->puntos;
        $descuentoBase = round((float) $this->descuento, 2);

        if ($puntosBase <= 0 || $descuentoBase <= 0 || $saldoPuntos < $puntosBase) {
            return [
                'bloques' => 0,
                'puntos' => 0,
                'descuento' => 0.0,
            ];
        }

        $bloques = intdiv($saldoPuntos, $puntosBase);

        return [
            'bloques' => $bloques,
            'puntos' => $bloques * $puntosBase,
            'descuento' => round($bloques * $descuentoBase, 2),
        ];
    }

    /**
     * Igual que el canje disponible, limitado a los bloques que cubre el subtotal.
     * Si un solo bloque ya supera la compra, se aplica uno y el descuento se topa al subtotal.
     *
     * @return array{bloques: int, puntos: int, descuento: float}
     */
    public function canjeParaSubtotal(int $saldoPuntos, float $subtotal): array
    {
        $disponible = $this->canjeDisponible($saldoPuntos);
        $subtotal = round($subtotal, 2);

        if ($disponible['bloques'] < 1 || $subtotal <= 0) {
            return [
                'bloques' => 0,
                'puntos' => 0,
                'descuento' => 0.0,
            ];
        }

        $puntosBase = (int) $this->puntos;
        $descuentoCentavos = (int) round(((float) $this->descuento) * 100);
        $subtotalCentavos = (int) round($subtotal * 100);
        $bloques = $disponible['bloques'];

        if ($descuentoCentavos > 0) {
            $maxBloques = intdiv($subtotalCentavos, $descuentoCentavos);
            $bloques = $maxBloques >= 1
                ? min($bloques, $maxBloques)
                : 1;
        }

        $descuento = round($bloques * (float) $this->descuento, 2);

        return [
            'bloques' => $bloques,
            'puntos' => $bloques * $puntosBase,
            'descuento' => min($descuento, $subtotal),
        ];
    }
}
