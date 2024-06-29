<?php

namespace App\Http\Controllers;

use App\Models\WebsiteStatus;
use App\Models\Website;
use App\Services\UptimeService\UptimeService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    /**
     * Display the dashboard page.
     */
    public function index()
    {
        return Inertia::render('panel/dashboard/index', [
            'uptimeCards' => UptimeService::getUptimeCards([
                24 => "Uptime (24H)",
                168 => "Uptime (7D)",
                720 => "Uptime (30D)"
            ]),
            'uptimeTrend' => UptimeService::getUptimeTrendChartData(24),
            'websiteStatusDistribution' => $this->getWebsiteStatusDistribution(),
            'breadcrumbs' => [
                ['label' => 'Dashboard', 'href' => route('panel.dashboard')],
            ],
        ]);
    }

    /**
     * Get the website status distribution data.
     */
    public function getWebsiteStatusDistribution()
    {
        $availableWebsiteIds = Website::availableToUser(auth()->user())->pluck('website_id')->toArray();

        $statusData = WebsiteStatus::query()
            ->withCount([
                'websites' => function ($query) use ($availableWebsiteIds) {
                    $query->whereIn('website_id', $availableWebsiteIds);
                }
            ])
            ->whereHas('websites', function ($query) use ($availableWebsiteIds) {
                $query->whereIn('website_id', $availableWebsiteIds);
            })
            ->get();

        // Define the colors for each status
        $colors = [
            'paused' => '#eab308', // Amber 500
            'online' => '#10b981', // Emerald 500
            'offline' => '#f43f5e', // Rose 500
            'default' => '#e5e7eb' // Gray 200
        ];

        // Map the data for Chart.js
        $chartData = [
            'labels' => [],
            'datasets' => [
                [
                    'data' => [],
                    'backgroundColor' => [],
                    'borderColor' => [],
                    'borderWidth' => 1
                ]
            ]
        ];

        foreach ($statusData as $status) {
            $chartData['labels'][] = $status->name;
            $chartData['datasets'][0]['data'][] = $status->websites_count;
            $chartData['datasets'][0]['backgroundColor'][] = $colors[$status->slug];
            $chartData['datasets'][0]['borderColor'][] = $colors[$status->slug];
        }

        logger()->info("chartData", [
            'raw' => $chartData
        ]);

        return $chartData;
    }
}
