<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PresenceMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $presenceMethods = [
            [
                'slug' => 'qrcode-telao',
                'name' => 'QR Code Telão',
                'description' => 'Pessoa escaneia QR Code exibido no telão (cultos)',
                'is_automatic' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'qrcode-scanner',
                'name' => 'QR Code Scanner',
                'description' => 'Líder escaneia QR Code da pessoa (grupos pequenos)',
                'is_automatic' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'manual',
                'name' => 'Manual',
                'description' => 'Registro manual por email ou código (fallback)',
                'is_automatic' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'google-meet',
                'name' => 'Google Meet',
                'description' => 'Detecção automática por tempo de permanência no Meet',
                'is_automatic' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'nfc',
                'name' => 'NFC',
                'description' => 'Aproximação de tag NFC (futuro)',
                'is_automatic' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'biometria',
                'name' => 'Biometria',
                'description' => 'Reconhecimento biométrico (futuro)',
                'is_automatic' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('presence_methods')->insert($presenceMethods);
    }
}
