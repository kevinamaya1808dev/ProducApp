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
        'start_date' => 'date',
        'end_date'   => 'date',
    ];

    protected $fillable = [
        'production_order_id',
        'recipe_component_id',
        'proceso',
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

    // Relación con el componente de la receta (si aplica)
    public function recipeComponent(): BelongsTo
    {
        return $this->belongsTo(RecipeComponent::class);
    }

    // Operarios y estaciones asignadas a esta suborden (Muchos a Muchos)
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
}