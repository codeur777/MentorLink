<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'password', 'role', 'bio', 'avatar', 'suspended'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'role',
        'password',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
        'suspended'         => 'boolean',
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

    public function mentorSessions(): HasMany
    {
        return $this->hasMany(Session::class, 'mentor_id');
    }

    public function menteeSessions(): HasMany
    {
        return $this->hasMany(Session::class, 'mentee_id');
    }

    public function reportsMade(): HasMany
    {
        return $this->hasMany(Report::class, 'reporter_id');
    }

    public function reportsReceived(): HasMany
    {
        return $this->hasMany(Report::class, 'reported_id');
    }

    // ------------------------------------------------------------------ role helpers

    public function isMentor(): bool  { return $this->role === 'mentor'; }
    public function isMentee(): bool  { return $this->role === 'mentee'; }
    public function isAdmin(): bool   { return $this->role === 'admin'; }
    public function mentorMentorships(): HasMany
    {
        return $this->hasMany(Mentorship::class, 'mentor_id');
    }

    public function menteeMentorships(): HasMany
    {
        return $this->hasMany(Mentorship::class, 'mentee_id');
    }
}
