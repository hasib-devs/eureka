/**
 * Central tracking utility.
 *
 * One function fires every event everywhere: trackEvent(). It mints an event_id,
 * sends the Meta pixel copy with that id, pushes the GA4-shaped copy to
 * dataLayer, and returns the id so a server-side CAPI / Measurement Protocol
 * call for the same user action can share it. Matching event_name + event_id is
 * what makes Meta collapse the browser and server copies into one event rather
 * than counting two.
 *
 * Config is injected server-side as window.__TRACKING__ (see the tracking head
 * partial). It contains no secrets — only public IDs.
 */

const config = window.__TRACKING__ || {}

/** Meta event name -> GA4 event name. */
const GA4_NAMES = {
    PageView: 'page_view',
    ViewContent: 'view_item',
    Search: 'search',
    AddToCart: 'add_to_cart',
    InitiateCheckout: 'begin_checkout',
    AddPaymentInfo: 'add_payment_info',
    Purchase: 'purchase',
    Lead: 'generate_lead',
    CompleteRegistration: 'sign_up',
    Contact: 'contact',
}

function uuid() {
    if (window.crypto && typeof window.crypto.randomUUID === 'function') {
        return window.crypto.randomUUID()
    }

    // Older Safari/in-app browsers have no randomUUID. Uniqueness is all that
    // matters here (dedup key), not cryptographic quality.
    return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, (c) => {
        const r = (Math.random() * 16) | 0
        const v = c === 'x' ? r : (r & 0x3) | 0x8
        return v.toString(16)
    })
}

function dataLayerPush(payload) {
    window.dataLayer = window.dataLayer || []
    window.dataLayer.push(payload)
}

/**
 * Meta's `contents` -> GA4's `items`. The two platforms want the same facts in
 * different shapes; converting here keeps every caller on one payload.
 */
function toGa4Items(customData) {
    if (Array.isArray(customData.items)) return customData.items

    const contents = customData.contents || []

    return contents.map((c) => ({
        item_id: String(c.id),
        item_name: c.item_name || c.name || undefined,
        price: c.item_price,
        quantity: c.quantity,
    }))
}

/**
 * Fire one event to every configured destination.
 *
 * @param {string} eventName  Meta event name (e.g. "AddToCart")
 * @param {object} customData Meta-shaped custom data
 * @param {object} options    { eventId } to reuse a server-minted id
 * @returns {string} the event_id used
 */
export function trackEvent(eventName, customData = {}, options = {}) {
    const eventId = options.eventId || uuid()

    // Meta
    if (config.pixelId && typeof window.fbq === 'function') {
        window.fbq('track', eventName, customData, { eventID: eventId })
    }

    // GA4 / GTM
    const ga4Name = options.ga4Name || GA4_NAMES[eventName] || eventName

    if (config.ga4Id || config.gtmId) {
        const { contents, content_ids, content_type, num_items, ...rest } = customData
        const items = toGa4Items(customData)

        dataLayerPush({
            event: ga4Name,
            event_id: eventId,
            ecommerce: {
                ...rest,
                ...(items.length ? { items } : {}),
            },
        })
    }

    return eventId
}

window.trackEvent = trackEvent

// ─── fbc: preserve click attribution ────────────────────────────────────────

/**
 * The pixel cookies _fbc itself, but only once it has loaded. A visitor who
 * lands from an ad and bounces before that can lose click attribution entirely,
 * so cookie it immediately from the fbclid query param per Meta's format:
 *   fb.{subdomainIndex}.{creationTimeMs}.{fbclid}
 *
 * The cookie domain comes from the server (derived from the request host), never
 * a hardcoded string — so a domain migration needs no code change here.
 */
function captureFbc() {
    const params = new URLSearchParams(window.location.search)
    const fbclid = params.get('fbclid')

    if (!fbclid || readCookie('_fbc')) return

    const host = config.cookieDomain || window.location.hostname
    const subdomainIndex = Math.max(1, host.split('.').length - 1)
    const value = `fb.${subdomainIndex}.${Date.now()}.${fbclid}`

    writeCookie('_fbc', value, 90)
}

function readCookie(name) {
    const match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'))
    return match ? decodeURIComponent(match[2]) : null
}

function writeCookie(name, value, days) {
    const expires = new Date(Date.now() + days * 864e5).toUTCString()
    const domain = config.cookieDomain ? `; domain=${config.cookieDomain}` : ''
    const secure = window.location.protocol === 'https:' ? '; secure' : ''

    document.cookie = `${name}=${encodeURIComponent(value)}; expires=${expires}; path=/${domain}${secure}; samesite=Lax`
}

// ─── Consent ────────────────────────────────────────────────────────────────

/**
 * Record a consent choice and tell Google about it.
 * Called by the consent banner; safe to call from anywhere.
 */
export function setConsent(granted) {
    const state = {
        ad_storage: granted ? 'granted' : 'denied',
        analytics_storage: granted ? 'granted' : 'denied',
        ad_user_data: granted ? 'granted' : 'denied',
        ad_personalization: granted ? 'granted' : 'denied',
    }

    if (typeof window.gtag === 'function') {
        window.gtag('consent', 'update', state)
    } else {
        dataLayerPush(['consent', 'update', state])
    }

    // The server reads this cookie to resolve defaults on the next page load
    // and to gate server-side GA4 hits.
    writeCookie(config.consentCookie || 'tracking_consent', JSON.stringify(state), 180)

    // A visitor who consents after the pixel loaded has not sent a PageView yet
    // under denied storage; send one now so the session is not lost.
    if (granted && config.pixelId && typeof window.fbq === 'function') {
        trackEvent('PageView')
    }
}

window.setTrackingConsent = setConsent

// ─── Boot ───────────────────────────────────────────────────────────────────

function fireQueuedServerEvents() {
    const queued = window.__TRACKING_QUEUED__ || []

    queued.forEach((item) => {
        // Reuses the server's event_id — this is the dedup contract.
        trackEvent(item.meta, item.data || {}, {
            eventId: item.eventId,
            ga4Name: item.ga4,
        })
    })

    window.__TRACKING_QUEUED__ = []
}

function boot() {
    if (!config.pixelId && !config.ga4Id && !config.gtmId) return

    captureFbc()

    // PageView. The pixel's own init does not send one, so this is the only
    // PageView — no double count.
    if (config.pixelId && typeof window.fbq === 'function') {
        window.fbq('track', 'PageView')
    }

    fireQueuedServerEvents()
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', boot)
} else {
    boot()
}

export default { trackEvent, setConsent }
