<?php

namespace App\Providers;

use App\Http\Controllers\AuthorizationController;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //Passport::ignoreRoutes();
        $this->app->when(AuthorizationController::class)
                ->needs(StatefulGuard::class)
                ->give(fn () => Auth::guard(config('passport.guard', null)));
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
    }
}
