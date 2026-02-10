<?php

namespace App\Http\Resources\V2;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FinanceResource extends JsonResource
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
            'finance_type_id' => $this->finance_type_id,
            'finance_category_id' => $this->finance_category_id,
            'payment_method_id' => $this->payment_method_id,
            'amount' => $this->amount,
            'status' => $this->status,
            'external_id' => $this->external_id,
            'confirmed_at' => $this->confirmed_at,

            // Relationships
            'person' => new PersonResource($this->whenLoaded('person')),
            'ministry' => new MinistryResource($this->whenLoaded('ministry')),
            'financeType' => $this->whenLoaded('financeType', fn() => [
                'id' => $this->financeType->id,
                'name' => $this->financeType->name,
                'slug' => $this->financeType->slug,
            ]),
            'financeCategory' => $this->whenLoaded('financeCategory', fn() => [
                'id' => $this->financeCategory->id,
                'name' => $this->financeCategory->name,
                'slug' => $this->financeCategory->slug,
            ]),
            'paymentMethod' => $this->whenLoaded('paymentMethod', fn() => [
                'id' => $this->paymentMethod->id,
                'name' => $this->paymentMethod->name,
                'slug' => $this->paymentMethod->slug,
            ]),

            // Timestamps
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
