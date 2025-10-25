<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promocion extends Model
{
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
        'estado' => 'boolean',  // Se asegura de que estado sea tratado como un booleano
    ];

    // Función de relación con la tabla 'festividades', ya que 'festividad_id' es una clave foránea
    public function festividad()
    {
        return $this->belongsTo(Festividad::class, 'festividad_id');
    }
}
