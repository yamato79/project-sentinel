<?php

namespace App\Widgets;

use App\Models\MonitorLocation;
use App\Models\Website;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ResponseTimeTrendWidget
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
     * Get the response time for a given period.
     */
    public function getResponseTimeForPeriod(Carbon $startDate, Carbon $endDate)
    {
        $availableWebsiteIds = Website::availableToUser(auth()->user())
            ->pluck('website_id')
            ->toArray();

        $result = DB::table('v_website_response_time_history')
            ->whereIn('website_id', $availableWebsiteIds)
            ->whereBetween('hour', [$startDate, $endDate])
            ->where('website_id', $this->request->get('website_id'))
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

        foreach ($this->getResponseTimeForPeriod(now()->subHours($this->request->hours), now()) as $monitorLocationSlug => $responseTimeData) {
            $monitorLocation = MonitorLocation::where('slug', $monitorLocationSlug)->firstOrFail();

            $chartData['datasets'][] = [
                'label' => $monitorLocation->name,
                'data' => $responseTimeData->map(fn ($row) => ['x' => $row->hour, 'y' => (int) $row->avg_response_time_ms]),
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
