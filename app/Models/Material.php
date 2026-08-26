<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;

    protected $table = 'materials';

    protected $fillable = [
        'name',
        'sku',
        'unit',
        'stock_actual',
        'stock_minimo',
        'proveedor',
    ];

    public function recipes()
    {
        return $this->hasMany(ProductRecipe::class);
    }

    // NUEVO: historial de entradas de stock, más reciente primero.
    public function stockLogs()
    {
        return $this->hasMany(MaterialStockLog::class)->latest();
    }
}