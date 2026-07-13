<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Admin-managed header navigation. Seeds the current default links so the
     * menu is populated (and editable) right after migrating; the frontend also
     * falls back to these defaults if the table is empty or absent.
     */
    public function up(): void
    {
        Schema::create('menu_items', function (Blueprint $table) {
            $table->id();
            $table->string('label');
            $table->string('url')->default('#');
            $table->integer('sort_order')->default(0);
            $table->boolean('new_tab')->default(false);
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        $now = now();
        $defaults = [
            ['label' => 'Home', 'route' => 'home'],
            ['label' => 'Shop', 'route' => 'product'],
            ['label' => 'Categories', 'route' => 'categories_all'],
            ['label' => 'Blog', 'route' => 'blogs'],
            ['label' => 'Track', 'route' => 'track'],
            ['label' => 'Contact', 'route' => 'contact'],
        ];

        $rows = [];
        foreach ($defaults as $i => $d) {
            $url = '#';
            try {
                $url = route($d['route'], [], false);
            } catch (Throwable $e) {
                // Route not available at migrate time — leave placeholder.
            }
            $rows[] = [
                'label' => $d['label'],
                'url' => $url,
                'sort_order' => $i + 1,
                'new_tab' => false,
                'status' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        DB::table('menu_items')->insert($rows);
    }

    public function down(): void
    {
        Schema::dropIfExists('menu_items');
    }
};
