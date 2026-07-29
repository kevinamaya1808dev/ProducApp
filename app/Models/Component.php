<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Component extends Model
{
    use HasFactory;

    // Campos permitidos para asignación masiva
    protected $fillable = [
        'category_id',
        'name',
        'sku',
        'base_unit'
    ];

    // Relación: Un componente pertenece a una categoría
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Relación: Un componente está en muchas recetas (BOM)
    public function recipes()
    {
        return $this->belongsToMany(Recipe::class, 'component_recipe')
                    ->withPivot('quantity')
                    ->withTimestamps();
    }
}