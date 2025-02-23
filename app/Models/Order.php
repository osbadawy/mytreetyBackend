<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @mixin IdeHelperOrder
 */
class Order extends Model
{
    public function orderDetails(): HasMany
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function refund_requests(): HasMany
    {
        return $this->hasMany(RefundRequest::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function seller(): HasOne
    {
        return $this->hasOne(Seller::class, 'user_id', 'seller_id');
    }


}
