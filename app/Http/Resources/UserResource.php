<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = [
            'user_id' => $this->getKey(),
            'name' => $this->name,
            'email' => $this->email,
        ];

        if ($this->pivot) {
            $data['pivot'] = [
                'can_view' => $this->pivot->can_view,
                'can_edit' => $this->pivot->can_edit,
                'joined_at' => $this->pivot->joined_at,
            ];
        }

        return $data;
    }
}
