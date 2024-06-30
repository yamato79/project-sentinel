<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            Production\MonitorLocationSeeder::class,
            Production\MonitorTypeSeeder::class,
            Production\WebsiteStatusSeeder::class,
        ]);

        if (app()->environment() !== 'production') {
            $this->call([
                WebsiteSeeder::class,
                UserSeeder::class,
                MonitorQueueSeeder::class,
            ]);
        }
    }
}
