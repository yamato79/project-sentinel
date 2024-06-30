<?php

namespace Database\Seeders;

use App\Models\MonitorLocation;
use App\Models\Website;
use App\Models\WebsiteStatus;
use Illuminate\Database\Seeder;

class WebsiteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        collect([
            ['name' => 'Google', 'slug' => 'google', 'address' => 'https://google.com', 'website_status_id' => WebsiteStatus::ONLINE, 'created_by_user_id' => 1],
        ])->each(function ($data) {
            $website = Website::firstOrCreate([
                'slug' => $data['slug'],
            ], array_merge($data, [
                'created_by_user_id' => 1,
            ]));

            $website
                ->monitorLocations()
                ->syncWithoutDetaching([
                    MonitorLocation::AFRICA,
                    MonitorLocation::ANTARCTICA,
                    MonitorLocation::ASIA,
                    MonitorLocation::EUROPE,
                    MonitorLocation::NORTH_AMERICA,
                    MonitorLocation::OCEANIA,
                    MonitorLocation::SOUTH_AMERICA,
                ]);
        });
    }
}
