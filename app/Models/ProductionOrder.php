<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductionOrder extends Model
{
    use HasFactory;

    protected $fillable = [
    'product_id',
    'user_id',
    'order_number',
    'quantity',
    'status',
    'estacion',
    'start_date',
    'end_date',
];

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
}