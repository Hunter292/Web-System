<?php

namespace App\Providers;

use App\Models\Employee;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\ServiceProvider;

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
        ResetPassword::createUrlUsing(function (Employee $user, string $token) {
        return 'http://localhost:4200/reset-password?token='.$token;
    });
    }
}
