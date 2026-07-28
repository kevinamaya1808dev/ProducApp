<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $fillable = ['name', 'slug'];

    // Un permiso pertenece a muchos roles
    public function roles() {
        return $this->belongsToMany(Role::class);
    }
}
