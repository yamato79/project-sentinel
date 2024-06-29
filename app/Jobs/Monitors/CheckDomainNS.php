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

class CheckDomainNS implements ShouldQueue
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
        $domain = parse_url($this->website->address, PHP_URL_HOST);
        $nameservers = [];

        // Use dig command to get nameservers
        exec("dig +short NS {$domain}", $nameservers);

        return [
            'app_location' => config('app.location'),
            'nameservers' => $nameservers,
        ];
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        MonitorQueue::create([
            'website_id' => $this->website->getKey(),
            'monitor_type_id' => MonitorType::DOMAIN_NS,
            'raw_data' => $this->executeMonitor(),
        ]);
    }
}
