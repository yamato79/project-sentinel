<?php

namespace App\Providers;

use App\Models\Website;
use App\Observers\WebsiteObserver;
use Illuminate\Support\Facades\Gate;
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
        Website::observe(WebsiteObserver::class);

        // Gate::define('viewPulse', function () {
        //     return true;
        // });
    }
}
