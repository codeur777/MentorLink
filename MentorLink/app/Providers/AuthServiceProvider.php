<?php

namespace App\Providers;

use App\Models\Availability;
use App\Models\MentorProfile;
use App\Policies\AvailabilityPolicy;
use App\Policies\MentorProfilePolicy;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        MentorProfile::class => MentorProfilePolicy::class,
        Availability::class  => AvailabilityPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Gate for admin-only actions
        Gate::define('admin', function ($user) {
            return $user->isAdmin();
        });

        ResetPassword::createUrlUsing(function (object $notifiable, string $token) {
            return config('app.frontend_url', config('app.url'))
                . "/password-reset/$token?email={$notifiable->getEmailForPasswordReset()}";
        });
    }
}
