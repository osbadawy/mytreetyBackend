<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperCart
 */
class Cart extends Model
{
    protected $guarded = [];
    protected $fillable = ['address_id', 'price', 'tax', 'shipping_cost', 'discount', 'referral_code', 'coupon_code', 'coupon_applied', 'quantity', 'user_id', 'temp_user_id', 'owner_id', 'product_id', 'variation'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class);
    }
}
