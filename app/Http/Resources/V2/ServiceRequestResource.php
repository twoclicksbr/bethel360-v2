<?php

namespace App\Http\Resources\V2;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceRequestResource extends JsonResource
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
            'requesting_ministry_id' => $this->requesting_ministry_id,
            'target_ministry_id' => $this->target_ministry_id,
            'description' => $this->description,
            'status' => $this->status,
            'accepted_at' => $this->accepted_at,
            'accepted_by' => $this->accepted_by,
            'declined_at' => $this->declined_at,
            'declined_by' => $this->declined_by,
            'decline_reason' => $this->decline_reason,

            // Relationships
            'requestingMinistry' => new MinistryResource($this->whenLoaded('requestingMinistry')),
            'targetMinistry' => new MinistryResource($this->whenLoaded('targetMinistry')),

            // Timestamps
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
