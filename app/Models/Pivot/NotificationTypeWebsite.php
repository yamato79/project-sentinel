<?php

namespace App\Models\Pivot;

use Illuminate\Database\Eloquent\Relations\Pivot;

class NotificationTypeWebsite extends Pivot
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
    protected $table = 'pivot_notification_types_websites';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'pivot_notification_types_websites_id';
}
