<?php

use App\Models\Task;
use App\Models\User;
use Database\Seeders\RajinSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;

function mcAdmin(): User
{
    test()->seed(RoleSeeder::class);

    return User::factory()->create([
        'role_id' => 1,
        'is_approved' => true,
        'status' => true,
    ]);
}

function mcRajin(): User
{
    return User::factory()->create([
        'role_id' => 1,
        'username' => 'rajin',
        'phone' => '01700000000',
        'is_approved' => true,
        'status' => true,
    ]);
}

function mcTask(User $creator, array $overrides = []): Task
{
    return Task::create(array_merge([
        'title' => 'Fix the banner',
        'description' => 'The homepage banner is stretched on mobile.',
        'priority' => 'normal',
        'status' => Task::STATUS_AWAITING,
        'created_by' => $creator->id,
    ], $overrides));
}

it('shows mission control to an admin and the old docs page is gone', function () {
    $admin = mcAdmin();

    $this->actingAs($admin)->get('/admin/mission-control')
        ->assertOk()
        ->assertSee('MISSION CONTROL')
        ->assertSee('missionControl(');

    $this->actingAs($admin)->get('/admin/setting/docs')->assertNotFound();
});

it('redirects non-admins away from mission control', function () {
    mcAdmin();
    $customer = User::factory()->create(['role_id' => 3, 'is_approved' => true, 'status' => true]);

    $this->actingAs($customer)->get('/admin/mission-control')->assertRedirect(route('login'));
});

it('creates a task without an image and logs the initial activity', function () {
    $admin = mcAdmin();

    $response = $this->actingAs($admin)->postJson('/admin/mission-control', [
        'title' => 'Redesign checkout',
        'description' => 'Make the checkout feel premium.',
        'priority' => 'urgent',
    ]);

    $response->assertCreated()
        ->assertJsonPath('task.status', Task::STATUS_AWAITING)
        ->assertJsonPath('task.priority', 'urgent')
        ->assertJsonPath('task.editable', true);

    $task = Task::firstOrFail();
    expect($task->activities)->toHaveCount(1)
        ->and($task->activities->first()->type)->toBe('status')
        ->and($task->activities->first()->status)->toBe(Task::STATUS_AWAITING);
});

it('creates a task with a reference image', function () {
    $admin = mcAdmin();

    $response = $this->actingAs($admin)->postJson('/admin/mission-control', [
        'title' => 'New product shots',
        'description' => 'Use the attached style.',
        'image' => UploadedFile::fake()->image('ref.jpg', 600, 400),
    ]);

    $response->assertCreated();
    $task = Task::firstOrFail();
    expect($task->image)->not->toBeNull()
        ->and($response->json('task.image_url'))->toContain('uploads/tasks/');
});

it('rejects a task without required fields', function () {
    $admin = mcAdmin();

    $this->actingAs($admin)->postJson('/admin/mission-control', ['priority' => 'nope'])
        ->assertStatus(422)
        ->assertJsonValidationErrors(['title', 'description', 'priority']);
});

it('blocks status changes from admins who are not rajin', function () {
    $admin = mcAdmin();
    $task = mcTask($admin);

    $this->actingAs($admin)
        ->patchJson("/admin/mission-control/{$task->id}/status", ['status' => Task::STATUS_APPROVED])
        ->assertForbidden();
});

it('lets rajin move a task through statuses and stamps the timestamps', function () {
    $admin = mcAdmin();
    $rajin = mcRajin();
    $task = mcTask($admin);

    $this->actingAs($rajin)
        ->patchJson("/admin/mission-control/{$task->id}/status", ['status' => Task::STATUS_APPROVED])
        ->assertOk()
        ->assertJsonPath('task.status', Task::STATUS_APPROVED);

    $task->refresh();
    expect($task->approved_at)->not->toBeNull()
        ->and($task->autoStartSecondsLeft())->toBeGreaterThan(0);

    $this->actingAs($rajin)
        ->patchJson("/admin/mission-control/{$task->id}/status", ['status' => Task::STATUS_DELIVERED])
        ->assertOk();

    $task->refresh();
    expect($task->completed_at)->not->toBeNull()
        ->and($task->activities()->where('type', 'status')->count())->toBe(2);
});

