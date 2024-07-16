<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class MonitorType extends Model
{
    const RESPONSE_CODE = 1;

    const RESPONSE_TIME = 2;

    const SSL_VALIDITY = 3;

    const SSL_EXPIRATION = 4;

    const DOMAIN_EXPIRATION = 5;

    const DOMAIN_NAMESERVERS = 6;

    const LIGHTHOUSE = 7;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'monitor_types';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'monitor_type_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    /**
     * The "booted" method of the model.
     */
    protected static function booted()
    {
        parent::booted();

        static::creating(function ($monitorType) {
            if (empty($monitorType->slug)) {
                $monitorType->slug = Str::slug($monitorType->name).'-'.strtolower(Str::random(12));
            }
        });
    }

    /**
     * The monitor queues that belong to the monitor type.
     */
    public function monitorQueues()
    {
        return $this->hasMany(MonitorQueue::class, 'monitor_type_id', 'monitor_type_id')
            ->orderBy('created_at', 'desc');
    }
}
