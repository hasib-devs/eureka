<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Services\Tracking\MetaCapiService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * Delivers one Conversions API event outside the customer's request.
 *
 * Dispatched with ->afterResponse(), so on the current `sync` queue driver it
 * runs after the response is flushed rather than blocking checkout on Meta's
 * API. If QUEUE_CONNECTION is ever switched to database/redis this becomes a
 * fully queued job with no code change.
 *
 * The payload is plain arrays, built while the Request was still available —
 * a Request cannot be serialized into a job.
 */
class SendMetaCapiEvent implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 1; // MetaCapiService owns its own retry/backoff.

    /**
     * @param  array<string, mixed>  $userData
     * @param  array<string, mixed>  $customData
     */
    public function __construct(
        public string $eventName,
        public string $eventId,
        public array $userData,
        public array $customData = [],
        public ?string $eventSourceUrl = null,
        public ?int $eventTime = null,
    ) {}

    public function handle(MetaCapiService $capi): void
    {
        $capi->send(
            $this->eventName,
            $this->eventId,
            $this->userData,
            $this->customData,
            $this->eventSourceUrl,
            $this->eventTime,
        );
    }
}
