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
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class CheckDomainNameservers implements ShouldQueue
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
     * The monitor location to use.
     */
    protected $monitorType;

    /**
     * Create a new job instance.
     */
    public function __construct(Website $website, MonitorLocation $monitorLocation)
    {
        $this->website = $website;
        $this->monitorLocation = $monitorLocation;
        $this->monitorType = MonitorType::findOrFail(MonitorType::DOMAIN_NAMESERVERS);
    }

    /**
     * Get the middleware the job should pass through.
     *
     * @return array<int, object>
     */
    public function middleware(): array
    {
        return [
            (new WithoutOverlapping(
                $this->website->getKey().'-'.$this->monitorLocation->getKey()
            ))->dontRelease()->expireAfter(60),
        ];
    }

    /**
     * Execute the monitor
     */
    public function executeMonitor()
    {
        $payload = [
            'app_location' => $this->monitorLocation->slug,
            'message' => '',
            'status' => 'success',
        ];

        try {
            $response = Http::timeout(10)
                ->withHeaders([
                    'X-SENTINEL-HEADER' => 'sentinel',
                ])
                ->get("{$this->monitorLocation->agent_url}/{$this->monitorType->slug}", [
                    'address' => $this->website->address,
                ]);

            $payload = array_merge(
                $payload,
                $response->json() ?? []
            );
        } catch (\Exception $e) {
            $payload['message'] = $e->getMessage();
            $payload['status'] = 'error';
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
            'monitor_type_id' => $this->monitorType->getKey(),
            'raw_data' => $this->executeMonitor(),
        ]);
    }
}
