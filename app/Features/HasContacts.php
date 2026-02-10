<?php

namespace App\Features;

use App\Models\Contact;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * HasContacts Trait
 *
 * Adiciona relacionamento polimórfico de contatos a qualquer model.
 * Usado por: Campus, Ministry, Group, Person, AuthorizedPickup
 */
trait HasContacts
{
    /**
     * Get all contacts for the model.
     */
    public function contacts(): MorphMany
    {
        return $this->morphMany(Contact::class, 'contactable');
    }

    /**
     * Add a new contact to the model.
     *
     * @param array $data
     * @return Contact
     */
    public function addContact(array $data): Contact
    {
        return $this->contacts()->create($data);
    }

    /**
     * Get the primary contact.
     *
     * @return Contact|null
     */
    public function primaryContact(): ?Contact
    {
        return $this->contacts()->where('is_primary', true)->first();
    }

    /**
     * Get verified contacts only.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function verifiedContacts()
    {
        return $this->contacts()->where('is_verified', true)->get();
    }

    /**
     * Get contacts by type.
     *
     * @param int $typeContactId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function contactsByType(int $typeContactId)
    {
        return $this->contacts()->where('type_contact_id', $typeContactId)->get();
    }

    /**
     * Get email contacts.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function emailContacts()
    {
        return $this->contacts()
            ->whereHas('typeContact', function ($query) {
                $query->where('slug', 'email');
            })
            ->get();
    }

    /**
     * Get phone contacts.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function phoneContacts()
    {
        return $this->contacts()
            ->whereHas('typeContact', function ($query) {
                $query->where('slug', 'telefone');
            })
            ->get();
    }

    /**
     * Update or create primary contact.
     *
     * @param array $data
     * @return Contact
     */
    public function updatePrimaryContact(array $data): Contact
    {
        // Remove primary flag from other contacts of same type
        $this->contacts()
            ->where('type_contact_id', $data['type_contact_id'])
            ->update(['is_primary' => false]);

        // Create or update primary contact
        $data['is_primary'] = true;

        return $this->contacts()->updateOrCreate(
            ['id' => $data['id'] ?? null],
            $data
        );
    }
}
