<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Services\Tracking\Ga4MeasurementProtocolService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * Delivers one GA4 Measurement Protocol event outside the customer's request.
 * See SendMetaCapiEvent for the dispatch rationale.
 */
class SendGa4MpEvent implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 1; // The service owns its own retry/backoff.

    /**
     * @param  array<string, mixed>  $params
     */
    public function __construct(
        public string $eventName,
        public string $clientId,
        public array $params = [],
        public ?string $userId = null,
    ) {}

    public function handle(Ga4MeasurementProtocolService $ga4): void
    {
        $ga4->send($this->eventName, $this->clientId, $this->params, $this->userId);
    }
}
