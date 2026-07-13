<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    /**
     * Get all of the users that are assigned this coupon.
     */
    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * The customer who EARNED this coupon (null for admin-created public coupons).
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Admin-created, publicly usable coupons (not personal reward coupons).
     */
    public function scopePublic($query)
    {
        return $query->whereNull('user_id');
    }
}
