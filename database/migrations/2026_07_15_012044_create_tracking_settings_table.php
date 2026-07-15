<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Single-row table holding every runtime-configurable tracking value.
 *
 * Deliberately NOT the shared `settings` key/value table: Setting::cached()
 * loads every row into one rememberForever array that the global setting()
 * helper exposes to all Blade views, so an access token stored there would sit
 * decrypted in a cache blob reachable from any template. Secrets live here with
 * `encrypted` casts instead, and never enter that path.
 *
 * Everything ships disabled with empty values, so deploying this changes no
 * live behaviour — the existing setting('fb_pixel') snippet keeps rendering
 * until an admin deliberately switches over.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tracking_settings', function (Blueprint $table) {
            $table->id();

            // Meta Pixel + Conversions API
            $table->string('meta_pixel_id')->nullable();
            $table->text('meta_access_token')->nullable();     // encrypted at rest
            $table->string('meta_test_event_code')->nullable();
            $table->string('meta_api_version')->default('v25.0');
            $table->boolean('meta_enabled')->default(false);

            // Google Tag Manager
            $table->string('gtm_container_id')->nullable();
            $table->boolean('gtm_enabled')->default(false);

            // Google Analytics 4 (client + Measurement Protocol)
            $table->string('ga4_measurement_id')->nullable();
            $table->text('ga4_api_secret')->nullable();        // encrypted at rest
            $table->boolean('ga4_enabled')->default(false);

            // Google Search Console
            $table->string('gsc_verification_code')->nullable();
            $table->boolean('gsc_enabled')->default(false);

            // Consent Mode v2
            $table->boolean('consent_mode_enabled')->default(true);
            $table->boolean('consent_banner_enabled')->default(true);
            $table->json('consent_default_row')->nullable();   // BD / rest-of-world defaults
            $table->json('consent_default_eu')->nullable();    // EU/EEA/UK defaults

            // Canonical domain — single source of truth for event_source_url,
            // GA4 document_location, canonical tags, og:url, sitemap, robots.
            $table->string('site_url')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tracking_settings');
    }
};
