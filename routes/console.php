<?php

use App\Models\Website;
use App\Models\WebsiteStatus;
use Illuminate\Support\Facades\Artisan;

function getWebsites()
{
    return Website::query()
        ->with([
            'monitorLocations' => function ($query) {
                $query->where('is_active', true);
            },
        ])
        ->whereNot('website_status_id', WebsiteStatus::PAUSED)
        ->cursor();
}

/**
 * Application: Migrate SQL Views
 */
Artisan::command('migrate:views', function () {
    $viewsDirectory = database_path('views');
    $viewMigrations = glob("$viewsDirectory/*.sql");

    foreach ($viewMigrations as $migrationFile) {
        \DB::statement(file_get_contents($migrationFile));
    }
});

/**
 * Monitor: Response Code
 */
Artisan::command('app:queue-batch-check-response-code', function () {
    foreach (getWebsites() as $website) {
        $website->monitorLocations->each(function ($monitorLocation) use ($website) {
            \App\Jobs\Monitors\CheckResponseCode::dispatch($website, $monitorLocation);
        });
    }
})->everyMinute();

/**
 * Monitor: Response Time
 */
Artisan::command('app:queue-batch-check-response-time', function () {
    foreach (getWebsites() as $website) {
        $website->monitorLocations->each(function ($monitorLocation) use ($website) {
            \App\Jobs\Monitors\CheckResponseTime::dispatch($website, $monitorLocation);
        });
    }
})->everyMinute();

/**
 * Monitor: SSL Validity
 */
Artisan::command('app:queue-batch-check-ssl-validity', function () {
    foreach (getWebsites() as $website) {
        $website->monitorLocations->each(function ($monitorLocation) use ($website) {
            \App\Jobs\Monitors\CheckSSLValidity::dispatch($website);
        });
    }
})->daily();

/**
 * Monitor: SSL Expiration
 */
Artisan::command('app:queue-batch-check-ssl-expiration', function () {
    foreach (getWebsites() as $website) {
        $website->monitorLocations->each(function ($monitorLocation) use ($website) {
            \App\Jobs\Monitors\CheckSSLExpiration::dispatch($website);
        });
    }
})->weekly();

/**
 * Monitor: Domain Expiration
 */
Artisan::command('app:queue-batch-check-domain-expiration', function () {
    foreach (getWebsites() as $website) {
        $website->monitorLocations->each(function ($monitorLocation) use ($website) {
            \App\Jobs\Monitors\CheckDomainExpiration::dispatch($website);
        });
    }
})->daily();

/**
 * Monitor: Domain Nameservers
 */
Artisan::command('app:queue-batch-check-domain-ns', function () {
    foreach (getWebsites() as $website) {
        $website->monitorLocations->each(function ($monitorLocation) use ($website) {
            \App\Jobs\Monitors\CheckDomainNameservers::dispatch($website);
        });
    }
})->daily();
