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
        // Cached average rating, recomputed on every review submit. The review
        // code has always written this column but no migration ever created it.
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('review', 3, 1)->nullable()->after('reach');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('review');
        });
    }
};
