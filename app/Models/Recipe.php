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
}
