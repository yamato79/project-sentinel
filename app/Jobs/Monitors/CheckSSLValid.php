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

class CheckSSLValid implements ShouldQueue
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
        $parsedUrl = parse_url($this->website->address);
        $hostname = $parsedUrl['host'];

        $context = stream_context_create(['ssl' => ['capture_peer_cert' => true]]);
        $client = stream_socket_client('ssl://'.$hostname.':443', $errno, $errstr, 30, STREAM_CLIENT_CONNECT, $context);
        $cert = stream_context_get_params($client)['options']['ssl']['peer_certificate'];
        $certInfo = openssl_x509_parse($cert);

        $valid = Carbon::now()->between(Carbon::createFromTimestamp($certInfo['validFrom_time_t']), Carbon::createFromTimestamp($certInfo['validTo_time_t']));

        return [
            'app_location' => config('app.location'),
            'ssl_valid' => $valid,
        ];
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        MonitorQueue::create([
            'website_id' => $this->website->getKey(),
            'monitor_type_id' => MonitorType::SSL_VALID,
            'raw_data' => $this->executeMonitor(),
        ]);
    }
}
