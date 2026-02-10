<?php

namespace App\Http\Resources\V2;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * PersonGuestResource
 *
 * Campos mínimos para pessoas não autorizadas a ver detalhes completos.
 */
class PersonGuestResource extends JsonResource
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
            'full_name' => $this->full_name,
            'qr_code' => $this->qr_code,

            // Relationships - muito limitadas
            'gender' => $this->whenLoaded('gender', fn() => [
                'name' => $this->gender->name,
            ]),
        ];
    }
}
