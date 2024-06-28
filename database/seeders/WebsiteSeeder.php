<?php

namespace Database\Seeders;

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
            ['name' => 'Google', 'slug' => 'google', 'address' => 'https://google.com', 'website_status_id' => WebsiteStatus::ONLINE],
        ])->each(function ($data) {
            Website::firstOrCreate([
                'slug' => $data['slug'],
            ], array_merge($data, [
                'created_by_user_id' => 1,
            ]));
        });
    }
}
