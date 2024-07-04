<?php

namespace App\Widgets;

use App\Models\Website;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UptimeFeedWidget
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

        $result = DB::table('v_website_uptime_feed')
            ->whereIn('website_id', $availableWebsiteIds)
            ->whereBetween('minute', [$startDate, $endDate])
            ->when($this->request->filled('website_id'), function ($query) {
                $query->where('website_id', $this->request->get('website_id'));
            })
            ->get()
            ->groupBy('minute')
            ->map(function ($row) {
                $appLocations = $row->reduce(function ($carry, $item) {
                    $carry[$item->app_location] = $item->avg_uptime_percent;

                    return $carry;
                }, []);

                return [
                    'app_location' => 'ALL',
                    'app_locations' => $appLocations,
                    'avg_uptime_percent' => $row->avg('avg_uptime_percent'),
                    'minute' => $row->first()->minute,
                    'website_id' => $row->first()->website_id,
                ];
            });

        return collect($result)->keyBy('minute');
    }

    /**
     * Generate the result array.
     */
    public function generateResultArray()
    {
        $result = [];

        for ($x = 0; $x < ($this->request->get('minutes') + 1); $x++) {
            $result[] = [
                'app_location' => $x,
                'avg_uptime_percent' => null,
                'minute' => now()->startOfMinute()->subMinutes($x),
                'website_id' => null,
            ];
        }

        return collect($result)->keyBy('minute');
    }

    /**
     * Get the data for the widget.
     */
    public function getData()
    {
        $validator = Validator::make($this->request->all(), [
            'website_id' => 'sometimes|integer|exists:websites,website_id',
            'minutes' => 'required|integer|min:1|max:1440',
        ]);

        if ($validator->fails()) {
            return [
                'data' => [],
                'errors' => $validator->errors(),
            ];
        }

        $data = $this->getUptimeForPeriod(
            now()->startOfMinute()->subMinutes($this->request->get('minutes') + 1),
            now()->startOfMinute()
        );

        $filteredData = $this->generateResultArray()
            ->merge($data)
            ->sortBy('minute')
            ->values();

        /**
         * Ensure the latest piece of data isn't null.
         */
        if ($filteredData->last() && ($filteredData->last()['avg_uptime_percent'] === null)) {
            $filteredData->pop();
        } else {
            $filteredData->shift();
        }

        return [
            'data' => $filteredData,
            'errors' => [],
        ];
    }
}
