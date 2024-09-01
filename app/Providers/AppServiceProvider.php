<?php

namespace App\Providers;

use App\Services\OTP\IchtrojanOTP;
use App\Services\OTP\OTP;
use App\Services\SMS\SMS;
use App\Services\SMS\TwilioSMS;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if ($this->app->environment('local')) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }


        $this->app->bind(OTP::class, IchtrojanOTP::class);
        $this->app->bind(SMS::class, TwilioSMS::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
