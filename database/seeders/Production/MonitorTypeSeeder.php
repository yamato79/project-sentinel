<?php

namespace Database\Seeders\Production;

use App\Models\MonitorType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MonitorTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        collect([
            ['name' => 'Response Code', 'slug' => 'response-code', 'description' => 'Monitors a website\'s HTTP response code.'],
            ['name' => 'Response Time', 'slug' => 'response-time', 'description' => 'Monitors a website\'s HTTP response time.'],
            ['name' => 'SSL Valid',     'slug' => 'ssl-valid',     'description' => 'Monitors a website\'s SSL certificate validity.'],
            ['name' => 'SSL Expiry',    'slug' => 'ssl-expiry',    'description' => 'Monitors a website\'s SSL certificate expiration.'],
            ['name' => 'Domain Expiry', 'slug' => 'domain-expiry', 'description' => 'Monitors a website\'s domain expiration.'],
            ['name' => 'Domain NS',     'slug' => 'domain-ns',     'description' => 'Monitors a website\'s domain nameservers.'],
        ])->each(function ($data) {
            MonitorType::updateOrCreate([
                'slug' => $data['slug'],
            ], array_merge($data, [
                // ...
            ]));
        });
    }
}
