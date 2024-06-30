<?php

namespace App\Models\Pivot;

use Illuminate\Database\Eloquent\Relations\Pivot;

class MonitorLocationWebsite extends Pivot
{
    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pivot_monitor_locations_websites';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'pivot_monitor_locations_websites_id';
}
