<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use App\Models\Feature;

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
        if(config('app.env') !== 'local' || request()->server('HTTPS')) {
            \URL::forceScheme('https');
        }
        Blade::if('feature', function ($code) {
            return Feature::isActive($code);
        });
    }
}
