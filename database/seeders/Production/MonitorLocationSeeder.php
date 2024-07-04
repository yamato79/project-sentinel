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
            ['name' => 'Africa',        'slug' => 'AF', 'agent_url' => "https://agent-af.{$monitorDomain}/api/monitors", 'is_active' => false, 'description' => 'Agent(s) found within the african region'],
            ['name' => 'Antarctica',    'slug' => 'AN', 'agent_url' => "https://agent-an.{$monitorDomain}/api/monitors", 'is_active' => false, 'description' => 'Agent(s) found within the antarctic region'],
            ['name' => 'Asia',          'slug' => 'AS', 'agent_url' => "https://agent-as.{$monitorDomain}/api/monitors", 'is_active' => true, 'description' => 'Agent(s) found within the asian region'],
            ['name' => 'Europe',        'slug' => 'EU', 'agent_url' => "https://agent-eu.{$monitorDomain}/api/monitors", 'is_active' => true, 'description' => 'Agent(s) found within the european region'],
            ['name' => 'North America', 'slug' => 'NA', 'agent_url' => "https://agent-na.{$monitorDomain}/api/monitors", 'is_active' => false, 'description' => 'Agent(s) found within the north american region'],
            ['name' => 'Oceania',       'slug' => 'OC', 'agent_url' => "https://agent-oc.{$monitorDomain}/api/monitors", 'is_active' => false, 'description' => 'Agent(s) found within the oceanian region'],
            ['name' => 'South America', 'slug' => 'SA', 'agent_url' => "https://agent-sa.{$monitorDomain}/api/monitors", 'is_active' => false, 'description' => 'Agent(s) found within the south american region'],
        ])->each(function ($data) {
            MonitorLocation::firstOrCreate([
                'slug' => $data['slug'],
            ], array_merge($data, [
                // ...
            ]));
        });
    }
}
