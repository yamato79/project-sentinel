<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationChannelResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'notification_channel_id' => $this->getKey(),
            'name' => $this->name,
            'field_values' => $this->field_values,
            'is_active' => $this->is_active,
            'notification_channel_driver_id' => $this->notification_channel_driver_id,
            'website_id' => $this->website_id,
        ];
    }
}
