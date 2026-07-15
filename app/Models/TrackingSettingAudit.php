<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * One row per changed tracking field. Secret values are masked before they get
 * here — see TrackingSettingsService::update().
 */
class TrackingSettingAudit extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    /** What a secret's value is recorded as, instead of the value itself. */
    public const MASK = '••••••';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
