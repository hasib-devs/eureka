<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Services\Tracking\Ga4MeasurementProtocolService;
use App\Services\Tracking\MetaCapiService;
use App\Services\Tracking\PiiHasher;
use App\Services\Tracking\TrackingSettingsService;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

/**
 * Fires one complete sample event through the whole pipeline so it can be
 * verified end to end with a single command.
 *
 * The browser leg cannot be executed from the CLI, so the exact fbq call is
 * printed instead — it carries the same event_id the server used, which is the
 * dedup contract. Paste it into the browser console on the live site to confirm
 * the two collapse into one event in Events Manager.
 */
class TrackingVerify extends Command
{
    protected $signature = 'tracking:verify
                            {--event=Purchase : Meta event name to send}
                            {--email=test@example.com : Sample email to match on}
                            {--phone=01712345678 : Sample phone to match on}
                            {--dry : Build and print the payloads without sending}';

    protected $description = 'Fire one sample event through Meta CAPI and GA4 Measurement Protocol to verify the tracking pipeline';

    public function handle(
        TrackingSettingsService $settings,
        MetaCapiService $capi,
        Ga4MeasurementProtocolService $ga4,
    ): int {
        $settings->flush();

        $this->components->info('Tracking pipeline verification');

        if ($settings->degraded()) {
            $this->components->error('Tracking settings could not be read. Is the database reachable and migrated?');

            return self::FAILURE;
        }

        // One id, shared by every leg. This is what makes Meta dedupe.
        $eventId = (string) Str::uuid();
        $eventName = (string) $this->option('event');
        $clientId = random_int(1_000_000_000, 9_999_999_999).'.'.time();

        $this->line('');
        $this->components->twoColumnDetail('<fg=gray>Event</>', $eventName);
        $this->components->twoColumnDetail('<fg=gray>Event ID (shared)</>', $eventId);
        $this->components->twoColumnDetail('<fg=gray>GA4 client_id</>', $clientId);
        $this->components->twoColumnDetail('<fg=gray>Site URL</>', $settings->siteUrl());
        $this->line('');

        $this->configTable($settings);

        $userData = [
            'em' => PiiHasher::email((string) $this->option('email')),
            'ph' => PiiHasher::phone((string) $this->option('phone')),
            'external_id' => PiiHasher::externalId('tracking-verify'),
            'client_ip_address' => '203.0.113.10',   // TEST-NET-3, reserved for docs
            'client_user_agent' => 'Mozilla/5.0 (tracking:verify)',
        ];

        $customData = [
            'currency' => setting('CURRENCY_CODE') ?: 'BDT',
            'value' => 1.0,
            'content_type' => 'product',
            'content_ids' => ['tracking-verify'],
            'contents' => [['id' => 'tracking-verify', 'quantity' => 1, 'item_price' => 1.0]],
            'num_items' => 1,
            'order_id' => 'VERIFY-'.strtoupper(Str::random(6)),
        ];

        if ($this->option('dry')) {
            $this->components->warn('Dry run — nothing was sent.');
            $this->payloadPreview($capi, $ga4, $eventName, $eventId, $userData, $customData, $clientId, $settings);

            return self::SUCCESS;
        }

        $metaOk = $this->sendMeta($settings, $capi, $eventName, $eventId, $userData, $customData);
        $ga4Ok = $this->sendGa4($settings, $ga4, $eventId, $clientId, $customData);

        $this->browserLeg($settings, $eventName, $eventId, $customData);
        $this->nextSteps($settings);

        return ($metaOk || $ga4Ok) ? self::SUCCESS : self::FAILURE;
    }

    private function configTable(TrackingSettingsService $settings): void
    {
        $rows = [
            ['Meta Pixel (browser)', $settings->metaPixelEnabled()],
            ['Meta CAPI (server)', $settings->metaCapiEnabled()],
            ['GTM', $settings->gtmEnabled()],
            ['GA4 (browser)', $settings->ga4Enabled()],
            ['GA4 MP (server)', $settings->ga4MpEnabled()],
            ['Search Console', $settings->gscEnabled()],
            ['Consent Mode', $settings->consentModeEnabled()],
        ];

        foreach ($rows as [$label, $on]) {
            $this->components->twoColumnDetail(
                $label,
                $on ? '<fg=green;options=bold>ENABLED</>' : '<fg=gray>disabled</>'
            );
        }

        $this->line('');
    }

