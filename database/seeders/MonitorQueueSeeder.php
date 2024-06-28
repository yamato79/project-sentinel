<?php

namespace Database\Seeders;

use App\Models\MonitorQueue;
use App\Models\MonitorType;
use App\Models\Website;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class MonitorQueueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $responseCodes = collect([200, 500]);
        $startTime = Carbon::now()->subDays(3);
        $batchSize = 1440;
        $entries = [];

        foreach (Website::cursor() as $website) {
            $currentTime = clone $startTime;
            while ($currentTime->lessThan(Carbon::now())) {
                $entries[] = [
                    'website_id' => $website->getKey(),
                    'monitor_type_id' => MonitorType::RESPONSE_CODE,
                    'raw_data' => json_encode([
                        'response_code' => $responseCodes->random(),
                    ]),
                    'created_at' => $currentTime,
                ];

                // Insert in batches to speed things up
                if (count($entries) >= $batchSize) {
                    MonitorQueue::insert($entries);
                    $entries = [];
                }

                $currentTime->addMinute();
            }
        }

        // Insert any remaining entries
        if (! empty($entries)) {
            MonitorQueue::insert($entries);
        }
    }
}
