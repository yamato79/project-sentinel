<?php

namespace App\Models;

use App\Models\NotificationChannelDriver;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class Website extends Model
{
    use HasFactory;
    use Notifiable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'websites';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'website_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'address',
        'website_status_id',
        'created_by_user_id',
    ];

    /**
     * The "booted" method of the model.
     */
    protected static function booted()
    {
        parent::booted();

        static::addGlobalScope('orderByName', function ($builder) {
            $builder->orderBy('name');
        });
    }

    /**
     * The 'availableToUser' query scope.
     */
    public function scopeAvailableToUser(Builder $query, User $user)
    {
        return $query
            ->where(function ($groupedQuery) use ($user) {
                $groupedQuery
                    // The user created the website.
                    ->where('created_by_user_id', $user->getKey())
                    ->orWhereHas('stacks', function ($subQuery) use ($user) {
                        $subQuery
                            // The website is in a stack created by the user.
                            ->where('stacks.created_by_user_id', $user->getKey())

                            // The website is in a stack that the user is a member of.
                            ->orWhereHas('users', function ($altQuery) use ($user) {
                                $altQuery
                                    ->where('pivot_stacks_users.user_id', $user->getKey())
                                    ->whereNotNull('pivot_stacks_users.joined_at');
                            });
                    });
            });
    }

    /**
     * Get the 'is_monitor_active' attribute.
     */
    public function getIsMonitorActiveAttribute()
    {
        return $this->website_status_id !== WebsiteStatus::PAUSED;
    }

    /**
     * Notification Route: 'Webhook'.
     */
    public function routeNotificationForWebhook()
    {
        $channelFieldValues = $this->notificationChannels()
            ->where('notification_channel_driver_id', NotificationChannelDriver::WEBHOOK)
            ->where('is_active', true)
            ->pluck('field_values')
            ->first();

        return isset($channelFieldValues['webhook_url'])
            ? $channelFieldValues['webhook_url']
            : null;
    }

    /**
     * Get the user that the website belongs to.
     */
    public function createdByUser()
    {
        return $this->belongsTo(User::class, 'created_by_user_id', 'user_id');
    }

    /**
     * Get the monitor locations that the website belongs to.
     */
    public function monitorLocations()
    {
        return $this->belongsToMany(MonitorLocation::class, 'pivot_monitor_locations_websites', 'website_id', 'monitor_location_id')
            ->using(Pivot\MonitorLocationWebsite::class)
            ->withTimestamps();
    }

    /**
     * The monitor queues that belong to the monitor type.
     */
    public function monitorQueues()
    {
        return $this->hasMany(MonitorQueue::class, 'website_id', 'website_id')
            ->orderBy('created_at', 'desc');
    }

    /**
     * Get the notification channels that belong to the website.
     */
    public function notificationChannels()
    {
        return $this->hasMany(NotificationChannel::class, 'website_id', 'website_id');
    }

    /**
     * Get the notification types that the website belongs to.
     */
    public function notificationTypes()
    {
        return $this->belongsToMany(NotificationType::class, 'pivot_notification_types_websites', 'website_id', 'notification_type_id')
            ->using(Pivot\NotificationTypeWebsite::class)
            ->withTimestamps();
    }

    /**
     * Get the stacks that the website belongs to.
     */
    public function stacks()
    {
        return $this->belongsToMany(Stack::class, 'pivot_stacks_websites', 'website_id', 'stack_id')
            ->withTimestamps()
            ->using(Pivot\StackWebsite::class);
    }

    /**
     * Get the website status that the website belongs to.
     */
    public function websiteStatus()
    {
        return $this->belongsTo(WebsiteStatus::class, 'website_status_id', 'website_status_id');
    }
}
