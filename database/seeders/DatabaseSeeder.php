<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            AdminSeeder::class,
            RajinSeeder::class,
            SettingSeeder::class,
            PageSeeder::class,
            DemoDataSeeder::class,
            DemoVariationSeeder::class,
        ]);
    }
}
