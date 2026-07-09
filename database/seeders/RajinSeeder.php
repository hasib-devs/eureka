<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RajinSeeder extends Seeder
{
    /**
     * Create the Mission Control executor account. The username "rajin" is
     * what grants task-control access — see User::isTaskExecutor().
     */
    public function run(): void
    {
        if (User::where('username', 'rajin')->exists()) {
            return;
        }

        User::create([
            'role_id' => 1,
            'name' => 'Rajin',
            'refer' => 0,
            'username' => 'rajin',
            'email' => 'rajin@mission.local',
            'phone' => env('RAJIN_PHONE', '01700000000'),
            'password' => Hash::make('password'),
            'is_approved' => true,
            'status' => true,
            'cancel_attempt' => 0,
            'avatar' => 'default.png',
            'point' => 0,
            'joining_date' => now()->toDateString(),
            'joining_month' => now()->format('F'),
            'joining_year' => now()->year,
            'email_verified_at' => now(),
            'wallate' => 0,
            'remember_token' => Str::random(10),
        ]);
    }
}
