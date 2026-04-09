<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MentorProfile extends Model
{
    use HasFactory;

    protected $table = 'mentor_profiles';

    protected $fillable = ['user_id', 'domains', 'hourly_rate', 'is_validated'];

    protected $casts = [
        'domains'      => 'array',
        'is_validated' => 'boolean',
        'hourly_rate'  => 'decimal:2',
    ];

    public function getAverageRatingAttribute(): ?float
    {
        $avg = Review::whereHas('session', function ($q) {
            $q->where('mentor_id', $this->user_id);
        })->avg('rating');

        return $avg ? round($avg, 2) : null;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
