<?php

namespace App\Observers;

use App\Models\NotificationType;
use App\Models\Website;
use Illuminate\Contracts\Events\ShouldHandleEventsAfterCommit;
use Illuminate\Support\Str;

class WebsiteObserver implements ShouldHandleEventsAfterCommit
{
    /**
     * Handle the Website "creating" event.
     */
    public function creating(Website $website): void
    {
        $website->created_by_user_id = auth()->user()->getKey();
        $website->slug = strtolower(Str::random(16));
    }

    /**
     * Handle the Website "updated" event.
     */
    public function updated(Website $website): void
    {
        if ($website->wasChanged('website_status_id')) {
            if (in_array(
                NotificationType::WEBSITE_STATUS_CHANGED, 
                $website->notificationTypes->pluck('notification_type_id')->toArray(),
            )) {
                $website->notify(new \App\Notifications\Website\WebsiteStatusUpdated($website));
            }
        }
    }
}
