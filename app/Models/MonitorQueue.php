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
        'raw_data',
        'created_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'raw_data' => 'array',
        ];
    }

    /**
     * The monitor type that the monitor queue belongs to.
     */
    public function monitorType()
    {
        return $this->belongsTo(MonitorType::class, 'monitor_type_id', 'monitor_type_id');
    }

    /**
     * The website that the monitor queue belongs to.
     */
    public function website()
    {
        return $this->belongsTo(Website::class, 'website_id', 'website_id');
    }
}
