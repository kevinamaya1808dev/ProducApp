<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'code',
        'name',
        'description',
        'stock',
        'unit_cost',

        'image',

    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function productionOrders(): HasMany
    {
        return $this->hasMany(ProductionOrder::class);
    }

    public function recipes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Recipe::class);
    }
}