<?php

namespace App\Services\UptimeService;

use App\Models\Views\WebsiteUptimeHistoryDaily;
use App\Models\Views\WebsiteUptimeHistoryHourly;
use App\Models\Website;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UptimeService {
    /**
     * Get the average website uptime globally or for a specific website.
     * 
     * The function defaults to global average uptime the past 24 hours and
     * taking in the last 24 hours and previous to that.
     */
    public static function getAverageUptime(int $hours = 24, int $websiteId = null)
    {
        $now = Carbon::now();
        $period1Start = $now->copy()->subHours($hours);
        $period1End = $now;
        $period2Start = $now->copy()->subHours(2 * $hours);
        $period2End = $now->copy()->subHours($hours);
        $availableWebsiteIds = Website::availableToUser(auth()->user())->pluck('website_id')->toArray();

        if (empty($availableWebsiteIds)) {
            return [
                [
                    'start' => $period1Start->toDateTimeString(),
                    'end' => $period1End->toDateTimeString(),
                    'uptime_percentage' => 0,
                ],
                [
                    'start' => $period2Start->toDateTimeString(),
                    'end' => $period2End->toDateTimeString(),
                    'uptime_percentage' => 0,
                ],
            ];
        }

        // Average uptime for the first period
        $period1Uptime = WebsiteUptimeHistoryHourly::query()
            ->whereIn('website_id', $availableWebsiteIds)
            ->when(!empty($websiteId), function ($query) use ($websiteId) {
                $query->where('website_id', $websiteId);
            })
            ->whereBetween('hour_start', [$period1Start, $period1End])
            ->avg('uptime_percentage');

        // Average uptime for the second period
        $period2Uptime = WebsiteUptimeHistoryHourly::query()
            ->whereIn('website_id', $availableWebsiteIds)
            ->when(!empty($websiteId), function ($query) use ($websiteId) {
                $query->where('website_id', $websiteId);
            })
            ->whereBetween('hour_start', [$period2Start, $period2End])
            ->avg('uptime_percentage');

        return [
            [
                'start' => $period1Start->toDateTimeString(),
                'end' => $period1End->toDateTimeString(),
                'uptime_percentage' => $period1Uptime,
            ],
            [
                'start' => $period2Start->toDateTimeString(),
                'end' => $period2End->toDateTimeString(),
                'uptime_percentage' => $period2Uptime,
            ],
        ];
    }

    /**
     * Get the uptime trend data globally or for a specific website.
     */
    public static function getUptimeTrend(int $hours = 24, int $websiteId = null)
    {
        $now = Carbon::now();
        $startDate = $now->copy()->subHours($hours);
        $endDate = $now;

        $uptimeTrend = WebsiteUptimeHistoryHourly::query()
            ->whereIn('website_id', Website::availableToUser(auth()->user())->pluck('website_id')->toArray())
            ->when(!empty($websiteId), function ($query) use ($websiteId) {
                $query->where('website_id', $websiteId);
            })
            ->whereBetween('hour_start', [$startDate, $endDate])
            ->get();

        return $uptimeTrend;
    }

    /**
     * Get the uptime trend chart data.
     * 
     * This function maps for Chart.JS Line Chart.
     */
    public static function getUptimeTrendChartData(int $hours = 24, int $websiteId = null)
    {
        $uptimeData = self::getUptimeTrend($hours, $websiteId);

        // Map the data for Chart.js Line Chart
        $chartData = [
            'labels' => [],
            'datasets' => [
                [ 
                    'label' => "Uptime Trend",
                    'data' => [],
                    'backgroundColor' => '#344155', // Tailwind Slate 600
                    'borderColor' => '#344155', // Tailwind Slate 600
                    'fill' => false
                ]
            ]
        ];

        foreach ($uptimeData as $dataPoint) {
            $chartData['labels'][] = $dataPoint->hour_start;
            $chartData['datasets'][0]['data'][] = $dataPoint->uptime_percentage;
        }

        return $chartData;
    }

    /**
     * Get the uptime cards data.
     * 
     * 24H  (1 Day)
     * 168H (7 Days)
     * 720H (30 Days)
     */
    public static function getUptimeCards(array $periods = [], int $websiteId = null)
    {
        /**
         * Determine the color based on the current and previous values.
         *
         * @param float $currentValue
         * @param float $previousValue
         * @return string
         */
        function getColor($currentValue, $previousValue)
        {
            if ($currentValue > $previousValue) {
                return 'success'; // green
            } elseif ($currentValue < $previousValue) {
                return 'danger'; // red
            } else {
                return 'default'; // gray
            }
        }

        return collect($periods)->map(function ($title, $hours) use ($websiteId) {
            $uptimeData = self::getAverageUptime($hours, $websiteId);
            
            $currentData = $uptimeData[0] ?? null;
            $previousData = $uptimeData[1] ?? null;

            $currentValue = $currentData ? round($currentData['uptime_percentage'], 2) : 0;
            $previousValue = $previousData ? round($previousData['uptime_percentage'], 2) : 0;

            $valueIncrease = max(0, $currentValue - $previousValue);
            $valueDecrease = max(0, $previousValue - $currentValue);

            $color = getColor($currentValue, $previousValue);

            return [
                'title' => $title,
                'currentValue' => number_format($currentValue, 0) . "%",
                'previousValue' => number_format($previousValue, 0) . "%",
                'valueIncrease' => $valueIncrease,
                'valueDecrease' => $valueDecrease,
                'color' => $color
            ];
        })->toArray();
    }
}