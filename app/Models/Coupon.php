<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperCoupon
 */
class Coupon extends Model
{
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
