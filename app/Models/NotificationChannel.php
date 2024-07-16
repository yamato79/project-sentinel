<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationChannel extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'notification_channels';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'notification_channel_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'field_values',
        'is_active',
        'notification_channel_driver_id',
        'website_id',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'field_values' => 'array',
            'is_active' => 'boolean',
        ];
    }

    /**
     * The notification channel driver that the notification channel belongs to.
     */
    public function notificationChannelDriver()
    {
        return $this->belongsTo(NotificationChannelDriver::class, 'notification_channel_driver_id', 'notification_channel_driver_id');
    }

    /**
     * The website that the notification channel belongs to.
     */
    public function website()
    {
        return $this->belongsTo(Website::class, 'website_id', 'website_id');
    }
}
