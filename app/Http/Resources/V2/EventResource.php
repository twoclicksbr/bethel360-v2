<?php

namespace App\Http\Resources\V2;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
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
            'name' => $this->name,
            'description' => $this->description,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'eventable_type' => $this->eventable_type,
            'eventable_id' => $this->eventable_id,

            // Polymorphic relationship
            'eventable' => $this->whenLoaded('eventable'),

            // Notes
            'notes' => $this->whenLoaded('notes'),

            // Presences
            'presences_count' => $this->when(
                $this->relationLoaded('presences'),
                fn() => $this->presences->count()
            ),
            'presences' => PresenceResource::collection($this->whenLoaded('presences')),

            // Timestamps
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
        ];
    }
}
