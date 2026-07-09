<?php

namespace App\Http\Controllers\Admin\Ecommerce;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use App\Models\User;
use App\Services\SslWirelessSms;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class MissionControlController extends Controller
{
    public function index()
    {
        Task::flipDueApprovals();

        return view('admin.mission-control.index', ['boot' => $this->bootPayload()]);
    }

    public function feed()
    {
        Task::flipDueApprovals();

        return response()->json(['tasks' => $this->taskList()]);
    }

    public function store(StoreTaskRequest $request)
    {
        $task = Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'priority' => $request->priority ?? 'normal',
            'due_date' => $request->due_date,
            'image' => $this->storeImage($request),
            'status' => Task::STATUS_AWAITING,
            'created_by' => $request->user()->id,
        ]);

        $task->activities()->create([
            'user_id' => $request->user()->id,
            'type' => 'status',
            'status' => Task::STATUS_AWAITING,
        ]);

        return response()->json(['task' => $this->taskPayload($task)], 201);
    }

    public function update(UpdateTaskRequest $request, Task $task)
    {
        abort_unless(
            $task->status === Task::STATUS_AWAITING,
            422,
            'This task is locked: Rajin has already started working on it.'
        );

        $task->update([
            'title' => $request->title,
            'description' => $request->description,
            'priority' => $request->priority ?? $task->priority,
            'due_date' => $request->due_date,
            'image' => $this->storeImage($request, $task) ?? $task->image,
        ]);

        return response()->json(['task' => $this->taskPayload($task)]);
    }

    public function destroy(Task $task)
    {
        abort_unless(
            $task->status === Task::STATUS_AWAITING,
            422,
            'This task is locked: Rajin has already started working on it.'
        );

        $this->deleteImage($task);
        $task->delete();

        return response()->json(['ok' => true]);
    }

    public function updateStatus(Request $request, Task $task)
    {
        Gate::authorize('control-tasks');

        $request->validate(['status' => 'required|in:'.implode(',', Task::STATUSES)]);

        $task->transitionTo($request->status, $request->user());

        return response()->json(['task' => $this->taskPayload($task)]);
    }

    public function comment(Request $request, Task $task)
    {
        $request->validate(['body' => 'required|string|max:2000']);

        $activity = $task->activities()->create([
            'user_id' => $request->user()->id,
            'type' => 'comment',
            'body' => $request->body,
        ]);

        return response()->json(['activity' => $activity->load('user')->toFeedArray()], 201);
    }

    public function remind(Task $task)
    {
        if (($wait = $task->reminderWaitSeconds()) > 0) {
            return response()->json([
                'message' => 'A reminder was sent recently. Please wait before sending another.',
                'wait_seconds' => $wait,
            ], 429);
        }

        $rajin = User::where('username', 'rajin')->first();

        if (! $rajin || ! $rajin->phone) {
            return response()->json(['message' => 'Executor account or phone number is missing.'], 422);
        }

        $sent = SslWirelessSms::send($rajin->phone, sprintf(
            'Mission Control: Task #%d "%s" (%s) is %s. Please check the admin panel.',
            $task->id,
            $task->title,
            ucfirst($task->priority),
            Task::statusLabel($task->status)
        ));

        if (! $sent) {
            return response()->json(['message' => 'The SMS gateway did not accept the message.'], 502);
        }

        $task->update(['last_reminded_at' => now()]);

        return response()->json(['ok' => true, 'wait_seconds' => Task::REMINDER_COOLDOWN]);
    }

    private function bootPayload(): array
    {
        return [
            'tasks' => $this->taskList(),
            'isExecutor' => auth()->user()->isTaskExecutor(),
            'statuses' => Task::STATUSES,
            'urls' => [
                'store' => route('admin.mission.store'),
                'feed' => route('admin.mission.feed'),
                'base' => url('admin/mission-control'),
            ],
        ];
    }

    private function taskList(): array
    {
        return Task::with('activities.user', 'creator')
            ->latest()
            ->get()
            ->map
            ->toBoardArray()
            ->values()
            ->all();
    }

    private function taskPayload(Task $task): array
    {
        return $task->fresh()->load('activities.user', 'creator')->toBoardArray();
    }

    private function storeImage(Request $request, ?Task $current = null): ?string
    {
        $file = $request->file('image');

        if (! $file) {
            return null;
        }

        if ($current) {
            $this->deleteImage($current);
        }

        $name = now()->toDateString().'-'.uniqid().'.'.$file->getClientOriginalExtension();

        if (! file_exists(public_path('uploads/tasks'))) {
            mkdir(public_path('uploads/tasks'), 0777, true);
        }

        $file->move(public_path('uploads/tasks'), $name);

        return $name;
    }

    private function deleteImage(Task $task): void
    {
        if ($task->image && file_exists(public_path('uploads/tasks/'.$task->image))) {
            @unlink(public_path('uploads/tasks/'.$task->image));
        }
    }
}
