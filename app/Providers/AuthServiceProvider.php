<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        Passport::routes();
        Passport::routes(null, ['prefix' => 'api/v1/oauth']);
        Passport::personalAccessTokensExpireIn(now()->add(config('session.token_lifetime'), config('session.token_lifetime_unit')));
        Passport::tokensExpireIn(now()->addMinutes(config('session.token_lifetime'), config('session.token_lifetime_unit')));
        Passport::refreshTokensExpireIn(now()->addMinutes(config('session.token_lifetime_refresh'), config('session.token_lifetime_refresh_unit')));
    }
}
