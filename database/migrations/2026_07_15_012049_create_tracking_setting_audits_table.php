<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Who changed which tracking field, and when.
 *
 * Secret fields (meta_access_token, ga4_api_secret) record a mask rather than
 * the value: the fact of the change, its timestamp and the admin responsible
 * are all auditable without ever writing a token into a second table.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tracking_setting_audits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('user_name')->nullable();   // retained if the user is later deleted
            $table->string('field');
            $table->text('old_value')->nullable();     // masked for secrets
            $table->text('new_value')->nullable();     // masked for secrets
            $table->timestamps();

            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tracking_setting_audits');
    }
};
