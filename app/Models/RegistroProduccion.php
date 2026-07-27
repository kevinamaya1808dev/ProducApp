<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegistroProduccion extends Model
{
    use HasFactory;

    protected $table = 'registro_produccions';

    protected $fillable = [
        'user_id',
        'lote_id',
        'cantidad',
        'fecha_registro',
    ];

    /**
     * Relación con el usuario (operario)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}