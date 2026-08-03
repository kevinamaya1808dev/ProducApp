<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IncidenceLog extends Model
{
    protected $fillable = ['incidence_id', 'user_id', 'type', 'comment'];

    public function incidence() {
        return $this->belongsTo(Incidence::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}