<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'password', 'role', 'bio', 'avatar'];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
    ];

    // ------------------------------------------------------------------ relationships

    public function mentorProfile(): HasOne
    {
        return $this->hasOne(MentorProfile::class);
    }

    public function availabilities(): HasMany
    {
        return $this->hasMany(Availability::class, 'mentor_id');
    }

    /** Sessions where this user is the mentor */
    public function mentorSessions(): HasMany
    {
        return $this->hasMany(Session::class, 'mentor_id');
    }

    /** Sessions where this user is the mentee */
    public function menteeSessions(): HasMany
    {
        return $this->hasMany(Session::class, 'mentee_id');
    }

    // ------------------------------------------------------------------ role helpers

    public function isMentor(): bool
    {
        return $this->role === 'mentor';
    }

    public function isMentee(): bool
    {
        return $this->role === 'mentee';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
}
