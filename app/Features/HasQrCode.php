<?php

namespace App\Features;

/**
 * HasQrCode Trait
 *
 * Gera e valida QR Code de 6 dígitos baseado no ID.
 * Formato: ID 42 → "000042"
 * Usado por: Person
 */
trait HasQrCode
{
    /**
     * Get QR Code (6-digit formatted ID).
     *
     * @return string
     */
    public function getQrCodeAttribute(): string
    {
        return str_pad($this->id, 6, '0', STR_PAD_LEFT);
    }

    /**
     * Resolve Person from QR Code.
     *
     * @param string $qrCode
     * @return static|null
     */
    public static function resolveFromQrCode(string $qrCode): ?self
    {
        // Remove leading zeros to get ID
        $id = ltrim($qrCode, '0');

        // Handle case where all zeros (000000) should be ID 0
        if ($id === '') {
            $id = 0;
        }

        return static::find($id);
    }

    /**
     * Validate QR Code format.
     *
     * @param string $qrCode
     * @return bool
     */
    public static function isValidQrCode(string $qrCode): bool
    {
        // Must be exactly 6 digits
        return preg_match('/^\d{6}$/', $qrCode) === 1;
    }

    /**
     * Generate QR Code URL for display (placeholder for ETAPA 3).
     *
     * @return string
     */
    public function qrCodeUrl(): string
    {
        // TODO ETAPA 3: Generate actual QR Code image URL
        return "https://api.qrserver.com/v1/create-qr-code/?data={$this->qr_code}&size=200x200";
    }
}
