<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Website;
use App\Reports\WebsiteDomainExpirationHistoryReport;
use App\Reports\WebsiteDomainNameserversHistoryReport;
use App\Reports\WebsiteResponseTimeHistoryReport;
use App\Reports\WebsiteSSLExpirationHistoryReport;
use App\Reports\WebsiteSSLValidityHistoryReport;
use App\Reports\WebsiteUptimeFeedReport;
use App\Reports\WebsiteUptimeHistoryReport;
use Illuminate\Http\Request;

class WebsiteController extends Controller
{
    /**
     * Get the domain expiration history for websites.
     */
    public function getDomainExpirationHistory(Request $request, Website $website)
    {
        $data = (new WebsiteDomainExpirationHistoryReport(
            startDate: now()->subDays(30),
            endDate: now(),
            website: $website
        ))->toCollection();

        return response()->json([
            'data' => $data,
        ]);
    }

    /**
     * Get the domain expiration status for websites.
     */
    public function getDomainExpirationStatus(Request $request, Website $website)
    {
        $data = (new WebsiteDomainExpirationHistoryReport(
            startDate: now()->subDays(30),
            endDate: now(),
            website: $website
        ))->toCard();

        return response()->json([
            'data' => $data,
        ]);
    }

    /**
     * Get the domain nameserver history for websites.
     */
    public function getDomainNameserversHistory(Request $request, Website $website)
    {
        $data = (new WebsiteDomainNameserversHistoryReport(
            startDate: now()->subDays(30),
            endDate: now(),
            website: $website
        ))->toCollection();

        return response()->json([
            'data' => $data,
        ]);
    }

    /**
     * Get the response time history for websites.
     */
    public function getWebsiteResponseTimeHistory(Request $request, Website $website)
    {
        $data = (new WebsiteResponseTimeHistoryReport(
            startDate: now()->subDays(30),
            endDate: now(),
            website: $website
        ));

        return response()->json([
            'data' => [
                'avgLocations' => $data->toLineChartMedian(),
                'allLocations' => $data->toLineChart(),
            ],
        ]);
    }

    /**
     * Get the SSL expiration history for websites.
     */
    public function getSSLExpirationHistory(Request $request, Website $website)
    {
        $data = (new WebsiteSSLExpirationHistoryReport(
            startDate: now()->subDays(30),
            endDate: now(),
            website: $website
        ))->toCollection();

        return response()->json([
            'data' => $data,
        ]);
    }

    /**
     * Get the SSL expiration status for websites.
     */
    public function getSSLExpirationStatus(Request $request, Website $website)
    {
        $data = (new WebsiteSSLExpirationHistoryReport(
            startDate: now()->subDays(30),
            endDate: now(),
            website: $website
        ))->toCard();

        return response()->json([
            'data' => $data,
        ]);
    }

    /**
     * Get the SSL validity history for websites.
     */
    public function getSSLValidityHistory(Request $request, Website $website)
    {
        $data = (new WebsiteSSLValidityHistoryReport(
            startDate: now()->subDays(30),
            endDate: now(),
            website: $website
        ))->toCollection();

        return response()->json([
            'data' => $data,
        ]);
    }

    /**
     * Get the SSL validity status for websites.
     */
    public function getSSLValidityStatus(Request $request, Website $website)
    {
        $data = (new WebsiteSSLValidityHistoryReport(
            startDate: now()->subDays(30),
            endDate: now(),
            website: $website
        ))->toCard();

        return response()->json([
            'data' => $data,
        ]);
    }

    /**
     * Get the uptime feed for websites.
     */
    public function getUptimeFeed(Request $request, Website $website)
    {
        $data = (new WebsiteUptimeFeedReport(
            startDate: now()->subDays(1),
            endDate: now(),
            website: $website
        ))->toCard();

        return response()->json([
            'data' => $data,
        ]);
    }

    /**
     * Get the uptime history for websites.
     */
    public function getUptimeHistory(Request $request, Website $website)
    {
        $data = (new WebsiteUptimeHistoryReport(
            startDate: now()->subDays(1),
            endDate: now(),
            website: $website
        ))->toLineChart();

        return response()->json([
            'data' => $data,
        ]);
    }
}
