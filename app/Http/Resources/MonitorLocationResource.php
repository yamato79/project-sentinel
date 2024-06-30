<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MonitorLocationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'monitor_location_id' => $this->monitor_location_id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'agent_url' => $this->agent_url,
            'is_active' => $this->is_active,
            'websites' => WebsiteResource::collection($this->whenLoaded('websites')),
        ];
    }
}
