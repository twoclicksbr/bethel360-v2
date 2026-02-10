<?php

namespace App\Http\Resources\V2;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PersonResource extends JsonResource
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
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'full_name' => $this->full_name,
            'qr_code' => $this->qr_code,
            'birth_date' => $this->birth_date?->format('Y-m-d'),
            'age' => $this->birth_date?->age,
            'gender_id' => $this->gender_id,

            // Relationships
            'gender' => $this->whenLoaded('gender', fn() => [
                'id' => $this->gender->id,
                'name' => $this->gender->name,
                'slug' => $this->gender->slug,
            ]),
            'user' => $this->whenLoaded('user', fn() => [
                'id' => $this->user->id,
                'email' => $this->user->email,
            ]),

            // Polymorphic relationships
            'addresses' => $this->whenLoaded('addresses'),
            'contacts' => $this->whenLoaded('contacts'),
            'documents' => $this->whenLoaded('documents'),

            // Computed - only if loaded
            'ministries_count' => $this->when(
                $this->relationLoaded('ministryPersons'),
                fn() => $this->ministryPersons->count()
            ),
            'groups_count' => $this->when(
                $this->relationLoaded('groupPersons'),
                fn() => $this->groupPersons->count()
            ),
            'achievements_count' => $this->when(
                $this->relationLoaded('achievements'),
                fn() => $this->achievements->count()
            ),

            // Timestamps
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
        ];
    }
}
