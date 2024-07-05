<?php

namespace App\Widgets;

use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DomainNameserversTableWidget
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
            'days' => 'required|integer|min:1|max:30'
        ]);

        if ($validator->fails()) {
            return [
                'data' => [],
                'errors' => $validator->errors(),
            ];
        }

        $result = DB::table('v_website_domain_nameservers_history')
            ->where('website_id', $this->request->get('website_id'))
            ->wherebetween('created_at', [now()->startOfMinute()->subDays($this->request->get('days')), now()])
            ->orderBy('created_at', 'DESC')
            ->get();

        return [
            'data' => $result,
            'errors' => [],
        ];
    }
}
