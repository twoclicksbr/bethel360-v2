<?php

namespace App\Services;

use App\Models\Person;
use App\Models\FamilyLink;

/**
 * FamilyLinkService
 *
 * Gerencia vínculos familiares (rede social interna).
 * Vínculos construídos por solicitação e aceite.
 */
class FamilyLinkService
{
    /**
     * Request family link.
     *
     * @param Person $person
     * @param int $relatedPersonId
     * @param int $relationshipId
     * @return FamilyLink
     */
    public function request(Person $person, int $relatedPersonId, int $relationshipId): FamilyLink
    {
        return $person->requestFamilyLink($relatedPersonId, $relationshipId);
    }

    /**
     * Respond to family link request.
     *
     * @param FamilyLink $familyLink
     * @param bool $accept
     * @return FamilyLink
     */
    public function respond(FamilyLink $familyLink, bool $accept): FamilyLink
    {
        if ($accept) {
            $familyLink->status = 'accepted';
            $familyLink->accepted_at = now();
        } else {
            $familyLink->status = 'rejected';
            $familyLink->rejected_at = now();
        }

        $familyLink->save();

        // TODO ETAPA 3: Dispatch event FamilyLinkResponded
        // event(new FamilyLinkResponded($familyLink, $accept));

        return $familyLink;
    }

    /**
     * Remove family link.
     *
     * @param FamilyLink $familyLink
     * @return bool
     */
    public function remove(FamilyLink $familyLink): bool
    {
        return $familyLink->delete();
    }

    /**
     * Get family links for person.
     *
     * @param Person $person
     * @return \Illuminate\Support\Collection
     */
    public function getFamilyLinks(Person $person)
    {
        return $person->familyLinks();
    }

    /**
     * Get pending requests for person.
     *
     * @param Person $person
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getPendingRequests(Person $person)
    {
        return $person->pendingFamilyRequests();
    }

    /**
     * Auto-create family link (for kids ministry, no approval needed).
     *
     * @param Person $child
     * @param Person $parent
     * @param int $relationshipId
     * @return FamilyLink
     */
    public function autoCreate(Person $child, Person $parent, int $relationshipId): FamilyLink
    {
        $familyLink = $child->sentFamilyLinks()->create([
            'related_person_id' => $parent->id,
            'relationship_id' => $relationshipId,
            'status' => 'accepted',
            'requested_at' => now(),
            'accepted_at' => now(),
        ]);

        // TODO ETAPA 3: Dispatch event FamilyLinkAutoCreated
        // event(new FamilyLinkAutoCreated($familyLink));

        return $familyLink;
    }
}
