<?php

namespace App\Services;

use App\Models\Person;
use App\Models\Event;
use App\Models\Presence;
use App\Exceptions\InvalidQrCodeException;
use App\Exceptions\DuplicatePresenceException;
use App\Jobs\ProcessPresenceBatchJob;

/**
 * PresenceService
 *
 * Registra presença via QR Code, manual ou batch.
 * Presença é automática - líder não marca, sistema registra.
 */
class PresenceService
{
    /**
     * Register presence by QR Code.
     *
     * @param string $qrCode
     * @param int $eventId
     * @param int $presenceMethodId
     * @param int|null $roleId
     * @return Presence
     * @throws InvalidQrCodeException
     * @throws DuplicatePresenceException
     */
    public function registerByQrCode(string $qrCode, int $eventId, int $presenceMethodId, ?int $roleId = null): Presence
    {
        // 1. Validate QR Code format
        if (!Person::isValidQrCode($qrCode)) {
            throw new InvalidQrCodeException('Invalid QR Code format. Must be 6 digits.');
        }

        // 2. Resolve Person from QR Code
        $person = Person::resolveFromQrCode($qrCode);

        if (!$person) {
            throw new InvalidQrCodeException('QR Code not found or person does not exist');
        }

        // 3. Get Event
        $event = Event::findOrFail($eventId);

        // 4. Register presence
        return $this->register($person, $event, $presenceMethodId, $roleId);
    }

    /**
     * Register presence manually (fallback).
     *
     * @param int $personId
     * @param int $eventId
     * @param int $presenceMethodId
     * @param int|null $roleId
     * @return Presence
     * @throws DuplicatePresenceException
     */
    public function registerManual(int $personId, int $eventId, int $presenceMethodId, ?int $roleId = null): Presence
    {
        $person = Person::findOrFail($personId);
        $event = Event::findOrFail($eventId);

        return $this->register($person, $event, $presenceMethodId, $roleId);
    }

    /**
     * Register batch presences (queue).
     *
     * @param array $qrCodes
     * @param int $eventId
     * @param int $presenceMethodId
     * @return array
     */
    public function registerBatch(array $qrCodes, int $eventId, int $presenceMethodId): array
    {
        // TODO ETAPA 3: Dispatch job for batch processing
        // ProcessPresenceBatchJob::dispatch($qrCodes, $eventId, $presenceMethodId);

        // Por enquanto, processa síncrono
        $results = [
            'success' => [],
            'failed' => [],
        ];

        foreach ($qrCodes as $qrCode) {
            try {
                $presence = $this->registerByQrCode($qrCode, $eventId, $presenceMethodId);
                $results['success'][] = [
                    'qr_code' => $qrCode,
                    'person_id' => $presence->person_id,
                ];
            } catch (\Exception $e) {
                $results['failed'][] = [
                    'qr_code' => $qrCode,
                    'error' => $e->getMessage(),
                ];
            }
        }

        return [
            'message' => 'Batch presence registration completed',
            'total' => count($qrCodes),
            'success_count' => count($results['success']),
            'failed_count' => count($results['failed']),
            'results' => $results,
        ];
    }

    /**
     * Register presence (internal).
     *
     * @param Person $person
     * @param Event $event
     * @param int $presenceMethodId
     * @param int|null $roleId
     * @return Presence
     * @throws DuplicatePresenceException
     */
    private function register(Person $person, Event $event, int $presenceMethodId, ?int $roleId): Presence
    {
        // Check if already registered
        $existing = Presence::where('person_id', $person->id)
            ->where('event_id', $event->id)
            ->first();

        if ($existing) {
            throw new DuplicatePresenceException('Person already has presence registered for this event');
        }

        // Create presence
        return Presence::create([
            'person_id' => $person->id,
            'event_id' => $event->id,
            'presence_method_id' => $presenceMethodId,
            'role_id' => $roleId,
            'registered_at' => now(),
        ]);
    }

    /**
     * Get presences for event.
     *
     * @param int $eventId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getPresencesByEvent(int $eventId)
    {
        return Presence::where('event_id', $eventId)
            ->with(['person', 'presenceMethod', 'role'])
            ->orderBy('registered_at', 'desc')
            ->get();
    }

    /**
     * Get presences count for event.
     *
     * @param int $eventId
     * @return int
     */
    public function getPresencesCountByEvent(int $eventId): int
    {
        return Presence::where('event_id', $eventId)->count();
    }
}
