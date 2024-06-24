<?php

namespace Database\Seeders\Production;

use App\Models\WebsiteStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WebsiteStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        collect([
            ['name' => 'Pending', 'slug' => 'default', 'description' => 'The website\'s status is currently not known.', 'color' => 'default'],
            ['name' => 'Online',  'slug' => 'online',  'description' => 'The website is online.',                        'color' => 'success'],
            ['name' => 'Offline', 'slug' => 'offline', 'description' => 'The website is offline.',                       'color' => 'danger'],
            ['name' => 'Paused',  'slug' => 'paused',  'description' => 'The website is not being monitored.',           'color' => 'warning'],
        ])->each(function ($data) {
            WebsiteStatus::updateOrCreate([
                'slug' => $data['slug'],
            ], array_merge($data, [
                // ...
            ]));
        });
    }
}
