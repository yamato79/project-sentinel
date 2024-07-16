<?php

namespace App\Notifications\Website;

use App\Models\Website;
use App\Models\WebsiteStatus;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use NotificationChannels\Webhook\WebhookChannel;
use NotificationChannels\Webhook\WebhookMessage;

class WebsiteStatusUpdated extends Notification implements ShouldQueue
{
    use Queueable;

    private Website $website;
    private WebsiteStatus $oldWebsiteStatus;
    private WebsiteStatus $newWebsiteStatus;

    /**
     * Create a new notification instance.
     */
    public function __construct(Website $website)
    {
        $this->website = $website;
        $this->oldWebsiteStatus = WebsiteStatus::findOrFail($website->getOriginal('website_status_id'));
        $this->newWebsiteStatus = WebsiteStatus::findOrFail($website->website_status_id);
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        $notificationChannels = $this->website->notificationChannels()
            ->with(['notificationChannelDriver'])
            ->where('is_active', true)
            ->get()
            ->map(fn ($notificationChannel) => $notificationChannel->notificationChannelDriver->class)
            ->toArray();

        return $notificationChannels;
    }

    /**
     * Get the webhook representation of the notification.
     */
    public function toWebhook($notifiable)
    {
        return WebhookMessage::create()
            ->data([
                'message' => "{$this->website->name} ({$this->website->address}) - status changed from {$this->oldWebsiteStatus->name} to {$this->newWebsiteStatus->name}",
                'data' => [
                    // ...
                ],
            ])
            ->header('Content-Type', 'application/json');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
