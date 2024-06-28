<?php

namespace App\Models\Views;

use Illuminate\Database\Eloquent\Model;

class WebsiteUptimeHistoryHourly extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'v_website_uptime_history_hourly';

    /**
     * The "booted" method of the trait.
     */
    protected static function booted()
    {
        static::creating(function ($model) {
            return false;
        });

        static::updating(function ($model) {
            return false;
        });

        static::deleting(function ($model) {
            return false;
        });
    }
}
