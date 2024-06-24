<?php

namespace App\Jobs\Monitors;

use App\Models\MonitorQueue;
use App\Models\MonitorType;
use App\Models\Website;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CheckDomainExpiry implements ShouldQueue
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
        $whois = shell_exec("whois $domain");

        if (preg_match('/Registry Expiry Date: (.*)/', $whois, $matches)) {
            $expirationDate = Carbon::parse(trim($matches[1]));
            $expiresIn = Carbon::now()->diffInDays($expirationDate, false);

            return json_encode([
                'domain_expires_in' => $expiresIn,
            ]);
        }

        return json_encode([
            'error' => 'Could not retrieve expiration date',
        ]);
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
