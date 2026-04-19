<?php

namespace App\Providers;

use App\Models\Availability;
use App\Models\MentorProfile;
use App\Models\Review;
use App\Models\Session;
use App\Policies\AvailabilityPolicy;
use App\Policies\MentorProfilePolicy;
use App\Policies\ReviewPolicy;
use App\Policies\SessionPolicy;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        MentorProfile::class => MentorProfilePolicy::class,
        Availability::class  => AvailabilityPolicy::class,
        Session::class       => SessionPolicy::class,
        Review::class        => ReviewPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();

        Gate::define('admin', fn($user) => $user->isAdmin());

        ResetPassword::createUrlUsing(function (object $notifiable, string $token) {
            return config('app.frontend_url', config('app.url'))
                . "/password-reset/$token?email={$notifiable->getEmailForPasswordReset()}";
        });
    }
}
