<?php

namespace App\Jobs\Monitors;

use App\Models\MonitorLocation;
use App\Models\MonitorQueue;
use App\Models\MonitorType;
use App\Models\Website;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class CheckDomainExpiry implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The website to check.
     */
    protected $website;

    /**
     * The monitor location to use.
     */
    protected $monitorLocation;

    /**
     * Create a new job instance.
     */
    public function __construct(Website $website, MonitorLocation $monitorLocation)
    {
        $this->website = $website;
        $this->monitorLocation = $monitorLocation;
    }

    /**
     * Execute the monitor
     */
    public function executeMonitor()
    {
        $payload = [
            'app_location' => $this->monitorLocation->slug,
            'domain_expiry' => null,
        ];

        try {
            $response = Http::timeout(10)
                ->withHeaders([
                    'X-SENTINEL-HEADER' => 'sentinel',
                ])
                ->get("{$this->monitorLocation->agent_url}/domain-expiry", [
                    'address' => $this->website->address,
                ]);

            $parsedResponse = $response->json();

            if (isset($parsedResponse['domain_expiry'])) {
                $payload['domain_expiry'] = $parsedResponse['domain_expiry'];
            }
        } catch (\Exception $e) {
            logger()->error('CheckDomainExpiry Failed', [
                'message' => $e->getMessage(),
            ]);
        }

        return $payload;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        MonitorQueue::create([
            'website_id' => $this->website->getKey(),
            'monitor_type_id' => MonitorType::DOMAIN_EXPIRY,
            'raw_data' => $this->executeMonitor(),
        ]);
    }
}
