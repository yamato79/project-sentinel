<?php

namespace App\Jobs\Monitors;

use App\Models\MonitorLocation;
use App\Models\MonitorQueue;
use App\Models\MonitorType;
use App\Models\Website;
use App\Models\WebsiteStatus;
use DB;
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
        $this->monitorType = MonitorType::findOrFail(MonitorType::RESPONSE_CODE);
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
                $response->json()
            );

            $isOnline = (
                $payload['data']['response_code'] >= 200 &&
                $payload['data']['response_code'] <= 299
            );

            $averageUptimePercent = DB::table('v_website_uptime_feed')
                ->where('website_id', $this->website->getKey())
                ->whereBetween('minute', [
                    now()->startOfMinute()->subMinutes(2),
                    now(),
                ])
                ->get()
                ->avg('avg_uptime_percent');

            /**
             * If the website is offline AND the website status is not offline or paused.
             */
            if (! $isOnline && ! in_array($this->website->website_status_id, [WebsiteStatus::OFFLINE, WebsiteStatus::PAUSED])) {
                if ($averageUptimePercent < 100 && $averageUptimePercent > 0) {
                    $this->website->update([
                        'website_status_id' => WebsiteStatus::LIMITED,
                    ]);
                } else {
                    $this->website->update([
                        'website_status_id' => WebsiteStatus::OFFLINE,
                    ]);
                }
            }

            /**
             * If the website is online AND the website status is not online or paused.
             */
            if ($isOnline && ! in_array($this->website->website_status_id, [WebsiteStatus::ONLINE, WebsiteStatus::PAUSED])) {
                if ($averageUptimePercent < 100 && $averageUptimePercent > 0) {
                    $this->website->update([
                        'website_status_id' => WebsiteStatus::LIMITED,
                    ]);
                } else {
                    $this->website->update([
                        'website_status_id' => WebsiteStatus::ONLINE,
                    ]);
                }
            }
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
