<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaskActivity extends Model
{
    protected $guarded = ['id'];

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function toFeedArray(): array
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'status' => $this->status,
            'status_label' => $this->status ? Task::statusLabel($this->status) : null,
            'body' => $this->body,
            'user' => $this->user?->name ?? 'System',
            'time_human' => $this->created_at->diffForHumans(),
        ];
    }
}
