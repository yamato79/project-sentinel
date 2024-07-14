<?php

namespace App\Widgets;

use App\Models\MonitorLocation;
use App\Models\Website;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UptimeTrendWidget
{
    /**
     * The request
     */
    private Request $request;

    /**
     * Create a new instance.
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Get the uptime for a given period.
     */
    public function getUptimeForPeriod(Carbon $startDate, Carbon $endDate)
    {
        $availableWebsiteIds = Website::availableToUser(auth()->user())
            ->pluck('website_id')
            ->toArray();

        $result = DB::table('v_website_uptime_history')
            ->whereIn('website_id', $availableWebsiteIds)
            ->whereBetween('hour', [$startDate, $endDate])
            ->when($this->request->filled('website_id'), function ($query) {
                $query->where('website_id', $this->request->get('website_id'));
            })
            ->get()
            ->sortBy('hour')
            ->groupBy('app_location');

        return $result;
    }

    /**
     * Format the data into chart format.
     */
    public function toChart()
    {
        $chartData = [
            'labels' => [],
            'datasets' => [],
        ];

        foreach ($this->getUptimeForPeriod(now()->subHours($this->request->hours), now()) as $monitorLocationSlug => $uptimeData) {
            $monitorLocation = MonitorLocation::where('slug', $monitorLocationSlug)->firstOrFail();

            if (! $this->request->filled('website_id')) {
                $uptimeData = $uptimeData
                    ->groupBy('hour')
                    ->map(function ($item) {
                        return [
                            'website_id' => $item->first()->website_id,
                            'app_location' => 'ALL',
                            'avg_uptime_percent' => $item->avg('avg_uptime_percent'),
                            'hour' => $item->first()->hour,
                        ];
                    })->values();

                $chartData['datasets'][] = [
                    'label' => $monitorLocation->name,
                    'data' => $uptimeData->map(fn ($row) => ['x' => $row['hour'], 'y' => (int) $row['avg_uptime_percent']]),
                    'backgroundColor' => $monitorLocation->color_code,
                    'borderColor' => $monitorLocation->color_code,
                    'fill' => false,
                ];

                continue;
            }

            $chartData['datasets'][] = [
                'label' => $monitorLocation->name,
                'data' => $uptimeData->map(fn ($row) => ['x' => $row->hour, 'y' => (int) $row->avg_uptime_percent]),
                'backgroundColor' => $monitorLocation->color_code,
                'borderColor' => $monitorLocation->color_code,
                'fill' => false,
            ];
        }

        return $chartData;
    }

    /**
     * Get the data for the widget.
     */
    public function getData()
    {
        $validator = Validator::make($this->request->all(), [
            'website_id' => 'sometimes|integer|exists:websites,website_id',
            'hours' => 'required|integer|min:1|max:720',
        ]);

        if ($validator->fails()) {
            return [
                'data' => [],
                'errors' => $validator->errors(),
            ];
        }

        return [
            'data' => $this->toChart(),
            'errors' => [],
        ];
    }
}
