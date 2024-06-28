<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StackResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'stack_id' => $this->getKey(),
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'users_count' => $this->when(isset($this->users_count), $this->users_count),
            'websites_count' => $this->when(isset($this->websites_count), $this->websites_count),
            'created_by_user_id' => $this->created_by_user_id,
            'created_by_user' => new UserResource($this->whenLoaded('createdByUser')),
            'users' => UserResource::collection($this->whenLoaded('users')),
        ];
    }
}
