<?php

namespace App\Widgets;

use App\Models\Website;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DomainStatusWidget
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
     * Get the data for the widget.
     */
    public function getData()
    {
        $validator = Validator::make($this->request->all(), [
            'website_id' => 'sometimes|integer|exists:websites,website_id',
        ]);

        if ($validator->fails()) {
            return [
                'data' => [],
                'errors' => $validator->errors(),
            ];
        }

        $expiresIn = DB::table('v_website_domain_expiration_history')
            ->where('website_id', $this->request->get('website_id'))
            ->orderBy('created_at', 'DESC')
            ->first();

        $isValid = ($expiresIn ? ($expiresIn->expires_in > 0) : null);

        return [
            'data' => array_merge([
                'is_valid' => $isValid,
            ], collect($expiresIn)->toArray()),
            'errors' => [],
        ];
    }

    /**
     * Execute the monitor in synchronously.
     */
    public function execute()
    {
        try {
            $website = Website::findOrFail($this->request->get('website_id'));

            $monitorLocations = $website->monitorLocations()
                ->where('is_active', true)
                ->get();

            $monitorLocations->each(function ($monitorLocation) use ($website) {
                \App\Jobs\Monitors\CheckDomainExpiration::dispatch($website, $monitorLocation);
            });

            return [
                'message' => 'ok',
            ];
        } catch (\Exception $e) {
            return [
                'message' => $e->getMessage(),
            ];
        }
    }
}
