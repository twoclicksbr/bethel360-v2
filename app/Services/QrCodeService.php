<?php

namespace App\Services;

use App\Models\Person;

/**
 * QrCodeService
 *
 * Gera e valida QR Code de 6 dígitos.
 * Formato: ID 42 → "000042"
 */
class QrCodeService
{
    /**
     * Generate QR Code for person.
     *
     * @param Person $person
     * @return string
     */
    public function generateForPerson(Person $person): string
    {
        return $person->qr_code;
    }

    /**
     * Resolve person from QR Code.
     *
     * @param string $qrCode
     * @return Person|null
     */
    public function resolve(string $qrCode): ?Person
    {
        return Person::resolveFromQrCode($qrCode);
    }

    /**
     * Validate QR Code format.
     *
     * @param string $qrCode
     * @return bool
     */
    public function validate(string $qrCode): bool
    {
        return Person::isValidQrCode($qrCode);
    }

    /**
     * Generate QR Code image URL (placeholder for ETAPA 3).
     *
     * @param Person $person
     * @return string
     */
    public function generateImageUrl(Person $person): string
    {
        return $person->qrCodeUrl();
    }

    /**
     * Batch resolve QR Codes.
     *
     * @param array $qrCodes
     * @return array ['found' => [...], 'not_found' => [...]]
     */
    public function batchResolve(array $qrCodes): array
    {
        $found = [];
        $notFound = [];

        foreach ($qrCodes as $qrCode) {
            $person = $this->resolve($qrCode);

            if ($person) {
                $found[] = [
                    'qr_code' => $qrCode,
                    'person_id' => $person->id,
                    'name' => $person->full_name,
                ];
            } else {
                $notFound[] = $qrCode;
            }
        }

        return [
            'found' => $found,
            'not_found' => $notFound,
        ];
    }
}
