<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Newsletter extends Model
{
    protected $fillable = [
        'email',
        'name',
        'is_active',
        'subscribed_at'
    ];

    protected $casts = [
        'subscribed_at' => 'datetime',
        'is_active' => 'boolean'
    ];
}
