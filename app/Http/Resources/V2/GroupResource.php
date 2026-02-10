<?php

namespace App\Http\Resources\V2;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GroupResource extends JsonResource
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
            'slug' => $this->slug,
            'description' => $this->description,
            'is_active' => $this->is_active,
            'is_confidential' => $this->is_confidential,
            'ministry_id' => $this->ministry_id,

            // Relationships
            'ministry' => new MinistryResource($this->whenLoaded('ministry')),
            'features' => $this->whenLoaded('features'),

            // Computed
            'active_members_count' => $this->when(
                $this->relationLoaded('groupPersons'),
                fn() => $this->activeMembersCount()
            ),
            'available_capacity' => $this->when(
                $this->relationLoaded('features'),
                fn() => $this->availableCapacity()
            ),
            'is_full' => $this->when(
                $this->relationLoaded('features'),
                fn() => $this->isFull()
            ),

            // Polymorphic relationships
            'addresses' => $this->whenLoaded('addresses'),
            'contacts' => $this->whenLoaded('contacts'),
            'files' => $this->whenLoaded('files'),
            'notes' => $this->whenLoaded('notes'),
            'events' => EventResource::collection($this->whenLoaded('events')),

            // Timestamps
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
        ];
    }
}
