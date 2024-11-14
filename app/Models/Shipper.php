<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shipper extends Model
{
    public function shipments()
    {
        return $this->hasMany(Shipment::class);
    }
}
