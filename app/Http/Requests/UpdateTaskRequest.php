<?php

namespace App\Http\Requests;

use App\Models\Task;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:150',
            'description' => 'required|string',
            'priority' => 'nullable|in:'.implode(',', Task::PRIORITIES),
            'due_date' => 'nullable|date',
            'image' => 'nullable|image|max:5120',
        ];
    }
}
