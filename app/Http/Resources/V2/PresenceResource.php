<?php

namespace App\Http\Resources\V2;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PresenceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'person_id' => $this->person_id,
            'event_id' => $this->event_id,
            'presence_method_id' => $this->presence_method_id,
            'role_id' => $this->role_id,
            'registered_at' => $this->registered_at,

            // Relationships
            'person' => new PersonResource($this->whenLoaded('person')),
            'event' => new EventResource($this->whenLoaded('event')),
            'presenceMethod' => $this->whenLoaded('presenceMethod', fn() => [
                'id' => $this->presenceMethod->id,
                'name' => $this->presenceMethod->name,
                'slug' => $this->presenceMethod->slug,
            ]),
            'role' => $this->whenLoaded('role', fn() => [
                'id' => $this->role->id,
                'name' => $this->role->name,
                'slug' => $this->role->slug,
            ]),

            // Timestamps
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
