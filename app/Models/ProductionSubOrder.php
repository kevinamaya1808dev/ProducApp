<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductionSubOrder extends Model
{
    use HasFactory;

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
    ];

    protected $fillable = [
        'production_order_id',
        'user_id',
        'proceso',
        'estacion',
        'quantity',
        'completed_pieces',
        'status',
        'start_date',
        'end_date',
        'notas',
    ];

    public function productionOrder(): BelongsTo
    {
        return $this->belongsTo(ProductionOrder::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getPorcentajeAvanceAttribute(): float
    {
        if ($this->quantity <= 0) return 0;
        return min(($this->completed_pieces / $this->quantity) * 100, 100);
    }
}