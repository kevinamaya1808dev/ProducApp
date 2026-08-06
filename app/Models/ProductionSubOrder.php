<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ProductionSubOrder extends Model
{
    use HasFactory;

    protected $casts = [
        'start_date'    => 'date',
        'end_date'      => 'date',
        'es_ensamblaje' => 'boolean',
    ];

    protected $fillable = [
        'production_order_id',
        'component_id',
        'proceso',
        'quantity',
        'completed_pieces',
        'status',
        'es_ensamblaje',
        'start_date',
        'end_date',
        'notas',
    ];

    public function productionOrder(): BelongsTo
    {
        return $this->belongsTo(ProductionOrder::class);
    }

    // Renombrado: antes apuntaba a un modelo/tabla que no existía
    public function component(): BelongsTo
    {
        return $this->belongsTo(Component::class);
    }

    public function assignedUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'production_sub_order_user')
                    ->withPivot('estacion', 'pieces_contributed')
                    ->withTimestamps();
    }

    public function getPorcentajeAvanceAttribute(): float
    {
        if ($this->quantity <= 0) return 0;
        return min(($this->completed_pieces / $this->quantity) * 100, 100);
    }

    // Piezas que faltan para terminar esta fase
    public function getRestantesAttribute(): int
    {
        return max(0, $this->quantity - $this->completed_pieces);
    }

    // true cuando quedan entre 1 y 3 piezas (para disparar la alerta a todos los operarios asignados)
    public function getAlertaCercanaAttribute(): bool
    {
        return $this->restantes > 0 && $this->restantes <= 3;
    }
}