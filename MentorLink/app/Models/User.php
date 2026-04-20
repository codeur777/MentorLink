<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name', 
        'email', 
        'password', 
        'role', 
        'bio', 
        'avatar',
        'average_rating',
        'total_reviews'
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
        'average_rating'    => 'decimal:2',
        'total_reviews'     => 'integer',
    ];

    public function mentorProfile(): HasOne
    {
        return $this->hasOne(MentorProfile::class);
    }

    public function availabilities(): HasMany
    {
        return $this->hasMany(Availability::class, 'mentor_id');
    }

    public function mentorSessions(): HasMany
    {
        return $this->hasMany(MentorSession::class, 'mentor_id');
    }

    public function menteeSessions(): HasMany
    {
        return $this->hasMany(MentorSession::class, 'mentee_id');
    }

    public function penalties(): HasMany
    {
        return $this->hasMany(MentorPenalty::class, 'mentor_id');
    }

    public function isMentor(): bool { return $this->role === 'mentor'; }
    public function isMentee(): bool { return $this->role === 'mentee'; }
    public function isAdmin(): bool  { return $this->role === 'admin'; }
}
