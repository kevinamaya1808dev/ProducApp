<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Incidence extends Model
{
    protected $fillable = ['production_order_id', 'user_id', 'title', 'description', 'status'];

    public function order() {
        return $this->belongsTo(ProductionOrder::class, 'production_order_id');
    }

    public function operario() {
        return $this->belongsTo(User::class, 'user_id');
    }
}
