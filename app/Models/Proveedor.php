<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    use HasFactory;

    public const EMPRESA    = 'empresa';
    public const PARTICULAR = 'particular';
    public const TIPOS      = [self::EMPRESA, self::PARTICULAR];

    protected $table = 'proveedores';

    protected $fillable = [
        'nombre',
        'tipo',
        'contacto_nombre',
        'telefono',
        'email',
        'direccion',
    ];

    protected $attributes = [
        'tipo' => self::EMPRESA,
    ];

    public function materiales()
    {
        return $this->hasMany(Material::class, 'proveedor_id');
    }

    public function ingresos()
    {
        return $this->hasMany(MaterialStockLog::class, 'proveedor_id');
    }
}
