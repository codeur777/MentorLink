<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MentorshipGoal extends Model
{
    use HasFactory;

    protected $fillable = [
        'mentorship_id',
        'title',
        'description',
        'status',
        'progress',
        'due_at',
    ];

    protected $casts = [
        'progress' => 'integer',
        'due_at' => 'datetime',
    ];

    public function mentorship(): BelongsTo
    {
        return $this->belongsTo(Mentorship::class);
    }
}
