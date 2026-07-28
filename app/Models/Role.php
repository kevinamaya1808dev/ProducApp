<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug'];

    /**
     * Relación Muchos a Muchos con Permisos (tabla pivote permission_role)
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }

    /**
     * Relación Muchos a Muchos con Usuarios (tabla pivote role_user)
     */
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}