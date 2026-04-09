<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class MentorSession extends Model
{
    use HasFactory;

    protected $table = 'mentor_sessions';

    protected $fillable = ['mentor_id', 'mentee_id', 'scheduled_at', 'duration_min', 'status'];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'duration_min' => 'integer',
    ];

    public function mentor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'mentor_id');
    }

    public function mentee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'mentee_id');
    }

    public function review(): HasOne
    {
        return $this->hasOne(Review::class, 'session_id');
    }

    public function isConfirmed(): bool { return $this->status === 'confirmee'; }
    public function isCompleted(): bool { return $this->status === 'terminee'; }
}
