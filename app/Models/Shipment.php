<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    public function orders()
    {
        return $this->belongsTo(Order::class);
    }

    public function shipper()
    {
        return $this->belongsTo(Shipper::class);
    }

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }
}
