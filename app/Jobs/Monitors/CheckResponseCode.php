<?php

namespace App\Jobs\Monitors;

use App\Models\MonitorQueue;
use App\Models\MonitorType;
use App\Models\Website;
use App\Models\WebsiteStatus;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class CheckResponseCode implements ShouldQueue
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
        $response = Http::timeout(15)->get($this->website->address);
        $responseStatus = $response->status();
        $isOnline = ($responseStatus >= 200 && $responseStatus <= 299);

        /**
         * If the website is offline AND the website status is not offline or paused.
         */
        if (!$isOnline && !in_array($this->website->website_status_id, [WebsiteStatus::OFFLINE, WebsiteStatus::PAUSED])) {
            $this->website->update([
                'website_status_id' => WebsiteStatus::OFFLINE,
            ]);
        }

        /**
         * If the website is online AND the website status is not online or paused.
         */
        if ($isOnline && !in_array($this->website->website_status_id, [WebsiteStatus::ONLINE, WebsiteStatus::PAUSED])) {
            $this->website->update([
                'website_status_id' => WebsiteStatus::ONLINE,
            ]);
        }

        return json_encode([
            'response_code' => $responseStatus,
        ]);
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        MonitorQueue::create([
            'website_id' => $this->website->getKey(),
            'monitor_type_id' => MonitorType::RESPONSE_CODE,
            'raw_data' => $this->executeMonitor(),
        ]);
    }
}
