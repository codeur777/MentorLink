<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id', 
        'reviewer_id', 
        'rating', 
        'comment'
    ];

    protected $casts = [
        'rating' => 'integer'
    ];

    public function session(): BelongsTo
    {
        return $this->belongsTo(MentorSession::class, 'session_id');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }
}
