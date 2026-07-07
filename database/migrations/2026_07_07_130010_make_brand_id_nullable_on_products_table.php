<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // The rebuilt admin product form no longer requires a brand, so the
        // column must accept null — it was NOT NULL, which made every save fail.
        Schema::table('products', function (Blueprint $table) {
            $table->unsignedBigInteger('brand_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->unsignedBigInteger('brand_id')->nullable(false)->change();
        });
    }
};
