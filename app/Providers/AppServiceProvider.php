<?php

namespace App\Providers;

use App\Services\OTP\IchtrojanOTP;
use App\Services\OTP\OTP;
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

//        $this->app->bind(OTPService::class, function () {
//            $otp = new IchtrojanOTP();
//            return new OTPService($otp);
//        });

        $this->app->bind(OTP::class, IchtrojanOTP::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
