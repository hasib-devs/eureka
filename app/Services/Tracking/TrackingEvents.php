<?php

declare(strict_types=1);

namespace App\Services\Tracking;

use App\Jobs\SendGa4MpEvent;
use App\Jobs\SendMetaCapiEvent;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

/**
 * The one entry point controllers use to record a conversion.
 *
 * Follows the same shape as the existing OrderSms::send($order) call it sits
 * beside in OrderController::createOrder().
 *
 * Each method:
 *  - mints one event_id,
 *  - queues the browser leg (fbq + dataLayer) to render on the next page,
 *  - dispatches the server legs (Meta CAPI, GA4 MP) after the response,
 * all sharing that event_id. Matching event_name + event_id is exactly what
 * lets Meta collapse the browser and server copies into a single event.
 */
class TrackingEvents
{
    /** Session key holding browser events waiting to be rendered. */
    public const QUEUE_KEY = 'tracking.browser_events';

    public function __construct(
        private TrackingSettingsService $settings,
        private MetaUserData $userData,
        private TrackingContext $context,
        private ConsentResolver $consent,
        private Ga4MeasurementProtocolService $ga4,
    ) {}

    // ─── Conversions ────────────────────────────────────────────────────────

    /**
     * Purchase — fired from OrderController::createOrder(), the single point
     * every order path funnels through.
     */
    public function purchase(Request $request, Order $order): string
    {
        $eventId = $this->eventId();

        // Queried fresh: the rows are written immediately before this call, so a
        // previously-loaded relation would be empty.
        $items = $order->orderDetails()->get();

        $contents = [];
        $contentIds = [];
        $numItems = 0;

        foreach ($items as $item) {
            $contentIds[] = (string) $item->product_id;
            $contents[] = [
                'id' => (string) $item->product_id,
                'quantity' => (int) $item->qty,
                'item_price' => round((float) $item->price, 2),
            ];
            $numItems += (int) $item->qty;
        }

        $custom = [
            'currency' => $this->currency(),
            'value' => round((float) $order->total, 2),
            'content_type' => 'product',
            'content_ids' => $contentIds,
            'contents' => $contents,
            'num_items' => $numItems,
            'order_id' => $order->order_id,
        ];

        // Identifiers taken from the order itself, which carries name, phone,
        // email and address even for a guest — the richest set available.
        $userData = $this->userData->fromOrder($request, $order);

        $this->queueBrowserEvent($request, 'Purchase', 'purchase', $eventId, $custom);
        $this->dispatchMeta($request, 'Purchase', $eventId, $userData, $custom);

        $this->dispatchGa4($request, 'purchase', [
            'currency' => $this->currency(),
            'value' => round((float) $order->total, 2),
            'transaction_id' => $order->order_id,
            'shipping' => round((float) $order->shipping_charge, 2),
            'items' => $this->ga4Items($items),
        ], $order->user_id ? (string) $order->user_id : null);

        return $eventId;
    }

    /**
     * Lead — an abandoned-checkout capture. Also remembers the identifiers so
     * every later event in this session can match on them.
     */
    public function lead(Request $request, array $identifiers = [], array $custom = []): string
    {
        $eventId = $this->eventId();

        $this->userData->remember($request, $identifiers);

        $userData = $this->userData->build($request, Auth::user(), $identifiers);

        $this->queueBrowserEvent($request, 'Lead', 'generate_lead', $eventId, $custom);
        $this->dispatchMeta($request, 'Lead', $eventId, $userData, $custom);
        $this->dispatchGa4($request, 'generate_lead', $custom);

        return $eventId;
    }

    public function completeRegistration(Request $request, User $user): string
    {
        $eventId = $this->eventId();

        $userData = $this->userData->build($request, $user);
        $custom = ['content_name' => 'Registration'];

        $this->queueBrowserEvent($request, 'CompleteRegistration', 'sign_up', $eventId, $custom);
        $this->dispatchMeta($request, 'CompleteRegistration', $eventId, $userData, $custom);
        $this->dispatchGa4($request, 'sign_up', $custom, (string) $user->id);

        return $eventId;
    }

    /**
     * InitiateCheckout. Identifiers are remembered here so a guest who types
     * their phone at checkout keeps matching on later events, not just Purchase.
     */
    public function initiateCheckout(Request $request, array $custom = [], array $identifiers = []): string
    {
        $eventId = $this->eventId();

        if ($identifiers !== []) {
            $this->userData->remember($request, $identifiers);
        }

        $userData = $this->userData->build($request, Auth::user(), $identifiers);

        $this->queueBrowserEvent($request, 'InitiateCheckout', 'begin_checkout', $eventId, $custom);
        $this->dispatchMeta($request, 'InitiateCheckout', $eventId, $userData, $custom);

        return $eventId;
    }

