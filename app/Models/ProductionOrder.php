<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductionOrder extends Model
{
    use HasFactory;

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    protected $fillable = [
        'product_id',
        'user_id',
        'order_number',
        'quantity',
        'status',
        'priority',
        'estacion',
        'start_date',
        'end_date',
    ];

    // --- Relaciones ---
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function registros(): HasMany
    {
        return $this->hasMany(RegistroProduccion::class);
    }

    public function incidences(): HasMany
    {
        return $this->hasMany(Incidence::class);
    }

    public function subOrders(): HasMany
    {
        return $this->hasMany(ProductionSubOrder::class);
    }

    // --- Accessors ---

    // Este método devuelve la clase CSS según la prioridad
    public function getPriorityBadgeClassAttribute(): string
    {
        return match ($this->priority) {
            'low' => 'bg-stone-500',
            'medium' => 'bg-amber-500',
            'high' => 'bg-red-600',
            default => 'bg-stone-400',
        };
    }

    public function getPiezasRegistradasAttribute(): int
    {
        return $this->relationLoaded('registros')
            ? $this->registros->sum('cantidad')
            : $this->registros()->sum('cantidad');
    }

    public function getPorcentajeAvanceAttribute(): float
    {
        if ($this->quantity <= 0) {
            return 0;
        }
        return min(($this->piezas_registradas / $this->quantity) * 100, 100);
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'Pendiente',
            'in_progress' => 'En Progreso',
            'completed' => 'Completada',
            'cancelled' => 'Cancelada',
            default => $this->status,
        };
    }

    public function getPriorityLabelAttribute(): string
    {
        return match ($this->priority) {
            'low' => 'Baja',
            'medium' => 'Media',
            'high' => 'Alta',
            default => $this->priority ?? 'Sin definir',
        };
    }

    public function getPorcentajeAvanceGlobalAttribute(): float
    {
        if ($this->subOrders->isEmpty()) {
            return $this->porcentaje_avance;
        }

        return round($this->subOrders->avg(function ($sub) {
            return $sub->porcentaje_avance;
        }), 2);
    }
}