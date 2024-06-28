<?php

use App\Models\Website;
use App\Models\WebsiteStatus;
use Illuminate\Support\Facades\Artisan;

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
    $websites = Website::query()
        ->whereNot('website_status_id', WebsiteStatus::PAUSED)
        ->cursor();

    foreach ($websites as $website) {
        \App\Jobs\Monitors\CheckResponseCode::dispatch($website);
    }
})->everyMinute();

/**
 * Monitor: Response Time
 */
Artisan::command('app:queue-batch-check-response-time', function () {
    $websites = Website::query()
        ->whereNot('website_status_id', WebsiteStatus::PAUSED)
        ->cursor();

    foreach ($websites as $website) {
        \App\Jobs\Monitors\CheckResponseTime::dispatch($website);
    }
})->everyMinute();

/**
 * Monitor: SSL Valid
 */
Artisan::command('app:queue-batch-check-ssl-valid', function () {
    $websites = Website::query()
        ->whereNot('website_status_id', WebsiteStatus::PAUSED)
        ->cursor();

    foreach ($websites as $website) {
        \App\Jobs\Monitors\CheckSSLValid::dispatch($website);
    }
})->daily();

/**
 * Monitor: SSL Expiry
 */
Artisan::command('app:queue-batch-check-ssl-expiry', function () {
    $websites = Website::query()
        ->whereNot('website_status_id', WebsiteStatus::PAUSED)
        ->cursor();

    foreach ($websites as $website) {
        \App\Jobs\Monitors\CheckSSLExpiry::dispatch($website);
    }
})->weekly();

/**
 * Monitor: Domain Expiry
 */
Artisan::command('app:queue-batch-check-domain-expiry', function () {
    $websites = Website::query()
        ->whereNot('website_status_id', WebsiteStatus::PAUSED)
        ->cursor();

    foreach ($websites as $website) {
        \App\Jobs\Monitors\CheckDomainExpiry::dispatch($website);
    }
})->daily();

/**
 * Monitor: Domain Nameservers
 */
Artisan::command('app:queue-batch-check-domain-ns', function () {
    $websites = Website::query()
        ->whereNot('website_status_id', WebsiteStatus::PAUSED)
        ->cursor();

    foreach ($websites as $website) {
        \App\Jobs\Monitors\CheckDomainNS::dispatch($website);
    }
})->daily();
