<?php

namespace App\Http\Resources\V2;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FamilyLinkResource extends JsonResource
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
            'related_person_id' => $this->related_person_id,
            'relationship_id' => $this->relationship_id,
            'status' => $this->status,
            'requested_at' => $this->requested_at,
            'accepted_at' => $this->accepted_at,
            'rejected_at' => $this->rejected_at,

            // Relationships
            'person' => new PersonResource($this->whenLoaded('person')),
            'relatedPerson' => new PersonResource($this->whenLoaded('relatedPerson')),
            'relationship' => $this->whenLoaded('relationship', fn() => [
                'id' => $this->relationship->id,
                'name' => $this->relationship->name,
                'slug' => $this->relationship->slug,
                'inverse_name' => $this->relationship->inverse_name,
                'inverse_slug' => $this->relationship->inverse_slug,
            ]),

            // Timestamps
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