    private function sendMeta(
        TrackingSettingsService $settings,
        MetaCapiService $capi,
        string $eventName,
        string $eventId,
        array $userData,
        array $customData,
    ): bool {
        if (! $settings->metaCapiEnabled()) {
            $this->components->warn('Meta CAPI skipped — not enabled, or Pixel ID / Access Token missing.');

            return false;
        }

        $testCode = $settings->current()->meta_test_event_code;

        if (blank($testCode)) {
            $this->components->warn(
                'No test_event_code set. This event will count as REAL traffic. '
                .'Set one in the admin panel to send it to Test Events instead.'
            );
        }

        $ok = $capi->send(
            $eventName,
            $eventId,
            $userData,
            $customData,
            $settings->canonical('tracking-verify')
        );

        $ok
            ? $this->components->info('Meta CAPI: sent.')
            : $this->components->error('Meta CAPI: failed — see the log for the status and reason.');

        return $ok;
    }

    private function sendGa4(
        TrackingSettingsService $settings,
        Ga4MeasurementProtocolService $ga4,
        string $eventId,
        string $clientId,
        array $customData,
    ): bool {
        if (! $settings->ga4MpEnabled()) {
            $this->components->warn('GA4 Measurement Protocol skipped — not enabled, or Measurement ID / API Secret missing.');

            return false;
        }

        $ok = $ga4->send('purchase', $clientId, [
            'currency' => $customData['currency'],
            'value' => $customData['value'],
            'transaction_id' => $customData['order_id'],
            'event_id' => $eventId,
            'debug_mode' => 1,   // makes it visible in DebugView
            'engagement_time_msec' => 1,
            'items' => [[
                'item_id' => 'tracking-verify',
                'item_name' => 'Tracking Verification',
                'price' => 1.0,
                'quantity' => 1,
            ]],
        ]);

        $ok
            ? $this->components->info('GA4 Measurement Protocol: sent.')
            : $this->components->error('GA4 Measurement Protocol: failed — see the log.');

        return $ok;
    }

    /** The browser half of the dedup pair, for pasting into the console. */
    private function browserLeg(TrackingSettingsService $settings, string $eventName, string $eventId, array $customData): void
    {
        if (! $settings->metaPixelEnabled()) {
            return;
        }

        $this->line('');
        $this->components->info('Browser leg — paste into the console on '.$settings->siteUrl().':');
        $this->line('');
        $this->line(sprintf(
            "  <fg=cyan>trackEvent(%s, %s, { eventId: '%s' })</>",
            json_encode($eventName),
            json_encode($customData),
            $eventId
        ));
        $this->line('');
        $this->line('  <fg=gray>Same event_id as the server leg. Events Manager must show ONE event, not two.</>');
    }

    private function nextSteps(TrackingSettingsService $settings): void
    {
        $this->line('');
        $this->components->info('Confirm it landed:');

        $lines = [
            'Meta:  Events Manager -> your pixel -> Test Events'
                .(filled($settings->current()->meta_test_event_code) ? '' : ' (needs a test_event_code)'),
            'GA4:   Admin -> DebugView (this event sets debug_mode)',
            'EMQ:   Events Manager -> Data Sources -> Event Match Quality (takes ~24-48h to settle)',
        ];

        foreach ($lines as $line) {
            $this->line('  <fg=gray>-</> '.$line);
        }

        $this->line('');
    }

    private function payloadPreview(
        MetaCapiService $capi,
        Ga4MeasurementProtocolService $ga4,
        string $eventName,
        string $eventId,
        array $userData,
        array $customData,
        string $clientId,
        TrackingSettingsService $settings,
    ): void {
        $meta = $capi->payload($eventName, $eventId, $userData, $customData, $settings->canonical('tracking-verify'));

        // The token would otherwise be printed to a terminal and scrollback.
        $meta['access_token'] = '[redacted]';

        $this->line('');
        $this->line('<fg=gray>Meta CAPI payload:</>');
        $this->line(json_encode($meta, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        $this->line('');
        $this->line('<fg=gray>GA4 MP payload:</>');
        $this->line(json_encode(
            $ga4->payload('purchase', $clientId, ['currency' => $customData['currency'], 'value' => $customData['value']]),
            JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
        ));
        $this->line('');
    }
}
