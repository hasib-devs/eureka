<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Encryption\Encrypter;

/**
 * Generates a SETTINGS_ENCRYPTION_KEY.
 *
 * Deliberately prints the key rather than writing to .env: .env is not in the
 * repo and deploys reset the working tree, so the value has to be placed on the
 * server by hand anyway. Printing keeps that explicit.
 */
class TrackingKey extends Command
{
    protected $signature = 'tracking:key';

    protected $description = 'Generate a SETTINGS_ENCRYPTION_KEY for encrypting tracking secrets at rest';

    public function handle(): int
    {
        $key = 'base64:'.base64_encode(Encrypter::generateKey(config('app.cipher', 'AES-256-CBC')));

        $this->line('');
        $this->components->info('Add this to your .env, then re-paste the secrets in the admin panel:');
        $this->line('');
        $this->line('  <fg=cyan>SETTINGS_ENCRYPTION_KEY='.$key.'</>');
        $this->line('');

        $this->components->warn(
            'Rotating this key makes existing encrypted secrets unreadable. '
            .'After changing it, re-enter the Meta access token and GA4 API secret in '
            .'Admin -> Tracking & Integrations.'
        );

        if (filled(config('tracking.encryption_key'))) {
            $this->components->warn('A SETTINGS_ENCRYPTION_KEY is already configured — replacing it is a rotation.');
        } else {
            $this->line('  <fg=gray>Currently falling back to APP_KEY. Everything works without this; it only isolates the key.</>');
            $this->line('');
        }

        return self::SUCCESS;
    }
}
