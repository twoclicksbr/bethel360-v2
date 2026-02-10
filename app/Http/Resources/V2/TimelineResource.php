<?php

namespace App\Http\Resources\V2;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TimelineResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * Timeline events come from HasTimeline trait, already formatted as array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Resource is already an array from the trait
        return $this->resource;
    }
}
