<?php

namespace App\Http\Requests;

use App\Models\Task;
use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        // The admin middleware on the route group already gates access.
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:150',
            'description' => 'required|string',
            'priority' => 'nullable|in:'.implode(',', Task::PRIORITIES),
            'due_date' => 'nullable|date|after_or_equal:today',
            'image' => 'nullable|image|max:5120',
        ];
    }
}
