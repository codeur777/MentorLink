<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\MentorSession;
use App\Models\MentorProfile;
use App\Models\Review;
use App\Policies\SessionPolicy;
use App\Policies\ReviewPolicy;
use App\Policies\MentorProfilePolicy;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        MentorSession::class => SessionPolicy::class,
        MentorProfile::class => MentorProfilePolicy::class,
        Review::class        => ReviewPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
