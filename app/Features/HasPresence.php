<?php

namespace App\Features;

use App\Models\Person;
use App\Models\Presence;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * HasPresence Trait
 *
 * Registra presença automática em eventos de grupos/ministérios.
 * Presença pode ser: participante ou servindo.
 * Usado por: Group, Event
 */
trait HasPresence
{
    /**
     * Get all presences for the model.
     */
    public function presences(): MorphMany
    {
        return $this->morphMany(Presence::class, 'presenceable');
    }

    /**
     * Register presence for a person.
     *
     * @param Person $person
     * @param int $presenceMethodId
     * @param int|null $roleId
     * @return Presence
     */
    public function registerPresence(Person $person, int $presenceMethodId, ?int $roleId = null): Presence
    {
        // Verifica se já existe presença registrada
        $existing = $this->presences()
            ->where('person_id', $person->id)
            ->first();

        if ($existing) {
            throw new \Exception('Person already has presence registered for this entity');
        }

        return $this->presences()->create([
            'person_id' => $person->id,
            'presence_method_id' => $presenceMethodId,
            'role_id' => $roleId,
            'registered_at' => now(),
        ]);
    }

    /**
     * Get presences count.
     *
     * @return int
     */
    public function presencesCount(): int
    {
        return $this->presences()->count();
    }

    /**
     * Get presences by date range.
     *
     * @param \Carbon\Carbon $startDate
     * @param \Carbon\Carbon $endDate
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function presencesByDateRange($startDate, $endDate)
    {
        return $this->presences()
            ->whereBetween('registered_at', [$startDate, $endDate])
            ->get();
    }

    /**
     * Get presences by person.
     *
     * @param Person $person
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function presencesByPerson(Person $person)
    {
        return $this->presences()
            ->where('person_id', $person->id)
            ->orderBy('registered_at', 'desc')
            ->get();
    }

    /**
     * Check if person has presence.
     *
     * @param Person $person
     * @return bool
     */
    public function hasPresence(Person $person): bool
    {
        return $this->presences()
            ->where('person_id', $person->id)
            ->exists();
    }
}
