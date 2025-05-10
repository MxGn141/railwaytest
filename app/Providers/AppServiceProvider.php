<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Passport\PassportServiceProvider;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->register(PassportServiceProvider::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
            
    }
}

