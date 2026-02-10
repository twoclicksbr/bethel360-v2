<?php

namespace App\Http\Resources\V2;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MinistryResource extends JsonResource
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
            'campus_id' => $this->campus_id,

            // Relationships
            'campus' => new CampusResource($this->whenLoaded('campus')),
            'groups' => GroupResource::collection($this->whenLoaded('groups')),
            'features' => $this->whenLoaded('features'),

            // Computed
            'active_members_count' => $this->when(
                $this->relationLoaded('ministryPersons'),
                fn() => $this->activeMembersCount()
            ),
            'groups_count' => $this->when(
                $this->relationLoaded('groups'),
                fn() => $this->groups->count()
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
