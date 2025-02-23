<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @method static where(string $string, $id)
 * @mixin IdeHelperOrderDetail
 */
class OrderDetail extends Model
{

    protected $hidden = [
        'product'
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class)->withTrashed();
    }


    public function refund_request(): HasOne
    {
        return $this->hasOne(RefundRequest::class);
    }

}
