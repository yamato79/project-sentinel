<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MonitorQueue extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'monitor_queue';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'monitor_queue_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'monitor_type_id',
        'website_id',
        'raw_data'
    ];
}
