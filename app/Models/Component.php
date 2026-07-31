<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Component extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'component_type_id',
        'name',
        'sku',
        'base_unit',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function componentType()
    {
        return $this->belongsTo(ComponentType::class);
    }

    public function recipes()
    {
        return $this->belongsToMany(Recipe::class, 'component_recipe')
                    ->withPivot('quantity')
                    ->withTimestamps();
    }
}