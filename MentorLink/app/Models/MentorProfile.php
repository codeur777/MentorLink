<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MentorProfile extends Model
{
    use HasFactory;

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
}
