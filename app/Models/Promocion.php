<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promocion extends Model
{
    public const ESTADO_BAJA = 0;

    public const ESTADO_ACTIVO = 1;

    public const ESTADO_COMPLETADO = 2;

    protected $fillable = [
        'festividad_id', 'nombre', 'descuento', 'fecha_inicio', 'fecha_fin',
        'compra_minima', 'limite_uso', 'estado'
    ];

    // Si la tabla no sigue la convención plural, puedes definirla así
    protected $table = 'promociones';

    // Si los nombres de las columnas en la base de datos son diferentes a los del modelo
    // (por ejemplo, si usas snake_case en lugar de camelCase), puedes definirlos aquí.

    protected $casts = [
        'fecha_inicio' => 'datetime',
        'fecha_fin' => 'datetime',
        'estado' => 'integer',
    ];

    // Función de relación con la tabla 'festividades', ya que 'festividad_id' es una clave foránea
    public function festividad()
    {
        return $this->belongsTo(Festividad::class, 'festividad_id');
    }

    public function scopeConFestividadActiva($query)
    {
        return $query->whereHas('festividad', function ($q) {
            $q->where('estado', 1);
        });
    }

    public function scopeActivas($query)
    {
        return $query->where('estado', self::ESTADO_ACTIVO);
    }

    public static function sincronizarCompletadas(): void
    {
        $completadaPorVigenciaOUso = function ($query) {
            $query->whereDate('fecha_fin', '<', now()->toDateString())
                ->orWhere('limite_uso', '<=', 0);
        };

        static::query()
            ->activas()
            ->where($completadaPorVigenciaOUso)
            ->update(['estado' => self::ESTADO_COMPLETADO]);

        static::query()
            ->where('estado', self::ESTADO_BAJA)
            ->where($completadaPorVigenciaOUso)
            ->update(['estado' => self::ESTADO_COMPLETADO]);
    }

    public static function resolverEstado(string $fechaFin, ?int $limiteUso): int
    {
        if ($fechaFin < now()->toDateString()) {
            return self::ESTADO_COMPLETADO;
        }

        if ($limiteUso !== null && $limiteUso <= 0) {
            return self::ESTADO_COMPLETADO;
        }

        return self::ESTADO_ACTIVO;
    }

    public function marcarCompletadaSiAgotada(): void
    {
        if ((int) $this->estado !== self::ESTADO_ACTIVO) {
            return;
        }

        if ($this->limite_uso !== null && (int) $this->limite_uso <= 0) {
            $this->update(['estado' => self::ESTADO_COMPLETADO]);
        }
    }

    public function etiquetaEstado(): string
    {
        return match ((int) $this->estado) {
            self::ESTADO_ACTIVO => 'Activo',
            self::ESTADO_COMPLETADO => 'Completado',
            default => 'Dado de baja',
        };
    }

    public function colorEstado(): string
    {
        return match ((int) $this->estado) {
            self::ESTADO_ACTIVO => 'green',
            self::ESTADO_COMPLETADO => 'amber',
            default => 'red',
        };
    }
}
