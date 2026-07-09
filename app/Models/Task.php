<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model
{
    public const STATUS_AWAITING = 'awaiting_review';

    public const STATUS_REVIEW = 'under_review';

    public const STATUS_APPROVED = 'approved';

    public const STATUS_PROGRESS = 'in_progress';

    public const STATUS_DELIVERED = 'delivered';

    public const STATUSES = [
        self::STATUS_AWAITING,
        self::STATUS_REVIEW,
        self::STATUS_APPROVED,
        self::STATUS_PROGRESS,
        self::STATUS_DELIVERED,
    ];

    public const PRIORITIES = ['low', 'normal', 'urgent'];

    public const AUTO_START_MINUTES = 5;

    public const REMINDER_COOLDOWN = 1800; // seconds

    protected $guarded = ['id'];

    protected $casts = [
        'due_date' => 'date',
        'approved_at' => 'datetime',
        'completed_at' => 'datetime',
        'last_reminded_at' => 'datetime',
    ];

    public function activities(): HasMany
    {
        return $this->hasMany(TaskActivity::class)->orderBy('created_at')->orderBy('id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public static function statusLabel(string $status): string
    {
        return [
            self::STATUS_AWAITING => 'Awaiting Review',
            self::STATUS_REVIEW => 'Under Review',
            self::STATUS_APPROVED => 'Approved',
            self::STATUS_PROGRESS => 'In Progress',
            self::STATUS_DELIVERED => 'Delivered',
        ][$status] ?? $status;
    }

    public function transitionTo(string $status, ?User $user): void
    {
        $attributes = ['status' => $status];

        if ($status === self::STATUS_APPROVED) {
            $attributes['approved_at'] = now();
        }

        if ($status === self::STATUS_DELIVERED) {
            $attributes['completed_at'] = now();
        }

        $this->update($attributes);

        $this->activities()->create([
            'user_id' => $user?->id,
            'type' => 'status',
            'status' => $status,
        ]);
    }

    /**
     * Approved tasks auto-start after AUTO_START_MINUTES without needing a
     * scheduler: this runs lazily on every board/feed load.
     */
    public static function flipDueApprovals(): void
    {
        self::where('status', self::STATUS_APPROVED)
            ->where('approved_at', '<=', now()->subMinutes(self::AUTO_START_MINUTES))
            ->get()
            ->each(fn (Task $task) => $task->transitionTo(self::STATUS_PROGRESS, null));
    }

    public function autoStartSecondsLeft(): int
    {
        if ($this->status !== self::STATUS_APPROVED || ! $this->approved_at) {
            return 0;
        }

        $elapsed = (int) $this->approved_at->diffInSeconds(now());

        return max(0, self::AUTO_START_MINUTES * 60 - $elapsed);
    }

    public function reminderWaitSeconds(): int
    {
        if (! $this->last_reminded_at) {
            return 0;
        }

        $elapsed = (int) $this->last_reminded_at->diffInSeconds(now());

        return max(0, self::REMINDER_COOLDOWN - $elapsed);
    }

    public function toBoardArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'image_url' => $this->image ? asset('uploads/tasks/'.$this->image) : null,
            'priority' => $this->priority,
            'status' => $this->status,
            'status_label' => self::statusLabel($this->status),
            'due_date' => $this->due_date?->format('Y-m-d'),
            'overdue' => (bool) ($this->due_date
                && $this->due_date->endOfDay()->isPast()
                && $this->status !== self::STATUS_DELIVERED),
            'editable' => $this->status === self::STATUS_AWAITING,
            'auto_start_seconds_left' => $this->autoStartSecondsLeft(),
            'reminder_wait_seconds' => $this->reminderWaitSeconds(),
            'created_by' => $this->creator?->name,
            'created_at_human' => $this->created_at->diffForHumans(),
            'activities' => $this->activities->map->toFeedArray()->values()->all(),
        ];
    }
}
