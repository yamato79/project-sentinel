<?php

namespace Database\Seeders\Production;

use App\Models\MonitorLocation;
use Illuminate\Database\Seeder;

class MonitorLocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $monitorDomain = config('app.monitor_domain');

        collect([
            ['name' => 'Africa',        'slug' => 'AF', 'color_code' => 'rgb(255,  99, 132)', 'agent_url' => "https://agent-af.{$monitorDomain}/api/monitors", 'is_active' => false, 'description' => 'Agent(s) found within the african region'],
            ['name' => 'Antarctica',    'slug' => 'AN', 'color_code' => 'rgb(255, 159,  64)', 'agent_url' => "https://agent-an.{$monitorDomain}/api/monitors", 'is_active' => false, 'description' => 'Agent(s) found within the antarctic region'],
            ['name' => 'Asia',          'slug' => 'AS', 'color_code' => 'rgb(255, 205,  86)', 'agent_url' => "https://agent-as.{$monitorDomain}/api/monitors", 'is_active' => false, 'description' => 'Agent(s) found within the asian region'],
            ['name' => 'Europe',        'slug' => 'EU', 'color_code' => 'rgb(75,  192, 192)', 'agent_url' => "https://agent-eu.{$monitorDomain}/api/monitors", 'is_active' => false, 'description' => 'Agent(s) found within the european region'],
            ['name' => 'North America', 'slug' => 'NA', 'color_code' => 'rgb(54,  162, 235)', 'agent_url' => "https://agent-na.{$monitorDomain}/api/monitors", 'is_active' => false, 'description' => 'Agent(s) found within the north american region'],
            ['name' => 'Oceania',       'slug' => 'OC', 'color_code' => 'rgb(153, 102, 255)', 'agent_url' => "https://agent-oc.{$monitorDomain}/api/monitors", 'is_active' => false, 'description' => 'Agent(s) found within the oceanian region'],
            ['name' => 'South America', 'slug' => 'SA', 'color_code' => 'rgb(201, 203, 207)', 'agent_url' => "https://agent-sa.{$monitorDomain}/api/monitors", 'is_active' => false, 'description' => 'Agent(s) found within the south american region'],
        ])->each(function ($data) {
            MonitorLocation::firstOrCreate([
                'slug' => $data['slug'],
            ], array_merge($data, [
                // ...
            ]));
        });
    }
}
