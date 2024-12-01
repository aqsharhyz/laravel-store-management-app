<?php

namespace App\Models;

use App\Notifications\NewCategory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    // protected $fillable = ['name', 'description'];

    protected $dispatchesEvents = [
        'created' => NewCategory::class,
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
