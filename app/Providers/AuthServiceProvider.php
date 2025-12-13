<?php

namespace App\Providers;

use Carbon\Carbon;
use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();


        $expire_time = Carbon::now('+06:00')->addMinute(config('app.session_lifetime'));
        // dd($expire_time);
        Passport::routes();
        Passport::tokensExpireIn($expire_time);
        Passport::refreshTokensExpireIn($expire_time);
        Passport::personalAccessTokensExpireIn($expire_time);
    }
}
