<?php

declare(strict_types=1);

namespace App\Services\Tracking;

use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

/**
 * Assembles Meta's user_data block — the single biggest lever on Event Match
 * Quality.
 *
 * Two rules drive everything here:
 *  - Send every identifier you legitimately have. Each additional matched field
 *    raises EMQ; omitting a field you already hold is pure lost match rate.
 *  - PII is hashed (see PiiHasher); IP, user agent, fbp and fbc are NOT — Meta
 *    matches those verbatim, and hashing them would silently break matching.
 *
 * Guest identifiers are remembered in the session from the moment they are
 * first seen (checkout email/phone entry), so every later event in the funnel
 * carries them — not just Purchase.
 */
class MetaUserData
{
    /** Session key holding identifiers captured from a guest earlier in the funnel. */
    public const SESSION_KEY = 'tracking.identifiers';

    public function __construct(private TrackingContext $context) {}

    /**
     * @param  array<string, string|null>  $overrides  raw (unhashed) values, e.g. from a checkout form
     * @return array<string, mixed>
     */
    public function build(Request $request, ?User $user = null, array $overrides = []): array
    {
        $identifiers = array_merge($this->remembered($request), array_filter($overrides, fn ($v) => filled($v)));

        // A logged-in user's profile is authoritative over anything remembered.
        if ($user) {
            $identifiers = array_merge($identifiers, array_filter([
                'em' => $user->email ?? null,
                'ph' => $user->phone ?? null,
                'external_id' => (string) $user->id,
            ], fn ($v) => filled($v)));

            $identifiers = array_merge($identifiers, $this->profileFields($user));
        }

        return $this->assemble($request, $identifiers);
    }

    /**
     * user_data for a placed order — the richest source of identifiers this app
     * has, since checkout collects name, phone, email and full address even for
     * guests.
     *
     * @return array<string, mixed>
     */
    public function fromOrder(Request $request, Order $order): array
    {
        $identifiers = array_filter([
            'em' => $order->email ?: null,
            'ph' => $order->phone ?: null,
            'fn' => $order->first_name ?: null,
            'ln' => $order->last_name ?: null,
            'ct' => $order->town ?: ($order->district ?: null),
            'st' => $order->district ?: null,
            'zp' => $order->post_code ?: null,
            'country' => $order->country ?: null,
            'external_id' => $order->user_id ? (string) $order->user_id : null,
        ], fn ($v) => filled($v));

        // Fall back to anything remembered that the order itself lacks.
        $identifiers = array_merge($this->remembered($request), $identifiers);

        return $this->assemble($request, $identifiers);
    }

    /**
     * Persist identifiers seen mid-funnel (a guest typing their phone at
     * checkout) so subsequent events can match on them too.
     *
     * @param  array<string, string|null>  $identifiers  raw values
     */
    public function remember(Request $request, array $identifiers): void
    {
        $clean = array_filter($identifiers, fn ($v) => filled($v));

        if ($clean === []) {
            return;
        }

        $request->session()->put(
            self::SESSION_KEY,
            array_merge($this->remembered($request), $clean)
        );
    }

    /** @return array<string, string> */
    public function remembered(Request $request): array
    {
        if (! $request->hasSession()) {
            return [];
        }

        $stored = $request->session()->get(self::SESSION_KEY, []);

        return is_array($stored) ? $stored : [];
    }

    /**
     * Profile fields beyond email/phone, when the User model happens to carry
     * them. Columns vary across installs, so each is probed rather than assumed.
     *
     * @return array<string, string>
     */
    private function profileFields(User $user): array
    {
        $candidates = [
            'fn' => ['first_name', 'fname', 'name'],
            'ln' => ['last_name', 'lname'],
            'ct' => ['city', 'town'],
            'st' => ['state', 'district'],
            'zp' => ['post_code', 'zip', 'postcode'],
            'country' => ['country'],
            'ge' => ['gender'],
            'db' => ['date_of_birth', 'dob', 'birthday'],
        ];

        $found = [];

        foreach ($candidates as $metaKey => $columns) {
            foreach ($columns as $column) {
                $value = $user->getAttribute($column);

                if (filled($value)) {
                    $found[$metaKey] = (string) $value;

                    break;
                }
            }
        }

        return $found;
    }

    /**
     * Hash the PII, attach the unhashed signals, drop the empties.
     *
     * @param  array<string, string>  $identifiers
     * @return array<string, mixed>
     */
    private function assemble(Request $request, array $identifiers): array
    {
        $name = $identifiers['fn'] ?? null;

        // "name" holding a full name is common in this app; split it so both
        // fn and ln can match rather than hashing "john doe" as a first name.
        if ($name !== null && ! isset($identifiers['ln']) && str_contains(trim($name), ' ')) {
            $parts = preg_split('/\s+/', trim($name)) ?: [];
            $identifiers['fn'] = array_shift($parts);
            $identifiers['ln'] = implode(' ', $parts);
        }

        $userData = [
            // Hashed PII.
            'em' => PiiHasher::email($identifiers['em'] ?? null),
            'ph' => PiiHasher::phone($identifiers['ph'] ?? null),
            'fn' => PiiHasher::firstName($identifiers['fn'] ?? null),
            'ln' => PiiHasher::lastName($identifiers['ln'] ?? null),
            'ge' => PiiHasher::gender($identifiers['ge'] ?? null),
            'db' => PiiHasher::birthdate($identifiers['db'] ?? null),
            'ct' => PiiHasher::city($identifiers['ct'] ?? null),
            'st' => PiiHasher::state($identifiers['st'] ?? null),
            'zp' => PiiHasher::zip($identifiers['zp'] ?? null),
            'country' => PiiHasher::country($identifiers['country'] ?? null),
            'external_id' => PiiHasher::externalId($identifiers['external_id'] ?? null),

            // Deliberately unhashed — Meta matches these verbatim.
            'client_ip_address' => $this->context->clientIp($request),
            'client_user_agent' => $this->context->userAgent($request),
            'fbp' => $this->context->fbp($request),
            'fbc' => $this->context->fbc($request),
        ];

        return array_filter($userData, fn ($v) => $v !== null);
    }
}
