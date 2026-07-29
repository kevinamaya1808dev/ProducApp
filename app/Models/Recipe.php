<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    protected $fillable = ['product_id', 'name', 'instructions'];

    // Una receta pertenece a un producto
    public function product() {
        return $this->belongsTo(Product::class);
    }

    public function components()
{
    // Una receta tiene muchos componentes
    // withPivot nos permite traer la cantidad específica de esa receta
    return $this->belongsToMany(Component::class)
                ->withPivot('quantity')
                ->withTimestamps();
}
}
