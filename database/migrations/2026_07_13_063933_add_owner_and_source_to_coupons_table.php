<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add ownership + origin to coupons so customers can EARN personal reward
     * coupons (owner set) alongside admin-created public coupons (owner null).
     */
    public function up(): void
    {
        Schema::table('coupons', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('id')->constrained()->nullOnDelete();
            $table->string('source')->nullable()->after('description'); // review | purchase | admin
        });
    }

    public function down(): void
    {
        Schema::table('coupons', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');
            $table->dropColumn('source');
        });
    }
};
