<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MentorProfile extends Model
{
    use HasFactory;

    protected $table = 'mentor_profiles';

    protected $fillable = ['user_id', 'domains', 'hourly_rate', 'is_validated'];

    protected $casts = [
        'domains'     => 'array',
        'is_validated' => 'boolean',
        'hourly_rate' => 'decimal:2',
    protected $fillable = [
        'user_id',
        'headline',
        'bio',
        'focus_area',
        'availability_note',
        'session_format',
        'expertise_tags',
        'years_experience',
        'is_listed',
    ];

    protected $casts = [
        'expertise_tags' => 'array',
        'years_experience' => 'integer',
        'is_listed' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Average rating calculated from reviews of completed sessions for this mentor.
     */
    public function getAverageRatingAttribute(): ?float
    {
        $avg = Review::where('mentor_id', $this->user_id)->avg('rating');
        return $avg ? round((float) $avg, 1) : null;
    }

    /**
     * Total number of reviews for this mentor.
     */
    public function getReviewCountAttribute(): int
    {
        return Review::where('mentor_id', $this->user_id)->count();
    }
}
