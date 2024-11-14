<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    public function orders()
    {
        return $this->belongsTo(Order::class);
    }
}
