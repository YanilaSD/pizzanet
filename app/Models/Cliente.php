<?php

namespace App\Models;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use NotificationChannels\WebPush\HasPushSubscriptions;

class Cliente extends Model
{
    use HasPushSubscriptions, Notifiable;

    protected $fillable = [
        'nombre',
        'ci',
        'celular',
        'correo',
        'estado',
    ];

    protected $table = 'clientes';

    public function ventas()
    {
        return $this->hasMany(Venta::class);
    }

    public function historialCanjes()
    {
        return $this->hasMany(HistorialCanje::class);
    }

    public function getSaldoPuntosAttribute(): int
    {
        $ganados = $this->ventas()->where('estado', 1)->sum('puntos');
        $canjeados = $this->historialCanjes()->where('estado', 1)->sum('puntos');

        return $ganados - $canjeados;
    }

    public function movimientosPuntos(int $perPage = 10, string $pageName = 'page'): LengthAwarePaginator
    {
        $texto = DB::getDriverName() === 'sqlite' ? 'TEXT' : 'CHAR(255)';

        $ganados = DB::table('ventas')
            ->selectRaw("ventas.id as origen_id, ventas.created_at as fecha, ventas.puntos as puntos, 'ganado' as tipo, ventas.id as venta_id, CAST('' AS {$texto}) as detalle, ventas.estado as estado, CAST('venta' AS {$texto}) as origen")
            ->where('ventas.cliente_id', $this->id)
            ->where('ventas.puntos', '>', 0);

        $canjeados = DB::table('historial_canjes')
            ->leftJoin('canje_puntos', 'canje_puntos.id', '=', 'historial_canjes.canje_punto_id')
            ->selectRaw("historial_canjes.id as origen_id, historial_canjes.created_at as fecha, ABS(historial_canjes.puntos) as puntos, CASE WHEN historial_canjes.puntos < 0 THEN 'revertido' ELSE 'canjeado' END as tipo, historial_canjes.venta_id as venta_id, CAST(COALESCE(canje_puntos.nombre, '') AS {$texto}) as detalle, historial_canjes.estado as estado, CAST('canje' AS {$texto}) as origen")
            ->where('historial_canjes.cliente_id', $this->id);

        $reversoGanados = DB::table('ventas')
            ->selectRaw("ventas.id as origen_id, ventas.updated_at as fecha, ventas.puntos as puntos, 'revertido' as tipo, ventas.id as venta_id, CAST('' AS {$texto}) as detalle, 0 as estado, CAST('venta' AS {$texto}) as origen")
            ->where('ventas.cliente_id', $this->id)
            ->where('ventas.estado', 0)
            ->where('ventas.puntos', '>', 0);

        return DB::query()
            ->fromSub($ganados->unionAll($canjeados)->unionAll($reversoGanados), 'movimientos')
            ->orderByDesc('fecha')
            ->orderByDesc('origen_id')
            ->paginate($perPage, ['*'], $pageName)
            ->withQueryString();
    }

    public static function calcularPuntos(float $total): int
    {
        return (int) floor($total / 10);
    }

    public function esAnonimo(): bool
    {
        return $this->ci === config('app.ci_anonimo');
    }

    public static function validateClienteAnonimo($cliente): bool
    {
        return $cliente instanceof self && $cliente->esAnonimo();
    }
}
