<?php

namespace Database\Seeders\Production;

use App\Models\MonitorType;
use Illuminate\Database\Seeder;

class MonitorTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        collect([
            ['name' => 'Response Code', 'slug' => 'response-code',      'description' => 'Monitors a website\'s HTTP response code.'],
            ['name' => 'Response Time', 'slug' => 'response-time',      'description' => 'Monitors a website\'s HTTP response time.'],
            ['name' => 'SSL Valid',     'slug' => 'ssl-validity',       'description' => 'Monitors a website\'s SSL certificate validity.'],
            ['name' => 'SSL Expiry',    'slug' => 'ssl-expiration',     'description' => 'Monitors a website\'s SSL certificate expiration.'],
            ['name' => 'Domain Expiry', 'slug' => 'domain-expiration',  'description' => 'Monitors a website\'s domain expiration.'],
            ['name' => 'Domain NS',     'slug' => 'domain-nameservers', 'description' => 'Monitors a website\'s domain nameservers.'],
            ['name' => 'Lighthouse',    'slug' => 'lighthouse',         'description' => 'Monitors a website\'s lighthouse scores.'],
        ])->each(function ($data) {
            MonitorType::firstOrCreate([
                'slug' => $data['slug'],
            ], array_merge($data, [
                // ...
            ]));
        });
    }
}
