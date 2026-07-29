<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserCertification extends Model
{
    protected $fillable = ['user_id', 'nombre', 'fecha_obtencion'];

    protected $casts = [
        'fecha_obtencion' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}