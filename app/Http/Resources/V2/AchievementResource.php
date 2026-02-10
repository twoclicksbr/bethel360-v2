<?php

namespace App\Http\Resources\V2;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AchievementResource extends JsonResource
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
            'ministry_id' => $this->ministry_id,
            'group_id' => $this->group_id,
            'title' => $this->title,
            'achieved_at' => $this->achieved_at,

            // Relationships
            'person' => new PersonResource($this->whenLoaded('person')),
            'ministry' => new MinistryResource($this->whenLoaded('ministry')),
            'group' => new GroupResource($this->whenLoaded('group')),

            // Timestamps
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
