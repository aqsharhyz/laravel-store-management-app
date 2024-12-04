<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'phone_number',
        'address',
        'position',
        'salary',
        'status',
        'date_of_birth',
        'date_of_joining',
        'date_of_leaving',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
