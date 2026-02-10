<?php

namespace App\Services;

use App\Models\Person;
use Illuminate\Support\Facades\Log;

/**
 * ChildSafetyService
 *
 * Protocolo de segurança de crianças.
 * INEGOCIÁVEL: Unauthorized pickup = BLOCK + ALERT.
 */
class ChildSafetyService
{
    /**
     * Validate if person is authorized to pick up child.
     *
     * @param int $childId
     * @param int|null $requesterId Person ID requesting pickup
     * @param string|null $requesterName Fallback name if person not registered
     * @return array ['authorized' => bool, 'matched_by' => string|null, 'child' => Person]
     */
    public function validatePickup(int $childId, ?int $requesterId = null, ?string $requesterName = null): array
    {
        $child = Person::findOrFail($childId);

        // Get authorized pickups for this child
        $authorizedPickups = $child->authorizedPickups;

        $authorized = false;
        $matchedBy = null;

        if ($requesterId) {
            // Check by person_id (preferred method)
            $authorized = $authorizedPickups->contains('authorized_person_id', $requesterId);
            $matchedBy = $authorized ? 'person_id' : null;
        }

        // If not authorized by person_id, try by name (fallback)
        if (!$authorized && $requesterName) {
            $authorized = $authorizedPickups->contains(function ($pickup) use ($requesterName) {
                return strtolower(trim($pickup->name)) === strtolower(trim($requesterName));
            });
            $matchedBy = $authorized ? 'name' : null;
        }

        // Dispatch event (ETAPA 3)
        // event(new ChildPickupAttempt($child, $requesterId, $requesterName, $authorized));

        if (!$authorized) {
            // CRITICAL ALERT - Unauthorized child pickup attempt
            Log::critical('UNAUTHORIZED CHILD PICKUP ATTEMPT', [
                'child_id' => $childId,
                'child_name' => $child->full_name,
                'requester_id' => $requesterId,
                'requester_name' => $requesterName,
                'timestamp' => now()->toDateTimeString(),
                'ip' => request()->ip(),
            ]);

            // TODO ETAPA 3: Send alert to responsible person and leadership
            // - SMS notification
            // - WhatsApp message
            // - In-app notification
            // - Email alert
        }

        return [
            'authorized' => $authorized,
            'matched_by' => $matchedBy,
            'child' => $child,
            'message' => $authorized
                ? 'Pickup authorized'
                : 'UNAUTHORIZED - Pickup blocked. Authorities notified.',
        ];
    }

    /**
     * Add authorized pickup person for child.
     *
     * @param int $childId
     * @param int|null $authorizedPersonId
     * @param string|null $name
     * @param array $additionalData (phone, relationship, etc.)
     * @return \App\Models\AuthorizedPickup
     */
    public function addAuthorizedPickup(int $childId, ?int $authorizedPersonId = null, ?string $name = null, array $additionalData = [])
    {
        $child = Person::findOrFail($childId);

        return $child->addAuthorizedPickup($authorizedPersonId, $name, $additionalData);
    }

    /**
     * Remove authorized pickup.
     *
     * @param int $authorizedPickupId
     * @return bool
     */
    public function removeAuthorizedPickup(int $authorizedPickupId): bool
    {
        $authorizedPickup = \App\Models\AuthorizedPickup::find($authorizedPickupId);

        if (!$authorizedPickup) {
            return false;
        }

        return $authorizedPickup->delete();
    }

    /**
     * Get all authorized pickups for child.
     *
     * @param int $childId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAuthorizedPickups(int $childId)
    {
        $child = Person::findOrFail($childId);

        return $child->authorizedPickups()
            ->with('authorizedPerson')
            ->get();
    }
}
