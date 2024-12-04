<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shipper extends Model
{
    protected $fillable = ['name', 'phone'];

    public function shipments()
    {
        return $this->hasMany(Shipment::class);
    }
}
