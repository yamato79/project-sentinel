<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WebsiteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'website_id' => $this->getKey(),
            'name' => $this->name,
            'slug' => $this->slug,
            'address' => $this->address,
            'website_status_id' => $this->website_status_id,
            'website_status' => new WebsiteStatusResource($this->whenLoaded('websiteStatus')),
            'is_monitor_active' => $this->isMonitorActive,
        ];
    }
}
