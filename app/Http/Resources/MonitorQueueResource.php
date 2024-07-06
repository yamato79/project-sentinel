<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MonitorQueueResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'monitor_queue_id' => $this->getKey(),
            'website' => new WebsiteResource($this->whenLoaded('website')),
            'monitor_type' => new MonitorTypeResource($this->whenLoaded('monitorType')),
            'raw_data' => $this->raw_data,
            'created_at' => $this->created_at->format('F d, Y @ H:i A'),
        ];
    }
}