    public function addPaymentInfo(Request $request, array $custom = [], array $identifiers = []): string
    {
        $eventId = $this->eventId();

        if ($identifiers !== []) {
            $this->userData->remember($request, $identifiers);
        }

        $userData = $this->userData->build($request, Auth::user(), $identifiers);

        $this->queueBrowserEvent($request, 'AddPaymentInfo', 'add_payment_info', $eventId, $custom);
        $this->dispatchMeta($request, 'AddPaymentInfo', $eventId, $userData, $custom);

        return $eventId;
    }

    public function contact(Request $request, array $identifiers = [], array $custom = []): string
    {
        $eventId = $this->eventId();

        $this->userData->remember($request, $identifiers);

        $userData = $this->userData->build($request, Auth::user(), $identifiers);

        $this->queueBrowserEvent($request, 'Contact', 'contact', $eventId, $custom);
        $this->dispatchMeta($request, 'Contact', $eventId, $userData, $custom);

        return $eventId;
    }

    // ─── Browser queue ──────────────────────────────────────────────────────

    /**
     * Hand an event to the browser layer to fire with this same event_id.
     *
     * Uses flash so it works whether the controller returns a view directly
     * (OrderController renders order_success inline) or redirects: the head
     * component renders it and immediately forgets it, so it fires exactly once
     * either way.
     */
    public function queueBrowserEvent(
        Request $request,
        string $metaName,
        string $ga4Name,
        string $eventId,
        array $custom = [],
    ): void {
        if (! $request->hasSession()) {
            return;
        }

        $queued = $request->session()->get(self::QUEUE_KEY, []);

        $queued[] = [
            'meta' => $metaName,
            'ga4' => $ga4Name,
            'eventId' => $eventId,
            'data' => $custom,
        ];

        $request->session()->flash(self::QUEUE_KEY, $queued);
    }

    /**
     * Drain the queue for rendering. Forgetting here is what prevents a
     * same-request render from firing again on the following request.
     *
     * @return array<int, array<string, mixed>>
     */
    public function drainBrowserEvents(Request $request): array
    {
        if (! $request->hasSession()) {
            return [];
        }

        $queued = $request->session()->get(self::QUEUE_KEY, []);

        $request->session()->forget(self::QUEUE_KEY);

        return is_array($queued) ? $queued : [];
    }

    // ─── Dispatch ───────────────────────────────────────────────────────────

    private function dispatchMeta(Request $request, string $eventName, string $eventId, array $userData, array $custom): void
    {
        if (! $this->settings->metaCapiEnabled()) {
            return;
        }

        // Everything the job needs is resolved here, while the Request exists.
        SendMetaCapiEvent::dispatch(
            $eventName,
            $eventId,
            $userData,
            $custom,
            $this->context->eventSourceUrl($request),
            time(),
        )->afterResponse();
    }

    private function dispatchGa4(Request $request, string $eventName, array $params, ?string $userId = null): void
    {
        if (! $this->settings->ga4MpEnabled()) {
            return;
        }

        // The browser's Consent Mode signals never reach a server-side hit, so
        // a denied user would be tracked anyway unless we check it ourselves.
        if (! $this->consent->analyticsGranted($request)) {
            return;
        }

        SendGa4MpEvent::dispatch(
            $eventName,
            $this->ga4->clientIdFor($request),
            $params + ['engagement_time_msec' => 1],
            $userId ?? (Auth::id() ? (string) Auth::id() : null),
        )->afterResponse();
    }

    // ─── Helpers ────────────────────────────────────────────────────────────

    public function eventId(): string
    {
        return (string) Str::uuid();
    }

    /** ISO 4217 code from shop settings; BDT is this store's default. */
    public function currency(): string
    {
        return (string) (setting('CURRENCY_CODE') ?: 'BDT');
    }

    /**
     * @param  iterable<object>  $items
     * @return array<int, array<string, mixed>>
     */
    private function ga4Items(iterable $items): array
    {
        $mapped = [];

        foreach ($items as $item) {
            $mapped[] = [
                'item_id' => (string) $item->product_id,
                'item_name' => $item->title ?? '',
                'price' => round((float) $item->price, 2),
                'quantity' => (int) $item->qty,
            ];
        }

        return $mapped;
    }
}