it('auto-flips an approved task to in progress after five minutes on feed load', function () {
    $admin = mcAdmin();
    $task = mcTask($admin, [
        'status' => Task::STATUS_APPROVED,
        'approved_at' => now()->subMinutes(Task::AUTO_START_MINUTES + 1),
    ]);

    $this->actingAs($admin)->getJson('/admin/mission-control/feed')
        ->assertOk()
        ->assertJsonPath('tasks.0.status', Task::STATUS_PROGRESS);

    $system = $task->activities()->latest('id')->first();
    expect($system->user_id)->toBeNull()
        ->and($system->status)->toBe(Task::STATUS_PROGRESS);
});

it('does not auto-flip before the five minute window', function () {
    $admin = mcAdmin();
    mcTask($admin, [
        'status' => Task::STATUS_APPROVED,
        'approved_at' => now()->subMinutes(2),
    ]);

    $this->actingAs($admin)->getJson('/admin/mission-control/feed')
        ->assertOk()
        ->assertJsonPath('tasks.0.status', Task::STATUS_APPROVED);
});

it('allows editing and deleting only while the task is awaiting review', function () {
    $admin = mcAdmin();
    $task = mcTask($admin);

    $this->actingAs($admin)->putJson("/admin/mission-control/{$task->id}", [
        'title' => 'Fix the banner (updated)',
        'description' => 'Now with more detail.',
    ])->assertOk()->assertJsonPath('task.title', 'Fix the banner (updated)');

    $task->update(['status' => Task::STATUS_REVIEW]);

    $this->actingAs($admin)->putJson("/admin/mission-control/{$task->id}", [
        'title' => 'Too late',
        'description' => 'Should be locked.',
    ])->assertStatus(422);

    $this->actingAs($admin)->deleteJson("/admin/mission-control/{$task->id}")->assertStatus(422);

    $task->update(['status' => Task::STATUS_AWAITING]);
    $this->actingAs($admin)->deleteJson("/admin/mission-control/{$task->id}")->assertOk();
    expect(Task::count())->toBe(0);
});

it('lets both the creator and rajin comment on a task', function () {
    $admin = mcAdmin();
    $rajin = mcRajin();
    $task = mcTask($admin);

    $this->actingAs($admin)
        ->postJson("/admin/mission-control/{$task->id}/comment", ['body' => 'Any update?'])
        ->assertCreated()
        ->assertJsonPath('activity.type', 'comment');

    $this->actingAs($rajin)
        ->postJson("/admin/mission-control/{$task->id}/comment", ['body' => 'On it today.'])
        ->assertCreated();

    expect($task->activities()->where('type', 'comment')->count())->toBe(2);
});

it('sends an sms reminder to rajin and then throttles repeats', function () {
    Http::fake(['smsplus.sslwireless.com/*' => Http::response(['status' => 'SUCCESS'])]);

    $admin = mcAdmin();
    mcRajin();
    $task = mcTask($admin);

    $this->actingAs($admin)
        ->postJson("/admin/mission-control/{$task->id}/remind")
        ->assertOk()
        ->assertJsonPath('wait_seconds', Task::REMINDER_COOLDOWN);

    Http::assertSent(function ($request) use ($task) {
        return str_contains($request->url(), 'smsplus.sslwireless.com')
            && $request['msisdn'] === '01700000000'
            && str_contains($request['sms'], "Task #{$task->id}");
    });

    $this->actingAs($admin)
        ->postJson("/admin/mission-control/{$task->id}/remind")
        ->assertStatus(429);
});

it('fails gracefully when the executor account is missing', function () {
    $admin = mcAdmin();
    $task = mcTask($admin);

    $this->actingAs($admin)
        ->postJson("/admin/mission-control/{$task->id}/remind")
        ->assertStatus(422);
});

it('seeds the rajin executor account once', function () {
    $this->seed(RoleSeeder::class);
    $this->seed(RajinSeeder::class);
    $this->seed(RajinSeeder::class);

    $rajin = User::where('username', 'rajin')->get();
    expect($rajin)->toHaveCount(1)
        ->and($rajin->first()->role_id)->toBe(1)
        ->and($rajin->first()->isTaskExecutor())->toBeTrue();
});

it('marks overdue tasks in the board payload', function () {
    $admin = mcAdmin();
    mcTask($admin, ['due_date' => now()->subDays(2)->toDateString()]);

    $this->actingAs($admin)->getJson('/admin/mission-control/feed')
        ->assertOk()
        ->assertJsonPath('tasks.0.overdue', true);
});
