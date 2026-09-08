<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RegistroProduccion extends Model
{
    use HasFactory;

    protected $table = 'registro_produccions';

    protected $fillable = [
        'user_id',
        'production_order_id',
        'sub_order_id', // CORREGIDO: faltaba aquí, así que Eloquent lo descartaba en silencio al hacer create()
        'cantidad',
        'nota',
        'fecha_registro',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function productionOrder(): BelongsTo
    {
        return $this->belongsTo(ProductionOrder::class);
    }

    // NUEVO: antes no existía ninguna relación hacia la suborden, porque la
    // columna ni siquiera existía en la tabla.
    public function subOrder(): BelongsTo
    {
        return $this->belongsTo(ProductionSubOrder::class, 'sub_order_id');
    }
}