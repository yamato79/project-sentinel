<?php

namespace App\Widgets;

use App\Models\Website;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UptimeCardWidget
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
     * Get the average uptime for a given period.
     */
    public function getAverageUptimeForPeriod(Carbon $startDate, Carbon $endDate)
    {
        $availableWebsiteIds = Website::availableToUser(auth()->user())
            ->pluck('website_id')
            ->toArray();

        $period = CarbonPeriod::create(
            $startDate->startOfHour(),
            '1 hour',
            $endDate->startOfHour()
        );

        $filledResults = [];

        foreach ($period as $date) {
            $formattedDate = $date->format('Y-m-d H:i:s');
            $filledResults[$formattedDate] = 0;
        }

        $results = DB::table('v_website_uptime_history')
            ->whereIn('website_id', $availableWebsiteIds)
            ->whereBetween('hour', [$startDate, $endDate])
            ->when($this->request->filled('website_id'), function ($query) {
                $query->where('website_id', $this->request->get('website_id'));
            })
            ->get()
            ->groupBy('hour')
            ->map(function ($result) {
                return $result->avg('avg_uptime_percent');
            })
            ->toArray();

        return collect(array_merge($filledResults, $results))->avg();
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

        $color = 'default';
        $tooltipColor = 'default';

        $currentValue = $this->getAverageUptimeForPeriod(
            now()->subHours($this->request->get('hours')),
            now(),
        );

        $previousValue = $this->getAverageUptimeForPeriod(
            now()->subHours($this->request->get('hours') * 2),
            now()->subHours($this->request->get('hours')),
        );

        $valueIncrease = max(0, $currentValue - $previousValue);
        $valueDecrease = max(0, $previousValue - $currentValue);

        if ($currentValue >= 95) {
            $color = 'success';
        } elseif ($currentValue >= 85 && $currentValue < 95) {
            $color = 'warning';
        } elseif ($currentValue < 85 && $currentValue > 0) {
            $color = 'danger';
        } else {
            $color = 'default';
        }

        if ($valueIncrease) {
            $tooltipColor = 'success';
        }

        if ($valueDecrease) {
            $tooltipColor = 'danger';
        }

        return [
            'data' => [
                'currentValue' => $currentValue ?? 0,
                'previousValue' => $previousValue ?? 0,
                'valueIncrease' => $valueIncrease,
                'valueDecrease' => $valueDecrease,
                'color' => $color,
                'tooltipColor' => $tooltipColor,
            ],
            'errors' => [],
        ];
    }
}
