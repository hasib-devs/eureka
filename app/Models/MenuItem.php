<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'new_tab' => 'boolean',
        'status' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('status', true)->orderBy('sort_order')->orderBy('id');
    }
}
