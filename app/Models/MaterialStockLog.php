<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaterialStockLog extends Model
{
    protected $fillable = [
        'material_id',
        'user_id',
        'quantity_added',
        'stock_resultante',
        'proveedor_id',
        'proveedor_manual',
    ];

    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function proveedor()
{
    return $this->belongsTo(Proveedor::class);
}

protected $appends = ['proveedor_nombre'];

public function getProveedorNombreAttribute()
{
    return $this->proveedor?->nombre ?? $this->proveedor_manual ?? '—';
}
}