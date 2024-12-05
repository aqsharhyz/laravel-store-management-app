<?php

namespace App\Models;

use App\Events\CategoryCreated;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name', 'description'];

    protected $dispatchesEvents = [
        'created' => CategoryCreated::class,
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
