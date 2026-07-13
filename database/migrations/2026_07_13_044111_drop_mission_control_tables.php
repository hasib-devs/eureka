<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Remove the Mission Control ("Wedevs AI") feature: drop its tables
     * (child before parent) and the seeded executor account.
     */
    public function up(): void
    {
        Schema::dropIfExists('task_activities');
        Schema::dropIfExists('tasks');

        DB::table('users')->where('email', 'rajin@mission.local')->delete();
    }

    /**
     * Recreate the tables (schema only) so the migration is reversible.
     */
    public function down(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title', 150);
            $table->text('description');
            $table->string('image')->nullable();
            $table->string('priority')->default('normal');
            $table->string('status')->default('awaiting_review');
            $table->date('due_date')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('last_reminded_at')->nullable();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::create('task_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('type');
            $table->string('status')->nullable();
            $table->text('body')->nullable();
            $table->timestamps();
        });
    }
};
