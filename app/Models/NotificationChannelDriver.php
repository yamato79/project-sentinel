<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class NotificationChannelDriver extends Model
{
    const WEBHOOK = 1;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'notification_channel_drivers';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'notification_channel_driver_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'class',
        'description',
        'fields',
        'validator_rules',
        'validator_messages',
        'is_active',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'fields' => 'array',
            'validator_rules' => 'array',
            'validator_messages' => 'array',
            'is_active' => 'boolean',
        ];
    }

    /**
     * The "booted" method of the model.
     */
    protected static function booted()
    {
        parent::booted();

        static::creating(function ($notificationChannelDriver) {
            if (empty($notificationChannelDriver->slug)) {
                $notificationChannelDriver->slug = Str::slug($notificationChannelDriver->name).'-'.strtolower(Str::random(12));
            }
        });
    }

    /**
     * The notification channels that belong to the channel driver.
     */
    public function notificationChannels()
    {
        return $this->hasMany(NotificationChannel::class, 'notification_channel_driver_id', 'notification_channel_driver_id');
    }
}
