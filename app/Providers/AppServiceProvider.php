<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Laravel\Socialite\Contracts\Factory;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (config('app.env') !== 'local') {
            URL::forceScheme('https');
        }

        $socialite = $this->app->make(Factory::class);
        $socialite->extend('sipetra', function ($app) use ($socialite) {
            $config = $app['config']['services.sipetra'];

            return $socialite->buildProvider(SipetraSocialiteProvider::class, $config);
        });

        // Register custom Gate before hook
        Gate::before(function ($user, $ability, $args) {
            // Prevent users from deleting themselves
            if ($ability === 'delete' && isset($args[0]) && $args[0] instanceof User && $user->id === $args[0]->id) {
                return false;
            }

            // Super admin has all permissions
            if (method_exists($user, 'isSuperAdmin') && $user->isSuperAdmin()) {
                return true;
            }
        });
    }
}
