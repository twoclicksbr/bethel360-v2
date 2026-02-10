<?php

namespace App\Features;

use App\Models\Address;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * HasAddresses Trait
 *
 * Adiciona relacionamento polimórfico de endereços a qualquer model.
 * Usado por: Campus, Ministry, Group, Person
 */
trait HasAddresses
{
    /**
     * Get all addresses for the model.
     */
    public function addresses(): MorphMany
    {
        return $this->morphMany(Address::class, 'addressable');
    }

    /**
     * Add a new address to the model.
     *
     * @param array $data
     * @return Address
     */
    public function addAddress(array $data): Address
    {
        return $this->addresses()->create($data);
    }

    /**
     * Get the primary address.
     *
     * @return Address|null
     */
    public function primaryAddress(): ?Address
    {
        return $this->addresses()->where('is_primary', true)->first();
    }

    /**
     * Get addresses by type.
     *
     * @param int $typeAddressId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function addressesByType(int $typeAddressId)
    {
        return $this->addresses()->where('type_address_id', $typeAddressId)->get();
    }

    /**
     * Update or create primary address.
     *
     * @param array $data
     * @return Address
     */
    public function updatePrimaryAddress(array $data): Address
    {
        // Remove primary flag from other addresses
        $this->addresses()->update(['is_primary' => false]);

        // Create or update primary address
        $data['is_primary'] = true;

        return $this->addresses()->updateOrCreate(
            ['id' => $data['id'] ?? null],
            $data
        );
    }
}
