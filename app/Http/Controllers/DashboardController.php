<?php

namespace App\Http\Controllers;

use App\Models\Website;
use App\Models\WebsiteStatus;
use Inertia\Inertia;

class DashboardController extends Controller
{
    /**
     * Display the dashboard page.
     */
    public function index()
    {
        return Inertia::render('panel/dashboard/index', [
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
                },
            ])
            ->whereHas('websites', function ($query) use ($availableWebsiteIds) {
                $query->whereIn('website_id', $availableWebsiteIds);
            })
            ->get();

        // Define the colors for each status
        $colors = [
            'limited' => 'rgba(255, 205, 86, 1)',
            'paused' => 'rgba(201, 203, 207, 1)',
            'online' => 'rgba(16, 185, 129, 1)', // Emerald 500
            'offline' => 'rgba(244, 63, 94, 1)', // Rose 500
            'default' => 'rgba(201, 203, 207, 1)',
        ];
        

        // Map the data for Chart.js
        $chartData = [
            'labels' => [],
            'datasets' => [
                [
                    'data' => [],
                    'backgroundColor' => [],
                    'borderColor' => [],
                    'borderWidth' => 1,
                ],
            ],
        ];

        foreach ($statusData as $status) {
            $chartData['labels'][] = $status->name;
            $chartData['datasets'][0]['data'][] = $status->websites_count;
            $chartData['datasets'][0]['backgroundColor'][] = $colors[$status->slug];
            $chartData['datasets'][0]['borderColor'][] = $colors[$status->slug];
        }

        return $chartData;
    }
}
