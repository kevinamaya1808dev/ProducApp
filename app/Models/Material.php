<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Material extends Model
{
    use HasFactory;

    protected $table = 'materials';

    public const CACHE_STOCK_BAJO = 'sidebar.low_stock_materials';

    protected static function booted(): void
    {
        // Cualquier cambio de material invalida el contador del sidebar.
        static::saved(fn () => Cache::forget(self::CACHE_STOCK_BAJO));
        static::deleted(fn () => Cache::forget(self::CACHE_STOCK_BAJO));
    }

    /** Materiales en o bajo su mínimo (badge del sidebar). Cacheado 2 min como respaldo. */
    public static function lowStockCount(): int
    {
        return Cache::remember(self::CACHE_STOCK_BAJO, 120, fn () => static::whereColumn('stock_actual', '<=', 'stock_minimo')->count());
    }

    protected $fillable = [
        'name',
        'sku',
        'unit',
        'stock_actual',
        'stock_minimo',
        'proveedor',      // proveedor TEMPORAL: texto escrito a mano (no está en el directorio)
        'proveedor_id',   // proveedor REGISTRADO (directorio)
    ];

    public function proveedorRegistrado()
    {
        return $this->belongsTo(Proveedor::class, 'proveedor_id');
    }

    /** Nombre a mostrar: el del proveedor registrado, o el texto temporal. */
    public function getProveedorNombreAttribute(): ?string
    {
        return $this->proveedorRegistrado?->nombre ?? ($this->proveedor ?: null);
    }

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