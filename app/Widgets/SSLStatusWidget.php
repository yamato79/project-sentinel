<?php

namespace App\Widgets;

use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SSLStatusWidget
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

        $expiresIn = DB::table('v_website_ssl_expiration_history')
            ->where('website_id', $this->request->get('website_id'))
            ->orderBy('hour', 'DESC')
            ->first();

        $isValid = DB::table('v_website_ssl_validity_history')
            ->where('website_id', $this->request->get('website_id'))
            ->orderBy('hour', 'DESC')
            ->first();

        return [
            'data' => [
                'is_valid' => ($isValid ? $isValid->is_valid : null),
                'expires_in' => ($expiresIn ? $expiresIn->expires_in : null),
            ],
            'errors' => [],
        ];
    }
}