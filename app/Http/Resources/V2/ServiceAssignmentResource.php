<?php

namespace App\Http\Resources\V2;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceAssignmentResource extends JsonResource
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
            'service_request_id' => $this->service_request_id,
            'person_id' => $this->person_id,
            'ministry_id' => $this->ministry_id,
            'role_id' => $this->role_id,
            'scheduled_at' => $this->scheduled_at,
            'status' => $this->status,
            'accepted_at' => $this->accepted_at,
            'declined_at' => $this->declined_at,
            'decline_reason' => $this->decline_reason,

            // Relationships
            'serviceRequest' => new ServiceRequestResource($this->whenLoaded('serviceRequest')),
            'person' => new PersonResource($this->whenLoaded('person')),
            'ministry' => new MinistryResource($this->whenLoaded('ministry')),
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
