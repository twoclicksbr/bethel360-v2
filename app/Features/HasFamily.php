<?php

namespace App\Features;

use App\Models\FamilyLink;
use App\Models\AuthorizedPickup;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * HasFamily Trait
 *
 * Vínculos familiares (rede social interna).
 * Construídos por solicitação e aceite (como rede social).
 * Segurança de crianças via authorized_pickups.
 * Usado por: Person
 */
trait HasFamily
{
    /**
     * Get family links where this person is the requester.
     */
    public function sentFamilyLinks(): HasMany
    {
        return $this->hasMany(FamilyLink::class, 'person_id');
    }

    /**
     * Get family links where this person is the related person.
     */
    public function receivedFamilyLinks(): HasMany
    {
        return $this->hasMany(FamilyLink::class, 'related_person_id');
    }

    /**
     * Get all family links (sent + received, accepted only).
     *
     * @return \Illuminate\Support\Collection
     */
    public function familyLinks()
    {
        $sent = $this->sentFamilyLinks()
            ->where('status', 'accepted')
            ->with(['relatedPerson', 'relationship'])
            ->get();

        $received = $this->receivedFamilyLinks()
            ->where('status', 'accepted')
            ->with(['person', 'relationship'])
            ->get();

        return $sent->merge($received);
    }

    /**
     * Get pending family link requests (received).
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function pendingFamilyRequests()
    {
        return $this->receivedFamilyLinks()
            ->where('status', 'pending')
            ->with(['person', 'relationship'])
            ->get();
    }

    /**
     * Request family link.
     *
     * @param int $relatedPersonId
     * @param int $relationshipId
     * @return FamilyLink
     */
    public function requestFamilyLink(int $relatedPersonId, int $relationshipId): FamilyLink
    {
        // Verifica se já existe vínculo
        $existing = FamilyLink::where(function ($query) use ($relatedPersonId, $relationshipId) {
            $query->where('person_id', $this->id)
                ->where('related_person_id', $relatedPersonId)
                ->where('relationship_id', $relationshipId);
        })->orWhere(function ($query) use ($relatedPersonId, $relationshipId) {
            $query->where('person_id', $relatedPersonId)
                ->where('related_person_id', $this->id)
                ->where('relationship_id', $relationshipId);
        })->first();

        if ($existing) {
            throw new \Exception('Family link already exists');
        }

        return $this->sentFamilyLinks()->create([
            'related_person_id' => $relatedPersonId,
            'relationship_id' => $relationshipId,
            'status' => 'pending',
            'requested_at' => now(),
        ]);
    }

    /**
     * Get authorized pickups for this person (if child).
     */
    public function authorizedPickups(): HasMany
    {
        return $this->hasMany(AuthorizedPickup::class, 'child_id');
    }

    /**
     * Add authorized pickup person.
     *
     * @param int|null $authorizedPersonId
     * @param string|null $name
     * @param array $additionalData
     * @return AuthorizedPickup
     */
    public function addAuthorizedPickup(?int $authorizedPersonId = null, ?string $name = null, array $additionalData = []): AuthorizedPickup
    {
        if (!$authorizedPersonId && !$name) {
            throw new \InvalidArgumentException('Either authorized_person_id or name must be provided');
        }

        return $this->authorizedPickups()->create(array_merge([
            'authorized_person_id' => $authorizedPersonId,
            'name' => $name,
            'authorized_at' => now(),
        ], $additionalData));
    }

    /**
     * Check if person is authorized to pick up this child.
     *
     * @param int $personId
     * @return bool
     */
    public function isAuthorizedPickup(int $personId): bool
    {
        return $this->authorizedPickups()
            ->where('authorized_person_id', $personId)
            ->exists();
    }

    /**
     * Get family members by relationship type.
     *
     * @param string $relationshipSlug (e.g., 'conjuge', 'filho', 'pai')
     * @return \Illuminate\Support\Collection
     */
    public function familyMembersByRelationship(string $relationshipSlug)
    {
        return $this->familyLinks()->filter(function ($link) use ($relationshipSlug) {
            return $link->relationship && $link->relationship->slug === $relationshipSlug;
        });
    }

    /**
     * Get spouse.
     *
     * @return Person|null
     */
    public function spouse()
    {
        $link = $this->familyMembersByRelationship('conjuge')->first();

        if (!$link) {
            return null;
        }

        return $link->person_id === $this->id
            ? $link->relatedPerson
            : $link->person;
    }

    /**
     * Get children.
     *
     * @return \Illuminate\Support\Collection
     */
    public function children()
    {
        return $this->familyMembersByRelationship('filho');
    }
}
