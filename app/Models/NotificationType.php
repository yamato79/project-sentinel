<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationType extends Model
{
    use HasFactory;

    const WEBSITE_STATUS_CHANGED = 1;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'notification_types';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'notification_type_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
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
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the websites that the notification type belongs to.
     */
    public function websites()
    {
        return $this->belongsToMany(Website::class, 'pivot_notification_types_websites', 'notification_type_id', 'website_id')
            ->using(Pivot\NotificationTypeWebsite::class)
            ->withTimestamps()
            ->withPivot([
                'is_active',
            ]);
    }
}
