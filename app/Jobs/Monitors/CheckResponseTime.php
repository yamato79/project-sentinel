<?php

namespace App\Jobs\Monitors;

use App\Models\MonitorQueue;
use App\Models\MonitorType;
use App\Models\Website;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class CheckResponseTime implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The website to check.
     */
    protected $website;

    /**
     * Create a new job instance.
     */
    public function __construct(Website $website)
    {
        $this->website = $website;
    }

    /**
     * Execute the monitor
     */
    public function executeMonitor()
    {
        $responseTime = null;
        $start = microtime(true);

        try {
            Http::timeout(15)->get($this->website->address);
            $end = microtime(true);
            $responseTime = ($end - $start) * 1000; // Response time in milliseconds
        } catch (\Exception $e) {
            // ...
        }

        return [
            'app_location' => config('app.location'),
            'response_time' => $responseTime,
        ];
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        MonitorQueue::create([
            'website_id' => $this->website->getKey(),
            'monitor_type_id' => MonitorType::RESPONSE_TIME,
            'raw_data' => $this->executeMonitor(),
        ]);
    }
}
