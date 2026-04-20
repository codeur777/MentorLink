<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MentorPenalty extends Model
{
    use HasFactory;

    protected $fillable = [
        'mentor_id',
        'penalty_amount',
        'reason',
        'description'
    ];

    protected $casts = [
        'penalty_amount' => 'decimal:2'
    ];

    public function mentor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'mentor_id');
    }
}